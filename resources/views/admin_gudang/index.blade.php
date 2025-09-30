@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')
@include('admin_gudang.menu')
@endsection

@section('content')
<div class="row"> 
    <div class="col-md-6">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>{{ $total_produk }}</h3>

                <p>Total Produk</p>
            </div>
            <div class="icon">
                <i class="ion ion-cube"></i>
            </div>

        </div>
    </div>

    <div class="col-md-6">
        <div class="small-box bg-warning shadow">
            <div class="inner">
                <h3>{{ $total_persediaan }}</h3>

                <p>Total Persediaan</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>

        </div>
    </div>
</div>
@endsection
