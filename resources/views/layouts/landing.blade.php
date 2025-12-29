<!DOCTYPE html>
<html class="light" lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($head)
        {{ $head }}
    @else
        <title>{{ config('app.name', 'ta\'miratt') }} - Ofis Mobilyası Tamirat Çözümleri</title>
    @endisset

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0e121b] dark:text-white font-body" x-data="{ quoteModalOpen: false }">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
        {{ $slot }}
        <x-landing.scroll-to-top />
        <x-landing.whatsapp-widget />
    </div>
</body>
</html>
