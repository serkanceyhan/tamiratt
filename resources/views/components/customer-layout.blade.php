@props(['title' => 'Hesabım'])

<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | ta'miratt</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Fonts: Inter (Filament Default) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full bg-gray-50 text-gray-950 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex">
        {{-- Mobile Sidebar Overlay --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
            <div class="fixed inset-0 bg-gray-950/50 backdrop-blur-sm"></div>
        </div>

        {{-- Sidebar (Filament Replica) --}}
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-[270px] bg-white border-r border-gray-950/5 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto"
        >
            {{-- Logo Header --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-950/5">
                <a href="/" class="flex items-center gap-2">
                    <img alt="ta'miratt Logo" class="h-8 w-auto" src="https://www.tamiratt.com/assets/images/logo.png"/>
                </a>
                <button @click="sidebarOpen = false" class="ml-auto lg:hidden p-1.5 rounded-lg hover:bg-gray-50 text-gray-500">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
                @php
                    $currentRoute = request()->route()->getName() ?? '';
                @endphp

                {{-- Dashboard Group (No Header) --}}
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentRoute === 'dashboard' ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-950' }}">
                            @if($currentRoute === 'dashboard')
                                <svg class="w-6 h-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                            @endif
                            Panel
                        </a>
                    </li>
                </ul>

                {{-- Talepler Group --}}
                <div>
                    <h3 class="px-3 text-sm font-medium text-gray-500 mb-2">Talepler</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'customer.request') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-950' }}">
                                @if(str_starts_with($currentRoute, 'customer.request'))
                                    <svg class="w-6 h-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                                    </svg>
                                @endif
                                Taleplerim
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Bildirimler Group --}}
                <div>
                    <h3 class="px-3 text-sm font-medium text-gray-500 mb-2">Bildirimler</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-950 transition-colors">
                                <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                Bildirimler
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Ayarlar Group --}}
                <div>
                     <h3 class="px-3 text-sm font-medium text-gray-500 mb-2">Ayarlar</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-950 transition-colors">
                                <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Profilim
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Footer Actions --}}
                <div class="pt-6 mt-6 border-t border-gray-950/5">
                    <ul class="space-y-1">
                        @if(!auth()->user()->isProvider())
                            <li>
                                <a href="{{ route('provider.apply') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-950 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400 hover:text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.702.127 1.5.876 2.056 2.671 1.725 4.148-.28 1.25-.972 2.227-1.898 2.872M4.646 4.39a3.75 3.75 0 105.303 5.303 3.75 3.75 0 00-5.303-5.303z" />
                                    </svg>
                                    Hizmet Veren Ol
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="/panel" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-950 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400 hover:text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                                    </svg>
                                    Hizmet Veren Paneli
                                </a>
                            </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-950 transition-colors">
                                    <svg class="w-6 h-6 text-gray-400 hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    Çıkış Yap
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        {{-- Main Content Wrapper --}}
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50">
           {{-- Top Bar (Matches Filament Panel) --}}
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-950/5">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-gray-50 text-gray-500">
                     <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex-1"></div> {{-- Spacer --}}

                <div class="flex items-center gap-4">
                     <a href="/" class="hidden sm:flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Ana Sayfa
                    </a>
                    
                    <a href="{{ route('services') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-primary-600 hover:bg-primary-500 text-white shadow-sm transition-colors" title="Yeni Talep">
                         <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative ml-2" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3">
                             <div class="hidden md:flex flex-col items-end mr-1">
                                 <span class="text-sm font-semibold text-gray-700 leading-tight">{{ auth()->user()->name }}</span>
                                 <span class="text-[11px] text-gray-500 leading-tight">Müşteri</span>
                             </div>
                             <div class="w-9 h-9 rounded-full bg-gray-950 text-white flex items-center justify-center text-sm font-semibold shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                             </div>
                             <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        
                        <div x-show="open" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                             <div class="px-4 py-3 border-b border-gray-50 md:hidden">
                                  <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                             </div>
                             <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profilim</a>
                             <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Çıkış Yap</button>
                             </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-6 lg:p-8 max-w-7xl mx-auto w-full">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>
