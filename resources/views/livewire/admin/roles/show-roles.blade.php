<div>

    <div class="animate__animated animate__fadeIn mt-3">

        @if ($roles->hasPages())
            <div class="my-5">{{ $roles->links() }}</div>
        @endif

        @if (count($roles))
            <div class="w-full flex flex-col gap-2">
                @foreach ($roles as $item)
                    <div
                        class="w-full flex flex-col justify-between p-1 border border-colorborder rounded bg-fondocard shadow shadow-bgshadowicono">
                        <div class="w-full">
                            <span
                                class="block w-full font-bold text-xs text-textoprincipal p-1">{{ $item->name }}</span>

                            @if ($item->permissions)
                                <div class="flex justify-between flex-wrap gap-1">
                                    @foreach ($item->permissions as $permission)
                                        <x-span-text :text="$permission->descripcion" />
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="w-full mt-5 flex justify-end gap-3">
                            @can('admin.roles.edit')
                                <button wire:click="edit({{ $item->id }})"
                                    class="rounded text-white p-1 bg-orange-500 ring-orange-300 hover:bg-orange-700 focus:bg-orange-700 hover:ring focus:ring transition-colors ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                            @endcan

                            @can('admin.roles.delete')
                                <button wire:click="delete({{ $item->id }})"
                                    class="rounded text-white p-1 bg-red-600 ring-red-300 hover:bg-red-800 focus:bg-red-800 hover:ring focus:ring transition-colors ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    <x-jet-dialog-modal wire:model="open" maxWidth="3xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Actualizar Rol</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="NOMBRE ROL :" />
                    <x-input-text wire:model.defer="role.name" type="text" placeholder="Ingrese nombre de rol..." />
                    <x-jet-input-error for="role.name" />
                </div>

                <h1 class="font-bold text-xs border-b inline-block my-3 border-textoprincipal text-textoprincipal">
                    ASIGNAR PERMISOS</h1>

                {{-- @if (count($permisos)) --}}
                @if (count($permisos))
                    <div class="flex flex-wrap justify-around gap-3 mt-3">
                        @foreach ($permisos as $tabla => $permission)
                            <div class="shadow shadow-bgshadowicono bg-fondocard rounded w-full sm:w-48 text-xs">
                                <h1 class="font-semibold text-textoprincipal p-1 rounded-t bg-fondoform">
                                    {{ $tabla }}</h1>
                                <div class="w-full flex flex-wrap gap-1 p-1 justify-start">
                                    @foreach ($permission as $permiso)
                                        <x-label-checkbox :text="$permiso->descripcion" :for="'permiso_edit_' . $permiso->id">
                                            <x-input-checkbox :value="$permiso->id" wire:model.defer="selectedPermisos"
                                                :id="'permiso_edit_' . $permiso->id" name="permisos[]" />
                                        </x-label-checkbox>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedPermisos" />

                <div class="w-full pt-4 flex justify-center">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="save">
                        Registrar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
