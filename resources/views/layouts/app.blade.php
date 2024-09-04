<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Web') }}</title>

    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}

    <!-- Styles -->
    @livewireStyles
    {{-- <link href="{{ asset('assets/flowbite/flowbite_1-7-0.min.css') }}" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        @font-face {
            font-family: ArchivoNarrow;
            src: url('/assets/fonts/ArchivoNarrow.ttf')
        }

        body {
            font-family: 'ArchivoNarrow', sans-serif;
        }
    </style>

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}" defer></script>

</head>

<body class="{{ config('app.theme') }}">

    <div class="flex bg-principal h-screen w-full relative">

        <div
            class="fixed bottom-0 w-full md:w-20 group md:hover:w-60 md:relative md:flex md:flex-col bg-bgnavlink z-50 transition-all ease-in duration-150">

            @livewire('navigation-menu')
        </div>

        <div class="flex flex-col md:w-full flex-grow overflow-x-hidden">
            {{-- <div class="w-full h-20 flex justify-center items-center bg-gray-200">
                <h1 class="text-3xl uppercase text-gray-500">HEADER</h1>
            </div> --}}
            <div class="overflow-y-auto h-full w-full p-3 mb-24 md:mb-0">

                {{ $slot }}

            </div>

        </div>
    </div>

    @stack('modals')
    <script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/flowbite/flowbite_1-7-0.min.js') }}"></script>
    <script src="{{ asset('assets/sweetAlert2/sweetalert2.all.min.js') }}"></script>
    @livewireScripts

    @yield('js')

</body>
{{-- @section('js') --}}
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        // iconColor: 'white',
        // customClass: {

        // },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
    });

    Livewire.on('created', () => {
        Toast.fire({
            icon: 'success',
            title: 'Registrado correctamente'
        });
    });

    Livewire.on('updated', () => {
        Toast.fire({
            icon: 'success',
            title: 'Actualizado correctamente'
        });
    });

    Livewire.on('deleted', () => {
        Toast.fire({
            icon: 'success',
            title: 'Eliminado correctamente'
        });
    });

    Livewire.on('verified', () => {
        Toast.fire({
            icon: 'success',
            title: 'Cupón aplicado correctamente'
        });
    });

    Livewire.on('notfound', () => {
        Toast.fire({
            icon: 'warning',
            title: 'No disponible'
        });
    });

    Livewire.on('sockets', () => {
        Swal.fire({
            title: 'Acción realizada correctamente, Servidor Websockets no se encuentra disponible !',
            text: 'Datos registrados correctamente, es posible que tenga que refrescar la página manualmente para mostrar los cambios.',
            icon: 'info',
            showCancelButton: false,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
        })

        // Toast.fire({
        //     icon: 'info',
        //     title: 'Acción realizada correctamente, Servidor Websockets no está disponible !'
        // });
    });

    Livewire.on('alert', data => {
        // console.log(data);
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.type,
            showCancelButton: false,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3085d6',
        })
    })
</script>
{{-- @endsection --}}

</html>
