<div>

    <x-button-modal wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nuevo Rol</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="NOMBRE ROL :" />
                    <x-input-text wire:model.defer="name" type="text" placeholder="Ingrese nombre de rol..." />
                    <x-jet-input-error for="name" />
                </div>

                <h1 class="font-bold text-xs border-b inline-block my-3 border-textoprincipal text-textoprincipal">
                    ASIGNAR PERMISOS</h1>

                @if (count($permisos))
                    <div class="flex flex-wrap justify-around gap-3 mt-3">
                        @foreach ($permisos as $tabla => $permission)
                            <div class="shadow shadow-bgshadowicono bg-fondocard rounded w-full sm:w-48 text-xs">
                                <h1 class="font-semibold text-textoprincipal p-1 rounded-t bg-fondoform">
                                    {{ $tabla }}</h1>
                                <div class="w-full flex flex-wrap gap-1 p-1 justify-start">
                                    @foreach ($permission as $permiso)
                                        <x-label-checkbox :text="$permiso->descripcion" :for="'permiso_' . $permiso->id">
                                            <x-input-checkbox :value="$permiso->id" wire:model.defer="selectedPermisos"
                                                :id="'permiso_' . $permiso->id" name="selectedPermisos[]" />
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
