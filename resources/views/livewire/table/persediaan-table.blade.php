<div>

    <table class="table table-bordered" role="table">
        <thead>
            <tr>
                <th scope="col" class="text-center">Kode Produk</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Harga Beli</th>
                <th scope="col">Harga Jual</th>
                <th scope="col" class="text-center">Total Persediaan</th>
                <th scope="col">Detail Batch (FEFO)</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($this->produkList as $item)

                <tr>
                    <td class="text-center">{{ $item->kode_produk }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->label_harga_beli }}</td>
                    <td>{{ $item->label_harga_jual}}</td>
                    <td class="text-center fw-bold">{{ $item->totalPersediaan() }}</td>
                    <td>
                        @if($item->persediaan->count() > 0)
                            <table class="table table-sm table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th class="py-1" style="font-size: 0.8rem;">Tanggal Exp</th>
                                        <th class="py-1 text-center" style="font-size: 0.8rem;">Stok</th>
                                        <th class="py-1" style="font-size: 0.8rem;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->persediaan as $batch)
                                        <tr>
                                            <td class="py-1" style="font-size: 0.8rem;">
                                                {{ $batch->tanggal_exp ? $batch->tanggal_exp->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="py-1 text-center" style="font-size: 0.8rem;">
                                                {{ $batch->jumlah }}
                                            </td>
                                            <td class="py-1" style="font-size: 0.8rem;">
                                                @if($batch->tanggal_exp)
                                                    @if($batch->is_expired)
                                                        <span class="badge bg-danger">Expired</span>
                                                    @elseif($batch->is_hampir_expired)
                                                        <span class="badge bg-warning text-dark">Hampir Exp</span>
                                                    @else
                                                        <span class="badge bg-success">Aman</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <span class="text-muted">Tidak ada stok</span>
                        @endif
                    </td>
                </tr>

            @endforeach

        </tbody>
    </table>
    <!-- /.card-body -->
    <x-pagination :items="$this->produkList" />
</div>