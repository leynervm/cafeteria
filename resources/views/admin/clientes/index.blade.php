<x-app-layout>

    @can('admin.clients')
        @can('admin.clients.create')
            <div class="flex gap-3">
                @livewire('admin.clientes.create-cliente')
            </div>
        @endcan

        <x-tittle>LISTADO DE CLIENTES</x-tittle>
        @livewire('admin.clientes.show-clientes')
    @endcan
</x-app-layout>
