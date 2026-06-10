<div class="w-full space-y-2">
    <div class="flex justify-between text-xs text-secondary-500 mb-1">
        <span>{{ $label ?? 'Progresso' }}</span>
        <span class="font-medium" x-text="progress + '%'">{{ $progress ?? 0 }}%</span>
    </div>
    <div class="h-2.5 bg-secondary-200 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full
                    transition-all duration-700 ease-out"
             :style="'width: ' + progress + '%'">
        </div>
    </div>
</div>