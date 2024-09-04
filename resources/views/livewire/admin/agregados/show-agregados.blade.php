<div>
    <div class="flex gap-3 mt-3">
        <x-input-text class="max-w-md" placeholder="Buscar agregado..." wire:model="search" />
    </div>

    @if (count($agregados))
        <div class="text-left mt-3">
            {{ $agregados->links() }}
        </div>
    @endif


    <div class="relative overflow-x-auto sm:rounded-md mt-3">
        <x-table-default>
            <x-slot name="headers">
                <tr>
                    <th class="p-2 text-left">NOMBRE AGREGADO</th>
                    <th class="p-2">PRECIO</th>
                    @canany(['admin.agregados.edit', 'admin.agregados.delete'])
                        <th class="p-2">OPCIONES</th>
                    @endcanany
                </tr>
            </x-slot>
            <x-slot name="rows">
                @foreach ($agregados as $item)
                    <tr class="border-b border-bghovertable hover:bg-bghovertable">
                        <td class="p-2 uppercase text-left">{{ $item->name }} </td>
                        <td class="p-2 text-center">S/. {{ $item->price }}</td>
                        @canany(['admin.agregados.edit', 'admin.agregados.delete'])
                            <td class="p-2 align-middle text-center">
                                @can('admin.agregados.edit')
                                    <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                        wire:click="edit({{ $item->id }})">
                                    </x-button-edit>
                                @endcan
                                @can('admin.agregados.delete')
                                    <x-button-delete wire:loading.attr="disabled" wire:target="delete"
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


    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Actualizar Agregado</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="Descripción agregado :" />
                    <x-input-text wire:model.defer="agregado.name" type="text"
                        placeholder="Ingrese nombre del agregado..." />
                    <x-jet-input-error for="agregado.name" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Precio :" />
                    <x-input-text wire:model.defer="agregado.price" type="number" placeholder="0.00" />
                    <x-jet-input-error for="agregado.price" />
                </div>
                <div class="w-full mt-3 text-center">
                    <x-button-default wire:loading.attr="disabled" wire:target="update" type="submit">
                        Actualizar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    @section('js')
        <script>
            Livewire.on('confirmDelete', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar agregado : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.agregados.show-agregados', 'delete', event);
                        }
                    })
            });
        </script>
    @endsection

</div>
