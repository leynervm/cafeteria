<div>

    <x-button-modal wire:click="$set('open', true)">NUEVO</x-button-modal>

    <x-jet-dialog-modal wire:model="open" maxWidth="2xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Nuevo Producto</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">

            <form wire:submit.prevent="save">
                <div class="w-full flex flex-wrap md:flex-nowrap gap-2">
                    <div class="w-full md:w-2/3 space-y-2">
                        <div class="w-full">
                            <x-label value="Descripción producto :" />
                            <x-input-text wire:model.defer="name" type="text"
                                placeholder="Ingrese nombre del producto..." />
                            <x-jet-input-error for="name" />
                        </div>

                        <div class="w-full">
                            <x-label value="Categoría :" />
                            <x-select-input wire:model.defer="category_id" class="w-full">
                                <x-slot name="options">
                                    <option value="" selected>Seleccionar</option>
                                    @if ($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select-input>
                            <x-jet-input-error for="category_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="Precio :" />
                            <x-input-text wire:model.defer="price" type="number" placeholder="0.00" />
                            <x-jet-input-error for="price" />
                        </div>

                        <div class="w-full">
                            <x-label value="Porcentaje Rendimiento (%) :" />
                            <x-input-text wire:model.defer="rendimiento" type="number" placeholder="0.00" />
                            <x-jet-input-error for="rendimiento" />
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 text-center mt-2">
                        @if ($imagen)
                            <div class="w-full h-60 mx-auto block">
                                <img class="w-full h-full object-scale-down overflow-hidden block rounded shadow-lg"
                                    src="{{ $imagen->temporaryUrl() }}" alt="">
                            </div>
                        @endif

                        <x-load-image wire:loading wire:target="imagen"></x-load-image>

                        <div class="w-full">
                            <x-label-file>
                                <span class="text-xs font-semibold">Adjuntar Imagen</span>
                                <input type="file" name="imagen" class="hidden" wire:model="imagen" accept="image/*"
                                    id="{{ $identificador }}">
                            </x-label-file>
                        </div>
                        <x-jet-input-error for="imagen" />
                    </div>

                </div>

                @if (count($agregados))
                    <x-label value="AGREGADOS :" />
                    <div class="w-full flex flex-wrap gap-1 items-center justify-between">
                        @foreach ($agregados as $agregado)
                            <label for="agregado_{{ $agregado->id }}"
                                class="flex items-center cursor-pointer bg-fondospan rounded p-1">
                                <input type="checkbox" value="{{ $agregado->id }}" wire:model.defer="selectedAgregados"
                                    class="rounded border-checkboxdefault text-checkboxdefault shadow-sm focus:ring focus:ring-transparent"
                                    id="agregado_{{ $agregado->id }}" name="agregados[]">
                                <span class="pl-2 text-[10px] font-semibold text-textospan">{{ $agregado->name }}
                                    <span class="font-bold">(S/.{{ $agregado->price }})</span></span>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="w-full mt-3 text-center">
                    <x-button-default wire:loading.attr="disabled" wire:target="save" type="submit">
                        Registrar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

</div>
