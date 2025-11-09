@extends('admin.layout')

@section('title', 'Create User')
@section('page-title', 'Create New User')
@section('page-description', 'Add a new user to the system')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-900 rounded-xl p-6 border border-dark-800">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Balance -->
            <div>
                <label for="balance" class="block text-sm font-medium text-gray-300 mb-2">Initial Balance</label>
                <input type="number" id="balance" name="balance" value="{{ old('balance', 0) }}" step="0.01" min="0"
                       class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('balance') border-red-500 @enderror">
                @error('balance')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin Role -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}
                       class="w-4 h-4 text-primary-600 bg-dark-800 border-dark-700 rounded focus:ring-primary-500 focus:ring-2">
                <label for="is_admin" class="text-sm font-medium text-gray-300">Grant admin privileges</label>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-dark-800">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-3 bg-dark-800 hover:bg-dark-700 text-white rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
