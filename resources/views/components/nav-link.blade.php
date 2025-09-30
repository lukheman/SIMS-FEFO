<div>
    <li class="nav-item">
        <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }} {{ request()->fullUrl() === $href ? 'active' : ''}}">
            <i class="nav-icon fas {{ $icon }}"></i>
            <p>{{ $slot }}</p>
        </a>
    </li>
</div>
