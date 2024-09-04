<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'text-center inline-flex gap-1 text-xs bg-fondobotondefault shadow p-2.5 rounded-sm text-textobotondefault border-2 border-fondobotondefault font-bold hover:bg-hoverbotondefault focus:bg-hoverbotondefault hover:text-hovertextobotondefault focus:text-hovertextobotondefault transition-colors ease-in-out duration-150']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"
        class="w-4 h-4 mx-auto">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
    </svg>
    {{ $slot }}
</button>
