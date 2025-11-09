<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false" aria-hidden="true"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <x-application-logo class="h-8 w-auto" />
                        <span class="ml-2 text-white text-xl font-semibold">{{ config('app.name') }}</span>
                    </div>
                    @include('admin.partials.navigation')
                </div>
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <a href="#" class="flex-shrink-0 group block">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-9 w-9 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-gray-400 group-hover:text-gray-300">View profile</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex-shrink-0 w-14">
                <!-- Force sidebar to shrink to fit close icon -->
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-gray-800">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <x-application-logo class="h-8 w-auto" />
                        <span class="ml-2 text-white text-xl font-semibold">{{ config('app.name') }}</span>
                    </div>
                    @include('admin.partials.navigation')
                </div>
                <div class="flex-shrink-0 flex border-t border-gray-700 p-4">
                    <a href="#" class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-9 w-9 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-gray-400 group-hover:text-gray-300">View profile</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="md:pl-64 flex flex-col flex-1">
            <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-100">
                <button @click="sidebarOpen = true" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <main class="flex-1">
                <div class="py-6">
                    @if (session('success'))
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                            <div class="rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-4">
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
