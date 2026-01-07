<x-wizard-layout :title="$service->name . ' Talebi'">
    <div class="min-h-screen">
        {{-- Header with Logo --}}
        <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
            <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
                {{-- Logo (Left) --}}
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img src="https://www.tamiratt.com/assets/images/logo.png" alt="Ta'miratt" class="h-8 w-auto object-contain">
                </a>
                
                {{-- Service Name (Center) --}}
                <h1 class="text-base font-semibold text-gray-900 dark:text-white truncate max-w-[180px] sm:max-w-none text-center flex-1 mx-4">
                    {{ $service->name }}
                </h1>
                
                {{-- Close Button (Right) --}}
                <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0">
                    <span class="material-symbols-outlined">close</span>
                </a>
            </div>
        </header>

        {{-- Wizard Content --}}
        <main class="max-w-2xl mx-auto px-4 py-6">
            @livewire('request-wizard', [
                'serviceId' => $service->id,
                'subServiceId' => null,
                'locationId' => $location->id ?? null
            ])
        </main>
    </div>
</x-wizard-layout>
