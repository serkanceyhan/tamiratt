<!DOCTYPE html>
<html class="light" lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $page->location->name }} - {{ $page->service->name }} | Flux360</title>
    <meta name="description" content="{{ $page->location->parent->name }}, {{ $page->location->name }} bölgesinde profesyonel {{ $page->service->name }} hizmeti">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2463eb",
                        "secondary": "#16A34A",
                        "background-light": "#ffffff",
                        "background-dark": "#111621",
                        "surface-light": "#f8f9fc",
                        "surface-dark": "#1e2430",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"],
                        "body": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#0e121b] dark:text-white font-body" x-data="{ quoteModalOpen: false }">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
    <x-landing.header />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-sm text-gray-600">
                <a href="/" class="hover:text-gray-900">Ana Sayfa</a> 
                <span class="mx-2">›</span>
                <a href="{{ route('seo.page', ['slug' => $page->service->slug]) }}" class="hover:text-gray-900">
                    {{ $page->service->name }}
                </a>
                <span class="mx-2">›</span>
                <a href="{{ route('seo.page', ['slug' => $page->location->parent->slug . '-' . $page->service->slug]) }}" class="hover:text-gray-900">
                    {{ $page->location->parent->name }}
                </a>
                <span class="mx-2">›</span>
                <span class="text-gray-900">{{ $page->location->name }}</span>
            </nav>

            {{-- Hero Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h1 class="text-4xl font-bold mb-4">
                    {{ $page->custom_hero_title ?? $page->location->name . ' ' . $page->service->name }}
                </h1>
                @if($page->service->short_description)
                    <p class="text-lg text-gray-600 mb-2">{{ $page->service->short_description }}</p>
                @endif
                <p class="text-xl text-gray-600">{{ $page->location->parent->name }}, {{ $page->location->name }} Bölgesinde Profesyonel Hizmet</p>
            </div>

            {{-- İçerik --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="prose max-w-none">
                    @if($page->custom_content)
                        {!! $page->custom_content !!}
                    @else
                        {!! str_replace('{location}', $page->location->name, $page->service->master_content) !!}
                    @endif
                </div>
            </div>

            {{-- Neden Bizi Seçmelisiniz --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">Neden Bizi Seçmelisiniz?</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="p-6 border rounded-lg">
                        <div class="text-blue-600 text-3xl mb-4">✓</div>
                        <h3 class="font-bold mb-2">Deneyimli Ekip</h3>
                        <p class="text-gray-600">{{ $page->location->name }}'da uzman kadromuzla hizmetinizdeyiz</p>
                    </div>
                    <div class="p-6 border rounded-lg">
                        <div class="text-blue-600 text-3xl mb-4">⚡</div>
                        <h3 class="font-bold mb-2">Hızlı Servis</h3>
                        <p class="text-gray-600">Aynı gün içinde {{ $page->location->name }}'a ulaşıyoruz</p>
                    </div>
                    <div class="p-6 border rounded-lg">
                        <div class="text-blue-600 text-3xl mb-4">💰</div>
                        <h3 class="font-bold mb-2">Uygun Fiyat</h3>
                        <p class="text-gray-600">{{ $page->location->name }} için özel fiyatlandırma</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="bg-blue-600 text-white p-8 rounded-lg text-center">
                <h3 class="text-2xl font-bold mb-4">{{ $page->location->name }}'da Hemen Teklif Alın</h3>
                <p class="mb-6">{{ $page->service->name }} hizmeti için ücretsiz teklif alın</p>
                <a href="#quote-form" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Teklif Formu
                </a>
            </div>
        </div>
    </div>
    
    <x-landing.footer />
    <x-landing.scroll-to-top />
    </div>
</body>
</html>
