<div>
    <div class="relative overflow-x-auto sm:rounded-md mt-3">
        <x-table-default>
            <x-slot name="headers">
                <tr>
                    <th class="p-2">USUARIO</th>
                    <th class="p-2">NOMBRES</th>
                    <th class="p-2">ROL</th>
                    <th class="p-2">MESAS AGREGADAS</th>
                    <th class="p-2">CATEGORIAS AGREGADAS</th>
                    <th class="p-2">STATUS</th>
                    <th class="p-2">OPCIONES</th>
                </tr>
            </x-slot>
            <x-slot name="rows">
                @if (count($users))
                    @foreach ($users as $item)
                        <tr class="border-b border-b-bghovertable hover:bg-bghovertable">
                            <td class="p-2 uppercase text-center">{{ $item->name }}</td>
                            <td class="p-2 uppercase text-center">{{ $item->email }}</td>
                            <td class="p-2 uppercase">{{ $item->role->name }}</td>
                            <td class="p-2 uppercase text-center">
                                @if (count($item->mesas))
                                    @foreach ($item->mesas as $mesa)
                                        <x-span-text :text="$mesa->name" />
                                    @endforeach
                                @endif
                            </td>
                            <td class="p-2 uppercase text-center">
                                @if (count($item->categories))
                                    @foreach ($item->categories as $category)
                                        <x-span-text :text="$category->name" />
                                    @endforeach
                                @endif
                            </td>
                            <td class="p-2 uppercase text-center">

                                @if ($item->status == 1)
                                    <small
                                        class="inline-block bg-red-500 text-white leading-3 rounded p-1 text-xs">Suspendido</small>
                                @else
                                    <small
                                        class="inline-block bg-green-500 text-white leading-3 rounded p-1 text-xs">Activo</small>
                                @endif
                            </td>
                            <td class="p-2 align-middle text-center">
                                @can('admin.users.edit')
                                    <x-button-edit class="inline-block" wire:loading.attr="disabled" wire:target="edit"
                                        wire:click="edit({{ $item->id }})">
                                    </x-button-edit>
                                @endcan
                                @can('admin.users.delete')
                                    <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                        wire:click="$emit('confirmDelete',{{ $item }})"></x-button-delete>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @endif
            </x-slot>
        </x-table-default>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Actualizar Usuario</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="NOMBRES :" />
                    <x-input-text wire:model.defer="user.name" type="text"
                        placeholder="Ingrese nombre del usuario..." />
                    <x-jet-input-error for="user.name" />
                </div>

                <div class="flex gap-2 flex-wrap sm:flex-nowrap mt-2">
                    <div class="w-full">
                        <x-label value="USUARIO :" />
                        <x-input-text wire:model.defer="user.email" type="text"
                            placeholder="Ingrese correo del usuario..." />
                        <x-jet-input-error for="user.email" />
                    </div>

                    <div class="w-full">
                        <x-label value="ROL :" />
                        <x-select-input wire:model.defer="user.role_id" class="block w-full">
                            <x-slot name="options">
                                <option value="" selected>Seleccionar</option>
                                @if (count($roles))
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select-input>
                        <x-jet-input-error for="user.role_id" />
                    </div>
                </div>

                <h1 class="font-bold text-xs border-b inline-block my-3 border-textoprincipal text-textoprincipal">
                    ASIGNAR MESAS</h1>

                @if (count($mesas))
                    <div class="w-full flex flex-wrap gap-1 items-center">
                        @foreach ($mesas as $mesa)
                            <x-label-checkbox :text="$mesa->name" :for="'mesa_edit_' . $mesa->id">
                                <x-input-checkbox :value="$mesa->id" wire:model.defer="selectedMesas" :id="'mesa_edit_' . $mesa->id"
                                    name="mesas[]" />
                            </x-label-checkbox>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedMesas" />

                <h1 class="font-bold text-xs border-b inline-block my-3 border-textoprincipal text-textoprincipal">
                    ASIGNAR CATEGORÍAS</h1>

                @if (count($categories))
                    <div class="w-full flex flex-wrap gap-1 items-center">
                        @foreach ($categories as $category)
                            <x-label-checkbox :text="$category->name" :for="'category_edit_' . $category->id">
                                <x-input-checkbox :value="$category->id" wire:model.defer="selectedCategories"
                                    :id="'category_edit_' . $category->id" name="categories[]" />
                            </x-label-checkbox>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedCategories" />

                <div class="w-full pt-4 flex justify-center">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="update">
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
                        text: 'Desea eliminar el usuario : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.users.show-users', 'delete', event);
                        }
                    })
            });
        </script>
    @endsection
</div>
