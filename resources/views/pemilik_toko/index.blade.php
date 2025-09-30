@extends('layouts.main')

@section('title', 'Pemilik Toko')

@section('sidebar-menu')
@include('pemilik_toko.menu')
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="info-box bg-warning shadow">
            <span class="info-box-icon"><i class="fas fa-list-ol"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Transaksi Selesai</span>
                <span class="info-box-number">{{ $transaksi }}</span>

            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box bg-warning shadow">
            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Persediaan Barang</span>
                <span class="info-box-number">{{ $persediaan_barang }}</span>

            </div>
            <!-- /.info-box-content -->
        </div>
    </div>

</div>
@endsection
