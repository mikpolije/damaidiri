@props([
    'links' => [],
])

<nav aria-label="breadcrumb" class="d-flex justify-content-end">
    <ol class="breadcrumb breadcrumb-container breadcrumb-separator-chevron">
        @foreach ($links as $item)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>