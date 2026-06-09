<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan Reseller</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        hr {
            height: 2px;
            background-color: black;
            border: none;
            margin: 20px 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: end;
        }

        #keterangan {
            margin-bottom: 20px;
        }

        #keterangan tr td:first-child {
            width: 150px;
            font-weight: bold;
        }

        #pesanan {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        #pesanan th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 12px 8px;
            border: 1px solid #ddd;
        }

        #pesanan td {
            border: 1px solid #ddd;
            padding: 10px 8px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 50px;
        }

        .col {
            flex: 1;
            padding: 10px;
        }

        .col.signature {
            flex: 0.5;
            text-align: center;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                width: 90%;
            }
        }
    </style>

</head>

<body onload="window.print()">
    <div class="container">

        <x-kop-laporan />

        <h5 class="text-center"><u>Laporan Penjualan Reseller</u></h5>

        <table id="keterangan">
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $periode }}</td>
            </tr>
            <tr>
                <td>Reseller</td>
                <td>:</td>
                <td>{{ $reseller_nama }}</td>
            </tr>
        </table>

        <table id="pesanan">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Reseller</th>
                    <th>Status Transaksi</th>
                    <th>Status Pembayaran</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $item)
                <tr>
                    <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($item->status->value ?? '-') }}</td>
                    <td>{{ ucfirst($item->status_pembayaran->value ?? '-') }}</td>
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col">
            </div>
            <div class="col signature">
                <p style="margin-bottom: 80px;"><b>Toko Bintang Timur Poleang</b></p>
                <p><b><u>{{ $ttd }}</u></b></p>
            </div>
        </div>
    </div>

</body>

</html>
