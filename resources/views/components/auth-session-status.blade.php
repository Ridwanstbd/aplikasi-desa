@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'fs-2']) }}>
        {{ $status }}
    </div>
@endif
