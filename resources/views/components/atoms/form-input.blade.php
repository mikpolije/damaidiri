@props([
    'id' => '',
    'class' => '',
    'label' => '',
    'required' => 'true',
    'type' => 'text',
    'name' => '',
    'value' => '',
    'readonly' => 'false',
    'style' => '',
])

<label class="form-label fw-bold" for="{{ $id }}">{{ $label }} {!! $required == 'true' ? '<span class="text-danger">*</span>' : '' !!} :</label>
<input class="form-control form-control-solid-bordered @error($name) border border-2 border-danger @enderror {{ $class }}" id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" placeholder="Masukkan {{ $label }}..." value="{{ old($name, $value) }}" {{ $readonly == 'true' ? 'readonly' : ''}} style="{{ $readonly == 'true' ? 'cursor: not-allowed' : '' }} {{ $style }}" />
@error($name)
    <div id="passwordHelpBlock" class="form-text text-danger">
        {{ $message }}.
    </div>
@enderror