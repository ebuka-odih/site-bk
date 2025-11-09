<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionCode;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->input('date_to', Carbon::now()->endOfMonth());

        // Transaction statistics
        $transactionStats = [
            'total' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'completed' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'completed')->count(),
            'pending' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'pending')->count(),
            'failed' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'failed')->count(),
            'total_value' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'completed')->sum('amount') / 100,
        ];

        // Transaction by type
        $transactionsByType = Transaction::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('type')
            ->get();

        // Daily transactions for chart
        $dailyTransactions = Transaction::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // User statistics
        $userStats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'locked' => User::where('status', 'locked')->count(),
            'new_this_month' => User::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count(),
        ];

        // Top users by transaction volume
        $topUsers = User::withCount('transactions')
            ->orderBy('transactions_count', 'desc')
            ->limit(10)
            ->get();

        // Transaction codes stats
        $codeStats = [
            'total' => TransactionCode::count(),
            'active' => TransactionCode::where('is_used', false)->where('expires_at', '>', now())->count(),
            'used' => TransactionCode::where('is_used', true)->count(),
            'expired' => TransactionCode::where('is_used', false)->where('expires_at', '<', now())->count(),
        ];

        // Recent audit logs
        $recentAudits = AuditLog::with('actor')
            ->latest()
            ->limit(10)
            ->get();

        return inertia('Admin/Reports/Index', [
            'transactionStats' => $transactionStats,
            'transactionsByType' => $transactionsByType,
            'dailyTransactions' => $dailyTransactions,
            'userStats' => $userStats,
            'topUsers' => $topUsers,
            'codeStats' => $codeStats,
            'recentAudits' => $recentAudits,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
    }

    /**
     * Export transactions report.
     */
    public function exportTransactions(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->input('date_to', Carbon::now()->endOfMonth());

        $transactions = Transaction::with(['user', 'recipient'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $filename = 'transactions_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Reference', 'User', 'Type', 'Amount', 'Status', 'Date']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->reference,
                    $transaction->user->name ?? 'N/A',
                    $transaction->type,
                    $transaction->amount / 100,
                    $transaction->status,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Fraud detection report.
     */
    public function fraudDetection()
    {
        // Suspicious activities
        $suspiciousActivities = [];

        // Multiple failed transactions
        $failedTransactions = Transaction::where('status', 'failed')
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->with('user')
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->having('count', '>', 5)
            ->get();

        // Users with high transaction volumes
        $highVolumeUsers = Transaction::where('created_at', '>', Carbon::now()->subDay())
            ->with('user')
            ->select('user_id', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('user_id')
            ->having('count', '>', 20)
            ->get();

        return inertia('Admin/Reports/Fraud', [
            'failedTransactions' => $failedTransactions,
            'highVolumeUsers' => $highVolumeUsers
        ]);
    }
}
