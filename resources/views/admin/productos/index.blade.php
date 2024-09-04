<x-app-layout>
    @can('admin.productos')
        <div class="flex gap-3">

            @can('admin.productos.create')
                @livewire('admin.productos.create-producto')
            @endcan

            @can('admin.agregados')
                <x-link-modal class="px-1.5" href="{{ route('admin.agregados') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    AGREGADOS
                </x-link-modal>
            @endcan

            @can('admin.categories')
                <x-link-modal class="px-1.5" href="{{ route('admin.categorias') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    CATEGORÍAS
                </x-link-modal>
            @endcan
        </div>

        <x-tittle>LISTADO DE PRODUCTOS</x-tittle>
        @livewire('admin.productos.show-productos')
    @endcan
</x-app-layout>
