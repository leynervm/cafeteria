<div>

    <div class="sm:flex flex-wrap gap-3 mt-3">
        <x-input-text class="sm:w-60" wire:model="search" placeholder="Buscar order, cliente..." />
        <x-input-text type="date" wire:model="searchfecha" class="w-full min-w-min sm:w-48 mt-2 sm:mt-0" />

        <div class="relative inline-block mt-2 sm:mt-0" x-data="{ open: false }" @click.away="open = false">
            <button x-on:click="open = !open" :class="{ 'bg-gray-50': open, 'bg-transparent': !open }"
                class="relative border-0 border-b w-40 sm:w-32 bg-transparent p-2.5 rounded-sm text-xs font-semibold border-gray-300 text-gray-500 focus:ring-0 focus:border-gray-400 text-center inline-flex items-center"
                type="button">Estado Pago
                <svg :class="{ 'rotate-180': open }"
                    class="absolute right-2 w-4 h-4 ml-2 transform transition duration-150" aria-hidden="true"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                    </path>
                </svg>
            </button>

            <div :class="{ 'block': open, 'hidden': !open }" x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute hidden z-10 w-full max-w-xs bg-white rounded-lg shadow-md">
                <ul class="p-1 space-y-1 text-gray-700" aria-labelledby="dropdownCheckboxButton">

                    {{-- @if ($categories)
                        @foreach ($categories as $category) --}}
                    <li>
                        <div class="flex items-center hover:bg-gray-50 rounded-lg p-1">
                            <input id="checkbox-category-0" type="checkbox" value="0" wire:model="searchestado"
                                name="searchestado[]"
                                class="w-4 h-4 text-checkboxdefault border-checkboxdefault cursor-pointer rounded focus:ring-0 focus:ring-transparent">
                            <label for="checkbox-category-0"
                                class="pl-2 text-[10px] font-semibold text-textospan cursor-pointer">Pendientes</label>
                        </div>
                    </li>
                    {{-- @endforeach
                    @endif --}}
                </ul>
            </div>
        </div>
        <div class="relative inline-block mt-2 sm:mt-0" x-data="{ open: false }" @click.away="open = false">
            <button x-on:click="open = !open" :class="{ 'bg-gray-50': open, 'bg-transparent': !open }"
                class="relative border-0 border-b w-40 sm:w-32 bg-transparent p-2.5 rounded-sm text-xs font-semibold border-gray-300 text-gray-500 focus:ring-0 focus:border-gray-400 text-center inline-flex items-center"
                type="button">Mesa
                <svg :class="{ 'rotate-180': open }"
                    class="absolute right-2 w-4 h-4 ml-2 transform transition duration-150" aria-hidden="true"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                    </path>
                </svg>
            </button>

            <div :class="{ 'block': open, 'hidden': !open }" x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute hidden z-10 w-full max-w-xs bg-white rounded-lg shadow-md">
                <ul class="p-1 space-y-1 text-gray-700" aria-labelledby="dropdownCheckboxButton">

                    @if (count($mesas))
                        @foreach ($mesas as $mesa)
                            <li>
                                <div class="flex items-center hover:bg-gray-50 rounded-lg p-1">
                                    <input id="checkbox-mesa-{{ $mesa->id }}" type="checkbox"
                                        value="{{ $mesa->id }}" wire:model="searchmesa" name="searchmesa[]"
                                        class="w-4 h-4 text-checkboxdefault border-checkboxdefault cursor-pointer rounded focus:ring-0 focus:ring-transparent">
                                    <label for="checkbox-mesa-{{ $mesa->id }}"
                                        class="pl-2 text-[10px] font-semibold text-textospan cursor-pointer">{{ $mesa->name }}</label>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>

    @if (count($orders))
        <div class="text-left mt-3">
            {{ $orders->links() }}
        </div>
    @endif

    @if (count($orders))
        <div class="relative overflow-x-auto sm:rounded-md mt-3">
            <x-table-default>
                <x-slot name="headers">
                    <tr>
                        <th class="p-2">ORDER</th>
                        <th class="p-2">FECHA</th>
                        <th class="p-2">CLIENTE</th>
                        <th class="p-2">MESA</th>
                        <th class="p-2">ESTADO</th>
                        <th class="p-2">USUARIO</th>
                        @can('admin.orders.delete')
                            <th class="p-2">OPCIONES</th>
                        @endcan
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($orders as $item)
                        <tr class="border-b border-bghovertable hover:bg-bghovertable">
                            <td class="p-2 uppercase text-center">
                                <a href="{{ route('admin.orders.show', $item->id) }}"
                                    class="text-colorcard underline">ORD-{{ $item->id }}</a>
                            </td>
                            <td class="p-2 uppercase text-center">
                                {{ Carbon\Carbon::parse($item->date)->format('d/m/Y h:i:s A') }}</td>
                            <td class="p-2 uppercase">{{ $item->name }}</td>
                            <td class="p-2 uppercase text-center">{{ $item->mesa->name }}</td>
                            <td class="p-2 text-center align-middle">

                                @if ($item->status == 1)
                                    <small
                                        class="bg-green-500 text-white rounded p-1 text-[10px] leading-3">PAGADO</small>
                                @else
                                    @if ($item->pedidospendientes->count() > 0)
                                        <small
                                            class="bg-red-500 text-white rounded p-1 text-[10px] leading-3">PENDIENTE</small>
                                    @else
                                        <small class="bg-blue-500 text-white rounded p-1 text-[10px] leading-3">EN
                                            ATENCIÓN</small>
                                    @endif
                                @endif
                            </td>
                            <td class="p-2 uppercase text-center">{{ $item->user->name }}</td>
                            @can('admin.orders.delete')
                                <td class="p-2 align-middle text-center">
                                    <x-button-delete></x-button-delete>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </x-slot>
            </x-table-default>
        </div>
    @endif
</div>
