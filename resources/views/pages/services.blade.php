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
                                                <span class="material-symbols-outlined text-primary text-2xl">{{ $service->icon ?? 'home_repair_service' }}</span>
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

        {{-- Cities & Districts Grid --}}
        <section class="py-16 bg-gray-50 dark:bg-surface-dark border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-[1280px] mx-auto px-6">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Hizmet Verdiğimiz Bölgeler</h2>
                
                @foreach($cities as $city)
                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">location_city</span>
                            {{ $city->name }}
                        </h3>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                            @foreach($city->children()->where('is_active', true)->orderBy('name')->get() as $district)
                                @php
                                    $firstService = $services->first();
                                    $districtUrl = $firstService ? route('seo.page', ['slug' => $district->slug . '-' . $firstService->slug]) : '#';
                                @endphp
                                <a href="{{ $districtUrl }}" 
                                   class="group flex items-center justify-center gap-1 p-2 bg-white dark:bg-background-dark rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-all border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md">
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors text-center">{{ $district->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <x-landing.footer />
</x-landing-layout>
