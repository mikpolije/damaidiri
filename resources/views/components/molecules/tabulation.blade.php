@props([
    'tabs' => [],
])

<ul class="nav nav-pills nav-justified align-items-center">
    @foreach ($tabs as $item)
        @can($item['permission'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs($item['route_active'].'*') ? 'active' : '' }}" aria-current="page" href="{{ $item['route'] }}">{{ $item['label'] }}</a>
            </li>
        @endcan
    @endforeach
</ul>