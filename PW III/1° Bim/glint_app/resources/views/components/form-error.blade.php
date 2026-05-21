@props(['field' => null])

@if (is_null($field))
    @if (session('error'))
        <div class="w-full mt-3 rounded-xl border border-red-400 bg-red-50 text-red-800 text-sm px-4 py-3 flex items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
@elseif ($field === '__all__')
    @if (session('error'))
        <div class="w-full mt-3 rounded-xl border border-red-400 bg-red-50 text-red-800 text-sm px-4 py-3 flex items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @elseif ($errors->any())
        <div class="w-full mt-3 rounded-xl border border-red-400 bg-red-50 text-red-800 text-sm px-4 py-3 flex items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
            <span>Por favor, verifique os dados informados.</span>
        </div>
    @endif
@else
    @error($field)
        <p class="text-red-500 font-bold text-xs mt-1">
            {{ $message }}
        </p>
    @enderror
@endif
