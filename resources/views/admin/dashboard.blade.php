@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your banking system')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Users -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-100 text-xs lg:text-sm font-medium">Total Users</p>
                    <p class="text-2xl lg:text-3xl font-bold">{{ $stats['total_users'] }}</p>
                    <p class="text-primary-100 text-xs lg:text-sm">+12% from last month</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs lg:text-sm font-medium">Transactions</p>
                    <p class="text-2xl lg:text-3xl font-bold">{{ $stats['total_transactions'] }}</p>
                    <p class="text-green-100 text-xs lg:text-sm">+8% from last month</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Balance -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs lg:text-sm font-medium">Total Balance</p>
                    <p class="text-2xl lg:text-3xl font-bold">${{ number_format($stats['total_balance'], 2) }}</p>
                    <p class="text-purple-100 text-xs lg:text-sm">+15% from last month</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-xs lg:text-sm font-medium">Active Sessions</p>
                    <p class="text-2xl lg:text-3xl font-bold">24</p>
                    <p class="text-orange-100 text-xs lg:text-sm">+3 from yesterday</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-dark-900 rounded-xl p-4 lg:p-6 border border-dark-800">
            <div class="flex items-center justify-between mb-4 lg:mb-6">
                <h3 class="text-base lg:text-lg font-semibold text-white">Recent Transactions</h3>
                <a href="{{ route('admin.transactions.index') }}" class="text-primary-400 hover:text-primary-300 text-xs lg:text-sm font-medium">View all</a>
            </div>
            
            <div class="space-y-4">
                @forelse($stats['recent_activity'] as $transaction)
                <div class="flex items-center justify-between p-3 lg:p-4 bg-dark-800 rounded-lg">
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-primary-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ $transaction['description'] ?? 'Transaction' }}</p>
                            <p class="text-gray-400 text-sm">{{ $transaction['date'] ?? 'Today' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-semibold">${{ number_format($transaction['amount'] ?? 0, 2) }}</p>
                        <p class="text-gray-400 text-sm">{{ $transaction['status'] ?? 'Completed' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <p class="text-gray-400">No recent transactions</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-dark-900 rounded-xl p-4 lg:p-6 border border-dark-800">
            <h3 class="text-base lg:text-lg font-semibold text-white mb-4 lg:mb-6">Quick Actions</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 lg:gap-4">
                <a href="{{ route('admin.users.create') }}" class="p-3 lg:p-4 bg-dark-800 rounded-lg hover:bg-dark-700 transition-colors group">
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-primary-600 rounded-lg flex items-center justify-center group-hover:bg-primary-500 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm lg:text-base">Add User</p>
                            <p class="text-gray-400 text-xs lg:text-sm">Create new account</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.transactions.create') }}" class="p-3 lg:p-4 bg-dark-800 rounded-lg hover:bg-dark-700 transition-colors group">
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-500 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm lg:text-base">New Transaction</p>
                            <p class="text-gray-400 text-xs lg:text-sm">Process payment</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="p-3 lg:p-4 bg-dark-800 rounded-lg hover:bg-dark-700 transition-colors group">
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-500 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm lg:text-base">Manage Users</p>
                            <p class="text-gray-400 text-xs lg:text-sm">View all accounts</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.profile') }}" class="p-3 lg:p-4 bg-dark-800 rounded-lg hover:bg-dark-700 transition-colors group">
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-orange-600 rounded-lg flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm lg:text-base">Profile</p>
                            <p class="text-gray-400 text-xs lg:text-sm">Account settings</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-dark-900 rounded-xl p-4 lg:p-6 border border-dark-800">
        <h3 class="text-base lg:text-lg font-semibold text-white mb-4 lg:mb-6">System Status</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <div>
                    <p class="text-white font-medium">Database</p>
                    <p class="text-gray-400 text-sm">Connected</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <div>
                    <p class="text-white font-medium">API Services</p>
                    <p class="text-gray-400 text-sm">Operational</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <div>
                    <p class="text-white font-medium">Backup</p>
                    <p class="text-gray-400 text-sm">In Progress</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
