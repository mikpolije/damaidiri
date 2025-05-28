@props([
    'icon' => '',
    'description' => '',
])

<div class="d-flex align-items-center gap-2">
    <span class="pt-1" title="Info">
        <i class="material-icons-two-tone fs-5">{{ $icon }}</i>
    </span>
    <span>
        {!! $description !!}
    </span>
</div>