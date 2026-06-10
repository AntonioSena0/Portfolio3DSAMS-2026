{{-- Default button (primary) --}}
@if ($variant ?? 'primary' === 'primary')
    <button type="{{$type ?? 'button'}}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700
                   text-white text-sm font-semibold rounded-xl
                   transition-all duration-200 hover:shadow-card-hover active:scale-95
                   focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                   {{$class ?? ''}}">
        {{$slot}}
    </button>
{{-- Outline button (secondary) --}}
@elseif ($variant ?? 'primary' === 'outline')
    <button type="{{$type ?? 'button'}}"
            class="inline-flex items-center gap-2 px-5 py-2.5 border border-secondary-200
                   text-secondary-700 text-sm font-semibold rounded-xl
                   hover:border-primary-300 hover:text-primary-600
                   transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                   {{$class ?? ''}}">
        {{$slot}}
    </button>
{{-- Ghost button --}}
@elseif ($variant ?? 'primary' === 'ghost')
    <button type="{{$type ?? 'button'}}"
            class="inline-flex items-center gap-2 px-4 py-2 text-secondary-600 text-sm font-medium
                   rounded-xl hover:bg-secondary-100 transition-colors duration-150
                   {{$class ?? ''}}">
        {{$slot}}
    </button>
{{-- Danger button --}}
@elseif ($variant ?? 'primary' === 'danger')
    <button type="{{$type ?? 'button'}}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600
                   text-white text-sm font-semibold rounded-xl transition-all duration-200
                   {{$class ?? ''}}">
        {{$slot}}
    </button>
{{-- Link button --}}
@else
    <a href="{{$href ?? '#' }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-secondary-600 text-sm font-medium
              rounded-xl hover:bg-secondary-100 transition-colors duration-150
              {{$class ?? ''}}">
        {{$slot}}
    </a>
@endif