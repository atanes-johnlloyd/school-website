<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymongoWebhookEvent;
use App\Services\Payment\ContributionService;
use App\Services\Payment\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymongoWebhookController extends Controller
{
    public function __construct(
        protected PayMongoService $paymongo,
        protected ContributionService $contributions,
    ) {}

        public function handle(Request $request)
    {
        $rawBody = $request->getContent();
        $signature = $request->header('Paymongo-Signature');

        if (! $this->paymongo->verifyWebhookSignature($rawBody, $signature)) {
            Log::warning('PayMongo webhook rejected: bad signature', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $payload = json_decode($rawBody, true);
        $eventId = $payload['data']['id'] ?? null;
        $type    = $payload['data']['attributes']['type'] ?? 'unknown';

        if (! $eventId) {
            return response()->json(['message' => 'Missing event id.'], 422);
        }

        // ─── Idempotency ─────────────────────────────────────────
        // Only skip if the event was SUCCESSFULLY processed before.
        // If a previous attempt exists but failed (processed_at is null),
        // we will retry processing it. PayMongo will resend the event
        // since we returned 500 on the previous failure.
        $event = PaymongoWebhookEvent::where('event_id', $eventId)->first();

        if ($event && $event->processed_at !== null) {
            return response()->json(['message' => 'Already processed.']);
        }

        // Create the event record on first delivery. On retries, reuse the existing row.
        if (! $event) {
            $event = PaymongoWebhookEvent::create([
                'event_id'    => $eventId,
                'type'        => $type,
                'payload'     => $payload,
                'received_at' => now(),
            ]);
        }

        try {
            $this->dispatch($type, $payload);

            // Mark as processed only on success
            $event->update([
                'processed_at' => now(),
                'error'        => null,
            ]);
        } catch (\Throwable $e) {
            $event->update(['error' => $e->getMessage()]);

            Log::error('PayMongo webhook handler failed', [
                'event_id'   => $eventId,
                'type'       => $type,
                'error'      => $e->getMessage(),
                'retry_note' => 'Returning 500 so PayMongo retries the delivery.',
            ]);

            // ✅ Return 500 so PayMongo retries.
            // PayMongo retries with exponential backoff (up to ~24 hours).
            // If it never succeeds, you have the 'error' column for manual inspection.
            return response()->json(['message' => 'Processing failed, will retry.'], 500);
        }

        return response()->json(['message' => 'Received.']);
    }

    protected function dispatch(string $type, array $payload): void
    {
        $data = $payload['data']['attributes']['data'] ?? [];

        match ($type) {
            'checkout_session.payment.paid'  => $this->handleCheckoutPaid($data),
            'payment.paid'                   => $this->handlePaymentPaid($data),
            'checkout_session.expired'       => $this->handleCheckoutExpired($data),
            default                          => null,   // ignore others
        };
    }

    protected function handleCheckoutPaid(array $data): void
    {
        $checkoutId = $data['id'] ?? null;
        if (! $checkoutId) return;

        $payment = Payment::where('paymongo_checkout_id', $checkoutId)->first();
        if (! $payment) {
            Log::warning('PayMongo webhook: payment not found', ['checkout_id' => $checkoutId]);
            return;
        }

        $payments = $data['attributes']['payments'] ?? [];
        $paidPayment = collect($payments)->firstWhere('attributes.status', 'paid') ?? $payments[0] ?? [];

        $method = $paidPayment['attributes']['source']['type'] ?? null;

        $this->contributions->markPaid($payment, [
            'method'    => $method,
            'payment_id'=> $paidPayment['id'] ?? null,
            'intent_id' => $data['attributes']['payment_intent']['id'] ?? null,
            'raw'       => $data,
        ]);
    }

    protected function handlePaymentPaid(array $data): void
    {
        $intentId = $data['attributes']['payment_intent_id'] ?? null;
        if (! $intentId) return;

        $payment = Payment::where('paymongo_payment_intent_id', $intentId)->first();
        if (! $payment) {
            Log::warning('PayMongo webhook: intent not found', ['intent_id' => $intentId]);
            return;
        }

        $method = $data['attributes']['source']['type'] ?? null;

        $this->contributions->markPaid($payment, [
            'method'    => $method,
            'payment_id'=> $data['id'] ?? null,
            'intent_id' => $intentId,
            'raw'       => $data,
        ]);
    }

    protected function handleCheckoutExpired(array $data): void
    {
        $checkoutId = $data['id'] ?? null;
        if (! $checkoutId) return;

        Payment::where('paymongo_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);
    }
}