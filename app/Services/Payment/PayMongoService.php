<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected string $baseUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('paymongo.base_url'), '/');
        $this->secretKey = (string) config('paymongo.secret_key');
    }

    /**
     * Create a Checkout Session for a Payment record.
     *
     * @return array{checkout_id: string, checkout_url: string, raw: array}
     * @throws \RuntimeException
     */
    public function createCheckoutSession(Payment $payment, array $options = []): array
    {
        $amountCentavos = (int) round(((float) $payment->amount) * 100);

        if ($amountCentavos < 100) {
            throw new \RuntimeException('Amount must be at least ₱1.00 (100 centavos).');
        }

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => [[
                        'amount'   => $amountCentavos,
                        'currency' => $payment->currency ?: config('paymongo.currency', 'PHP'),
                        'name'     => $options['description'] ?? ('Payment ' . $payment->reference_no),
                        'quantity' => 1,
                    ]],
                    'payment_method_types' => config('paymongo.payment_methods'),
                    'success_url'          => $options['success_url'],
                    'cancel_url'           => $options['cancel_url'],
                    'description'          => $options['description'] ?? 'School contribution',
                    'reference_number'     => $payment->reference_no,
                    'send_email_receipt'   => false,
                    'show_description'     => true,
                    'show_line_items'      => true,
                ],
            ],
        ];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->acceptJson()
            ->timeout(15)
            ->post("{$this->baseUrl}/checkout_sessions", $payload);

        if (! $response->successful()) {
            Log::error('PayMongo checkout session failed', [
                'status'   => $response->status(),
                'body'     => $response->body(),
                'payment'  => $payment->id,
            ]);
            $detail = $response->json('errors.0.detail') ?? 'PayMongo request failed.';
            throw new \RuntimeException($detail);
        }

        $data = $response->json('data');

        return [
            'checkout_id'  => $data['id'] ?? '',
            'checkout_url' => $data['attributes']['checkout_url'] ?? '',
            'raw'          => $data,
        ];
    }

    /**
     * Verify a webhook signature against the raw request body.
     * PayMongo sends a `Paymongo-Signature` header with format:
     *   t=<timestamp>,te=<test_signature>,li=<live_signature>
     */
    public function verifyWebhookSignature(string $rawBody, ?string $signatureHeader): bool
    {
        $secret = (string) config('paymongo.webhook_secret');
        if (! $secret || ! $signatureHeader) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $chunk) {
            if (str_contains($chunk, '=')) {
                [$k, $v] = explode('=', $chunk, 2);
                $parts[trim($k)] = trim($v);
            }
        }

        $timestamp = $parts['t'] ?? null;
        // PayMongo sends both te (test) and li (live); pick whichever exists
        $provided = $parts['li'] ?? $parts['te'] ?? null;

        if (! $timestamp || ! $provided) {
            return false;
        }

        // Reject stale signatures (> 5 min old) — replay protection
        if (abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$rawBody}", $secret);

        return hash_equals($expected, $provided);
    }
}