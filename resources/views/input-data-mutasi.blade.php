<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Mutasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            padding: 2rem;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #1a202c;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d3748;
            font-weight: 500;
            font-size: 0.9rem;
        }

        select, input[type="number"], input[type="date"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #fff;
            transition: border-color 0.2s ease-in-out;
        }

        select:focus, input[type="number"]:focus, input[type="date"]:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }

        .submit-btn {
            background-color: #3182ce;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .submit-btn:hover {
            background-color: #2b6cb0;
        }

        @media (max-width: 600px) {
            body {
                padding: 1rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Tambah Data Mutasi -->
        <div class="form-card">
            <h1>Input Data Pembelian / Barang Masuk</h1>
            <form action="{{ route('mutasi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="produk-pembelian">Produk</label>
                    <select name="id_produk" id="produk-pembelian">
                        @foreach ($produk as $item)
                            <option value="{{ $item->id }}">{{ $item->kode_produk }} - {{ $item->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="jenis" value="masuk">

                <div class="form-group">
                    <label for="jumlah-pembelian">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah-pembelian">
                </div>

                <div class="form-group">
                    <label for="tanggal-pembelian">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal-pembelian">
                </div>

                <button type="submit" class="submit-btn">Simpan</button>
            </form>
        </div>

        <!-- Input Data Penjualan -->
        <div class="form-card">
            <h1>Input Data Penjualan</h1>
            <form action="{{ route('mutasi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="produk-penjualan">Produk</label>
                    <select name="id_produk" id="produk-penjualan">
                        @foreach ($produk as $item)
                            <option value="{{ $item->id }}">{{ $item->kode_produk }} - {{ $item->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="jenis" value="keluar">

                <div class="form-group">
                    <label for="jumlah-penjualan">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah-penjualan">
                </div>

                <div class="form-group">
                    <label for="tanggal-penjualan">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal-penjualan">
                </div>

                <button type="submit" class="submit-btn">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>
