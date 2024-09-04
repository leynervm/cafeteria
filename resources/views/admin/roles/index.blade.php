<x-app-layout>

    @can('admin.roles')
        <div class="flex gap-3">
            @can('admin.roles.create')
                @livewire('admin.roles.create-role')
            @endcan
            @can('admin.usuarios')
                <x-link-modal class="" href="{{ route('admin.users') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    USUARIOS
                </x-link-modal>
            @endcan
            @can('admin.permisos')
                <x-link-modal class="px-2.5" href="{{ route('admin.permisos') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                    PERMISOS
                </x-link-modal>
            @endcan
        </div>

        <x-tittle>LISTADO DE ROLES</x-tittle>
        @livewire('admin.roles.show-roles')
    @endcan
</x-app-layout>
