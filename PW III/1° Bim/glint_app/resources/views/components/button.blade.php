<button {{ $attributes->merge(['class' => 'bg-blue-500 cursor-pointer px-4 py-2 rounded-full min-w-[7.5rem] text-center hover:bg-blue-600 transition-all']) }}>
    {{ $slot ?? '' }}
</button>
