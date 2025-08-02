<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Document</title>

   <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="dark:bg-gray-800">
  
   {{-- Sidebar --}}
   @include('layouts.dashboard.sidebar')

   {{-- Konten --}}
   <div class="p-4 sm:ml-64 dark:bg-gray-800">
      <div class="p-4 border-2 border-white border-dashed rounded-lg dark:border-gray-100">
         @yield('content')
      </div>
   </div>
   
   @include('layouts.dashboard.footer')
