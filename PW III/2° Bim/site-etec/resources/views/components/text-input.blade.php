@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#DDD] text-[#313131] shadow-sm focus:border-[#B20000] focus:ring-[#B20000]']) }}>
