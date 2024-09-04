<div>
    <x-button-modal wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nuevo Usuario</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="NOMBRES :" />
                    <x-input-text wire:model.defer="name" type="text" placeholder="Ingrese nombre del usuario..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="flex gap-2 flex-wrap sm:flex-nowrap mt-2">
                    <div class="w-full">
                        <x-label value="USUARIO :" />
                        <x-input-text wire:model.defer="email" type="text"
                            placeholder="Ingrese correo del usuario..." />
                        <x-jet-input-error for="email" />
                    </div>

                    <div class="w-full">
                        <x-label value="ROL :" />
                        <x-select-input wire:model.defer="role_id" class="block w-full">
                            <x-slot name="options">
                                <option value="" selected>Seleccionar</option>
                                @if (count($roles))
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select-input>
                        <x-jet-input-error for="role_id" />
                    </div>
                </div>

                <div class="flex gap-2 flex-wrap sm:flex-nowrap mt-2">
                    <div class="w-full">
                        <x-label value="CONTRASEÑA :" />
                        <x-input-text wire:model.defer="password" type="password" />
                        <x-jet-input-error for="password" />
                    </div>

                    <div class="w-full">
                        <x-label value="CONFIRMAR CONTRASEÑA :" />
                        <x-input-text type="password" name="password_confirmation"
                            wire:model.defer="password_confirmation" />
                        <x-jet-input-error for="password_confirmation" />
                    </div>
                </div>

                <h1 class="font-bold text-xs border-b inline-block my-3 border-textoprincipal text-textoprincipal">
                    ASIGNAR MESAS</h1>

                @if (count($mesas))
                    <div class="w-full flex flex-wrap gap-1 items-center">
                        @foreach ($mesas as $mesa)
                            <x-label-checkbox :text="$mesa->name" :for="'mesa_' . $mesa->id">
                                <x-input-checkbox :value="$mesa->id" wire:model.defer="selectedMesas" :id="'mesa_' . $mesa->id"
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
                            <x-label-checkbox :text="$category->name" :for="'category_' . $category->id">
                                <x-input-checkbox :value="$category->id" wire:model.defer="selectedCategories"
                                    :id="'category_' . $category->id" name="categories[]" />
                            </x-label-checkbox>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedCategories" />

                <div class="pt-4 flex w-full justify-center">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="save">
                        Registrar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

</div>
