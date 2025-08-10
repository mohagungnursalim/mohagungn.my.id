<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="dark:bg-gray-800">

    {{-- Navbar --}}
    @include('layouts.dashboard.navbar')

    {{-- Sidebar --}}
    @include('layouts.dashboard.sidebar')

    {{-- Konten --}}
    <div class="p-4 sm:ml-64 dark:bg-gray-800">
        <div class="p-4 border-2 border-white border-dashed rounded-lg dark:border-gray-100 mt-14">
            {{ $slot }}
        </div>
    </div>

    @include('layouts.dashboard.footer')

    @livewireScripts
    @stack('scripts')
</body>

</html>
