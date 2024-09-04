<div>

    {{-- @if (count($permisos))
        <div class="text-left mt-3">
            {{ $permisos->links() }}
        </div>
    @endif --}}

    @if (count($permisos))
        <div class="flex flex-wrap justify-around gap-3 mt-3">
            @foreach ($permisos as $tabla => $permission)
                <div class="shadow shadow-bgshadowicono bg-fondocard rounded w-full sm:w-72 text-xs">
                    <h1 class="font-semibold text-textoprincipal p-1 rounded-t bg-fondoform">{{ $tabla }}</h1>
                    <div class="w-full flex flex-wrap gap-1 p-1 justify-around">
                        @foreach ($permission as $item)
                            <x-label-checkbox :text="$item->descripcion" class="flex-row-reverse">
                                @can('admin.permisos.edit')
                                    <x-button-edit />
                                @endcan
                            </x-label-checkbox>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
