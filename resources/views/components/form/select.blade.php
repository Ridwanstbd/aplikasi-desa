@if ($label)
    <label for="{{ $name }}" class="form-label mt-1">{{ $label }}</label>
@endif
<select name="{{ $name }}" id="{{ $name }}" class="form-select">
    @foreach ($options as $option)
        <option value="{{ $option['id'] }}" {{ $selected == $option['id'] ? 'selected' : '' }} data-name="{{ $option['name'] }}">{{ $option['name'] }}</option>
    @endforeach
</select>
