<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <i class="fas fa-home brand-image elevation-3" style="opacity: .8; font-size: 24px; margin-left: 10px;"></i>
        <span class="brand-text font-weight-light">{{ config('app.name') ?? 'UD Toko Diva'}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
<img src="{{ Auth::guard('reseller')->check() ? (Auth::guard('reseller')->user()->foto ? asset('storage/' . Auth::guard('reseller')->user()->foto) : asset('dist/img/avatar5.png')) : (Auth::guard('web')->user()->foto ? asset('storage/' . Auth::guard('web')->user()->foto) : asset('dist/img/avatar5.png')) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('reseller')->user()->name ?? Auth::guard('web')->user()->name }}</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                @yield('sidebar-menu')

                <li class="nav-header">PENGGUNA</li>

                <x-nav-link :href="route('profile.index')" :active="$page === 'Profil'" icon="fas fa-user" >
                   Profile
                </x-nav-link>

                <x-nav-link
                    :href="route('logout')" icon="fas fa-sign-out-alt text-danger" >
                    Logout
                </x-nav-link>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
