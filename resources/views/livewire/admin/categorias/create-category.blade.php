<div>

    <x-button-modal wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nueva Categoría</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="NOMBRE CATEGORÍA :" />
                    <x-input-text wire:model.defer="name" type="text" placeholder="Ingrese nombre de categoría..." />
                    <x-jet-input-error for="name" />
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
