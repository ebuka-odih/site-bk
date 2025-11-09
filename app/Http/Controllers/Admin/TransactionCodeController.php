<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TransactionCodeAssignedMail;
use App\Models\TransactionCode;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class TransactionCodeController extends Controller
{
    /**
     * Display a listing of transaction codes.
     */
    public function index(Request $request)
    {
        $query = TransactionCode::with(['creator', 'usedBy']);

        $type = $request->input('type');
        $status = $request->input('status', 'active');

        if (!in_array($status, ['active', 'used', 'expired', 'all'], true)) {
            $status = 'active';
        }

        // Filter by type
        if (!empty($type)) {
            $query->where('type', $type);
        }

        // Filter by status
        switch ($status) {
            case 'used':
                $query->where('is_used', true);
                break;
            case 'expired':
                $query->where('is_used', false)->where('expires_at', '<', now());
                break;
            case 'all':
                // no additional filters
                break;
            case 'active':
            default:
                $query->where('is_used', false)->where('expires_at', '>', now());
                break;
        }

        $codes = $query->latest()->paginate(15);

        return inertia('Admin/Codes/Index', [
            'codes' => $codes,
            'filters' => [
                'status' => $status,
                'type' => $type,
            ],
        ]);
    }

    /**
     * Show the form for creating a new transaction code.
     */
    public function create()
    {
        return inertia('Admin/Codes/Create');
    }

    /**
     * Store a newly created transaction code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:deposit,withdrawal,transfer',
            'amount' => 'nullable|numeric|min:0.01',
            'expires_in_hours' => 'required|integer|min:1|max:168',
            'notes' => 'nullable|string|max:500',
        ]);

        $code = TransactionCode::create([
            'code' => TransactionCode::generateCode(),
            'type' => $request->type,
            'amount' => $request->amount ? $request->amount : null,
            'created_by' => auth()->id(),
            'expires_at' => Carbon::now()->addHours((int) $request->expires_in_hours),
            'notes' => $request->notes,
        ]);

        AuditLog::logEvent('transaction_code.created', [
            'code' => $code->code,
            'type' => $code->type,
            'amount' => $code->amount,
        ], $code);

        return redirect()
            ->route('admin.codes.show', $code)
            ->with('success', 'Transaction code generated successfully!');
    }

    /**
     * Display the specified transaction code.
     */
    public function show(TransactionCode $code)
    {
        $code->load(['creator', 'usedBy', 'transaction']);

        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return inertia('Admin/Codes/Show', [
            'code' => $code,
            'users' => $users,
        ]);
    }

    /**
     * Send a transaction code to a user via email.
     */
    public function send(Request $request, TransactionCode $code)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if ($code->is_used) {
            return back()->with('error', 'This code has already been used and cannot be sent.');
        }

        if ($code->isExpired()) {
            return back()->with('error', 'This code is expired and cannot be sent.');
        }

        $user = User::findOrFail($validated['user_id']);
        Mail::to($user->email)->sendNow(new TransactionCodeAssignedMail($code));

        AuditLog::logEvent('transaction_code.sent_to_user', [
            'code' => $code->code,
            'type' => $code->type,
            'amount' => $code->amount,
            'recipient_user_id' => $user->id,
        ], $code);

        return back()->with('success', 'Transaction code emailed to ' . $user->email . ' successfully.');
    }

    /**
     * Remove the specified transaction code.
     */
    public function destroy(TransactionCode $code)
    {
        if ($code->is_used) {
            return back()->with('error', 'Cannot delete a used transaction code.');
        }

        AuditLog::logEvent('transaction_code.deleted', [
            'code' => $code->code,
            'type' => $code->type,
        ], $code);

        $code->delete();

        return redirect()
            ->route('admin.codes.index')
            ->with('success', 'Transaction code deleted successfully.');
    }

    /**
     * Bulk generate transaction codes.
     */
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:deposit,withdrawal,transfer',
            'amount' => 'nullable|numeric|min:0.01',
            'quantity' => 'required|integer|min:1|max:50',
            'expires_in_hours' => 'required|integer|min:1|max:168',
        ]);

        $codes = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $codes[] = TransactionCode::create([
                'code' => TransactionCode::generateCode(),
                'type' => $request->type,
                'amount' => $request->amount ? $request->amount : null,
                'created_by' => auth()->id(),
                'expires_at' => Carbon::now()->addHours((int) $request->expires_in_hours),
            ]);
        }

        AuditLog::logEvent('transaction_codes.bulk_generated', [
            'quantity' => $request->quantity,
            'type' => $request->type,
        ]);

        return redirect()
            ->route('admin.codes.index')
            ->with('success', "{$request->quantity} transaction codes generated successfully!");
    }
}
