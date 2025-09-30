@extends('layouts.main')

@section('title', 'Dashbord')

@section('navbar-menu')

<li class="nav-item">
    <a href="#" class="nav-link active">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        Dashboard
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-th"></i>
        Data Pesanan
    </a>
</li>

<!-- <li class="nav-item menu-open"> -->
<!--     <a href="#" class="nav-link active"> -->
<!--         <i class="nav-icon fas fa-tachometer-alt"></i> -->
<!--         <p> -->
<!--             Starter Pages -->
<!--             <i class="right fas fa-angle-left"></i> -->
<!--         </p> -->
<!--     </a> -->
<!--     <ul class="nav nav-treeview"> -->
<!--         <li class="nav-item"> -->
<!--             <a href="#" class="nav-link active"> -->
<!--                 <i class="far fa-circle nav-icon"></i> -->
<!--                 <p>Active Page</p> -->
<!--             </a> -->
<!--         </li> -->
<!--         <li class="nav-item"> -->
<!--             <a href="#" class="nav-link"> -->
<!--                 <i class="far fa-circle nav-icon"></i> -->
<!--                 <p>Inactive Page</p> -->
<!--             </a> -->
<!--         </li> -->
<!--     </ul> -->
<!-- </li> -->
<!---->
<!-- <li class="nav-item"> -->
<!--     <a href="#" class="nav-link"> -->
<!--         <i class="nav-icon fas fa-th"></i> -->
<!--         <p> -->
<!--             <span class="right badge badge-danger">New</span> -->
<!--         </p> -->
<!--     </a> -->
<!-- </li> -->


@endsection

@section('content')
<div class="card">
    <div class="card-body">
        Selamat datang di AdminLTE dengan Laravel 11!
    </div>
</div>
@endsection
