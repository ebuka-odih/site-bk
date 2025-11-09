@extends('admin.layouts.app')

@section('title', 'Generate Transaction Code')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 md:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.codes.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Codes
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-gray-900">Generate Transaction Code</h1>
        <p class="mt-1 text-sm text-gray-600">Create a new authorization code for transactions</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.codes.store') }}" method="POST" x-data="{ generatingMultiple: false }">
            @csrf

            <!-- Single or Bulk -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" x-model="generatingMultiple" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Generate multiple codes at once</span>
                </label>
            </div>

            <!-- Quantity (only for bulk) -->
            <div x-show="generatingMultiple" class="mb-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="quantity" id="quantity" min="1" max="50" value="5" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Maximum 50 codes at once</p>
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <select name="type" id="type" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Type</option>
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                    <option value="transfer">Transfer</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount ($) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="0.00">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expiry -->
            <div class="mb-6">
                <label for="expires_in_hours" class="block text-sm font-medium text-gray-700 mb-2">Expires In (Hours) <span class="text-red-500">*</span></label>
                <select name="expires_in_hours" id="expires_in_hours" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="1">1 Hour</option>
                    <option value="3">3 Hours</option>
                    <option value="6">6 Hours</option>
                    <option value="12">12 Hours</option>
                    <option value="24" selected>24 Hours</option>
                    <option value="48">48 Hours</option>
                    <option value="72">3 Days</option>
                    <option value="168">1 Week</option>
                </select>
                @error('expires_in_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes (only for single) -->
            <div x-show="!generatingMultiple" class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Add any notes about this code..."></textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.codes.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                    @click="if(generatingMultiple) { $el.form.action = '{{ route('admin.codes.bulk-generate') }}'; }"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <span x-show="!generatingMultiple">Generate Code</span>
                    <span x-show="generatingMultiple">Generate Codes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">How Transaction Codes Work</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Users enter these codes to authorize deposits or withdrawals</li>
                        <li>Each code can only be used once</li>
                        <li>Codes automatically expire after the specified time</li>
                        <li>All code usage is tracked in audit logs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


