<div>
    @if (count($comprobantes))
        <div class="sm:flex gap-3 mt-3">
            <x-input-text class="sm:max-w-md" placeholder="Buscar cliente..." />
            <x-input-text type="date" class="w-full sm:w-40 mt-2 sm:mt-0" />
        </div>

        <div class="text-left mt-3">
            {{ $comprobantes->links() }}
        </div>
    @endif

    @if (count($comprobantes))

        @if (session()->has('message'))
            <div class="p-3 bg-blue-200 text-blue-500 inline-block rounded">
                <p class="text-xs font-semibold">{{ session('message') }}</p>
            </div>
        @endif
        <div class="relative w-full block overflow-x-auto sm:rounded-md mt-3">
            <x-table-default>
                <x-slot name="headers">
                    <tr>
                        <th class="p-2">SERIE</th>
                        <th class="p-2">FECHA</th>
                        <th class="p-2">CLIENTE</th>
                        <th class="p-2">TIPO PAGO</th>
                        <th class="p-2">GRAVADO</th>
                        <th class="p-2">EXONERADO</th>
                        <th class="p-2">DESCUENTOS</th>
                        <th class="p-2">IGV</th>
                        <th class="p-2">TOTAL</th>
                        <th class="p-2">FORMA PAGO</th>
                        <th class="p-2">MESA</th>
                        <th class="p-2">USUARIO</th>
                        <th class="p-2">SUNAT</th>
                        <th class="p-2">DESCRIPCIÓN SUNAT</th>
                        <th class="p-2">ESTADO</th>
                        <th class="p-2">OPCIONES</th>
                    </tr>
                </x-slot>
                @if (count($comprobantes))
                    <x-slot name="rows">
                        @foreach ($comprobantes as $item)
                            <tr class="border-b border-bghovertable hover:bg-bghovertable">
                                <td class="p-2 uppercase">
                                    <small class="leading-3 text-[10px]">{{ $item->serie->name }}</small>
                                    <p class="whitespace-nowrap">{{ $item->seriecompleta }}</p>
                                </td>
                                <td class="p-2 uppercase">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/y h:i A') }}
                                </td>
                                <td class="p-2 uppercase align-middle text-xs">
                                    <div>
                                        <p>({{ $item->client->document }}) {{ $item->client->name }}</p>
                                        <p>{{ $item->client->direccion }}</p>
                                    </div>
                                </td>
                                <td class="p-2 uppercase">{{ $item->payment }}</td>
                                <td class="p-2 uppercase text-center">S/. {{ $item->gravado }}</td>
                                <td class="p-2 uppercase text-center">S/. {{ $item->exonerado }}</td>
                                <td class="p-2 uppercase text-center">S/. {{ $item->descuento }}</td>
                                <td class="p-2 uppercase text-center">S/. {{ $item->igv }}</td>
                                <td class="p-2 uppercase text-center">S/. {{ $item->total }}</td>
                                <td class="p-2 uppercase text-center">{{ $item->formapago->name }}</td>
                                <td class="p-2 uppercase text-center">
                                    @if ($item->order)
                                        {{ $item->order->mesa->name }}
                                    @endif
                                </td>
                                <td class="p-2 uppercase text-center">
                                    <p>{{ $item->user->name }}</p>
                                </td>
                                <td class="p-2 uppercase text-center">
                                    @if (trim($item->codesunat) == '0')
                                        <small
                                            class="text-xs bg-green-500 text-white rounded p-1 leading-3">Enviado</small>
                                    @else
                                        <x-button-default wire:click="sendsunat({{ $item->id }})"
                                            wire:loading.attr="disabled">ENVIAR</x-button-default>
                                    @endif
                                </td>
                                <td class="p-2 uppercase text-center">{{ $item->descripcionsunat }}</td>
                                <td class="p-2 uppercase text-center">
                                    @if ($item->status == 0)
                                        <small
                                            class="text-[10px] bg-blue-500 text-white rounded p-1 leading-3">Registrado</small>
                                    @elseif ($item->status == 1)
                                        <small
                                            class="text-[10px] bg-green-500 text-white rounded p-1 leading-3">Aceptado</small>
                                    @else
                                        <small
                                            class="text-[10px] bg-red-500 text-white rounded p-1 leading-3">Anulado</small>
                                    @endif
                                </td>
                                <td class="p-2 align-middle text-center">
                                    <div class="flex flex-wrap gap-2 items-center justify-end">
                                        <x-button-default wire:click="items({{ $item->id }})"
                                            wire:loading.attr="disabled">DETALLE</x-button-default>

                                        <div class="relative" x-data="{ isOpen: false }">
                                            <x-button-default wire:loading.attr="disabled" @click="isOpen = !isOpen"
                                                @keydown.escape="isOpen = false">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11.992 12H12.001" />
                                                    <path d="M11.9842 18H11.9932" />
                                                    <path d="M11.9998 6H12.0088" />
                                                </svg>
                                            </x-button-default>
                                            <ul x-show="isOpen" @click.away="isOpen = false"
                                                class="absolute font-normal bg-fondomodal shadow-md overflow-hidden rounded w-36 mt-2 py-1 right-0 z-20">
                                                <li>
                                                    <button wire:loading.attr="disabled"
                                                        class="block w-full text-colornavlink hover:bg-hoverbgnavlink hover:text-hovercolornavlink button">
                                                        IMPRIMIR TICKET
                                                    </button>
                                                </li>
                                                <li>
                                                    <button wire:loading.attr="disabled"
                                                        class="block w-full text-colornavlink hover:bg-hoverbgnavlink hover:text-hovercolornavlink button">
                                                        IMPRIMIR A4
                                                    </button>
                                                </li>
                                                @if ($item->status !== 2 && $item->serie->code !== '07')
                                                    <li>
                                                        <button wire:click="anular({{ $item->id }})"
                                                            wire:loading.attr="disabled"
                                                            class="block w-full text-colornavlink hover:bg-hoverbgnavlink hover:text-hovercolornavlink button">
                                                            ANULAR
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                @endif
            </x-table-default>
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="2xl">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Detalle Comprobante</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">

            <h1 class="text-xs font-semibold text-textoprincipal">SERIE:
                {{ $comprobante->seriecompleta }}</h1>

            <h1 class="text-xs font-semibold text-textoprincipal mt-1">CLIENTE:
                @if ($comprobante->client)
                    ({{ $comprobante->client->document }}) - {{ $comprobante->client->name }}
                @endif
            </h1>

            @if ($comprobante->referencia)
                <h1 class="text-xs font-semibold text-textoprincipal mt-1">DOCUMENTO REFERENCIA:
                    {{ $comprobante->referencia }}</h1>
            @endif

            <h1 class="text-xs font-semibold text-textoprincipal mt-1">ESTADO SUNAT:
                @if (trim($comprobante->codesunat) == '0')
                    <small class="text-white bg-green-500 p-1 rounded leading-3">ENVIADO</small>
                @else
                    <small class="text-white bg-red-500 p-1 rounded leading-3">PENDIENTE</small>
                @endif
            </h1>

            <h1 class="text-xs font-semibold text-textoprincipal mt-1">DESCRIPCIÓN SUNAT:
                {{ $comprobante->descripcionsunat }}</h1>

            @if (count($comprobante->comprobanteitems))
                <div class="relative w-full block overflow-x-auto sm:rounded-md mt-3">
                    <x-table-default>
                        <x-slot name="headers">
                            <tr>
                                <th class="p-2">ITEM</th>
                                <th class="p-2">DESCRIPCIÓN</th>
                                <th class="p-2">CANTIDAD</th>
                                <th class="p-2">PRECIO</th>
                                <th class="p-2">IMPORTE</th>
                            </tr>
                        </x-slot>
                        <x-slot name="rows">
                            @foreach ($comprobante->comprobanteitems as $item)
                                <tr class="border-b border-b-bghovertable hover:bg-bghovertable">
                                    <td class="p-2 uppercase whitespace-nowrap">{{ $item->item }}</td>
                                    <td class="p-2 uppercase">{{ $item->descripcion }}</td>
                                    <td class="p-2 uppercase text-center">{{ $item->cantidad }}</td>
                                    <td class="p-2 uppercase text-center">S/. {{ $item->price }}</td>
                                    <td class="p-2 uppercase text-center">S/. {{ $item->importe }}</td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table-default>
                </div>
                <h1 class="text-xs font-semibold text-textoprincipal text-center w-full">
                    {{ $comprobante->leyenda }}</h1>

                <h1 class="text-xs font-semibold text-textoprincipal mt-5">EXONERADO: S/.
                    {{ $comprobante->exonerado }}</h1>

                <h1 class="text-xs font-semibold text-textoprincipal mt-1">GRAVADO: S/.
                    {{ $comprobante->gravado }}</h1>

                <h1 class="text-xs font-semibold text-textoprincipal mt-1">IGV: S/.
                    {{ $comprobante->igv }}</h1>

                <h1 class="text-xs font-semibold text-textoprincipal mt-1">DESCUENTOS: S/.
                    {{ $comprobante->descuento }}</h1>

                <h1 class="text-xs font-semibold text-textoprincipal mt-1">TOTAL: S/.
                    {{ $comprobante->total }}</h1>
            @endif

        </x-slot>
    </x-jet-dialog-modal>
</div>
