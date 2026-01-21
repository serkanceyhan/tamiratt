@php
    $faqs = \App\Models\Faq::where('is_active', true)->orderBy('order')->get();
@endphp

<section class="py-16 bg-gray-50 dark:bg-gray-900" id="faq">
    <div class="max-w-[1280px] mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Sıkça Sorulan Sorular</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">Hizmetlerimiz hakkında merak ettiğiniz soruların cevaplarını burada bulabilirsiniz.</p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            @foreach($faqs as $faq)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $faq->question }}</span>
                        <span class="material-symbols-outlined text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }">expand_more</span>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 py-6">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
