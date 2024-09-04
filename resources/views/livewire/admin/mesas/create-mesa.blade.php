<div>
    <x-button-modal class="" wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm0">Nueva Mesa</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="NOMBRE MESA :" />
                    <x-input-text wire:model.defer="name" type="text" placeholder="Ingrese nombre de mesa..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full mt-2">
                    <x-label value="UBICACIÓN MESA :" />
                    <x-select-input wire:model.defer="location_id" class="w-full">
                        <x-slot name="options">
                            <option value="" selected>Seleccionar</option>
                            @if (count($locations))
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select-input>
                    <x-jet-input-error for="location_id" />
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
