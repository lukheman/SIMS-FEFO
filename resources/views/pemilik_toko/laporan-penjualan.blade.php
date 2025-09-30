@extends('layouts.main')

@section('title', 'Pemilik Toko')

@section('sidebar-menu')

@include('pemilik_toko.menu')

@endsection

@section('content')

    <x-laporan.laporan-penjualan ttd="Pemilik Toko"/>

@endsection
