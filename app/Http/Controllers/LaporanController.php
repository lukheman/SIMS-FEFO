<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Constants\StatusTransaksi;

class LaporanController extends Controller
{
    public function laporanEOQ(Request $request)
    {

        $request->validate([
            'periode' => ['required', 'date_format:Y-m'],
            'ttd' => 'required|string|max:255',
        ]);

        $periode = Carbon::createFromFormat('Y-m', $request->periode)->startOfMonth();

        $data_eoq = Produk::EOQPerBulan($request->periode);

        return view('invoices.laporan-eoq', [
            'periode' => $periode->format('Y-m'),
            'ttd' => $request->ttd,
            'data_eoq' => $data_eoq,
        ]);

    }

    public function laporanPenjualan(Request $request)
    {
        // Validate input
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'ttd' => 'required|string|max:255',
        ]);

        // Parse periode
        $periode = Carbon::createFromFormat('Y-m', $request->periode)->startOfMonth();
        $year = $periode->year;
        $month = $periode->month;
        $jumlahHari = $periode->daysInMonth;

        // Fetch and group sales data
        $penjualan = Mutasi::with('produk')
            ->select(
                'mutasi.*',
                DB::raw('SUM(mutasi.jumlah) OVER (PARTITION BY mutasi.id_produk) / ? AS rata_rata_harian'),
                DB::raw('COUNT(*) OVER (PARTITION BY mutasi.id_produk) AS total_mutasi')
            )
            ->where('jenis', 'keluar')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('id_produk')
            ->orderBy('tanggal')
            ->setBindings([$jumlahHari, 'keluar', $year, $month])
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
            'periode' => $periode->format('Y-m'),
            'ttd' => $request->ttd,
            'top5' => $top5
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
