@props([
    'route' => '{{ route("maintenance") }}',
    'class' => '',
    'icon' => '',
    'label' => '',
])

<a href="{{ $route }}" class="btn {{ $class }}">
    <i class="material-icons">{{ $icon }}</i>
    {!! $label !!}
</a>