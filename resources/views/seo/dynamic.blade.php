<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    
    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Tamiratt - {{ $service->name }}",
        "description": "{{ $metaDescription }}",
        "areaServed": {
            "@type": "City",
            "name": "{{ $location->name }}"
        },
        "serviceType": "{{ $service->name }}"
    }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-landing.header />
    
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-3">
            <nav class="flex text-sm text-gray-600">
                @foreach($breadcrumb as $index => $item)
                    @if($loop->last)
                        <span class="text-gray-900 font-medium">{{ $item['name'] }}</span>
                    @else
                        @if($item['url'])
                            <a href="{{ $item['url'] }}" class="hover:text-primary-600">{{ $item['name'] }}</a>
                        @else
                            <span>{{ $item['name'] }}</span>
                        @endif
                        <span class="mx-2">›</span>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <article class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8">
            <!-- Hero Title -->
            <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $h1 }}</h1>
            
            <!-- Location Badge -->
            <div class="mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    {{ $location->name }}{{ $locationType === 'district' && $location->parent ? ', ' . $location->parent->name : '' }}
                </span>
            </div>
            
            <!-- Service Content -->
            <div class="prose prose-lg max-w-none">
                {!! $content !!}
            </div>
            
            <!-- CTA Section -->
            <div class="mt-12 p-6 bg-gradient-to-r from-primary-50 to-primary-100 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Hemen Teklif Alın</h3>
                <p class="text-gray-700 mb-6">
                    {{ $location->name }} bölgesinde {{ strtolower($service->name) }} hizmeti için ücretsiz fiyat teklifi alın.
                </p>
                <a href="#quote-form" class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                    Teklif Formu
                </a>
            </div>
        </article>
        
        <!-- Quote Form Section -->
        <div id="quote-form" class="max-w-2xl mx-auto mt-12">
            <!-- Your existing quote form component here -->
        </div>
    </main>
    
    <!-- Footer -->
    <x-landing.footer />
</body>
</html>
