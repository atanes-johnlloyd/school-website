<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'action'         => ['nullable', 'in:created,updated,deleted'],
            'user_id'        => ['nullable', 'integer', 'exists:users,id'],
            'model_type'     => ['nullable', 'string', 'max:255'],
            'date_from'      => ['nullable', 'date'],
            'date_to'        => ['nullable', 'date'],
            'search'         => ['nullable', 'string', 'max:200'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
        ]);

        $query = AuditLog::query()->with('user:id,name,email')->latest('id');

        if (! empty($validated['action'])) {
            $query->where('action', $validated['action']);
        }
        if (! empty($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }
        if (! empty($validated['model_type'])) {
            $query->where('auditable_type', $validated['model_type']);
        }
        if (! empty($validated['date_from'])) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }
        if (! empty($validated['date_to'])) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('action', 'like', "%{$s}%")
                  ->orWhere('auditable_type', 'like', "%{$s}%")
                  ->orWhere('ip_address', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$s}%"));
            });
        }

        $logs = $query->paginate($validated['per_page'] ?? 30);

        // Distinct model types for filter dropdown
        $modelTypes = AuditLog::query()
            ->select('auditable_type')
            ->distinct()
            ->pluck('auditable_type')
            ->filter()
            ->values();

        $payload = [
            'logs'        => $logs,
            'users'       => User::select('id', 'name')->orderBy('name')->get(),
            'model_types' => $modelTypes,
            'filters'     => [
                'action'     => $validated['action'] ?? null,
                'user_id'    => $validated['user_id'] ?? null,
                'model_type' => $validated['model_type'] ?? null,
                'date_from'  => $validated['date_from'] ?? null,
                'date_to'    => $validated['date_to'] ?? null,
                'search'     => $validated['search'] ?? null,
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Admin/AuditLogs/Index', $payload);
    }

    public function show(Request $request, AuditLog $auditLog)
    {
        $auditLog->load('user:id,name,email');

        $payload = ['log' => $auditLog];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Admin/AuditLogs/Show', $payload);
    }
}