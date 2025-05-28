@props([
    'label' => '',
    'required' => 'false',
    'class' => '',
])

<label class="form-label fw-bold {{ $class }}">{{ $label }}{!! $required == 'true' ? '<span class="text-danger">*</span>' : '' !!} :</label>