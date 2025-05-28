@props([
    'icon' => '',
    'label' => '',
    'sub_menu' => [],
])

@php
    $is_active = collect($sub_menu)->contains(function ($item) {
        return request()->routeIs($item['route_active']."*");
    });
@endphp

<li class="{{ $is_active ? 'active-page' : '' }}">
<a href="#"><i class="material-icons-two-tone">{{ $icon }}</i>{{ $label }}<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
    <ul class="sub-menu" style="display: none;">
        @foreach ($sub_menu as $item)
            @can($item['permission'])
            <li>
                <a class="{{ request()->routeIs($item['route_active']."*") ? 'active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
            </li>
            @endcan
        @endforeach
    </ul>
</li>