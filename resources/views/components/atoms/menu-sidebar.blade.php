@props([
    'route' => 'maintenance',
    'route_active' => 'maintenance',
    'icon' => '',
    'label' => '',
])

<li class="{{ request()->routeIs($route_active.'.*') ? 'active-page' : '' }}">
    <a href="{{ route($route) }}" class="{{ request()->routeIs($route_active.'*') ? 'active' : '' }}"><i class="material-icons-two-tone">{{ $icon }}</i>{{ $label }}</a>
</li>