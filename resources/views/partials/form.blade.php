<form class="{{$class}}" action="{{$action}}" id="{{$id}}" method="{{$method}}" enctype="{{$enctype}}" {{$attributes}} >
    @csrf
    @if ($method === 'POST' ||$method === 'PUT' || $method === 'PATCH' || $method === 'DELETE')
        @method($method)
    @endif
    {{$slot}}
</form>


