@extends('admin.layout')

@section('title', 'Edit Transaction')
@section('page-title', 'Edit Transaction')
@section('page-description', 'Update transaction information')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-900 rounded-xl p-6 border border-dark-800">
        <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- User Selection -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-300 mb-2">User *</label>
                <select id="user_id" 
                        name="user_id" 
                        required
                        class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select a user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (old('user_id', $transaction->user_id) == $user->id) ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transaction Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Transaction Type *</label>
                <select id="type" 
                        name="type" 
                        required
                        class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select transaction type</option>
                    <option value="deposit" {{ old('type', $transaction->type) == 'deposit' ? 'selected' : '' }}>Deposit</option>
                    <option value="withdrawal" {{ old('type', $transaction->type) == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                    <option value="transfer" {{ old('type', $transaction->type) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Amount *</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 sm:text-sm">$</span>
                    </div>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           step="0.01" 
                           min="0.01"
                           value="{{ old('amount', $transaction->amount_in_dollars) }}"
                           required
                           placeholder="0.00"
                           class="w-full pl-8 pr-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description *</label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          required
                          placeholder="Enter transaction description..."
                          class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $transaction->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status *</label>
                <select id="status" 
                        name="status" 
                        required
                        class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Select status</option>
                    <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $transaction->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ old('status', $transaction->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ old('status', $transaction->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-dark-800">
                <a href="{{ route('admin.transactions.show', $transaction) }}" 
                   class="px-4 py-2 bg-dark-800 hover:bg-dark-700 text-gray-300 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    Update Transaction
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
