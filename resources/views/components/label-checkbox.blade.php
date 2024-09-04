@props(['text' => null])

<label
    {{ $attributes->merge(['class' => 'flex gap-1 leading-3 items-center cursor-pointer bg-fondospan text-xs text-textospan rounded p-1']) }}>
    {{ $slot }}
    @if (isset($text))
        {{ $text }}
    @endif
</label>
