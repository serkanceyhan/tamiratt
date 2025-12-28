<x-landing-layout>
    {{-- SEO meta tags --}}
    <x-slot:head>
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
    </x-slot:head>

    <x-landing.header />
    
    <main class="flex-grow">
        {{-- Breadcrumb --}}
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

        {{-- Service Content --}}
        <section class="container mx-auto px-4 py-12">
            <article class="max-w-4xl mx-auto">
                {{-- Hero Section --}}
                <div class="mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $h1 }}</h1>
                    
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $location->name }}{{ $locationType === 'district' && $location->parent ? ', ' . $location->parent->name : '' }}
                        </span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose prose-lg max-w-none mb-12">
                    {!! $content !!}
                </div>

                {{-- CTA Section --}}
                <div class="bg-gradient-to-r from-primary-50 to-primary-100 rounded-2xl p-8 mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Hemen Ücretsiz Teklif Alın</h2>
                    <p class="text-gray-700 mb-6 text-lg">
                        {{ $location->name }} bölgesinde {{ strtolower($service->name) }} hizmeti için profesyonel ekibimizden ücretsiz fiyat teklifi alın.
                    </p>
                    <a href="#quote-form" class="inline-block bg-primary text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Teklif Formu İçin Tıklayın
                    </a>
                </div>
            </article>
        </section>
    </main>
    
    <x-landing.footer />
    <x-landing.quote-modal />
</x-landing-layout>
