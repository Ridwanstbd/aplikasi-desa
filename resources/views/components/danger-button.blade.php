<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger btn-lg']) }}>
    {{ $slot }}
</button>
