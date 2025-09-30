@extends('layouts.main')

@section('title', 'Admin Toko')

@section('sidebar-menu')
@include('admin_toko.menu')
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>{{ $pesanan }}</h3>

                <p>Pesanan</p>
            </div>
            <div class="icon">
                <i class="ion ion-clipboard"></i>
            </div>

        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-warning shadow">
            <div class="inner">
                <h3>{{ $total_penjualan }}</h3>

                <p>Total Penjualan</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-cart"></i>
            </div>

        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-success shadow">
            <div class="inner">
                <h3>{{ $persediaan_barang }}</h3>

                <p>Persediaan Barang</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>

        </div>
    </div>
</div>
@endsection
