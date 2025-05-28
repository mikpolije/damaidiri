@props([
    'id' => '',
    'name' => '',
    'class' => '',
    'options' => [],
])

<select class="form-select form-select-solid-bordered bg-light {{ $class }}" id="show" name="show">
    @foreach ($options as $item)
        <option value="{{ $item['value'] }}" {{ $item['config'] }}>{{ $item['label'] }}</option>
    @endforeach
</select>