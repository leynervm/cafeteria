<div>
    <div class="flex flex-wrap gap-3 mt-3">
        <x-input-text class="max-w-md" placeholder="Buscar cupon..." wire:model="search" />

        <x-select-input wire:model="searchestado" class="w-40">
            <x-slot name="options">
                <option value="" selected>Seleccionar estado...</option>
                <option value="0" selected>ACTIVO</option>
                <option value="1" selected>EXPIRADO</option>
            </x-slot>
        </x-select-input>
    </div>

    @if (count($cupones))
        <div class="text-left mt-3">
            {{ $cupones->links() }}
        </div>
    @endif

    @if (count($cupones))
        <div class="relative overflow-x-auto sm:rounded-md mt-3">
            <x-table-default>
                <x-slot name="headers">
                    <th style="min-width: 250px;" class="p-2">CUPÓN</th>
                    <th class="p-2">DESCUENTO (%)</th>
                    <th class="p-2">LÍMITE</th>
                    <th class="p-2">FECHA INICIO</th>
                    <th class="p-2">EXPIRACION</th>
                    <th class="p-2">ESTADO</th>

                    <th class="p-2">OPCIONES</th>

                </x-slot>
                <x-slot name="rows">
                    @foreach ($cupones as $item)
                        <tr class="border-b border-bghovertable hover:bg-bghovertable">
                            <td class="p-2 uppercase">{{ $item->code }}</td>
                            <td class="p-2 uppercase text-center">{{ $item->descuento }}</td>
                            <td class="p-2 uppercase text-center">{{ $item->limit }}</td>
                            <td class="p-2 uppercase text-center">
                                {{ \Carbon\Carbon::parse($item->start)->locale('es')->format('d M Y') }}</td>
                            <td class="p-2 uppercase text-center">
                                {{ \Carbon\Carbon::parse($item->end)->locale('es')->format('d M Y') }}</td>
                            <td class="p-2 uppercase text-center">
                                @if ($item->status == 0)
                                    <small class="bg-green-500 text-white p-1 rounded text-xs leading-3">ACTIVO</small>
                                @else
                                    <small class="bg-red-500 text-white p-1 rounded text-xs leading-3">EXPIRADO</small>
                                @endif
                            </td>


                            <td class="p-2 align-middle text-center">

                                @can('admin.cupones.edit')
                                    <x-button-edit class="inline-block" wire:loading.attr="disabled" wire:target="edit"
                                        wire:click="edit({{ $item->id }})">
                                    </x-button-edit>
                                @endcan

                                @can('admin.cupones.delete')
                                    <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                        wire:click="$emit('confirmDelete',{{ $item }})">
                                    </x-button-delete>
                                @endcan
                            </td>

                        </tr>
                    @endforeach
                </x-slot>
            </x-table-default>
        </div>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Editar Cupón</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <div class="w-full">
                <x-label value="Código cupón :" />
                <x-input-text wire:model.defer="coupon.code" type="text" placeholder="Ingrese código del cupon..." />
                <x-jet-input-error for="coupon.code" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Descuento :" />
                <x-input-text wire:model.defer="coupon.descuento" type="number" placeholder="0.00" />
                <x-jet-input-error for="coupon.descuento" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Límite :" />
                <x-input-text wire:model.defer="coupon.limit" type="number" placeholder="0.00" />
                <x-jet-input-error for="coupon.limit" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Fecha Inicio :" />
                <x-input-text wire:model.defer="coupon.start" type="date" />
                <x-jet-input-error for="coupon.start" />
            </div>

            <div class="w-full mt-2">
                <x-label value="Fecha Expiración :" />
                <x-input-text wire:model.defer="coupon.end" type="date" />
                <x-jet-input-error for="coupon.end" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button-default wire:loading.attr="disabled" wire:target="update" wire:click="update">
                Actualizar
            </x-button-default>
        </x-slot>

    </x-jet-dialog-modal>

    @section('js')
        <script>
            Livewire.on('confirmDelete', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar cupón de descuento : ' + event.code,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.cupones.show-cupones', 'delete', event);
                        }
                    })
            });
        </script>
    @endsection

</div>
