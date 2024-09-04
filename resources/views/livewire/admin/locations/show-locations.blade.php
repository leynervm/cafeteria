<div>

    @if (count($locations))
        <div class="text-left mt-3">
            {{ $locations->links() }}
        </div>
    @endif

    @if (count($locations))
        <div class="flex flex-wrap justify-start gap-1 mt-3">
            @foreach ($locations as $item)
                <x-card-product class="sm:w-28 w-28 text-center">

                    <h1 class="font-bold text-sm text-center text-textoprincipal my-1">{{ $item->name }}</h1>

                    <x-slot name="footer">
                        @can('admin.locations.edit')
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})">
                            </x-button-edit>
                        @endcan
                        @can('admin.locations.delete')
                            <x-button-delete wire:loading.attr="disabled"
                                wire:click="$emit('confirmDeleteLocation', {{ $item }})"></x-button-delete>
                        @endcan
                    </x-slot>
                </x-card-product>
            @endforeach
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar ubicación Mesa</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="NOMBRE UBICACIÓN :" />
                    <x-input-text wire:model="location.name" type="text"
                        placeholder="Ingrese lugar de ubicación..." />
                    <x-jet-input-error for="location.name" />
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
            Livewire.on('confirmDeleteLocation', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar ubicacion de mesa : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.locations.show-locations', 'delete', event.id);
                        }
                    })
            });
        });
    </script>
</div>
