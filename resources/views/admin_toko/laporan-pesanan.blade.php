@extends('layouts.main')

@section('title', 'Admin Toko')

@section('sidebar-menu')

@include('admin_toko.menu')

@endsection

@section('content')
    <x-laporan.laporan-pesanan ttd="Admin Toko"/>
@endsection
