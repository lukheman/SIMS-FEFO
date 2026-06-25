<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Enums\StatusTransaksi;

class LaporanController extends Controller
{
    public function laporanPenjualan(Request $request)
    {
        // Validate input
        $request->validate([
            'filter_type' => 'nullable|in:harian,mingguan,bulanan,tahunan',
            'periode' => 'required|string',
            'ttd' => 'required|string|max:255',
        ]);

        $filterType = $request->input('filter_type', 'bulanan');
        $periodeStr = $request->periode;

        $query = \App\Models\Pesanan::with(['produk', 'transaksi'])
            ->whereHas('transaksi', function ($q) {
                $q->whereNotIn('status', ['pending', 'dibatalkan']);
            });

        if ($filterType === 'harian') {
            $date = Carbon::createFromFormat('Y-m-d', $periodeStr);
            $query->whereHas('transaksi', function($q) use ($date) {
                $q->whereDate('tanggal', $date->format('Y-m-d'));
            });
            $periodeFormat = $date->format('d/m/Y');
        } elseif ($filterType === 'mingguan') {
            $year = substr($periodeStr, 0, 4);
            $week = substr($periodeStr, 6);
            $date = Carbon::now()->setISODate((int)$year, (int)$week);
            $query->whereHas('transaksi', function($q) use ($date) {
                $q->whereBetween('tanggal', [
                    $date->copy()->startOfWeek()->format('Y-m-d'),
                    $date->copy()->endOfWeek()->format('Y-m-d')
                ]);
            });
            $periodeFormat = "Minggu ke-{$week} {$year}";
        } elseif ($filterType === 'tahunan') {
            $query->whereHas('transaksi', function($q) use ($periodeStr) {
                $q->whereYear('tanggal', $periodeStr);
            });
            $periodeFormat = "Tahun {$periodeStr}";
        } else {
            $date = Carbon::createFromFormat('Y-m', $periodeStr)->startOfMonth();
            $query->whereHas('transaksi', function($q) use ($date) {
                $q->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);
            });
            $periodeFormat = $date->format('m/Y');
        }

        // Fetch sales data
        $penjualan = $query->get()->sortBy(function($pesanan) {
            return $pesanan->transaksi->tanggal;
        });

        // Calculate total sales
        $total = $penjualan->sum('total_harga');

        return view('invoices.laporan-penjualan', [
            'penjualan' => $penjualan,
            'total' => $total,
            'periode' => $periodeFormat,
            'ttd' => $request->ttd
        ]);
    }

    public function laporanPenjualanReseller(Request $request)
    {
        $request->validate([
            'id_reseller' => 'required',
            'filter_type' => 'nullable|in:harian,mingguan,bulanan,tahunan',
            'periode' => 'required|string',
            'ttd' => 'required|string|max:255',
        ]);

        $filterType = $request->input('filter_type', 'bulanan');
        $periodeStr = $request->periode;
        $id_reseller = $request->id_reseller;

        $query = Transaksi::with('user')->whereHas('user');

        if ($id_reseller !== 'semua') {
            $query->where('id_reseller', $id_reseller);
            $reseller_nama = \App\Models\Reseller::find($id_reseller)->nama_lengkap ?? 'Semua Reseller';
        } else {
            $reseller_nama = 'Semua Reseller';
        }

        if ($filterType === 'harian') {
            $date = Carbon::createFromFormat('Y-m-d', $periodeStr);
            $query->whereDate('tanggal', $date->format('Y-m-d'));
            $periodeFormat = $date->format('d/m/Y');
        } elseif ($filterType === 'mingguan') {
            $year = substr($periodeStr, 0, 4);
            $week = substr($periodeStr, 6);
            $date = Carbon::now()->setISODate((int)$year, (int)$week);
            $query->whereBetween('tanggal', [
                $date->copy()->startOfWeek()->format('Y-m-d'),
                $date->copy()->endOfWeek()->format('Y-m-d')
            ]);
            $periodeFormat = "Minggu ke-{$week} {$year}";
        } elseif ($filterType === 'tahunan') {
            $query->whereYear('tanggal', $periodeStr);
            $periodeFormat = "Tahun {$periodeStr}";
        } else {
            $date = Carbon::createFromFormat('Y-m', $periodeStr)->startOfMonth();
            $query->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);
            $periodeFormat = $date->format('m/Y');
        }

        $transaksi = $query->orderBy('tanggal', 'asc')->get();

        return view('invoices.laporan-penjualan-reseller', [
            'transaksi' => $transaksi,
            'periode' => $periodeFormat,
            'reseller_nama' => $reseller_nama,
            'ttd' => $request->ttd,
        ]);
    }

    public function laporanPesanan(Request $request) {

        // Validate input
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'ttd' => 'required|string|max:255',
        ]);

        // Parse periode
        $periode = Carbon::createFromFormat('Y-m', $request->periode)->startOfMonth();

        $pesanan = Transaksi::with('user')->whereHas('user')->get();


        $counts = [];

        foreach (StatusTransaksi::cases() as $status) {
            $counts[$status->value] = Transaksi::where('status', $status->value)->count();
        }

        return view('invoices.laporan-pesanan', [
            'pesanan' => $pesanan,
            'periode' => $periode->format('Y-m'),
            'ttd' => 'Admin Toko',
            'counts' => $counts
        ]);

    }

    public function laporanBarangMasuk(Request $request)
    {

        $request->validate([
            'periode' => 'required',
            'ttd' => 'required',
        ]);

        [$tahun, $bulan] = explode('-', $request->periode);

        $barang_masuk = Mutasi::with('produk')
            ->where('jenis', 'masuk')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->where('jenis', 'masuk')
            ->get();

        return view('invoices.laporan-barang-masuk', [
            'barang_masuk' => $barang_masuk,
            'periode' => $request->periode,
            'ttd' => $request->ttd,
        ]);
    }

    public function laporanPersediaanProduk()
    {

        $produk = Produk::with('persediaan')->get();

        return view('invoices.laporan-persediaan-produk', [
            'produk' => $produk,
            'ttd' => 'Pemilik Toko',
        ]);
    }

}
