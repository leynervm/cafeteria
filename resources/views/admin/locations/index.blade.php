<x-app-layout>
    @can('admin.locations')

        @can('admin.locations.create')
            <div class="flex gap-3">
                @livewire('admin.locations.create-location')
                <x-link-modal class="px-1.5">MESAS</x-link-modal>
            </div>
        @endcan

        <x-tittle>LISTADO DE UBICACIÓN DE MESAS</x-tittle>
        @livewire('admin.locations.show-locations')
    @endcan
</x-app-layout>
