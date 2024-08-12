<label for="{{$name}}" class="form-label mt-1 block text-sm font-medium text-gray-700">{{$label}}</label>
@if ($type === 'textarea')
    <textarea name="{{$name}}" id="{{$name}}" placeholder="{{$placeholder}}" {{ $required ? 'required': ''}} {{$disabled ? 'disabled': ''}} {{$attributes}} class="form-control focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('{{$name}}') is-invalid @enderror">{{$value}}</textarea>
    @error($name)
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
@else
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $attributes }} class="form-control focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" {{$attributes}}>
    @error($name)
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
@endif
