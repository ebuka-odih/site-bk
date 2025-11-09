<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('actor');

        // Search by event
        if ($request->filled('event')) {
            $query->where('event', 'like', '%' . $request->event . '%');
        }

        // Filter by actor
        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->ip_address);
        }

        $logs = $query->latest()->paginate(20);

        // Get unique events for filter
        $events = AuditLog::select('event')->distinct()->pluck('event');

        return inertia('Admin/AuditLogs/Index', [
            'logs' => $logs,
            'events' => $events
        ]);
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('actor', 'auditable');
        return inertia('Admin/AuditLogs/Show', [
            'auditLog' => $auditLog
        ]);
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('actor');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Event', 'Actor', 'IP Address', 'User Agent', 'Date']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->event,
                    $log->actor->name ?? 'System',
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
