<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center border border-transparent bg-[#B20000] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-[#8F0000] focus:outline-none focus:ring-2 focus:ring-[#B20000] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
