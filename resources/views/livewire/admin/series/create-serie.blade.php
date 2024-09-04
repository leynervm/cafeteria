<div>
    <x-button-modal class="inline-block" wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nuevo Tipo Comprobante</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="TIPO COMPROBANTE :" />
                    <x-select-input wire:model.defer="code" class="w-full">
                        <x-slot name="options">
                            <option value="" selected>Seleccionar</option>
                            <option value="01">FACTURA ELECTRÓNICA</option>
                            <option value="03">BOLETA DE VENTA</option>
                            <option value="07">NOTA DE CREDITO</option>
                        </x-slot>
                    </x-select-input>
                    <x-jet-input-error for="code" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="SERIE COMPROBANTE :" />
                    <x-input-text wire:model.defer="serie" type="text" placeholder="Ingrese serie..." />
                    <x-jet-input-error for="serie" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="CONTADOR :" />
                    <x-input-text wire:model.defer="contador" type="number" min="0" step="1" />
                    <x-jet-input-error for="contador" />
                </div>

                <div class="w-full mt-3 text-center">
                    <x-button-default wire:loading.attr="disabled" wire:target="save" type="submit">
                        Registrar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
