<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Info Banner --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="flex gap-3">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <p>
                        Tamiratt e-arşiv/e-fatura uygulamasına geçti. Bu sistem ile artık resmi faturalarınız email yoluyla ve aşağıdaki linklere tıklayarak gönderilecektir. 
                        Geçmiş dönemlere ait faturalarınla ilgili soruların için 
                        <a href="mailto:destek@tamiratt.com" class="font-medium underline">destek@tamiratt.com</a> 
                        adresine iletebilirsin.
                    </p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
