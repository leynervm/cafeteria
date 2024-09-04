<div>
    @if (count($othercostos))
        <div class="text-left mt-3">
            {{ $othercostos->links() }}
        </div>

        <div class="relative overflow-x-auto sm:rounded-md mt-3">
            <x-table-default>
                <x-slot name="headers">
                    <tr>
                        <th class="p-2">DESCRIPCION</th>
                        <th class="p-2">PRECIO</th>
                        @canany(['admin.othercostos.edit', 'admin.othercostos.delete'])
                            <th class="p-2">OPCIONES</th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($othercostos as $item)
                        <tr class="border-b border-bghovertable hover:bg-bghovertable">
                            <td class="p-2 uppercase text-left">{{ $item->name }} </td>
                            <td class="p-2 uppercase text-center">{{ $item->price }}</td>
                            @canany(['admin.othercostos.edit', 'admin.othercostos.delete'])
                                <td class="p-2 align-middle text-center">
                                    @can('admin.othercostos.edit')
                                        <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})">
                                        </x-button-edit>
                                    @endcan
                                    @can('admin.othercostos.delete')
                                        <x-button-delete wire:loading.attr="disabled"
                                            wire:click="$emit('confirmDeleteOthercosto',{{ $item }})">
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

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar otros costos</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="DESCRIPCIÓN :" />
                    <x-input-text wire:model.defer="othercosto.name" />
                    <x-jet-input-error for="othercosto.name" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="PRECIO :" />
                    <x-input-text wire:model.defer="othercosto.price" type="number" min="0" step="0.01" />
                    <x-jet-input-error for="othercosto.price" />
                </div>

                <div class="w-full text-center mt-3">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="update">
                        Actualizar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('confirmDeleteOthercosto', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar el registro con nombre : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.empresas.show-other-costos', 'delete', event.id);
                        }
                    })
            });
        })
    </script>
</div>
