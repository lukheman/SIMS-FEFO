@php

use App\Enums\Role;

@endphp
<div>

    @if (getActiveUser()->role === Role::RESELLER)

    <livewire:dashboard.reseller-dashboard />
    @elseif (getActiveUser()->role === Role::KASIR)
    <livewire:dashboard.kasir-dashboard />

    @elseif (getActiveUser()->role === Role::ADMINGUDANG)
    <livewire:dashboard.admin-gudang-dashboard />

    @elseif (getActiveUser()->role === Role::KURIR)
    <livewire:dashboard.kurir-dashboard />
    @elseif (getActiveUser()->role === Role::PIMPINAN)
    <livewire:dashboard.pimpinan-dashboard />

    @else

    @endif

</div>
