<x-customer-layout title="Talep #{{ $requestId }}">
    @livewire('customer.request-detail', ['id' => $requestId])
</x-customer-layout>
