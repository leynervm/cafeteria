<div>
    <x-button-modal wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nuevo Cliente</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">

            <form wire:submit.prevent="save">
                <div class="w-full flex items-end">
                    <div class="w-full">
                        <x-label value="DNI / RUC :" />
                        <x-input-text wire:model.defer="document" type="number" wire:keydown.enter="searchclient"
                            wire:loading.attr="disabled" wire:target="searchclient" />
                    </div>
                    <x-button-default wire:click="searchclient" wire:loading.attr="disabled" wire:target="searchclient">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </x-button-default>
                </div>
                <x-jet-input-error for="document" />

                <div class="w-full mt-2">
                    <x-label value="NOMBRES :" />
                    <x-input-text wire:model.defer="name" type="text"
                        placeholder="Ingrese nombres del cliente..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full mt-2">
                    <x-label value="DIRECCIÓN :" />
                    <x-input-text wire:model.defer="direccion" type="text"
                        placeholder="Ingrese dirección del cliente..." />
                    <x-jet-input-error for="direccion" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="TELÉFONO / CELULAR :" />
                    <x-input-text wire:model.defer="telefono" type="text"
                        placeholder="Ingrese teléfono del cliente..." />
                    <x-jet-input-error for="telefono" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="FECHA NACIMIENTO  :" />
                    <x-input-text wire:model.defer="dateparty" type="date" />
                    <x-jet-input-error for="dateparty" />
                </div>

                <div class="mt-3 w-full text-center">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="save">
                        Registrar
                    </x-button-default>
                </div>
            </form>
        </x-slot>

    </x-jet-dialog-modal>
</div>
