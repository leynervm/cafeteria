<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Sistema Web') }}</title>

    <!-- Fonts -->

    <!-- Styles -->
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

</head>

<body class="{{ config('app.theme') }}">
    <div class="relative flex justify-center min-h-screen items-center">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center pt-8 gap-2">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('admin') }}"
                            class="bg-fondobotondefault ring-ringbotondefault text-textobotondefault hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:ring focus:ring hover:text-hovertextobotondefault focus:text-hovertextobotondefault button">PANEL
                            ADMINISTRACIÓN</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-fondobotondefault ring-ringbotondefault text-textobotondefault hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:ring focus:ring hover:text-hovertextobotondefault focus:text-hovertextobotondefault button">INICIAR
                            SESIÓN</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-fondobotondefault ring-ringbotondefault text-textobotondefault hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:ring focus:ring hover:text-hovertextobotondefault focus:text-hovertextobotondefault button">REGISTRARSE</a>
                        @endif
                    @endauth

                @endif

            </div>
        </div>
    </div>
</body>

</html>
