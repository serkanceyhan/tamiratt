@php
    $services = \App\Models\Service::where('is_active', true)->get();
    $cities = \App\Models\Location::where('type', 'city')->where('is_active', true)->get();
@endphp

<x-landing-layout>
    <x-slot:head>
        <title>Hizmetlerimiz | ta'miratt</title>
        <meta name="description" content="Ofis mobilyası tamiri ve yenileme hizmetlerimiz. Tüm hizmet kategorilerimizi ve hizmet verdiğimiz şehirleri keşfedin.">
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow">
        {{-- Page Header --}}
        <section class="bg-gray-50 dark:bg-background-dark py-16 border-b border-gray-200 dark:border-gray-800">
            <div class="max-w-[1280px] mx-auto px-6 text-center">
                <h1 class="text-4xl lg:text-5xl font-black text-gray-900 dark:text-white mb-4">Hizmetlerimiz</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Ofis mobilyalarınızı fabrika çıkışı kalitesinde yeniliyoruz. Tüm hizmet kategorilerimizi ve hizmet verdiğimiz bölgeleri aşağıda bulabilirsiniz.
                </p>
            </div>
        </section>

        {{-- Services Grid --}}
        <section class="py-16 bg-white dark:bg-background-dark">
            <div class="max-w-[1280px] mx-auto px-6">
                @php
                    // Group services by parent
                    $parentServices = $services->whereNull('parent_id');
                    $childServices = $services->whereNotNull('parent_id')->groupBy('parent_id');
                @endphp

                @foreach($parentServices as $parent)
                    <div class="mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ $parent->name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @if(isset($childServices[$parent->id]))
                                @foreach($childServices[$parent->id] as $service)
                                    <a href="{{ route('seo.page', ['slug' => $service->slug]) }}" 
                                       class="group block p-6 bg-white dark:bg-surface-dark rounded-xl border-2 border-gray-100 dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-all hover:shadow-xl">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary text-2xl">home_repair_service</span>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-primary transition-colors">
                                                    {{ $service->name }}
                                                </h3>
                                                @if($service->short_description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                                        {{ $service->short_description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Cities Grid --}}
        <section class="py-16 bg-gray-50 dark:bg-surface-dark border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-[1280px] mx-auto px-6">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Hizmet Verdiğimiz Şehirler</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($cities as $city)
                        <div class="bg-white dark:bg-background-dark rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition-shadow">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-3">{{ $city->name }}</h3>
                            <div class="space-y-2">
                                @foreach($city->children()->where('is_active', true)->limit(5)->get() as $district)
                                    @php
                                        // Get first active service for this district
                                        $firstService = $services->first();
                                        $districtUrl = $firstService ? route('seo.page', ['slug' => $district->slug . '-' . $firstService->slug]) : '#';
                                    @endphp
                                    <a href="{{ $districtUrl }}" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">
                                        {{ $district->name }}
                                    </a>
                                @endforeach
                                @if($city->children()->where('is_active', true)->count() > 5)
                                    <span class="block text-xs text-gray-400 mt-2">+{{ $city->children()->where('is_active', true)->count() - 5 }} ilçe daha</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <x-landing.footer />
</x-landing-layout>
