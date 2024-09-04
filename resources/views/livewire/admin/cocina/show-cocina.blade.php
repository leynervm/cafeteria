<div>
    @if (count($orders))
        <div class="mt-3 flex flex-col gap-3">
            @foreach ($orders as $order)
                @if (count($order->pedidos))
                    <div class="w-full border border-fondocard rounded">
                        <span
                            class="block w-full bg-fondocard text-textoprincipal text-xs p-1 font-bold rounded-t border-b border-fondocard">
                            {{ $order->mesa->name }}</span>

                        <div class="w-full p-1 px-2 flex flex-wrap gap-2">
                            @foreach ($order->pedidos as $pedido)
                                @if ($pedido->producto)
                                    <div
                                        class="p-1 px-2 w-full flex lg:w-96 border border-fondocard bg-fondocard rounded shadow shadow-fondocard">
                                        <div class="w-full">
                                            <p class="text-left font-bold text-sm text-colorcard">
                                                {{ $pedido->producto->name }} /
                                                {{ $pedido->producto->category->name }}
                                                <span>[S/. {{ $pedido->price }}]</span>
                                            </p>

                                            @if (count($pedido->pedidoitems))
                                                <h1 class="text-xs font-semibold underline mt-3 text-textoprincipal">
                                                    AGREGADOS</h1>
                                                <div class="flex flex-wrap mt-1 gap-1">
                                                    @foreach ($pedido->pedidoitems as $item)
                                                        <div class="inline-block">
                                                            <span
                                                                class="text-[10px] font-bold bg-fondospan text-textospan p-1 rounded">
                                                                {{ $item->agregado->name }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex justify-center items-center mt-2">
                                            <button wire:key="confirmar_pedido{{ $pedido->id }}"
                                                wire:click="confirmar_pedido({{ $pedido->id }})"
                                                class="bg-red-500 hover:bg-red-700 focus:bg-red-700 inline-block w-auto truncate text-xs text-center text-white hover:ring focus:ring hover:ring-red-300 focus:ring-red-300 button">
                                                <span>
                                                    @if ($pedido->status == 0)
                                                        CONFIRMAR
                                                    @endif
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="flex justify-center mt-1 w-full">
                                <x-button-default type="button" wire:key="confirmar_todo{{ $pedido->id }}"
                                    wire:click="confirmar_todo({{ $order->id }})">
                                    CONFIRMAR TODO
                                </x-button-default>
                            </div>

                        </div>

                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<script>
    // document.addEventListener("livewire:load", function() {
    //     // window.onload = function() {
    //     Echo.channel("chat").listen("MessageSent", (e) => {
    //         Livewire.emitTo('admin.cocina.show-cocina', 'render');

    //         // console.log("Listen desde livewire.admin.cocina.show-cocina");
    //         console.log(e);
    //         reproducir();
    //     });

    //     // window.Echo.channel("chat").listen("MessageSent", (data) => {
    //     //     console.log(data);
    //     //     Livewire.emit('render');
    //     //     document.dispatchEvent(new Event('recargarpagina'));
    //     // });

    //     // };
    // })

    // function reproducir() {
    //     // var audio = new Audio('');
    //     // audio.play();

    //     var reproductor = document.getElementById('reproductor');
    //     reproductor.play();
    // }
</script>
