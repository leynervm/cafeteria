<x-app-layout>
    @can('admin.comprobantes')
        <div>
            <x-tittle>LISTADO DE COMPROBANTES ELECTRÓNICOS</x-tittle>
            @livewire('admin.comprobantes.show-comprobantes')
        </div>
    @endcan
</x-app-layout>
