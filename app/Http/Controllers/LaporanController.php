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

        $query = Mutasi::with('produk')->where('jenis', 'keluar');
        $jumlahHari = 1;

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
            $jumlahHari = 7;
            $periodeFormat = "Minggu ke-{$week} {$year}";
        } elseif ($filterType === 'tahunan') {
            $query->whereYear('tanggal', $periodeStr);
            $jumlahHari = Carbon::createFromDate($periodeStr)->daysInYear;
            $periodeFormat = "Tahun {$periodeStr}";
        } else {
            $date = Carbon::createFromFormat('Y-m', $periodeStr)->startOfMonth();
            $query->whereYear('tanggal', $date->year)
                  ->whereMonth('tanggal', $date->month);
            $jumlahHari = $date->daysInMonth;
            $periodeFormat = $date->format('m/Y');
        }

        // Fetch and group sales data
        $penjualan = $query->select(
                'mutasi.*',
                DB::raw("SUM(mutasi.jumlah) OVER (PARTITION BY mutasi.id_produk) / {$jumlahHari} AS rata_rata_harian"),
                DB::raw('COUNT(*) OVER (PARTITION BY mutasi.id_produk) AS total_mutasi')
            )
            ->orderBy('id_produk')
            ->orderBy('tanggal')
            ->get();

        // Group sales by product and format rata_rata_harian
        $groupedPenjualan = $penjualan->groupBy('id_produk')->map(function ($sales) use ($jumlahHari) {
            $firstSale = $sales->first();
            $rataRataHarian = $firstSale->rata_rata_harian;
            $unitKecil = $firstSale->produk->unit_kecil ?? 'pcs';
            $unitBesar = $firstSale->produk->unit_besar ?? 'dos';
            $tingkatKonversi = $firstSale->produk->tingkat_konversi ?? 1;

            // Format rata_rata_harian sebagai "X pcs (Y dos)"
            $rataRataBesar = $tingkatKonversi > 0 ? $rataRataHarian / $tingkatKonversi : 0;
            $formattedRataRata = sprintf(
                '%d %s (%d %s)',
                round($rataRataHarian),
                $unitKecil,
                round($rataRataBesar),
                $unitBesar
            );

            return [
                'items' => $sales,
                'rowspan' => $sales->count(),
                'rata_rata_harian' => $formattedRataRata,
                'rrh' => $rataRataHarian
            ];
        });

        // Calculate total sales
        $total = $penjualan->sum('total_harga_jual');

        // Ambil 5 produk dengan total penjualan tertinggi
        $top5 = $groupedPenjualan
            ->sortByDesc('total_penjualan')
            ->take(5);


        return view('invoices.laporan-penjualan', [
            'groupedPenjualan' => $groupedPenjualan,
            'total' => $total,
            'periode' => $periodeFormat,
            'ttd' => $request->ttd,
            'top5' => $top5
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
