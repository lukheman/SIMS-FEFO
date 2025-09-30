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
    </style>

</head>

<body onload="window.print()">
    <div class="container">

        <x-kop-laporan />

        <h5 class="text-center"><u>Laporan Persediaan</u></h5>

        <table id="pesanan">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga Beli (Rp.)</th>
                    <th>Harga Jual (Rp.)</th>
                    <th>Persediaan</th>


                </tr>

            </thead>

            <tbody>

                @foreach ($produk as $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td style="text-align: center;">{{ $item->kode_produk }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td style="text-align: right;"> {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td style="text-align: right;"> {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $item->persediaan->jumlah }}</td>
                </tr>
                @endforeach

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
