<div>

    <div class="flex gap-3 mt-3">
        <x-input-text class="max-w-md" wire:model="search" placeholder="Buscar producto..." />

        <div class="relative" x-data="{ open: false }" @click.away="open = false">

            <button x-on:click="open = !open" :class="{ 'bg-opacity-90': open, 'bg-transparent': !open }"
                class="border-0 border-b w-32 sm:w-40 bg-transparent p-2.5 rounded-sm text-xs font-semibold border-gray-300 text-gray-500 focus:ring-0 focus:border-gray-400 text-center inline-flex items-center"
                type="button">Categoría
                <svg :class="{ 'rotate-180': open }"
                    class="absolute right-2 w-4 h-4 ml-2 transform transition duration-150" aria-hidden="true"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                    </path>
                </svg>
            </button>

            <div :class="{ 'block': open, 'hidden': !open }" x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute hidden z-10 w-full max-w-xs bg-fondocard rounded-lg shadow-md">
                <ul class="text-textoprincipal" aria-labelledby="dropdownCheckboxButton">

                    @if ($categories)
                        @foreach ($categories as $category)
                            <li>
                                <div class="w-full hover:bg-hoverbgnavlink rounded">
                                    <label for="checkbox-category-{{ $category->id }}"
                                        class="block w-full p-2 text-[10px] font-semibold cursor-pointer">
                                        <input id="checkbox-category-{{ $category->id }}" type="checkbox"
                                            value="{{ $category->id }}" wire:model="searchcategory"
                                            name="searchcategory[]"
                                            class="w-4 h-4 pr-1 text-checkboxdefault border-checkboxdefault cursor-pointer rounded focus:ring-0 focus:ring-transparent">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>

    @if (count($productos))
        <div class="text-left mt-3">
            {{ $productos->links() }}
        </div>

        <div class="relative overflow-x-auto sm:rounded-md mt-3">
            <x-table-default>
                <x-slot name="headers">
                    <tr>
                        <th class="p-2 text-center">IMAGEN</th>
                        <th class="p-2">NOMBRE PRODUCTO</th>
                        <th class="p-2">CATEGORÍA</th>
                        <th class="p-2">PRECIO</th>
                        <th class="p-2">AGREGADOS</th>
                        @canany(['admin.productos.edit', 'admin.productos.delete'])
                            <th class="p-2">OPCIONES</th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($productos as $item)
                        <tr class="border-b border-bghovertable hover:bg-bghovertable">
                            <td class="p-2 uppercase text-center">
                                <x-button-default wire:click="openmodalimagen({{ $item->id }})"
                                    wire:loading.attr="disabled">
                                    Imagen
                                </x-button-default>
                            </td>
                            <td class="p-2 uppercase text-left">{{ $item->name }} </td>
                            <td class="p-2 uppercase text-center">
                                @if ($item->category)
                                    {{ $item->category->name }}
                                @endif
                            </td>
                            <td class="p-2 uppercase">S/. {{ $item->price }}</td>
                            <td class="p-2 uppercase text-center">
                                @if ($item->agregados)
                                    <div class="w-full justify-center flex flex-wrap items-center gap-1">
                                        @if (count($item->agregados))
                                            @foreach ($item->agregados as $agregado)
                                                <x-span-text :text="$agregado->name" />
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                            </td>
                            @canany(['admin.productos.edit', 'admin.productos.delete'])
                                <td class="p-2 align-middle text-center">
                                    @can('admin.productos.edit')
                                        <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                            wire:click="edit({{ $item->id }})">
                                        </x-button-edit>
                                    @endcan
                                    @can('admin.productos.delete')
                                        <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                            wire:click="$emit('confirmDelete',{{ $item }})">
                                        </x-button-delete>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @endforeach
                </x-slot>
            </x-table-default>
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar Producto</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full flex flex-wrap md:flex-nowrap gap-2">
                    <div class="w-full md:w-2/3 space-y-2">

                        <div class="w-full">
                            <x-label value="Descripción producto :" />
                            <x-input-text wire:model.defer="producto.name" type="text"
                                placeholder="Ingrese nombre del producto..." />
                            <x-jet-input-error for="producto.name" />
                        </div>

                        <div class="w-full">
                            <x-label value="Categoría :" />
                            <x-select-input wire:model.defer="producto.category_id" class="w-full">
                                <x-slot name="options">
                                    <option value="" selected>Seleccionar</option>
                                    @if ($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select-input>
                            <x-jet-input-error for="producto.category_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="Precio :" />
                            <x-input-text wire:model.defer="producto.price" type="number" placeholder="0.00" />
                            <x-jet-input-error for="producto.name" />
                        </div>

                        <div class="w-full">
                            <x-label value="Porcentaje Rendimiento (%) :" />
                            <x-input-text wire:model.defer="producto.rendimiento" type="number" placeholder="0.00" />
                            <x-jet-input-error for="producto.rendimiento" />
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 text-center mt-2">
                        @if ($imagen)
                            <div class="w-full h-60 mx-auto block">
                                <img class="w-full h-full object-scale-down overflow-hidden block rounded shadow-lg"
                                    src="{{ $imagen->temporaryUrl() }}" alt="">
                            </div>
                        @else
                            @if ($producto->imagen)
                                <div class="w-full h-60 mx-auto block">
                                    <img class="w-full h-full object-scale-down overflow-hidden block rounded shadow-lg"
                                        src="{{ Storage::url('images/productos/' . $producto->imagen) }}"
                                        alt="">
                                </div>
                            @endif
                        @endif

                        <x-load-image wire:loading wire:target="imagen"></x-load-image>

                        <div class="w-full">
                            <x-label-file>
                                <span class="text-xs font-semibold">Adjuntar Imagen</span>
                                <input type="file" name="imagen" class="hidden" wire:model="imagen"
                                    accept="image/*" id="{{ $identificador }}">
                            </x-label-file>
                        </div>
                        <x-jet-input-error for="producto.imagen" />
                    </div>
                </div>

                @if (count($agregados))
                    <div class="w-full pt-4">
                        <x-label value="AGREGADOS :" />
                        <div class="w-full flex flex-wrap gap-1 items-center justify-start">
                            @foreach ($agregados as $agregado)
                                <x-label-checkbox :text="$agregado->name . '[S/.' . $agregado->price . ']'" :for="'agregado_edit_' . $agregado->id">
                                    <x-input-checkbox :value="$agregado->id" wire:model.defer="selectedAgregados"
                                        :id="'agregado_edit_' . $agregado->id" name="agregados[]" />
                                </x-label-checkbox>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="w-full mt-3 text-center">
                    <x-button-default wire:loading.attr="disabled" wire:target="update" type="submit">
                        Actualizar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="openimagen" maxWidth="xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Visualizar imágen producto</h1>
            <x-button-close-modal wire:click="$toggle('openimagen')"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <div class="w-full h-96">
                @if ($producto->imagen)
                    <img class="w-full h-full object-center object-cover overflow-hidden block rounded shadow-lg"
                        src="{{ Storage::url('images/productos/' . $producto->imagen) }}" alt="">
                @endif
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    @section('js')
        <script>
            Livewire.on('confirmDelete', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar producto : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.productos.show-productos', 'delete', event);
                        }
                    })
            });
        </script>
    @endsection

</div>
