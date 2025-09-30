<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Pesanan Barang</title>


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

        <x-kop-laporan tanggal_cetak="kam"/>

        <h5 class="text-center"><u>Nota Pesanan Barang</u></h5>

        <table id="keterangan">
            <tr>
                <td>Pengirim</td>
                <td>:</td>
                <td>{{ $pengirim->name }}</td>
            </tr>
            <tr>
                <td>Penerima</td>
                <td>:</td>
                <td>{{ $penerima->name }}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>:</td>
                <td>{{ $penerima->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $penerima->alamat ?? '-'}}</td>
            </tr>
        </table>

        <table id="pesanan">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Jenis Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga(Rp)</th>
                </tr>

            </thead>

            <tbody>

                @php
                $total = 0;
                $i = 0;
                @endphp

                @foreach ($pesanan as $item)
                <tr>
                    <td style="text-align: center;">{{ ++$i}}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td style="text-align: center;">{{ $item->jumlah }}</td>
                    <td style="text-align: right;">{{ number_format( $item->produk->harga_jual, 2, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format( $item->total_harga, 2, ',', '.') }}</td>
                    @php
                    $total += $item->total_harga
                    @endphp
                </tr>
                @endforeach


                <tr>
                    <td style="text-align: center;" colspan="4">Total</td>
                    <td style="text-align: right;">{{ number_format($total, 2, ',', '.')}}</td>
                </tr>

            </tbody>

        </table>

        <div class="row">
            <div class="col">
                <img src="{{ $qrcode }}" alt="qrcode">
            </div>
            <div class="col" style="text-align: center;">
                <p style="margin-bottom: 100px;"><b>UD Toko Diva Mowewe</b></p>
                <p><b><u>Kepala Toko</u></b></p>
            </div>
        </div>
    </div>

</body>

</html>
