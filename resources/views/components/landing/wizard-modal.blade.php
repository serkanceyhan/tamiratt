{{-- Request Wizard Modal --}}
{{-- Armut-style locked service flow --}}

<div 
    x-show="quoteModalOpen" 
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    aria-labelledby="wizard-modal-title" 
    role="dialog" 
    aria-modal="true"
>
    {{-- Background backdrop --}}
    <div 
        x-show="quoteModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="quoteModalOpen = false"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
    ></div>

    {{-- Modal Panel --}}
    <div 
        x-show="quoteModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-lg bg-white dark:bg-surface-dark rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
    >
        {{-- Close Button --}}
        <button 
            @click="quoteModalOpen = false"
            class="absolute top-4 right-4 z-10 p-1 text-gray-400 hover:text-gray-600 transition-colors"
        >
            <span class="material-symbols-outlined">close</span>
        </button>

        {{-- Livewire Wizard Component --}}
        <div class="p-6">
            @livewire('request-wizard', [
                'serviceId' => $service->id ?? null,
                'subServiceId' => null,
                'locationId' => $location->id ?? null
            ])
        </div>
    </div>
</div>

{{-- Sticky CTA Button (appears after scroll) --}}
<div 
    x-data="{ showSticky: false }"
    x-init="window.addEventListener('scroll', () => { showSticky = window.scrollY > 400 })"
    class="fixed bottom-20 left-0 right-0 z-50 px-4 pointer-events-none md:hidden"
>
    <div 
        x-show="showSticky"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="max-w-md mx-auto pointer-events-auto"
    >
        <button 
            @click="quoteModalOpen = true"
            class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-primary text-white font-bold rounded-xl shadow-2xl"
        >
            <span class="material-symbols-outlined">edit_note</span>
            <span>Hemen Teklif Al</span>
        </button>
    </div>
</div>
