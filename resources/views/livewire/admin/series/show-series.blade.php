<div>

    @if (count($series))
        <div class="text-left mt-3">
            {{ $series->links() }}
        </div>
    @endif

    @if (count($series))
        <div class="flex flex-wrap justify-start gap-1 mt-3">
            @foreach ($series as $item)
                <x-card-product class="w-32 sm:w-32 text-center" :default="$item->default ?? null">

                    <h1 class="font-bold text-xs text-center text-textoprincipal my-1">{{ $item->name }}</h1>
                    <div class="w-full">
                        <span
                            class="bg-fondospan text-textospan p-1 rounded text-xs inline-block leading-3 font-semibold">
                            {{ $item->serie }}</span>
                        <span
                            class="bg-fondospan text-textospan p-1 rounded text-xs inline-block leading-3 font-semibold">
                            CONT: {{ $item->contador }}</span>
                    </div>


                    <x-slot name="footer">
                        @can('admin.series.edit')
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})">
                            </x-button-edit>
                        @endcan
                        @can('admin.series.delete')
                            <x-button-delete wire:loading.attr="disabled"
                                wire:click="$emit('confirmDeleteSerie',{{ $item }})">
                            </x-button-delete>
                        @endcan
                    </x-slot>
                </x-card-product>
            @endforeach
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar Tipo Comprobante</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="TIPO COMPROBANTE :" />
                    <x-select-input wire:model.defer="serie.code" class="w-full">
                        <x-slot name="options">
                            <option value="" selected>Seleccionar</option>
                            <option value="01">FACTURA ELECTRÓNICA</option>
                            <option value="03">BOLETA DE VENTA</option>
                            <option value="07">NOTA DE CREDITO</option>
                        </x-slot>
                    </x-select-input>
                    <x-jet-input-error for="serie.code" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="SERIE COMPROBANTE :" />
                    <x-input-text wire:model.defer="serie.serie" type="text" placeholder="Ingrese serie..." />
                    <x-jet-input-error for="serie.serie" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="CONTADOR :" />
                    <x-input-text wire:model.defer="serie.contador" type="number" min="0" step="1" />
                    <x-jet-input-error for="serie.contador" />
                </div>

                <div class="w-full text-center mt-3">
                    <x-button-default wire:loading.attr="disabled" wire:target="update" type="submit">
                        Registrar
                    </x-button-default>
                </div>

            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('confirmDeleteSerie', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar tipo comprobante : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.series.show-series', 'delete', event.id);
                        }
                    })
            });
        });
    </script>
</div>
