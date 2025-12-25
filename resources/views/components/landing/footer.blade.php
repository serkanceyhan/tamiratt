<footer class="bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-gray-800 pt-16 pb-8">
    <div class="max-w-[1280px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-1">
                <div class="mb-6">
                    <img alt="Flux360 Logo" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCL0Ux4Xs6k2OJMKMUgBkKHQEuyjl2LOpicn9nENU_zTyXiVeTv75FwZHISFozvtmyaGsmFQ62mLwjwieHs0oMR3Trk3_GdhLkHmcfPIIs6dTuZdy2piKAi00g2r8oHkF5pNiDwZfbxDRdoOJi-D6MOmq9mvZB9wT4z_1QyS0Dy66uBW2Gpd0_0m-Lb4KGqNqLGuPDquAYmOmAE7Ez6wCaRumVq8Vd7eU-ahoJl-3Nu9zdlOzNa21wucXYxP0HsbveIYfTmr7zz464"/>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Ofis mobilyaları için sürdürülebilir, ekonomik ve profesyonel tamirat çözümleri.
                </p>
                <div class="flex gap-4">
                    <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
                    <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">mail</span></a>
                    <a class="text-gray-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined">call</span></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Hizmetler</h4>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    @foreach(\App\Models\Service::where('is_active', true)->take(5)->get() as $service)
                        <li><a class="hover:text-primary transition-colors" href="{{ route('seo.page', ['slug' => $service->slug]) }}">{{ $service->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Kurumsal</h4>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-primary" href="#">Hakkımızda</a></li>
                    <li><a class="hover:text-primary" href="#">Sürdürülebilirlik Raporu</a></li>
                    <li><a class="hover:text-primary" href="#">Referanslar</a></li>
                    <li><a class="hover:text-primary" href="#">Kariyer</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Bize Ulaşın</h4>
                <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-xs">location_on</span> Maslak, İstanbul</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-xs">phone</span> +90 (212) 555 0123</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-xs">email</span> info@tamirat.com</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-400">© 2024 Flux360. Tüm hakları saklıdır.</p>
            <div class="flex gap-6 text-xs text-gray-400">
                <a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">Gizlilik Politikası</a>
                <a class="hover:text-gray-600 dark:hover:text-gray-200" href="#">Kullanım Şartları</a>
            </div>
        </div>
    </div>
</footer>
