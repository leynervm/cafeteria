<div class="w-full flex flex-col md:flex-row md:flex-nowrap gap-3">

    @if (count($mesas))
        <div class="w-full flex gap-2 flex-wrap md:flex-col md:w-60 font-medium text-center relative">
            @foreach ($mesas as $item)
                @if (count($item->orders->where('status', 0)))
                    <button wire:click="loadorder({{ $item->id }})"
                        class="bg-transparent flex gap-1 justify-between items-center ring-orange-300 text-orange-300 border border-orange-300 hover:text-white hover:bg-orange-500 focus:bg-orange-500 focus:text-white button">
                        {{ $item->name }}
                        <small class="p-0.5 rounded bg-red-500 text-white text-[10px] leading-3">Ocupdado</small>
                    </button>
                @else
                    <button wire:click="loadorder({{ $item->id }})"
                        class="bg-transparent  flex gap-1 justify-between items-center ring-green-300 text-green-300 border border-green-300 hover:text-white hover:bg-green-500 focus:bg-green-500 focus:text-white button">
                        {{ $item->name }}
                        <small class="p-0.5 rounded bg-green-500 text-white text-[10px] leading-3">Disponible</small>
                    </button>
                @endif
            @endforeach
        </div>

        <div class="w-full">
            @if (isset($order->id))
                <div class="flex flex-col gap-1">
                    <div class="w-full border border-fondocard rounded">
                        <span
                            class="block w-full bg-fondocard text-textoprincipal text-xs p-1 font-bold rounded-t border-b border-fondocard">ORD-{{ $order->id }}
                            /{{ $order->mesa->name }}</span>

                        @if (count($order->pedidos))
                            <div class="w-full flex flex-col gap-1 p-1">
                                @foreach ($order->pedidos as $pedido)
                                    <div
                                        class="p-1 px-2 w-full flex flex-wrap sm:flex-nowrap border border-fondocard bg-fondocard rounded shadow shadow-fondocard">
                                        <div class="w-full">
                                            <p class="text-left font-bold text-sm text-colorcard">
                                                {{ $pedido->producto->name }}
                                                <span>[S/. {{ $pedido->price }}]</span>
                                            </p>

                                            @if (count($pedido->pedidoitems))
                                                <h1 class="text-xs font-semibold underline mt-3 text-textoprincipal">
                                                    AGREGADOS</h1>
                                                <div class="flex flex-wrap mt-1 gap-1">
                                                    @foreach ($pedido->pedidoitems as $agregadoitem)
                                                        <x-span-text :text="'[S/.' .
                                                            $agregadoitem->price .
                                                            '] ' .
                                                            $agregadoitem->agregado->name" />
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex justify-center items-center mt-2">
                                            @if ($pedido->status == 0)
                                                <p
                                                    class="bg-red-500 inline-block w-auto truncate text-[10px] text-center text-white p-2 rounded opacity-60">
                                                    <span>EN ESPERA</span>
                                                </p>
                                            @elseif ($pedido->status == 1)
                                                @can('admin.orders.create')
                                                    <x-button-default wire:click="entregar_pedido({{ $pedido->id }})">
                                                        ENTREGAR
                                                    </x-button-default>
                                                @elsecan('admin.orders')
                                                    <p
                                                        class="bg-blue-500 inline-block w-auto truncate text-[10px] text-center text-white p-2 rounded opacity-60">
                                                        <span>ENTREGAR</span>
                                                    </p>
                                                @endcan
                                            @elseif ($pedido->status == 2)
                                                <p
                                                    class="bg-green-500 inline-block w-auto truncate text-[10px] text-center text-white p-2 rounded opacity-60">
                                                    <span>ENTREGADO</span>
                                                </p>
                                            @else
                                                <p
                                                    class="bg-transparent-500 border border-green-300 inline-block w-auto truncate text-[10px] text-center text-green-500 p-2 rounded">
                                                    <span>PAGADO</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @can('admin.orders.create')
                                    <div class="flex justify-between mt-1">

                                        <a href="{{ route('admin.show.order', $order->id) }}"
                                            class="bg-amber-500 hover:bg-amber-600 focus:bg-amber-600 hover:ring hover:ring-amber-200 focus:ring-amber-200 hover:text-white focus:text-white text-white button">
                                            <span>SEGUIR COMPRANDO</span>
                                        </a>

                                        @if (count($order->pedidos->where('status', 1)))
                                            <x-button-default wire:click="entregar_todo({{ $order->id }})">
                                                ENTREGAR PEDIDOS LISTOS
                                            </x-button-default>
                                        @else
                                            @if (count($order->pedidos->where('status', 0)))
                                                <p
                                                    class="bg-red-500 inline-block w-auto truncate text-[10px] text-center text-white p-2 rounded opacity-60">
                                                    <span>EN ESPERA</span>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                @endcan
                            </div>
                        @endif

                    </div>
                </div>
            @else
                @if ($order)
                @else
                    @can('admin.orders.create')
                        <a href="{{ route('admin.orders.create', $item) }}"
                            class="bg-amber-500 hover:bg-amber-600 focus:bg-amber-600 hover:ring hover:ring-amber-200 focus:ring-amber-200 hover:text-white focus:text-white text-white button">
                            <span>AGREGAR PEDIDOS</span>
                        </a>
                    @endcan
                @endif
            @endif
        </div>
    @endif
</div>
