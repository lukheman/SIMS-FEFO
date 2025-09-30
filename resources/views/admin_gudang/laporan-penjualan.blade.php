@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')

@include('admin_gudang.menu')

@endsection

@section('content')

    <x-laporan.laporan-penjualan ttd="Admin Gudang"/>

@endsection
