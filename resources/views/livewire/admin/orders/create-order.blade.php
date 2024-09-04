<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-3 bg-fondoform text-colorform p-5 shadow-md">
        <div class="w-full flex items-center justify-between">
            <h1 class="font-semibold text-xl">{{ $mesa->name }}</h1>
            <h2 class="font-semibold text-xl">{{ count($pedidos) }} Pedidos</h2>
        </div>

        <div class="w-full">
            <x-label value="NOMBRES DEL CLIENTE :" />
            <x-input-text wire:model.defer="cliente" type="text" placeholder="Ingrese nombre de mesa..."
                class="max-w-xs" />
            <x-jet-input-error for="cliente" />
            <x-jet-input-error for="mesa.id" />
            <x-jet-input-error for="items" />
        </div>

        <div class="w-full">
            <x-button-default type="submit" wire:loading.attr="disabled" wire:target="save">
                Registrar
            </x-button-default>
        </div>
    </form>
</div>
