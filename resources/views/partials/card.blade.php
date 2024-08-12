@props([
    'title' => '',
    'class' => '',
    'image' => null,
    'anchor' => null,
    'subtitle' => null,
    'button' => null,
    'footer' => null
])

<div class="card bg-white">
    @if ($image)
        <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}">
    @endif
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center {{$class}}">
            <h5 class="card-title">{{ $title }}</h5>
            @if ($anchor)
                <x-anchor :href="$anchor['href']" :label="$anchor['label']" :class="$anchor['class']" />
            @endif
            @if ($button)
                <x-button
                    :type="$button['type'] ?? 'button'"
                    :class="$button['class']"
                    :dataBsToggle="$button['dataBsToggle']"
                    :dataBsTarget="$button['dataBsTarget']"
                    :label="$button['label']"
                />
            @endif
        </div>
        @if ($subtitle)
            <h6 class="card-subtitle mb-2 text-muted">{{ $subtitle }}</h6>
        @endif
        {{ $slot }}
    </div>
    @if ($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
