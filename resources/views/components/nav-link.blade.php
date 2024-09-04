@props(['active'])

@php
    $classes = $active ?? false ? 'relative bg-bgnavlink text-center flex flex-col md:flex-row items-center justify-center md:justify-normal h-16 md:h-11 w-16 md:w-full focus:outline-none hover:bg-hoverbgnavlink text-colornavlink hover:text-hovercolornavlink md:pr-6 before:w-full md:before:w-1 before:h-1 md:before:h-full before:bg-ringnavlink before:absolute before:top-0 before:left-0 transition ease-in-out duration-100' : 'relative bg-bgnavlink text-[8px] text-[10px] flex text-center flex-col md:flex-row items-center justify-center md:justify-normal h-16 md:h-11 w-16 md:w-full focus:outline-none hover:bg-hoverbgnavlink text-colornavlink hover:text-hovercolornavlink md:pr-6 before:w-full md:before:w-1 before:h-1 md:before:h-full before:bg-ringnavlink before:absolute before:top-0 before:left-0 before:hidden hover:before:block transition ease-in-out duration-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span
        class="inline-flex group-hover:shadow group-hover:shadow-bgshadowicono justify-center items-center md:ml-4 bg-fondoicono text-coloricono p-1.5 rounded">
        {{ $slot }}
    </span>
    <p class="md:ml-4 text-[8px] md:text-[10px] pt-1 md:pt-0 tracking-wide truncate md:hidden md:group-hover:block">
        {{ $titulo }}</p>
</a>
