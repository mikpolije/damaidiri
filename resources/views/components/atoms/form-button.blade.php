@props([
    'type' => 'button',
    'class' => 'btn',
    'icon' => '',
    'label' => '',
    'id' => '',
])

<button type="{{ $type }}" class="btn {{ $class }}" id="{{ $id }}">
    <i class="material-icons">{{ $icon }}</i>
    {{ $label }}
</button>