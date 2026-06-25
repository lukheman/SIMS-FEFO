<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan Barang</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        hr {
            height: 2px;
            background-color: black;
            border: none;
        }

        .container {
            width: 70%;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        #keterangan tr td:first-child {
            width: 150px;
        }

        .pesanan,
        #rata-rata {
            border-collapse: collapse;
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100%;
            font-size: 14px;
        }

        .pesanan td,
        .pesanan th,
        #rata-rata td,
        #rata-rata th {
            border: 1px solid #777;
            padding: 8px;
        }

        .pesanan thead th,
        #rata-rata thead th {
            background-color: #1a5bb8;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .pesanan tbody tr:nth-child(even),
        #rata-rata tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pesanan tbody tr:nth-child(odd),
        #rata-rata tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .pesanan tfoot td, 
        .pesanan tr.total td,
        #rata-rata tfoot td {
            background-color: #1a5bb8;
            color: white;
            font-weight: bold;
        }

        .row {
            display: flex;
        }

        .col {
            flex: 1;
            padding: 10px;
        }

        .text-right {
            text-align: end;
        }

        /* Chart-specific styling for better print layout */
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px;
            margin-top: 20px;
            margin-bottom: 50px;
        }

        @media print {
            .chart-container {
                height: 500px !important;
                width: 100% !important;
            }

            canvas {
                font-size: 10pt;
            }
        }
    </style>

</head>

<body onload="window.print()">
    <div class="container">

        <x-kop-laporan />

        <h4 class="text-center"><b>LAPORAN PENJUALAN</b></h4>

        <table id="keterangan">
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $periode }}</td>
            </tr>
        </table>

        <table class="pesanan">

            <thead>
                <tr>
                    <th colspan="2">Barang</th>
                    <th rowspan="2">Metode<br>Pembayaran</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Satuan</th>
                    <th rowspan="2">Harga<br>Barang</th>
                    <th rowspan="2">Total<br>Pembayaran</th>
                    <th rowspan="2">HPP</th>
                    <th rowspan="2">Keuntungan</th>
                </tr>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $total_hpp = 0;
                    $total_keuntungan = 0;
                @endphp
                @foreach ($penjualan as $item)
                @php
                    $satuanUnit = $item->satuan ? ($item->produk->unit_kecil ?? '-') : ($item->produk->unit_besar ?? '-');
                    $hargaBeliUnit = $item->produk->harga_beli ?? 0;
                    if ($item->satuan && ($item->produk->tingkat_konversi ?? 1) > 0) {
                        $hargaBeliUnit = $hargaBeliUnit / $item->produk->tingkat_konversi;
                    }
                    $hpp = $hargaBeliUnit * ($item->jumlah ?? 0);
                    $total_harga = $item->total_harga; 
                    $keuntungan = $total_harga - $hpp;
                    
                    $total_hpp += $hpp;
                    $total_keuntungan += $keuntungan;
                @endphp
                <tr>
                    <td class="text-center">{{ $item->produk->kode_produk ?? '-' }}</td>
                    <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td class="text-center">{{ $item->transaksi->metode_pembayaran ? $item->transaksi->metode_pembayaran->value : '-' }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-center">{{ ucfirst($satuanUnit) }}</td>
                    <td class="text-right">Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($total_harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($hpp, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($keuntungan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total">
                    <td colspan="6" style="text-align: right; text-transform: uppercase;"><strong>Total</strong></td>
                    <td style="text-align: right;"><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
                    <td style="text-align: right;"><strong>Rp{{ number_format($total_hpp, 0, ',', '.') }}</strong></td>
                    <td style="text-align: right;"><strong>Rp{{ number_format($total_keuntungan, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>

        </table>

        <div class="row">
            <div class="col">
            </div>
            <div class="col" style="text-align: center;">
                <p style="margin-bottom: 100px;"><b>Toko Bintang Timur Poleang</b></p>
                <p><b><u>{{ $ttd }}</u></b></p>
            </div>
        </div>
    </div>

</body>



</html>
