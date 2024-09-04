<x-app-layout>

    @can('admin.othercostos')
        <x-tittle>ADMINISTRAR OTROS COSTOS</x-tittle>
        @can('admin.othercostos.create')
            <div class="mt-2">
                @livewire('admin.empresas.create-other-costo')
            </div>
        @endcan
        @livewire('admin.empresas.show-other-costos')
    @endcan

    @can('admin.formpayment')
        <x-tittle>ADMINISTRAR FORMAS DE PAGO</x-tittle>
        @can('admin.formpayment.create')
            <div class="mt-2">
                @livewire('admin.empresas.create-forma-pago')
            </div>
        @endcan
        @livewire('admin.empresas.show-forma-pagos')
    @endcan


    @can('admin.mesas')
        <x-tittle class="mt-8">ADMINISTRAR MESAS</x-tittle>
        @can('admin.mesas.create')
            <div class="mt-2">
                @livewire('admin.mesas.create-mesa')
            </div>
        @endcan
        @livewire('admin.mesas.show-mesas')
    @endcan

    @can('admin.locations')
        <x-tittle class="mt-8">ADMINISTRAR UBICACIONES DE MESAS</x-tittle>
        @can('admin.locations.create')
            <div class="mt-2">
                @livewire('admin.locations.create-location')
            </div>
        @endcan
        @livewire('admin.locations.show-locations')
    @endcan

    @can('admin.series')
        <x-tittle class="mt-8">ADMINISTRAR TIPO COMPROBANTES</x-tittle>
        @can('admin.series.create')
            <div class="mt-2">
                @livewire('admin.series.create-serie')
            </div>
        @endcan
        @livewire('admin.series.show-series')
    @endcan

</x-app-layout>
