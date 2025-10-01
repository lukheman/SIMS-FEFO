@props(['href', 'icon'])

<li class="nav-item">
    <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }} {{ request()->fullUrl() === $href ? 'active' : ''}}">
        <i class="nav-icon bi {{ $icon }}"></i>
        <p>{{ $slot }}</p>
    </a>
</li>
