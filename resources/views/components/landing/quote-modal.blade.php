<div 
    x-show="quoteModalOpen" 
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Background backdrop -->
    <div 
        x-show="quoteModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
        @click="quoteModalOpen = false"
    ></div>

    <!-- Modal panel -->
    <div 
        x-show="quoteModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-lg bg-white dark:bg-surface-dark rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto z-10"
        @click.stop
    >
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">Ücretsiz Teklif Al</h2>
                <button 
                    @click="quoteModalOpen = false"
                    type="button"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            {{-- Livewire Home Quote Wizard --}}
            <livewire:home-quote-wizard />
        </div>
    </div>
</div>
