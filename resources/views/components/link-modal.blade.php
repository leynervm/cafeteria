@props(['active'])

@php
    $classes = $active ?? false ? 'text-center inline-flex gap-1 text-xs bg-fondobotondefault shadow p-2.5 rounded-sm text-textobotondefault border-2 border-fondobotondefault font-bold hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:text-hovertextobotondefault focus:text-hovertextobotondefault transition-colors ease-in-out duration-150' : 'text-center inline-flex gap-1 text-xs bg-fondobotondefault shadow p-2.5 rounded-sm text-textobotondefault border-2 border-fondobotondefault font-bold hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:text-hovertextobotondefault focus:text-hovertextobotondefault transition-colors ease-in-out duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
