<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SPK PKH SAW' }} - Desa Thaibah Raya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity duration-200"
         x-transition:leave="transition-opacity duration-200"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" style="display: none;"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-200 lg:translate-x-0 lg:static lg:inset-auto lg:shrink-0 border-r border-slate-800 shadow-xl">
        @include('layouts.sidebar')
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto min-h-screen">
        <!-- Top App Bar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 p-1.5 rounded-lg border border-slate-200">
                        ☰
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ $header ?? 'Sistem Pendukung Keputusan PKH' }}</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">Metode SAW • Kantor Desa Thaibah Raya, Kec. Tatah Makmur, Kab. Banjar</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Online
                    </span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-6 lg:px-8 pt-4">
            @if(session('success'))
                <div class="p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-xs">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2 shadow-xs">
                    <span class="text-rose-600 font-bold">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm shadow-xs">
                    <div class="font-semibold mb-1">Periksa isian form berikut:</div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Body Content -->
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Kantor Desa Thaibah Raya, Kec. Tatah Makmur, Kab. Banjar, Kalimantan Selatan.
        </footer>
    </div>

</body>
</html>
