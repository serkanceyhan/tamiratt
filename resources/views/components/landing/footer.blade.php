<footer class="bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-gray-800 pt-16 pb-8">
    <div class="max-w-[1280px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-1">
                <div class="mb-6">
                    <img alt="ta'miratt Logo" class="h-8 w-auto object-contain" src="https://www.tamiratt.com/assets/images/logo.png"/>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ T('footer.company_description') }}
                </p>
                <div class="flex gap-4">
                    <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
                    <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">mail</span></a>
                    <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">call</span></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">{{ T('footer.services') }}</h4>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    @foreach(\App\Models\Service::where('is_active', true)->take(5)->get() as $service)
                        <li><a class="hover:text-primary transition-colors" href="{{ route('seo.page', ['slug' => $service->slug]) }}">{{ $service->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">{{ T('footer.corporate') }}</h4>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">{{ T('footer.about') }}</a></li>
                    <li><a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">{{ T('footer.sustainability') }}</a></li>
                    <li><a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">{{ T('footer.references') }}</a></li>
                    <li><a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">{{ T('footer.career') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">{{ T('footer.contact_us') }}</h4>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-xs">location_on</span> {{ T('footer.location') }}</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-xs">phone</span> {{ T('footer.phone') }}</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-xs">email</span> {{ T('footer.email') }}</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-400">{{ T('footer.copyright', ['year' => date('Y')]) }}</p>
            <div class="flex gap-6 text-xs text-gray-400">
                <a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">{{ T('footer.privacy_policy') }}</a>
                <a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">{{ T('footer.terms_of_service') }}</a>
            </div>
        </div>
    </div>
</footer>
