@if($isHidden())
    <input 
        type="hidden" 
        name="{{ $name }}" 
        value="{{ $value }}"
        {{ $attributes }}
    >
@else
    <div class="form-group mb-3">
        @if($label)
            <label for="{{ $name }}" class="form-label">{{ $label }}</label>
        @endif
        
        @if($type === 'textarea')
            <textarea 
                class="form-control @error($name) is-invalid @enderror" 
                name="{{ $name }}" 
                id="{{ $name }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes }}
            >{{ $value }}</textarea>
        @else
            <input 
                type="{{ $type }}" 
                class="form-control @error($name) is-invalid @enderror" 
                name="{{ $name }}" 
                id="{{ $name }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes }}
            >
        @endif

        @error($name)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
@endif