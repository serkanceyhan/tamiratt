<x-landing-layout>
    <x-slot:head>
        <title>Giriş Yap | ta'miratt</title>
        <meta name="description" content="ta'miratt hesabınıza telefon numaranız ile giriş yapın.">
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow py-16 px-6 bg-gradient-to-br from-blue-50 to-white dark:from-gray-900 dark:to-background-dark">
        <div class="max-w-[1280px] mx-auto">
            @livewire('customer-login')
        </div>
    </main>

    <x-landing.footer />
</x-landing-layout>
