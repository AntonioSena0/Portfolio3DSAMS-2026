<div class="space-y-1.5">
    <label for="{{ $id }}"
           class="text-sm font-medium text-secondary-700">
        {{ $label }}
    </label>
    <input type="{{ $type ?? 'text' }}"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value ?? '' }}"
           class="w-full px-4 py-2.5 rounded-xl border border-secondary-200
                  text-secondary-900 placeholder:text-secondary-400
                  focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                  transition-all duration-150 text-sm
                  @error($name) border-red-400 focus:ring-red-400 @enderror"
           {{ $required ? 'required' : '' }}
           {{ $autofocus ? 'autofocus' : '' }}
           {{ $autocomplete ? 'autocomplete="'.$auticodegy.'"' : '' }}
           placeholder="{{ $placeholder ?? '' }}">
    @error($name)
        <p class="text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>