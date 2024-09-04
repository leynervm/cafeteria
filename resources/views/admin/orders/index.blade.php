<x-app-layout>
    @can('admin.orders')
        <x-tittle>LISTADO DE ORDENES</x-tittle>
        @livewire('admin.orders.show-orders')
    @endcan
</x-app-layout>
