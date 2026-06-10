<div class="flex items-center">
    <input type="checkbox"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value ?? 1 }}"
           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-secondary-300 rounded
                  {{ $required ? 'required' : '' }}"
                  {{ $checked ? 'checked' : '' }} />
    <label for="{{ $id }}"
           class="ml-2 text-sm font-medium text-secondary-700">
        {{ $label }}
    </label>
</div>