@props([
    'id' => '',
    'image' => '',
    'style' => '',
])

<a data-fslightbox="gallery" href="{{ $image }}">
    <img src="{{ $image }}" style="{{ $style }}" id="{{ $id }}">
</a>