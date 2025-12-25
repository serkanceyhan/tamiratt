<section class="py-20 bg-white dark:bg-background-dark border-t border-gray-100 dark:border-gray-800" id="services">
    <div class="max-w-[1100px] mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-primary font-semibold text-sm uppercase tracking-wider mb-2 block">Hizmetlerimiz</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">Ofisiniz İçin Profesyonel Çözümler</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $services = \App\Models\Service::where('is_active', true)
                    ->where('show_on_homepage', true)
                    ->whereNull('parent_id')
                    ->orderBy('name')
                    ->take(6)
                    ->get();
                    
                $colors = [
                    ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-primary'],
                    ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-600'],
                    ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-600'],
                    ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-600'],
                    ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-600'],
                    ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-600'],
                ];
            @endphp
            
            @foreach($services as $index => $service)
                @php
                    $color = $colors[$index % count($colors)];
                    $icon = $service->icon ?? 'chair'; // Fallback to chair if no icon
                @endphp
                <a href="{{ route('seo.page', ['slug' => $service->slug]) }}" 
                   class="group relative p-8 rounded-2xl bg-surface-light dark:bg-surface-dark border border-transparent hover:border-primary/20 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="w-14 h-14 {{ $color['bg'] }} rounded-xl flex items-center justify-center {{ $color['text'] }} mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $service->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
                        {{ Str::limit($service->short_description ?? strip_tags($service->master_content), 120) }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>
</section>
