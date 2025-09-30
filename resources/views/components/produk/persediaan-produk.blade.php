<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">

                </div>
                <div class="col-sm-12 col-md-6">

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="datatable" class="table table-bordered table-striped dataTable dtr-inline"
                        aria-describedby="datatable_info">
                        <thead>
                            <tr class="text-center">

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
                                <td class="text-center"> {{ $item->kode_produk }}</td>
                                <td> {{ $item->nama_produk }}</td>
                                <td class="text-right">{{ $item->label_harga_beli}}</td>
                                <td class="text-right">{{ $item->label_harga_jual}}</td>
                                <td class="text-right">{{ $item->label_persediaan}}</td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                        <ul class="pagination">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
