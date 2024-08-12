@props(['active'])

@php
$classes = ($active ?? false)
            ? 'd-block w-100 ps-3 pe-4 py-2 border-start border-primary text-start fs-5 fw-medium text-primary bg-light border-primary'
            : 'd-block w-100 ps-3 pe-4 py-2 border-start border-transparent text-start fs-5 fw-medium text-secondary hover:text-dark hover:bg-light hover:border-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
