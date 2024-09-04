<div>
    <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" class="peer hidden"
        @if ($active) checked @endif />
    <label for="{{ $id }}"
        {{ $attributes->merge(['class' => 'block bg-fondobotondefault text-xs text-textobotondefault cursor-pointer rounded-sm p-2.5 text-center peer-checked:bg-hoverbotondefault peer-checked:ring peer-checked:ring-ringbotondefault peer-checked:font-bold peer-checked:text-hovertextobotondefault']) }}>
        {{ $text }}
    </label>
</div>
