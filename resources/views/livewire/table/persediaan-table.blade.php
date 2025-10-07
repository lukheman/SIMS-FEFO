<div>

        <table class="table table-bordered" role="table">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Kode Produk</th>
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Harga Beli</th>
                    <th scope="col">Harga Jual</th>
                    <th scope="col" class="text-center">Persediaan</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($this->produkList as $item)

                <tr>
                    <td class="text-center">{{ $item->kode_produk }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->label_harga_beli }}</td>
                    <td>{{ $item->label_harga_jual}}</td>
                    <td class="text-center">{{ $item->persediaan->jumlah}}</td>
                </tr>

                @endforeach

            </tbody>
        </table>
    <!-- /.card-body -->
        <x-pagination :items="$this->produkList" />
</div>
