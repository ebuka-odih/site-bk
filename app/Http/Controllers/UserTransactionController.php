<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class UserTransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $transactions = Transaction::where('user_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with(['user', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return inertia('Transactions', [
            'transactions' => $transactions,
        ]);
    }
}

