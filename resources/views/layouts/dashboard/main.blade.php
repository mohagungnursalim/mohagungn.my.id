<!DOCTYPE html>
<html lang="en" x-data :class="$store.theme?.dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>[x-cloak]{display:none !important}</style>

    {{-- Hindari flicker: set "dark" sebelum CSS di-load --}}
    <script>
        (() => {
            try {
                const dark = localStorage.getItem('dark') === 'true';
                if (dark) document.documentElement.classList.add('dark');
            } catch (_) {}
        })();
    </script>

    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">

    {{-- Navbar (akan kita isi tombolnya) --}}
    @include('layouts.dashboard.navbar')

    {{-- Sidebar --}}
    @include('layouts.dashboard.sidebar')

    {{-- Konten --}}
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            {{ $slot }}
        </div>
    </div>

    @include('layouts.dashboard.footer')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    @stack('scripts')

</body>
</html>
