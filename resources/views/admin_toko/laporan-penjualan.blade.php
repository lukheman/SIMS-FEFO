@extends('layouts.main')

@section('title', 'Admin Toko')

@section('sidebar-menu')

@include('admin_toko.menu')

@endsection

@section('content')
    <x-laporan.laporan-penjualan ttd="Admin Toko"/>
@endsection
