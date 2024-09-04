<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'bg-fondobotondefault ring-ringbotondefault text-textobotondefault hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:ring focus:ring hover:text-hovertextobotondefault focus:text-hovertextobotondefault button']) }}>
    {{ $slot }}
</button>