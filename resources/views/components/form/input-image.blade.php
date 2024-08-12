<label for="{{ $name }}{{ $multiple ? '[]' : '' }}" class="form-label mt-1 text-sm font-medium text-gray-700">{{ $label }}</label>
<div class="mt-1">
    <input type="file" id="{{ $name }}{{ $multiple ? '[]' : '' }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" accept="image/*" {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }} {{$disabled ? 'disabled': ''}} class="form-control focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
</div>
@error($name)
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror

