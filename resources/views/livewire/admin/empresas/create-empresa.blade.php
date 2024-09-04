<div>
    @if (count($empresas))
    @else
        <x-button-modal class="h-full flex-col p-4" wire:click="$set('open', true)">NUEVO</x-button-modal>

        <x-jet-dialog-modal wire:model="open" maxWidth="3xl">
            <x-slot name="title">
                <h1 class="font-semibold text-sm">Nueva Empresa</h1>
                <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
            </x-slot>

            <x-slot name="content">

                <div class="md:flex gap-3">
                    <div class="w-full md:w-1/3">
                        <div class="w-full flex items-end">
                            <div class="w-full">
                                <x-jet-label class="text-xs text-gray-900">RUC :</x-jet-label>
                                <x-input-text wire:model.defer="ruc" type="number" />
                            </div>
                            <x-button-default>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </x-button-default>
                        </div>
                        @error('ruc')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full md:w-2/3">
                        <div class="w-full mt-2 md:mt-0">
                            <x-jet-label class="text-xs text-gray-900">RAZÓN SOCIAL :</x-jet-label>
                            <x-input-text wire:model.defer="name" type="text" placeholder="Razón social..." />
                            @error('name')
                                <x-error-message>{{ $message }}</x-error-message>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="w-full sm:flex gap-3">
                    <div class="w-full sm:w-2/3 mt-2">
                        <x-jet-label class="text-xs text-gray-900">DIRECCIÓN :</x-jet-label>
                        <x-input-text wire:model.defer="direccion" type="text" placeholder="Dirección..." />
                        @error('direccion')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full sm:w-1/3 mt-2">
                        <x-jet-label class="text-xs text-gray-900">UBIGEO :</x-jet-label>
                        <x-input-text wire:model.defer="ubigeo" type="text" placeholder="Ubigeo..." />
                        @error('ubigeo')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                </div>

                <div class="sm:grid grid-cols-3 gap-x-3">
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">DISTRITO :</x-jet-label>
                        <x-input-text wire:model.defer="distrito" type="text" placeholder="Distrito..." />
                        @error('distrito')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">PROVINCIA :</x-jet-label>
                        <x-input-text wire:model.defer="provincia" type="text" placeholder="Provincia..." />
                        @error('provincia')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">DEPARTAMENTO :</x-jet-label>
                        <x-input-text wire:model.defer="departamento" type="text" placeholder="Departamento..." />
                        @error('departamento')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">ZONA :</x-jet-label>
                        <x-input-text wire:model.defer="zona" type="text" placeholder="Zona..." />
                        @error('zona')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">URBANIZACIÓN :</x-jet-label>
                        <x-input-text wire:model.defer="urbanizacion" type="text" placeholder="Urbanización..." />
                        @error('urbanizacion')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">ESTADO :</x-jet-label>
                        <x-input-text wire:model.defer="estado" type="text" placeholder="Estado..." />
                        @error('estado')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">CONDICIÓN :</x-jet-label>
                        <x-input-text wire:model.defer="condicion" type="text" placeholder="Condición..." />
                        @error('condicion')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900 font-bold text-left">LOGO :</x-jet-label>

                        @if ($logo)
                            <img class="w-60 sm:w-full h-32 object-scale-down" src="{{ $logo->temporaryUrl() }}"
                                alt="">
                        @endif
                        {{-- <div class="w-60 sm:w-full h-32">
                        <img src="" alt="" class="w-full h-full object-scale-down">
                    </div> --}}
                        <div wire:loading wire:target="logo">
                            <x-load-image></x-load-image>
                        </div>

                        <x-label-file>
                            <span class="text-xs font-semibold">Adjuntar Logo</span>
                            <input type="file" name="logo" class="hidden" wire:model="logo" accept="image/*"
                                id="{{ $identificador }}">
                        </x-label-file>
                        @error('logo')
                            <x-error-message>*{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900 font-bold text-left">ICONO :</x-jet-label>

                        @if ($icono)
                            <img class="w-60 sm:w-full h-32 object-scale-down" src="{{ $icono->temporaryUrl() }}"
                                alt="">
                        @endif

                        {{-- <div class="w-60 sm:w-full h-32">
                        <img src="" alt="" class="w-full h-full object-scale-down">
                    </div> --}}
                        <div wire:loading wire:target="icono">
                            <x-load-image></x-load-image>
                        </div>

                        <x-label-file>
                            <span class="text-xs font-semibold">Adjuntar Icono</span>
                            <input type="file" name="icono" class="hidden" wire:model="icono" accept="image/*"
                                id="{{ $identificador }}">
                        </x-label-file>
                        @error('icono')
                            <x-error-message>*{{ $message }}</x-error-message>
                        @enderror
                    </div>

                </div>


                <x-tittle class="mt-6">DATOS DE FACTURACIÓN</x-tittle>

                <div class="sm:grid sm:grid-cols-3 md:grid-cols-4 gap-x-3">
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">USUARIO SOL :</x-jet-label>
                        <x-input-text wire:model.defer="usuariosol" type="text" placeholder="Usuario SOL..." />
                        @error('usuariosol')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <x-jet-label class="text-xs text-gray-900">CLAVE SOL :</x-jet-label>
                        <x-input-text wire:model.defer="clavesol" type="text" placeholder="Clave SOL..." />
                        @error('clavesol')
                            <x-error-message>{{ $message }}</x-error-message>
                        @enderror
                    </div>
                    <div class="w-full mt-2">
                        <div>
                            <span class="span-default">Archivo.pem</span>
                        </div>
                        {{-- @if ($publickey)
                        <img class="w-60 sm:w-full h-32 object-scale-down" src="{{ $publickey->temporaryUrl() }}"
                            alt="">
                    @endif --}}
                        <x-label-file>
                            <x-slot name="icono">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </x-slot>
                            <span class="text-xs font-semibold">Adjuntar Clave Pública</span>
                            <input type="file" name="publickey" class="hidden" wire:model="publickey"
                                accept="image/*" id="{{ $identificador }}">
                        </x-label-file>
                    </div>
                    <div class="w-full mt-2">
                        <div>
                            <span class="span-default">Archivo.key</span>
                        </div>
                        <x-label-file>
                            <x-slot name="icono">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </x-slot>
                            <span class="text-xs font-semibold">Adjuntar Clave Privada</span>
                            <input type="file" name="privatekey" class="hidden" wire:model="privatekey"
                                accept="image/*" id="{{ $identificador }}">
                        </x-label-file>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-button-default wire:click="$set('open', false)">
                    Cancelar
                </x-button-default>
                <x-button-default wire:loading.attr="disabled" wire:target="save" wire:click="save">
                    Registrar
                </x-button-default>
            </x-slot>

        </x-jet-dialog-modal>
    @endif
</div>
