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
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga (Rp.)</th>
                </tr>

            </thead>

            <tbody>
                @foreach ($groupedPenjualan as $group)
                @foreach ($group['items'] as $index => $item)
                <tr>
                    <td class="text-center">{{ $item->tanggal }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-right"> {{ $item->label_jumlah_unit_terjual}} </td>
                    <td class="text-right"> {{ $item->label_harga_jual }} </td>
                    <td class="text-right"> {{ $item->label_total_harga_jual }} </td>
                </tr>
                @endforeach
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total</strong></td>
                    <td style="text-align: right;"><strong>Rp. {{ number_format($total, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>

        </table>



        <h5 class="text-center">5 Produk Terlaris</h5>
        <table class="pesanan">

            <thead>

                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                </tr>

            </thead>

            <tbody>
                @foreach ($top5 as $group)
                @foreach ($group['items'] as $index => $item)

                <tr>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-right"> {{ $item->label_jumlah_unit_terjual}} </td>


                </tr>
                @endforeach
                @endforeach
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
