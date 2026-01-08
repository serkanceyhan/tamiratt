<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end gap-3 pt-4">
            <button 
                type="submit"
                wire:loading.attr="disabled"
                class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50 flex items-center gap-2"
            >
                <x-heroicon-s-check class="w-5 h-5" />
                <span wire:loading.remove wire:target="save">Değişiklikleri Kaydet</span>
                <span wire:loading wire:target="save">Kaydediliyor...</span>
            </button>
        </div>
    </form>
</x-filament-panels::page>
