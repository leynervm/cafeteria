<div>

    <x-button-modal wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nuevo Cupón</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <x-label value="Código cupón :" />
                <x-input-text wire:model.defer="code" type="text" placeholder="Ingrese código del cupón..." />
                <x-jet-input-error for="code" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Descuento :" />
                <x-input-text wire:model.defer="descuento" type="number" placeholder="0.00" />
                <x-jet-input-error for="descuento" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Límite :" />
                <x-input-text wire:model.defer="limit" type="number" placeholder="0.00" />
                <x-jet-input-error for="limit" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Fecha Inicio :" />
                <x-input-text wire:model.defer="start" type="date" />
                <x-jet-input-error for="start" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Fecha Expiración :" />
                <x-input-text wire:model.defer="end" type="date" />
                <x-jet-input-error for="end" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button-default wire:loading.attr="disabled" wire:target="save" wire:click="save">
                Registrar
            </x-button-default>
        </x-slot>
    </x-jet-dialog-modal>

</div>
