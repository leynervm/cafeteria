@php
    $classes = 'font-bold text-sm bg-fondotitulo text-textotitulo p-1.5 mt-3 rounded-sm';
@endphp

<h1 {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</h1>
