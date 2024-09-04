<div>
    @if (count($clientes))
        <div class="sm:flex gap-3 mt-3">
            <x-input-text class="sm:max-w-md" placeholder="Buscar cliente..." />
            <x-input-text type="date" class="w-full sm:w-40 mt-2 sm:mt-0" />
        </div>

        <div class="text-left mt-3">
            {{ $clientes->links() }}
        </div>
    @endif

    @if (count($clientes))
        <div class="relative overflow-x-auto sm:rounded-md mt-3">
            <x-table-default>
                <x-slot name="headers">
                    <tr>
                        <th class="p-2">NOMBRES</th>
                        <th class="p-2">DIRECCIÓN</th>
                        <th class="p-2">TELEFONO</th>
                        <th class="p-2">FECHA NACIMIENTO</th>
                        <th class="p-2">OPCIONES</th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($clientes as $item)
                        <tr class="border-b border-bghovertable hover:bg-bghovertable">
                            <td class="p-2 uppercase">{{ $item->document }} {{ $item->name }}</td>
                            <td class="p-2 uppercase">{{ $item->direccion }}</td>
                            <td class="p-2 uppercase text-center">{{ $item->telefono }}</td>
                            <td class="p-2 uppercase text-center">{{ $item->dateparty }}</td>
                            <td class="p-2 align-middle text-center">
                                @can('admin.clients.edit')
                                    <x-button-edit class="inline-block" wire:loading.attr="disabled" wire:target="edit"
                                        wire:click="edit({{ $item->id }})">
                                    </x-button-edit>
                                @endcan
                                @can('admin.clients.delete')
                                    <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                        wire:click="$emit('confirmDelete',{{ $item }})"></x-button-delete>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table-default>
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar Cliente</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full flex items-end">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        <x-input-text wire:model.defer="client.document" type="number"
                            wire:keydown.enter="searchclient" wire:loading.attr="disabled" wire:target="searchclient" />
                    </div>
                    <x-button-default wire:click="searchclient" wire:loading.attr="disabled" wire:target="searchclient">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </x-button-default>
                </div>
                <x-jet-input-error for="client.document" />

                <div class="w-full mt-2">
                    <x-label value="NOMBRES :" />
                    <x-input-text wire:model.defer="client.name" type="text"
                        placeholder="Ingrese nombres del cliente..." />
                    <x-jet-input-error for="client.name" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="DIRECCIÓN :" />
                    <x-input-text wire:model.defer="client.direccion" type="text"
                        placeholder="Ingrese dirección del cliente..." />
                    <x-jet-input-error for="client.direccion" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="TELÉFONO / CELULAR  :" />
                    <x-input-text wire:model.defer="client.telefono" type="text"
                        placeholder="Ingrese teléfono del cliente..." />
                    <x-jet-input-error for="client.telefono" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="FECHA NACIMIENTO :" />
                    <x-input-text wire:model.defer="client.dateparty" type="date" />
                    <x-jet-input-error for="client.dateparty" />
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
                        text: 'Desea eliminar el cliente : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.clientes.show-clientes', 'delete', event);
                        }
                    })
            });
        </script>
    @endsection
</div>
