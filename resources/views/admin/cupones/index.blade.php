<x-app-layout>

    @can('admin.cupones')
        @can('admin.cupones.create')
            <div class="flex gap-3">
                @livewire('admin.cupones.create-cupon')
            </div>
        @endcan

        <x-tittle>LISTADO DE CUPONES</x-tittle>
        @livewire('admin.cupones.show-cupones')
    @endcan
</x-app-layout>
