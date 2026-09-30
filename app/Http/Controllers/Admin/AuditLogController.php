<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    /* ═══════════════ INDEX — page shell ═══════════════ */
    public function index(): Response
    {
        return Inertia::render('Admin/AuditLogs/Index', [
            'users' => User::whereHas('auditLogs')
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'modelTypes' => AuditLog::query()
                ->select('auditable_type')
                ->whereNotNull('auditable_type')
                ->distinct()
                ->orderBy('auditable_type')
                ->pluck('auditable_type')
                ->map(fn ($fqcn) => [
                    'value' => $fqcn,
                    'label' => class_basename($fqcn),
                ])
                ->values(),
        ]);
    }

    /* ═══════════════ LIST — JSON for the Vue table ═══════════════ */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'action'     => ['nullable', 'in:created,updated,deleted,restored'],
            'user_id'    => ['nullable', 'integer', 'exists:users,id'],
            'model_type' => ['nullable', 'string', 'max:255'],
            'date_from'  => ['nullable', 'date'],
            'date_to'    => ['nullable', 'date', 'after_or_equal:date_from'],
            'search'     => ['nullable', 'string', 'max:200'],
            'per_page'   => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'    => ['nullable', 'in:created_at,action,auditable_type'],
            'sort_dir'   => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'created_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = AuditLog::query()->with('user:id,name,email');

        if (! empty($validated['action']))     $query->where('action', $validated['action']);
        if (! empty($validated['user_id']))    $query->where('user_id', $validated['user_id']);
        if (! empty($validated['model_type'])) $query->where('auditable_type', $validated['model_type']);
        if (! empty($validated['date_from']))  $query->whereDate('created_at', '>=', $validated['date_from']);
        if (! empty($validated['date_to']))    $query->whereDate('created_at', '<=', $validated['date_to']);

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('action', 'like', "%{$s}%")
                  ->orWhere('auditable_type', 'like', "%{$s}%")
                  ->orWhere('ip_address', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        $query->orderBy($sortBy, $sortDir);

        $logs = $query->paginate($validated['per_page'] ?? 25);

        $logs->getCollection()->transform(fn (AuditLog $log) => [
            'id'              => $log->id,
            'action'          => $log->action,
            'auditable_type'  => $log->auditable_type,
            'auditable_label' => $log->auditable_type ? class_basename($log->auditable_type) : null,
            'auditable_id'    => $log->auditable_id,
            'user_id'         => $log->user_id,
            'user_name'       => $log->user?->name ?? 'System',
            'user_email'      => $log->user?->email,
            'ip_address'      => $log->ip_address,
            'created_at'      => $log->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'logs'    => $logs,
            'filters' => [
                'action'     => $validated['action']     ?? null,
                'user_id'    => $validated['user_id']    ?? null,
                'model_type' => $validated['model_type'] ?? null,
                'date_from'  => $validated['date_from']  ?? null,
                'date_to'    => $validated['date_to']    ?? null,
                'search'     => $validated['search']     ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total'     => AuditLog::count(),
                'today'     => AuditLog::whereDate('created_at', today())->count(),
                'this_week' => AuditLog::where('created_at', '>=', now()->subDays(7))->count(),
                'created'   => AuditLog::where('action', 'created')->count(),
                'deleted'   => AuditLog::where('action', 'deleted')->count(),
            ],
        ]);
    }

    /* ═══════════════ SHOW — single log entry ═══════════════ */
    public function show(Request $request, AuditLog $auditLog)
    {
        $auditLog->load('user:id,name,email');

        return response()->json([
            'log' => [
                'id'              => $auditLog->id,
                'action'          => $auditLog->action,
                'auditable_type'  => $auditLog->auditable_type,
                'auditable_label' => $auditLog->auditable_type ? class_basename($auditLog->auditable_type) : null,
                'auditable_id'    => $auditLog->auditable_id,
                'old_values'      => $auditLog->old_values,
                'new_values'      => $auditLog->new_values,
                'ip_address'      => $auditLog->ip_address,
                'user_agent'      => $auditLog->user_agent,
                'user_id'         => $auditLog->user_id,
                'user_name'       => $auditLog->user?->name ?? 'System',
                'user_email'      => $auditLog->user?->email,
                'created_at'      => $auditLog->created_at?->toIso8601String(),
            ],
        ]);
    }
}