@props([
    'name' => '',
    'id' => '',
    'value' => '',
    'config' => '',
    'label' => '',
])

<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="{{ $name }}" id="checkbox{{ $id }}" value="{{ $value }}" {{ $config }}>
    <label class="form-check-label" for="checkbox{{ $id }}">{{ $label }}</label>
</div>