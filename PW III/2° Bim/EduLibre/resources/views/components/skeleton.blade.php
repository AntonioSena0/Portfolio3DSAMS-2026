<!-- Skeleton Loading Component -->
@props([
    'width' => 'full',
    'height' => null,
    'rounded' => 'xl',
    'animate' => true
])

<div class="
    relative
    overflow-hidden
    bg-secondary-200
    {{ $rounded === 'xl' ? 'rounded-xl' : $rounded === 'lg' ? 'rounded-lg' : $rounded === 'md' ? 'rounded-md' : $rounded === 'sm' ? 'rounded-sm' : 'rounded-none' }}
    {{ $width === 'full' ? 'w-full' : 'w-' . $width }}
    {{ $height ? 'h-' . $height : 'h-32' }}
    {{ $animate ? 'animate-pulse' : '' }}
"></div>