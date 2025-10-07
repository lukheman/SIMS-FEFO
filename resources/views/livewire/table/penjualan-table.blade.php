<div>



        <table class="table table-bordered" role="table">
            <thead>
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Jumlah Terjual</th>
                    <th scope="col">Harga Jual</th>
                    <th scope="col">Total Harga</th>
                    <th scope="col" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($this->penjualanList as $item)

                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td>{{ $item->label_jumlah_unit_terjual }}</td>
                    <td>{{ $item->produk->label_harga_jual}}</td>
                    <td>{{ $item->label_total_harga_jual}}</td>
                    <td class="text-end">
                        <button wire:click="deletePenjualan({{ $item->id}})" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    <!-- /.card-body -->
        <x-pagination :items="$this->penjualanList" />
</div>
