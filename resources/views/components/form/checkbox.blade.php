<div class="form-check m-1">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" class="form-check-input" id="{{ $name }}" value="1" name="{{ $name }}"  {{ $checked ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $hidden ? 'hidden' : '' }}>
    @if ($label)
        <label class="form-check-label" for="{{ $name }}" {{ $hidden ? 'hidden' : '' }}>{{ $label }}</label>
    @endif
</div>

