<div>

    <div class="relative overflow-x-auto sm:rounded-md mt-3">
        <x-table-default>
            <x-slot name="headers">
                <tr>
                    <th class="p-2 text-center">IMAGEN</th>
                    <th class="text-left p-2">NOMBRE PRODUCTO</th>
                    <th class="p-2">CANTIDAD</th>
                    <th class="p-2">PRECIO</th>
                    <th class="p-2">AGREGADOS</th>
                    <th class="p-2">IMPORTE</th>
                </tr>
            </x-slot>
            <x-slot name="rows">
                @if (count($pedidos))
                    @foreach ($pedidos as $item)
                        @if ($order)
                            <tr class="border-b border-bghovertable hover:bg-bghovertable">
                                <td class="p-2 align-middle text-center">
                                    <x-button-default class="rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </x-button-default>
                                </td>
                                <td class="p-2 uppercase text-left">
                                    <p class="font-bold text-sm">{{ $item->producto->name }}
                                    </p>
                                    <p class="text-colorcard text-xs">
                                        @if ($item->producto->category)
                                            {{ $item->producto->category->name }}
                                        @endif
                                    </p>
                                    @can('admin.orders.delete')
                                        <div class="flex gap-1">
                                            @if ($item->status == 0)
                                                <x-button-delete
                                                    wire:click="$emit('orders.detalleorder.confirmDelete',{{ $item }})"
                                                    wire:loading.attr="disabled" wire:target="delete">
                                                </x-button-delete>
                                            @else
                                                <span class="p-1 text-red-600 rounded opacity-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    @endcan
                                </td>
                                <td class="p-2 uppercase">{{ $item->cantidad }}</td>
                                <td class="p-2 uppercase font-bold">S/. {{ $item->price }}
                                </td>

                                <td class="p-2 uppercase text-center gap-1">
                                    <div class="w-full inline-flex flex-wrap gap-1 items-center">
                                        @foreach ($item->pedidoitems as $itemAgregado)
                                            <div class="inline-block">
                                                <span
                                                    class="text-[10px] inline-flex items-center gap-1 font-bold bg-fondospan text-textospan p-1 rounded">
                                                    [S/. {{ $itemAgregado->price }}]
                                                    {{ $itemAgregado->agregado->name }}

                                                    @can('admin.orders.delete')
                                                        @if ($item->status == 0)
                                                            <x-button-delete class="inline-block"
                                                                wire:click="$emit('orders.agregados.confirmDelete',{{ $itemAgregado->id }})"
                                                                wire:loading.attr="disabled" wire:target="delete">
                                                            </x-button-delete>
                                                        @endif
                                                    @endcan
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="p-2 align-middle text-center font-bold">
                                    S/.
                                    {{ number_format($item->price + $item->pedidoitems->sum('price'), 2, '.', ',') }}
                                </td>
                            </tr>
                        @else
                            <tr class="border-b border-bghovertable hover:bg-bghovertable">
                                <td class="p-2 align-middle text-center">
                                    <x-button-default class="rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </x-button-default>
                                </td>
                                <td class="p-2 uppercase text-left">
                                    <p class="font-bold text-sm">{{ $item->producto->name }}
                                    </p>
                                    <p class="text-colorcard text-xs">
                                        @if ($item->producto->category)
                                            {{ $item->producto->category->name }}
                                        @endif
                                    </p>
                                    <div class="flex gap-1">
                                        <x-button-delete
                                            wire:click="$emit('orders.notapedido.confirmDelete',{{ $item }})"
                                            wire:loading.attr="disabled" wire:target="deleteitemnota">
                                        </x-button-delete>
                                    </div>
                                </td>
                                <td class="p-2 uppercase">{{ $item->cantidad }}</td>
                                <td class="p-2 uppercase font-bold">S/. {{ $item->producto->price }}</td>
                                <td class="p-2 uppercase align-middle">
                                    @php
                                        $sumaAgregados = 0;
                                    @endphp

                                    @if (count($item->detallenotas))
                                        <div class="w-full inline-flex flex-wrap gap-1 items-center">
                                            @foreach ($item->detallenotas as $itemAgregado)
                                                @php
                                                    $sumaAgregados = $sumaAgregados + $itemAgregado->agregado->price;
                                                @endphp

                                                <div class="inline-block">
                                                    <span
                                                        class="text-[10px] inline-flex items-center gap-1 font-bold bg-fondospan text-textospan p-1 rounded">
                                                        [S/. {{ $itemAgregado->agregado->price }}]
                                                        {{ $itemAgregado->agregado->name }}

                                                        <x-button-delete class="inline-block"
                                                            wire:click="$emit('orders.agregadonota.confirmDelete',{{ $itemAgregado->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="deleteagregadonota">
                                                        </x-button-delete>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="p-2 text-center">
                                    S/.
                                    {{ number_format($item->cantidad * $item->producto->price + $sumaAgregados, 2, '.', ', ') }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </x-slot>
        </x-table-default>

        @if (!$order)
            @if (count($pedidos))
                <div class="w-full p-5 flex justify-end">
                    <x-button-default wire:click="$emit('orders.pedidos.confirmDelete')" wire:loading.attr="disabled"
                        wire:target="deletenotapedidos">
                        Eliminar Todo
                    </x-button-default>
                </div>
            @endif
        @endif
    </div>

    @section('js')
        <script>
            Livewire.on('orders.notapedido.confirmDelete', event => {
                Swal.fire({
                        title: 'Eliminar el producto ' + event.producto.name + ' de la nota de pedido ?',
                        text: 'Se eliminará un registro de la base de datos, incluyendo sus datos contenidos.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.orders.show-pedidos', 'deleteitemnota', event.id);
                        }
                    })
            });

            Livewire.on('orders.agregadonota.confirmDelete', event => {
                Swal.fire({
                        title: 'Quitar agregado del pedido ?',
                        text: 'Se eliminará un registro de la base de datos.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.orders.show-pedidos', 'deleteagregadonota', event);
                        }
                    })
            });

            Livewire.on('orders.pedidos.confirmDelete', () => {
                Swal.fire({
                        title: 'Eliminar todos los pedidos de la lista ?',
                        text: 'Se eliminará una lista de registros de la base de datos.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.orders.show-pedidos', 'deletenotapedidos');
                        }
                    })
            });

            Livewire.on('orders.detalleorder.confirmDelete', event => {
                Swal.fire({
                        title: 'Eliminar el producto ' + event.producto.name + ' de la orden ?',
                        text: 'Se eliminará un registro de la base de datos, incluyendo sus datos contenidos.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.orders.show-pedidos', 'delete', event.id);
                        }
                    })
            });

            Livewire.on('orders.agregados.confirmDelete', event => {
                Swal.fire({
                        title: 'Quitar agregado de la orden ?',
                        text: 'Se eliminará un registro de la base de datos.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.orders.show-pedidos', 'deleteagregado', event);
                        }
                    })
            });
        </script>
    @endsection
</div>
