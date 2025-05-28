@props([
    'id' => '',
    'type' => 'text',
    'class' => '',
    'placeholder' => '',
    'name' => '',
    'value' => '',
])

<input
    id="{{ $id }}"
    type="{{ $type }}"
    class="form-control form-control-solid-bordered {{ $class }}"
    placeholder="{{ $placeholder }}"
    name="{{ $name }}"
    value="{{ $value }}"
    autocomplete="off"
/>