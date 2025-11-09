<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class AccountLookupController extends Controller
{
    public function lookup($accountNumber)
    {
        $wallet = Wallet::where('account_number', $accountNumber)
            ->with('user')
            ->first();
        
        if ($wallet && $wallet->user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $wallet->user->id,
                    'name' => $wallet->user->name,
                    'account_number' => $wallet->account_number,
                ],
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Account not found',
        ], 404);
    }
}

