@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold text-[#313131]']) }}>
    {{ $value ?? $slot }}
</label>
