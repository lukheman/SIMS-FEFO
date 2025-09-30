@extends('layouts.main')

@section('title', 'Kurir')

@section('sidebar-menu')
@include('kurir.menu')
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="info-box bg-warning shadow">
            <span class="info-box-icon"><i class="fas fa-box-open"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Pesanan Masuk</span>
                <span class="info-box-number">{{ $diproses }}</span>

            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box bg-warning shadow">
            <span class="info-box-icon"><i class="fas fa-truck"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Proses Pengiriman</span>
                <span class="info-box-number">{{ $dikirim }}</span>

            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>
@endsection
