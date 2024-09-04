<div>

    <div class="w-full mt-3">
        <x-select-input wire:model="searchlocation" class="w-full sm:w-60">
            <x-slot name="options">
                <option value="" selected>Seleccionar</option>
                @if (count($locationmesas))
                    @foreach ($locationmesas as $item)
                        <option value="{{ $item->location_id }}">{{ $item->location->name }}</option>
                    @endforeach
                @endif
            </x-slot>
        </x-select-input>
        <x-jet-input-error for="searchlocation" />
    </div>

    {{-- {{$locationmesas }} --}}
    @if (count($mesas))
        <div class="text-left mt-3">
            {{ $mesas->links() }}
        </div>
    @endif

    @if (count($mesas))
        <div class="flex flex-wrap justify-start gap-1 mt-3">
            @foreach ($mesas as $item)
                <x-card-product class="sm:w-32 w-32 text-center">

                    <h1 class=" font-bold text-sm text-center text-textoprincipal my-1">
                        {{ $item->name }}</h1>

                    <p class="bg-fondospan rounded p-1 font-bold leading-3 inline-block text-xs text-textospan">
                        {{ $item->location->name }}
                    </p>

                    <x-slot name="footer">
                        @can('admin.mesas.edit')
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})">
                            </x-button-edit>
                        @endcan
                        @can('admin.mesas.delete')
                            <x-button-delete wire:loading.attr="disabled"
                                wire:click="$emit('confirmDeleteMesa',{{ $item }})"></x-button-delete>
                        @endcan
                    </x-slot>
                </x-card-product>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar Mesa</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="NOMBRE MESA :" />
                    <x-input-text wire:model.defer="mesa.name" type="text" placeholder="Ingrese nombre de mesa..." />
                    <x-jet-input-error for="mesa.name" />
                </div>
                <div class="w-full mt-2">
                    <x-label value="UBICACIÓN MESA  :" />
                    <x-select-input wire:model.defer="mesa.location_id" class="w-full">
                        <x-slot name="options">
                            <option value="" selected>Seleccionar</option>
                            @if (count($locations))
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select-input>
                    <x-jet-input-error for="mesa.location_id" />
                </div>
                <div class="w-full mt-3 text-center">
                    <x-button-default wire:loading.attr="disabled" wire:target="update" type="submit">
                        Actualizar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('confirmDeleteMesa', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar mesa : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.mesas.show-mesas', 'delete', event.id);
                        }
                    })
            });
        });
    </script>
</div>
