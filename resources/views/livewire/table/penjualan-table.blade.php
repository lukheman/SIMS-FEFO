<div class="card">

<div class="card-body">



        <table class="table table-bordered" role="table">
            <thead>
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Jumlah Terjual</th>
                    <th scope="col">Metode Pembayaran</th>
                    <th scope="col">Harga Barang</th>
                    <th scope="col">Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($this->penjualanList as $item)

                <tr>
                    <td>{{ $item->transaksi->tanggal ? $item->transaksi->tanggal->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->produk->kode_produk ?? '-' }}</td>
                    <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td>{{ $item->label_jumlah_pesanan }}</td>
                    <td>{{ $item->transaksi->metode_pembayaran ? $item->transaksi->metode_pembayaran->value : '-' }}</td>
                    <td>{{ $item->label_harga_satuan }}</td>
                    <td>{{ $item->label_total_harga_jual }}</td>
                </tr>

                @endforeach

            </tbody>
        </table>
    <!-- /.card-body -->
        <x-pagination :items="$this->penjualanList" />
</div>
</div>
