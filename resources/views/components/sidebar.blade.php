@php

use App\Enums\Role;

@endphp
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="../index.html" class="brand-link">
            <!--begin::Brand Image-->
            <!-- <img -->
            <!--   src="{{ getActiveUser()->foto ? asset('storage/' . getActiveUser()->foto) : asset('assets/compiled/jpg/2.jpg') }}" -->
            <!--   alt="AdminLTE Logo" -->
            <!--   class="brand-image opacity-75 shadow rounded-circle" -->
            <!-- /> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SIMS</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >

                      @if (auth('web')->check())

                          @if ( getActiveUser()->role === Role::KASIR)
                                @include('admin_toko.menu')
                          @elseif (getActiveUser()->role === \App\Enums\Role::ADMINGUDANG)
                                @include('admin_gudang.menu')
                          @elseif (getActiveUser()->role === \App\Enums\Role::PIMPINAN)
                                @include('pimpinan.menu')
                          @elseif (getActiveUser()->role === \App\Enums\Role::KURIR)
                                @include('kurir.menu')
                          @endif
                      @elseif(auth('reseller')->check())

                            @include('reseller.menu')

                      @endif

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
