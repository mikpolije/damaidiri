@props([
    'id' => '',
    'class' => '',
    'label' => '',
    'required' => 'true',
    'type' => 'text',
    'name' => '',
    'value' => '',
    'readonly' => 'false',
])

<label class="form-label fw-bold" for="{{ $id }}">{{ $label }} {!! $required == 'true' ? '<span class="text-danger">*</span>' : '' !!} :</label>
<textarea name="{{ $name }}" id="{{ $id }}" rows="5" class="form-control form-control-solid-bordered @error($name) border border-2 border-danger @enderror {{ $class }}" placeholder="Masukkan {{ $label }}..." {{ $readonly == 'true' ? 'readonly' : ''}} style="{{ $readonly == 'true' ? 'cursor: not-allowed' : '' }}">{{ old($name, $value) }}</textarea>
@error($name)
    <div id="passwordHelpBlock" class="form-text text-danger">
        {{ $message }}.
    </div>
@enderror