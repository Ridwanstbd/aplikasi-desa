<div class="form-group">
    @if ($label)
        <label for="{{ $name }}" class="form-label mt-1 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
        id="{{ $name }}" 
        {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'form-select block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ' . 
                      ($errors->has($name) ? 'is-invalid border-red-500' : '')
        ]) }}
    >
        @if (!$multiple && !$required)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($mappedOptions as $option)
            <option 
                value="{{ $option['id'] }}" 
                {{ $option['selected'] ? 'selected' : '' }}
            >
                {{ $option['name'] }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if ($attributes->has('help'))
        <small class="form-text text-muted mt-1 text-sm">
            {{ $attributes->get('help') }}
        </small>
    @endif
</div>

@pushonce('scripts')
<script>
    function initializeSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for elements with select2 class
        const select2Elements = document.querySelectorAll('select.select2');
        if (select2Elements.length > 0) {
            select2Elements.forEach(select => {
                initializeSelect2(select);
            });
        }

        // Keep the existing multiple select initialization
        const multiSelects = document.querySelectorAll('select[multiple]');
        if (multiSelects.length > 0) {
            multiSelects.forEach(select => {
                initializeSelect2(select);
            });
        }
    });
</script>
@endpushonce