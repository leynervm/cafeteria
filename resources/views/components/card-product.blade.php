@php
    $classes = 'flex flex-col justify-between bg-fondocard shadow shadow-colorborder rounded shadow p-1 hover:shadow-lg';
@endphp

{{-- w-full sm:w-48 --}}
<div {{ $attributes->merge(['class' => $classes]) }}>

    <div class="w-full">
        @if (isset($imagen))
            <div class="h-60 sm:h-32">
                {{ $imagen }}
            </div>
        @endif

        {{ $slot }}

        @if (isset($body))
            <h1 class="font-bold text-xs border-b inline-block mt-2 mb-1 border-textoprincipal text-textoprincipal">
                AGREGADOS</h1>

            <div class="w-full flex flex-wrap gap-1 items-center justify-between">
                {{ $body }}
            </div>
        @endif
    </div>

    @if (isset($footer))
        @if ($default)
            <div class="w-full flex justify-between items-center gap-1 mt-3 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4 block text-green-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <div class="flex items-center justify-end gap-1">
                    {{ $footer }}
                </div>
            </div>
        @else
            <div class="w-full flex justify-end items-center gap-1 mt-3 ">
                {{ $footer }}
            </div>
        @endif
    @endif
</div>
