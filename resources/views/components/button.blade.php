@props(['type' => 'button', 'label' => '', 'id' => '', 'class' => '', 'onclick' => '', 'style' => '','disabled' => false, 'dataBsToggle' => '', 'dataBsTarget' => '', 'dataBsDismiss' => ''])

<button
type="{{ $type }}"
class="btn {{ $class }}"
id="{{$id}}"
onclick="{{ $onclick }}"
style="{{ $style }}"
{{ $disabled ? 'disabled' : '' }}
data-bs-toggle="{{ $dataBsToggle }}"
data-bs-target="{{ $dataBsTarget }}"
data-bs-dismiss="{{$dataBsDismiss}}"
>

    {{ $label }}
</button>
