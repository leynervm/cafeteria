<div>

    {{-- {{ ($empresa) }} --}}
    {{-- @if ($empresas) --}}
    <form class="block w-full mt-3" wire:submit.prevent="save">

        <div class="w-full flex gap-2 flex-wrap md:flex-nowrap mt-2">
            <div class="block w-full md:w-1/3">
                <x-label value="RUC :" />
                <x-input-text wire:model.defer="empresa.ruc" type="text" placeholder="" wire:keydown.enter="searchclient"
                    wire:loading.attr="disabled" wire:target="searchclient" />
                <x-jet-input-error for="empresa.ruc" />
            </div>
            <div class="block w-full md:w-2/3">
                <x-label value="RAZÓN SOCIAL :" />
                <x-input-text wire:model.defer="empresa.name" type="text" placeholder="" />
                <x-jet-input-error for="empresa.name" />
            </div>
        </div>

        <div class="w-full flex gap-2 flex-wrap md:flex-nowrap mt-2">
            <div class="block w-full md:w-2/3">
                <x-label value="DIRECCIÓN :" />
                <x-input-text wire:model.defer="empresa.direccion" type="text" placeholder="" />
                <x-jet-input-error for="empresa.direccion" />
            </div>
            <div class="block w-full md:w-1/3">
                <x-label value="UBIGEO :" />
                <x-input-text wire:model.defer="empresa.ubigeo" type="text" placeholder="" />
                <x-jet-input-error for="empresa.ubigeo" />
            </div>
        </div>

        <div class="w-full flex gap-2 flex-wrap md:flex-nowrap mt-2">
            <div class="block w-full md:w-1/3">
                <x-label value="DEPARTAMENTO :" />
                <x-input-text wire:model.defer="empresa.departamento" type="text" placeholder="" />
                <x-jet-input-error for="empresa.departamento" />
            </div>
            <div class="block w-full md:w-1/3">
                <x-label value="PROVINCIA :" />
                <x-input-text wire:model.defer="empresa.provincia" type="text" placeholder="" />
                <x-jet-input-error for="empresa.provincia" />
            </div>
            <div class="block w-full md:w-1/3">
                <x-label value="DISTRITO :" />
                <x-input-text wire:model.defer="empresa.distrito" type="text" placeholder="" />
                <x-jet-input-error for="empresa.distrito" />
            </div>
        </div>

        <div class="w-full flex gap-2 flex-wrap md:flex-nowrap mt-2">
            <div class="block w-full md:w-1/3">
                <x-label value="URBANIZACIÓN :" />
                <x-input-text wire:model.defer="empresa.urbanizacion" type="text" placeholder="" />
                <x-jet-input-error for="empresa.urbanizacion" />
            </div>
            <div class="block w-full md:w-1/3">
                <x-label value="ZONA :" />
                <x-input-text wire:model.defer="empresa.zona" type="text" placeholder="" />
                <x-jet-input-error for="empresa.zona" />
            </div>
            <div class="block w-full md:w-1/3">
                <x-label value="MONEDA :" />
                <x-input-text wire:model.defer="empresa.moneda" type="text" placeholder="" />
                <x-jet-input-error for="empresa.moneda" />
            </div>
        </div>

        <div class="w-full flex flex-wrap gap-2 sm:flex-nowrap mt-2">
            <div class="w-full sm:w-1/2">
                <x-label value="ESTADO :" />
                <x-input-text wire:model.defer="empresa.estado" type="text" placeholder="" />
                <x-jet-input-error for="empresa.estado" />
            </div>
            <div class="w-full sm:w-1/2">
                <x-label value="CONDICIÓN :" />
                <x-input-text wire:model.defer="empresa.condicion" type="text" placeholder="" />
                <x-jet-input-error for="empresa.condicion" />
            </div>
        </div>

        <div class="w-full flex flex-wrap sm:flex-nowrap gap-5 mt-5">
            <div class="w-full md:w-48">
                <div class="w-full overflow-hidden rounded-lg shadow border hover:shadow-lg">
                    @if ($logo)
                        <img class="w-full h-32 object-contain" src="{{ $logo->temporaryUrl() }}" alt="">
                    @else
                        @if (isset($empresa->logo))
                            <img src="{{ Storage::url('empresa/' . $empresa->logo) }}" alt=""
                                class="w-full h-32 object-contain">
                        @endif
                    @endif
                </div>

                <div wire:loading wire:target="logo">
                    <x-load-image></x-load-image>
                </div>

                @can('admin.empresa.create')
                    <div class="block w-full text-center">
                        <x-label-file>
                            <span class="text-xs font-semibold">Adjuntar Logo</span>
                            <input type="file" name="logo" class="hidden" wire:model="logo" accept="image/*"
                                id="{{ $identificador }}">
                        </x-label-file>
                        <x-jet-input-error for="logo" />
                    </div>
                @endcan
            </div>

            <div class="w-full md:w-48 ">
                <div class="w-full overflow-hidden rounded-lg shadow border hover:shadow-lg">
                    @if ($icono)
                        <img class="w-full h-32 object-scale-down" src="{{ $icono->temporaryUrl() }}" alt="">
                    @else
                        @if (isset($empresa->icono))
                            <img src="{{ Storage::url('empresa/' . $empresa->icono) }}" alt=""
                                class="w-full h-32 object-scale-down">
                        @endif
                    @endif
                </div>

                <div wire:loading wire:target="icono">
                    <x-load-image></x-load-image>
                </div>

                @can('admin.empresa.create')
                    <div class="block w-full text-center">
                        <x-label-file>
                            <span class="text-xs font-semibold">Adjuntar Icono</span>
                            <input type="file" name="icono" class="hidden" wire:model="icono" accept="image/*"
                                id="{{ $identificador }}">
                        </x-label-file>
                        <x-jet-input-error for="icono" />
                    </div>
                @endcan
            </div>
        </div>

        <x-tittle class="mt-6 mb-3">DATOS DE FACTURACIÓN</x-tittle>

        <div class="w-full flex flex-wrap sm:flex-nowrap gap-2">
            <div class="w-full sm:w-1/2">
                <x-label value="USUARIO SOL :" />
                <x-input-text wire:model.defer="empresa.usuariosol" type="text" placeholder="" />
                <x-jet-input-error for="empresa.usuariosol" />
            </div>
            <div class="w-full sm:w-1/2">
                <x-label value="CLAVE SOL :" />
                <x-input-text wire:model.defer="empresa.clavesol" type="text" placeholder="" />
                <x-jet-input-error for="empresa.clavesol" />
            </div>
        </div>

        <div class="w-full flex flex-wrap sm:flex-nowrap gap-5 mt-5">
            {{-- <div class="w-full md:w-48 text-center">
                @if (isset($empresa->publickey))
                    <div
                        class="flex items-center justify-center text-xs gap-1 p-2.5 bg-transparent text-colorlabel shadow border hover:shadow-lg">
                        <span class="w-4 h-4 block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </span>
                        <span>{{ $empresa->publickey }} </span>
                    </div>
                @else
                    <div class="w-24 h-24 shadow-lg border p-2 rounded mx-auto text-colorlabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                @endif

                @can('admin.empresa.create')
                    <x-label-file>
                        <x-slot name="icono">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </x-slot>
                        <span class="text-xs font-semibold">Adjuntar Clave Pública</span>
                        <input type="file" name="publickey" class="hidden" wire:model="publickey" accept=".pem"
                            id="{{ $identificador }}">
                    </x-label-file>
                @endcan
                <x-jet-input-error for="publickey" />
            </div>
            <div class="w-full md:w-48 text-center">
                @if (isset($empresa->privatekey))
                    <div
                        class="flex items-center justify-center text-xs gap-1 p-2.5 bg-transparent text-colorlabel shadow border hover:shadow-lg">
                        <span class="w-4 h-4 block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </span>
                        <span>
                            {{ $empresa->privatekey }}
                        </span>
                    </div>
                @else
                    <div class="w-24 h-24 shadow-lg border p-2 rounded mx-auto text-colorlabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                @endif

                @can('admin.empresa.create')
                    <x-label-file>
                        <x-slot name="icono">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </x-slot>
                        <span class="text-xs font-semibold">Adjuntar Clave Privada</span>
                        <input type="file" name="privatekey" class="hidden" wire:model="privatekey" accept=".pem"
                            id="{{ $idprivate }}">
                    </x-label-file>
                @endcan
                <x-jet-input-error for="privatekey" />
            </div> --}}

            <div class="w-full md:w-60 text-center">
                @if (isset($empresa->cert))
                    <div
                        class="flex items-center justify-center text-xs gap-1 p-2.5 bg-transparent text-colorlabel shadow border hover:shadow-lg">
                        <span class="w-4 h-4 block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </span>
                        <span>
                            {{ $empresa->cert }}
                        </span>
                    </div>
                @else
                    <div class="w-24 h-24 shadow-lg border p-2 rounded mx-auto text-colorlabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                @endif

                @can('admin.empresa.create')
                    <x-label-file>
                        <x-slot name="icono">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </x-slot>
                        <span class="text-xs font-semibold">CARGAR CERTIFICADO (.pfx)</span>
                        <input type="file" name="cert" class="hidden" wire:model="cert" accept=".pfx"
                            id="{{ $idprivate }}">
                    </x-label-file>
                @endcan
                <x-jet-input-error for="cert" />
            </div>
        </div>

        @can('admin.empresa.create')
            <div class="w-full pt-8 flex justify-center items-center">
                <x-button-default type="submit" wire:loading.attr="disabled" wire:target="save">
                    GUARDAR
                </x-button-default>
            </div>
        @endcan
    </form>
    {{-- @endif --}}

</div>
