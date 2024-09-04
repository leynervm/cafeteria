<x-app-layout>
    @can('admin.users')
        <div class="flex gap-3">

            @can('admin.users.create')
                @livewire('admin.users.create-user')
            @endcan

            @can('admin.roles')
                <x-link-modal class="" href="{{ route('admin.roles') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    ROLES
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

        <x-tittle>LISTADO DE USUARIOS</x-tittle>
        @livewire('admin.users.show-users')
    @endcan
</x-app-layout>
