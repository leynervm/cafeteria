@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs text-gray-500']) }}>
    {{ $value ?? $slot }}
</label>
