@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs text-colorlabel']) }}>
    {{ $value ?? $slot }}
</label>
