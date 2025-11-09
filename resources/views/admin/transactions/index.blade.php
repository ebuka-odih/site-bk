@extends('admin.layout')

@section('title', 'Transactions')
@section('page-title', 'Transaction Management')
@section('page-description', 'Manage all banking transactions')

@section('content')
<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="bg-dark-900 rounded-xl p-4 lg:p-6 border border-dark-800">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Search</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search transactions..."
                       class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                <select id="status" 
                        name="status" 
                        class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-300 mb-2">From Date</label>
                <input type="date" 
                       id="date_from" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Date To -->
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-300 mb-2">To Date</label>
                <input type="date" 
                       id="date_to" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 bg-dark-800 border border-dark-700 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Filter Button -->
            <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    Filter Transactions
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-dark-900 rounded-xl border border-dark-800">
        <div class="p-4 lg:p-6 border-b border-dark-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-base lg:text-lg font-semibold text-gray-100">All Transactions</h3>
                <a href="{{ route('admin.transactions.create') }}" 
                   class="w-full sm:w-auto px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors text-center">
                    Add New Transaction
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-dark-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-dark-800">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                #{{ $transaction->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-100">{{ $transaction->user->name }}</div>
                                <div class="text-sm text-gray-400">{{ $transaction->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($transaction->type === 'deposit') bg-green-100 text-green-800
                                    @elseif($transaction->type === 'withdrawal') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100">
                                ${{ number_format($transaction->amount_in_dollars, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                {{ Str::limit($transaction->description, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                       class="text-primary-400 hover:text-primary-300">View</a>
                                    <a href="{{ route('admin.transactions.edit', $transaction) }}" 
                                       class="text-yellow-400 hover:text-yellow-300">Edit</a>
                                    <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No transactions found</p>
                                    <p class="text-sm">Get started by creating your first transaction.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-dark-800">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
