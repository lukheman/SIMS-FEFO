<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Persediaan Produk</title>


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
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100%;
            font-size: 14px;
        }

        #pesanan td,
        #pesanan th {
            border: 1px solid #777;
            padding: 8px;
        }

        #pesanan thead th {
            background-color: #1a5bb8;
            color: white;
            text-align: center;
        }

        #pesanan tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #pesanan tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        #pesanan tfoot td, 
        #pesanan tr.total td {
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
    </style>

</head>

<body onload="window.print()">
    <div class="container">

        <x-kop-laporan />

        <h4 class="text-center"><b>LAPORAN PERSEDIAAN</b></h4>

        <table id="pesanan">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Persediaan</th>


                </tr>

            </thead>

            <tbody>

                @foreach ($produk as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td style="text-align: center;">{{ $item->kode_produk }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td style="text-align: right;">{{ $item->label_harga_beli}}</td>
                        <td style="text-align: right;">{{ $item->label_harga_jual}}</td>
                        <td style="text-align: center;">{{ $item->label_persediaan }}</td>
                    </tr>
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