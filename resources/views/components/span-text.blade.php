@props(['text'])

<p
    {{ $attributes->merge(['class' => 'bg-fondospan rounded p-1 font-bold leading-3 inline-block text-xs text-textospan']) }}>
    {{ $text }}
</p>
