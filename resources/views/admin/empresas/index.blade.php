<x-app-layout>

    @can('admin.empresa')
        <x-tittle>DATOS DE EMPRESA</x-tittle>
        @livewire('admin.empresas.show-empresas')
    @endcan
</x-app-layout>
