@extends('layouts.main')

@section('title', 'Toko Kecil')

@section('sidebar-menu')
@include('reseller.menu')
@endsection

@section('content')
<div class="row">
    <div class="col-6">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>{{ $keranjang }}</h3>

                <p>Barang di keranjang</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>

        </div>
    </div>
    <div class="col-6">
        <div class="small-box bg-warning shadow">
            <div class="inner">
                <h3>{{ $pesanan }}</h3>

                <p>Pesanan belum selesai</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-cart"></i>
            </div>

        </div>
    </div>
</div>
@endsection
