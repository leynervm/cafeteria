<div>
    <x-button-modal class="inline-block" wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Registrar forma pago</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="NOMBRE :" />
                    <x-input-text wire:model.defer="name" placeholder="Ingrese descripción de forma pago..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full text-center mt-3">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="save">
                        Registrar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
