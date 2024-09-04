<div>
    <div class="w-full shadow rounded transition-all ease-out duration-150 active">
        <div class="w-full my-3">
            <x-input-text wire:model="search" type="text" placeholder="Buscar producto..." />
        </div>
        <div class="flex flex-wrap lg:flex-nowrap gap-3 shadow rounded">
            <div class="tab_box w-full flex gap-2 flex-wrap lg:flex-col lg:w-60 font-medium text-center relative">

                @foreach ($categories as $item)
                    <x-radio-button id="prod_{{ $item->id }}" name="mesaorder[]" :value="$item->id" :text="$item->name"
                        :active="$loop->first ?? false" class="tab_btn" />
                @endforeach
            </div>
            <div class="content_box w-full lg:w-full">
                @foreach ($categories as $item)
                    <div
                        class="content transition-all ease-out duration-150 hidden @if ($loop->first) active @endif">

                        @if (count($item->productos))
                            <div class="w-full flex flex-col gap-1">
                                @foreach ($item->productos as $prod)
                                    <div class="flex p-1 gap-2 bg-fondocard border border-colorborder">
                                        <div class="block">
                                            <div class="w-24 h-24">
                                                <img src="" alt=""
                                                    class="w-full h-full object-scale-down">
                                            </div>
                                        </div>
                                        <div class="flex flex-col justify-between w-full">
                                            <div
                                                class="w-full flex flex-wrap sm:flex-nowrap gap-2 justify-end sm:justify-between text-colorcard">
                                                <div class="text-justify w-full text-xs tracking-widest">
                                                    <h1 class="w-full block text-textoprincipal">{{ $prod->name }}
                                                    </h1>
                                                    <div class="block">
                                                        <x-span-text :text="$prod->category->name" />
                                                    </div>
                                                </div>
                                                <h1
                                                    class="whitespace-nowrap pl-3 relative text-md font-bold rounded-lg">
                                                    S/. {{ $prod->price }} </h1>
                                            </div>

                                            @can('admin.orders.create')
                                                <form class="w-full items-end flex gap-1 justify-end"
                                                    wire:submit.prevent="addpedido(Object.fromEntries(new FormData($event.target)), {{ $prod->id }})">

                                                    <input type="number" value="1" min="1" max="100"
                                                        name="cantidad"
                                                        class="form-control text-center ring-1 ring-gray-300 border-0 border-none focus:ring-1 focus:ring-gray-400 inline-block rounded-sm">
                                                    <x-button-car type="submit" class="rounded"
                                                        wire:loading.attr="disabled" wire:target="addpedido">
                                                    </x-button-car>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Registrar Agregados</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">

            @if ($order)
                {{ $order->name }}
            @endif

            @if (count($pedidos) > 0)
                <div class="w-full flex flex-wrap justify-around gap-2">
                    @for ($i = 0; $i < $cantidad; $i++)
                        <div class="w-full p-1 bg-fondocard shadow border border-colorborder rounded">
                            <div class="flex gap-2">
                                <div class="block">
                                    <div class="w-24 h-24 rounded-md shadow-md">
                                        @if ($producto->imagen)
                                            <img class="w-full h-full object-center object-cover overflow-hidden block rounded shadow-lg"
                                                src="{{ Storage::url('images/productos/' . $producto->imagen) }}">
                                        @else
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col justify-between w-full gap-1">
                                    <div
                                        class="w-full inline-flex flex-wrap sm:flex-nowrap gap-2 justify-end sm:justify-between">
                                        <p
                                            class="inline-flex text-justify w-full text-xs tracking-widest text-textoprincipal">
                                            {{ $producto->name }}</p>
                                        <h1
                                            class="whitespace-nowrap px-3 relative text-md font-bold rounded-lg text-colorcard">
                                            S/.
                                            {{ $producto->price }} </h1>
                                    </div>

                                    @if (count($producto->agregados))
                                        <x-label value="AGREGADOS" />

                                        <div class="w-full flex flex-wrap gap-1  items-start">
                                            @foreach ($producto->agregados as $agregado)
                                                <x-label-checkbox :text="$agregado->name . ' [' . $agregado->price . ']'" :for="'agregado_' . $i . $agregado->id">
                                                    <x-input-checkbox :value="$agregado->id"
                                                        wire:model.defer="pedidos.{{ $i }}.agregados.{{ $agregado->id }}"
                                                        wire:loading.attr="disabled" wire:target="pedidos"
                                                        :id="'agregado_' . $i . $agregado->id" name="categories[]" />
                                                </x-label-checkbox>
                                            @endforeach
                                        </div>
                                    @endif
                                    <x-input-text max="100" wire:model.defer="pedidos.{{ $i }}.detalle"
                                        placeholder="Agregar descripcion..."
                                        class="mt-2 form-control ring-1 ring-gray-300 border-0 border-none focus:ring-1 focus:ring-gray-400 inline-block rounded-sm" />
                                </div>
                            </div>
                            <div class="w-full mt-2 flex justify-end">
                                <x-button-delete wire:click="delete({{ $i }})"></x-button-delete>
                            </div>
                        </div>
                    @endfor
                </div>
            @else
                <span class="p-1 rounded text-red-600 bg-red-100">No hay pedidos</span>
            @endif

            <div class="w-full mt-3">
                <x-jet-input-error for="pedidos" />
            </div>
        </x-slot>

        <x-slot name="footer">
            @if (count($pedidos) > 0)
                <x-button-default wire:loading.attr="disabled" wire:target="savepedidos" wire:click="savepedidos">
                    CONFIRMAR PEDIDOS
                </x-button-default>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", function() {
            // window.addEventListener('modal-confirmar', event => {
            //     console.log(event.detail);
            //     const Toast = Swal.mixin({
            //         toast: true,
            //         position: 'top-end',
            //         showConfirmButton: false,
            //         timer: 3000,
            //         timerProgressBar: true,
            //         didOpen: (toast) => {
            //             toast.addEventListener('mouseenter', Swal.stopTimer)
            //             toast.addEventListener('mouseleave', Swal.resumeTimer)
            //         }
            //     })

            //     Swal.fire({
            //         title: 'Agregar nuevo pedido a la orden detallada ?',
            //         text: "Agregar pedido ORDER-" + event.detail.id + ' (' + event.detail.mesa
            //             .name + ').',
            //         icon: 'question',
            //         // showClass: {
            //         //     popup: 'animate__animated animate__backInLeft'
            //         // },
            //         // hideClass: {
            //         //     popup: 'animate__animated animate__backOutRight'
            //         // },
            //         showCancelButton: true,
            //         allowOutsideClick: false,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Confirmar',
            //         cancelButtonText: 'Cancelar',
            //         allowEscapeKey: false,

            //     }).then((result) => {
            //         if (result.isConfirmed) {

            //             Livewire.emitTo('admin.inicio.detalle-order', 'confirmarPedido');

            //             // Toast.fire({
            //             //     icon: 'success',
            //             //     title: 'Agregado correctamente'
            //             // })

            //         }
            //     })
            // })
        });
    </script>
</div>
