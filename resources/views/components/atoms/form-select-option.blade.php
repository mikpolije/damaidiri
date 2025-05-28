@props([
    'id' => '',
    'class' => '',
    'label' => '',
    'required' => 'true',
    'name' => '',
    'value' => '',
    'options' => [],
])

<label class="form-label fw-bold" for="{{ $id }}">{{ $label }} {!! $required == 'true' ? '<span class="text-danger">*</span>' : '' !!} :</label>
<select class="form-select form-select-solid-bordered bg-light {{ $class }} @error($name) border border-2 border-danger @enderror" id="{{ $id }}" name="{{ $name }}">
    <option value="" disabled selected>Pilih {{ $label }}</option>
    @foreach ($options as $item)
        <option value="{{ $item['value'] }}" {{ $item['config'] }}>
            &nbsp; 
            {{ $item['label'] }}
        </option>
    @endforeach
</select>
@error($name)
    <div id="passwordHelpBlock" class="form-text text-danger">
        {{ $message }}.
    </div>
@enderror