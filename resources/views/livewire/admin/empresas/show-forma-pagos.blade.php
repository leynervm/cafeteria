<div>
    @if (count($formapagos))
        <div class="text-left mt-3">
            {{ $formapagos->links() }}
        </div>
    @endif

    @if (count($formapagos))
        <div class="flex flex-wrap justify-start gap-1 mt-3">
            @foreach ($formapagos as $item)
                <x-card-product class="sm:w-28 w-28 text-center" :default="$item->default ?? null">

                    <h1 class="font-bold text-sm text-center text-textoprincipal my-1">{{ $item->name }}</h1>

                    <x-slot name="footer">
                        @can('admin.formpayment.edit')
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})">
                            </x-button-edit>
                        @endcan
                        @can('admin.formpayment.delete')
                            <x-button-delete wire:loading.attr="disabled"
                                wire:click="$emit('confirmDeleteFormapago',{{ $item }})">
                            </x-button-delete>
                        @endcan
                    </x-slot>
                </x-card-product>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar forma pago</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="NOMBRE :" />
                    <x-input-text wire:model.defer="formapago.name" />
                    <x-jet-input-error for="formapago.name" />
                </div>

                <div class="w-full text-center mt-3">
                    <x-button-default type="submit" wire:loading.attr="disabled" wire:target="update">
                        Actualizar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('confirmDeleteFormapago', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar forma de pago : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.empresas.show-forma-pagos', 'delete', event.id);
                        }
                    })
            });
        });
    </script>
</div>
