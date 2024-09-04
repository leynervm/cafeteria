<div>

    @if (count($categories))
        <div class="text-left mt-3">
            {{ $categories->links() }}
        </div>
    @endif

    @if (count($categories))
        <div class="flex flex-wrap justify-around sm:justify-start gap-1 mt-3">
            @foreach ($categories as $item)
                <x-card-product class="w-32 sm:w-32">

                    <h1 class="font-bold text-xs text-center text-textoprincipal my-1">{{ $item->name }}</h1>

                    <x-slot name="footer">
                        @can('admin.categories.edit')
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                wire:target="edit"></x-button-edit>
                        @endcan
                        @can('admin.categories.delete')
                            <x-button-delete wire:click="$emit('confirmDelete',{{ $item }})"
                                wire:loading.attr="disabled" wire:target="confirmDelete"></x-button-delete>
                        @endcan
                    </x-slot>
                </x-card-product>
            @endforeach
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <h1 class="font-semibold text-sm">Actualizar Categoría</h1>
            <x-button-close-modal wire:click="$set('open', false)"></x-button-close-modal>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="NOMBRE CATEGORÍA :" />
                    <x-input-text wire:model.defer="category.name" type="text"
                        placeholder="Ingrese nombre de categoría..." />
                    <x-jet-input-error for="category.name" />
                </div>
                <div class="w-full mt-3 text-center">
                    <x-button-default wire:loading.attr="disabled" wire:target="update" type="submit">
                        Actualizar
                    </x-button-default>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    @section('js')
        <script>
            Livewire.on('confirmDelete', event => {
                Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Desea eliminar la categoría : ' + event.name,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emitTo('admin.categorias.show-categorias', 'delete', event);
                        }
                    })
            });
        </script>
    @endsection

</div>
