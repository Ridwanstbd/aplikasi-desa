<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-light border border-secondary text-secondary rounded-pill shadow-sm d-inline-flex align-items-center px-4 py-2 fs-6 fw-semibold uppercase tracking-widest hover-bg-light focus:outline-none focus:ring-2 focus:ring-primary disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
