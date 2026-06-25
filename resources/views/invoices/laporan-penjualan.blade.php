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
            margin-top: 50px;
            margin-bottom: 50px;
            width: 90%;
        }


        .pesanan td,
        .pesanan th,
        #rata-rata td,
        #rata-rata th {
            border: 1px solid black;
            padding: 8px;
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

        <h5 class="text-center"><u>Laporan Penjualan</u></h5>

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
                    <th>Tanggal</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Terjual</th>
                    <th>Metode Pembayaran</th>
                    <th>Harga Barang</th>
                    <th>Total Pembayaran</th>
                </tr>

            </thead>

            <tbody>
                @foreach ($penjualan as $item)
                <tr>
                    <td class="text-center">{{ $item->transaksi->tanggal ? $item->transaksi->tanggal->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $item->produk->kode_produk ?? '-' }}</td>
                    <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td class="text-right"> {{ $item->label_jumlah_pesanan }} </td>
                    <td class="text-center"> {{ $item->transaksi->metode_pembayaran ? $item->transaksi->metode_pembayaran->value : '-' }} </td>
                    <td class="text-right"> {{ $item->label_harga_satuan }} </td>
                    <td class="text-right"> {{ $item->label_total_harga_jual }} </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Total</strong></td>
                    <td style="text-align: right;"><strong>Rp. {{ number_format($total, 2, ',', '.') }}</strong></td>
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
