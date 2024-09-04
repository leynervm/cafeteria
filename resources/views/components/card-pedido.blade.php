<div

    {{ $attributes->merge(['class' => 'flex flex-col justify-between bg-white shadow-slate-400 rounded shadow p-1 hover:shadow-lg']) }}>

    <div class="w-full">
        @if (isset($header))
            <h1 class="bg-fondotitulo text-textotitulo font-bold">{{ $header }}</h1>
        @endif

        @if (isset($opciones))
            <div class="w-full flex gap-3 mt-1 justify-between">
                {{ $opciones }}
            </div>
        @endif

        {{ $slot }}

        @if (isset($body))
            <h1 class="text-left font-bold text-xs mt-3 mb-1 text-textoprincipal">
                <span class="border-b border-textoprincipal text-sm font-bold">AGREGADOS</span>
            </h1>

            <div class="w-full flex flex-wrap gap-1 items-center justify-between">
                {{ $body }}
            </div>
        @endif
    </div>

    @if (isset($footer))
        <div class="w-full flex items-center gap-2 mt-2 {{ isset($opciones) ? 'justify-between' : 'justify-end' }}">
            {{ $footer }}
        </div>
    @endif

</div>
