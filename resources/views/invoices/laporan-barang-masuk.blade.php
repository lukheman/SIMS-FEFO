<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Barang Masuk</title>


    <!-- TODO: buat perhari -->
    <!-- Google Font: Source Sans Pro -->
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

        #pesanan {
            border-collapse: collapse;
            margin-top: 50px;
            margin-bottom: 50px;
            width: 90%;
        }


        #pesanan td,
        #pesanan th {
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
    </style>

</head>

<body onload="window.print()">
    <div class="container">

        <x-kop-laporan />

        <h5 class="text-center"><u>Laporan Barang Masuk</u></h5>

        <table id="keterangan">
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $periode }}</td>
            </tr>
        </table>

        <table id="pesanan">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Jenis Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                </tr>

            </thead>

            <tbody>
                @php
                $total = 0;
                $i = 0;
                @endphp

                @foreach ($barang_masuk as $item)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-right"> {{ $item->label_jumlah_unit_dipesan}}</td>
                    <td style="text-align: right;">{{ $item->produk->label_harga_beli }}</td>
                    <td style="text-align: right;">{{ $item->label_total_harga_beli }}</td>
                    @php
                    $total += $item->total_harga_beli
                    @endphp
                </tr>

                @endforeach

                <tr>
                    <td style="text-align: center;" colspan="4">Total</td>
                    <td style="text-align: right;">Rp. {{ number_format($total, 0, ',', '.')}}</td>
                </tr>

            </tbody>
        </table>

        <div class="row">
            <div class="col">
            </div>
            <div class="col" style="text-align: center;">
                <p style="margin-bottom: 100px;"><b>UD Toko Diva Mowewe</b></p>
                <p><b><u>{{ $ttd }}</u></b></p>
            </div>
        </div>
    </div>

</body>

</html>
