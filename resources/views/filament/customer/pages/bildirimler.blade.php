<x-filament-panels::page>
    <div class="space-y-6">
        @if($notifications->isEmpty())
            <div class="fi-ta-empty-state flex flex-col items-center justify-center px-6 py-12">
                <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                    <x-heroicon-o-bell-slash class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                </div>
                <h3 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Bildirim yok
                </h3>
                <p class="fi-ta-empty-state-description mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Yeni bildirimler geldiğinde burada görünecek.
                </p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                @foreach($notifications as $notification)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-500/20 flex items-center justify-center">
                                    <x-heroicon-o-bell class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $notification->data['title'] ?? 'Bildirim' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $notification->data['body'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>
