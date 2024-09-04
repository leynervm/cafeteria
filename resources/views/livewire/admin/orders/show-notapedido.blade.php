<div>
    <div class="flex flex-wrap xl:flex-nowrap gap-2">
        <div class="w-full @if ($order->status < 1) xl:w-3/4 @endif  bg-fondoform p-5 text-colorform shadow-md">
            <div class="flex justify-between border-b pb-2">
                <h1 class="font-semibold text-xl">ORD-{{ $order->id }} \ {{ $order->name }} \
                    {{ $order->mesa->name }}
                </h1>
                <h2 class="font-semibold text-xl">{{ $order->pedidos->count() }} Pedidos</h2>
            </div>

            <div class="relative overflow-x-auto sm:rounded-md mt-3">
                <x-table-default>
                    <x-slot name="headers">
                        <tr>
                            <th class="p-2 text-center"></th>
                            <th class="text-left p-2">NOMBRE PRODUCTO</th>
                            <th class="p-2">CANTIDAD</th>
                            <th class="p-2">PRECIO</th>
                            <th class="p-2">AGREGADOS</th>
                            <th class="p-2">IMPORTE</th>
                        </tr>
                    </x-slot>
                    <x-slot name="rows">
                        @if (count($order->pedidos))
                            @foreach ($order->pedidos as $pedido)
                                <tr
                                    class="border-b border-bghovertable hover:bg-bghovertable hover:text-colorhovertable">
                                    <td class="p-2 uppercase text-center">
                                        @if ($pedido->status == 2)
                                            <input type="checkbox" wire:model="selectedPedidos"
                                                value="{{ $pedido->id }}">
                                        @endif
                                    </td>
                                    <td class="p-2 uppercase text-left">
                                        <p class="font-bold text-xs">{{ $pedido->producto->name }}
                                        </p>
                                        <p class="text-colorcard text-[10px]">
                                            @if ($pedido->producto->category)
                                                {{ $pedido->producto->category->name }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="p-2 uppercase">{{ $pedido->cantidad }}</td>
                                    <td class="p-2 uppercase font-bold">S/. {{ $pedido->price }}
                                    </td>
                                    <td class="p-2 uppercase text-center flex flex-col gap-1">
                                        <div class="w-full inline-flex flex-wrap gap-1 items-center">
                                            @foreach ($pedido->pedidoitems as $itemAgregado)
                                                <x-span-text :text="'[S/.' .
                                                    $itemAgregado->price .
                                                    '] ' .
                                                    $itemAgregado->agregado->name" />
                                            @endforeach
                                        </div>
                                    </td>

                                    <td class="p-2 align-middle text-center font-bold">
                                        S/.
                                        {{ number_format($pedido->price + $pedido->pedidoitems->sum('price'), 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </x-slot>
                </x-table-default>
            </div>
        </div>

        @if ($order->status == 0)
            <div class="w-full xl:w-1/4 bg-fondocard p-5 text-colorform shadow-md">
                <h1 class="font-semibold text-xl border-b pb-2">RESUMEN PAGO</h1>
                <div class="flex justify-between my-5">
                    <span class="font-semibold text-sm uppercase">{{ count($selectedPedidos) }} PEDIDO
                        SELECCIONADOS</span>
                    <span class="font-semibold text-sm">S/.
                        {{ number_format($amountPayment + $amountAgregados, 2, '.', ',') }}</span>
                </div>

                <form wire:submit.prevent="savePayment">
                    <x-label class="mb-1" value="SELECCIONAR COMPROBANTE PAGO :" />
                    @if (count($comprobantes))
                        <div class="w-full flex gap-1 flex-wrap">
                            @foreach ($comprobantes as $item)
                                <div class="inline-flex">
                                    <input type="radio" value="{{ $item->id }}" wire:model.defer="comprobante_id"
                                        name="comprobante" id="comprobante_{{ $item->id }}" class="peer hidden">
                                    <label for="comprobante_{{ $item->id }}"
                                        class="block bg-fondobotondefault text-xs text-textobotondefault cursor-pointer rounded-sm p-2.5 text-center peer-checked:bg-hoverbotondefault peer-checked:ring peer-checked:ring-ringbotondefault peer-checked:font-bold peer-checked:text-hovertextobotondefault">
                                        {{ $item->name }} ({{ $item->serie }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="comprobante_id" />

                    <div class="w-full flex flex-col md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1 gap-2 mt-3">

                        <div class="w-full">
                            <div class="w-full flex items-end">
                                <div class="w-full">
                                    <x-label value="DNI / RUC :" />
                                    <x-input-text wire:model.defer="document" min="0" type="text"
                                        name="document" maxlength="11" step="1" wire:keydown.enter="searchclient"
                                        wire:loading.attr="disabled" wire:target="searchclient" />
                                </div>
                                <x-button-default wire:click="searchclient" wire:loading.attr="disabled"
                                    wire:target="searchclient">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </x-button-default>
                            </div>
                            <x-jet-input-error for="document" />
                        </div>

                        <div class="w-full lg:col-span-2 xl:col-span-1">
                            <x-label value="NOMBRES DEL CLIENTE :" />
                            <x-input-text wire:model.defer="name" type="text"
                                placeholder="Ingrese nombres del cliente..." />
                            <x-jet-input-error for="name" />
                        </div>

                        <div class="w-full lg:col-span-2 xl:col-span-1">
                            <x-label value="DIRECCIÓN :" />
                            <x-input-text wire:model.defer="direccion" type="text"
                                placeholder="Ingrese dirección del cliente..." />
                            <x-jet-input-error for="direccion" />
                        </div>

                        <div class="w-full">
                            <x-label value="TIPO PAGO :" />
                            <x-input-text wire:model.defer="payment" type="text" value="Contado"
                                class="disabled:bg-opacity-70 disabled:bg-transparent uppercase" disabled readonly />
                            <x-jet-input-error for="payment" />
                        </div>

                        <div class="w-full">
                            <x-label value="FORMA PAGO :" />
                            <x-select-input wire:model.defer="formapago_id" class="w-full">
                                <x-slot name="options">
                                    <option value="" selected>Seleccionar</option>
                                    @if (count($formapagos))
                                        @foreach ($formapagos as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select-input>
                            <x-jet-input-error for="formapago_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="OTROS COSTOS :" />
                            <x-select-input wire:model="othercosto_id" class="w-full">
                                <x-slot name="options">
                                    <option value="" selected>SELECCIONAR..</option>
                                    @if (count($othercostos))
                                        @foreach ($othercostos as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} - S/.
                                                {{ $item->price }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select-input>
                            <x-jet-input-error for="othercosto_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="CUPÓN DSCTO :" />
                            <div class="flex gap-1">
                                <x-input-text wire:model.defer="coupon" wire:keydown.enter="verificarcupon"
                                    maxlength="12" :disabled="$validatedCoupon ?? true"
                                    class="ring-1 ring-gray-300 border-0 border-none focus:ring-1 focus:ring-gray-400 inline-block rounded-sm">
                                </x-input-text>

                                @if ($validatedCoupon)
                                    <x-button-default type="button" wire:click="resetcupon"
                                        wire:loading.attr="disabled" wire:target="resetcupon"
                                        class="rounded-sm inline-block p-1 bg-red-500 text-white disabled:opacity-25">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="7" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </x-button-default>
                                @endif

                                <x-button-default type="button" wire:click="verificarcupon"
                                    wire:loading.attr="disabled" wire:target="verificarcupon"
                                    class="rounded-sm inline-block p-1 bg-green-500 text-white disabled:opacity-25">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </x-button-default>
                            </div>
                            <x-jet-input-error for="coupon" />
                        </div>
                    </div>

                    <div class="my-3 text-center text-textoprincipal">

                        <x-jet-input-error for="selectedPedidos" />
                        <x-jet-input-error for="empresa.id" />

                        <div class="flex font-semibold justify-between mb-1 text-sm uppercase">
                            <span>IMPORTE TOTAL :</span>
                            <span>S/. {{ number_format($amountPayment + $amountAgregados, 2, '.', ',') }}</span>
                        </div>

                        @if ($othercosto)
                            <div class="flex font-semibold justify-between mb-1 text-sm uppercase">
                                <span>{{ $othercosto->name }} :</span>
                                <span>S/. {{ $othercosto->price }}</span>
                            </div>
                        @endif

                        @if ($validatedCoupon)
                            <div class="flex font-semibold justify-between mb-1 text-sm uppercase">
                                <span>DESCUENTO :</span>
                                <span>S/. {{ number_format($amountCoupon, 2, '.', ',') }}
                                    ({{ number_format($percentCoupon, 2) }} %)</span>
                            </div>
                        @endif

                        <div class="flex font-semibold justify-between mb-1 text-sm uppercase">
                            <span>TOTAL PAGAR :</span>
                            <span>S/.

                                @if ($othercosto)
                                    {{ number_format($amountPayment + $amountAgregados + $othercosto->price - $amountCoupon, 2, '.', ',') }}
                                @else
                                    {{ number_format($amountPayment + $amountAgregados - $amountCoupon, 2, '.', ',') }}
                                @endif
                            </span>
                        </div>

                        <x-button-default type="submit" wire:loading.attr="disabled">
                            REALIZAR PAGO
                        </x-button-default>
                    </div>
                </form>
            </div>
        @endif
    </div>

    @if (count($electronicos))
        <div class="w-full bg-fondoform p-5 shadow-md mt-1 text-colorform">
            <div class="w-full flex gap-1 items-center justify-start font-semibold border-b pb-2">
                <h1 class="text-xl">COMPROBANTES GENERADOS</h1>
                <span
                    class="font-semibold text-xs uppercase rounded-full flex items-center justify-center w-6 h-6 p-1 bg-blue-500 text-white">{{ count($electronicos) }}</span>
            </div>

            <div class="w-full flex gap-2 my-5 flex-wrap">
                @foreach ($electronicos as $item)
                    <a href="#"
                        class="bg-fondobotondefault ring-ringbotondefault text-textobotondefault hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:ring focus:ring hover:text-hovertextobotondefault focus:text-hovertextobotondefault button">
                        {{ $item->seriecompleta }}
                    </a>
                @endforeach
            </div>

        </div>
    @endif
</div>
