<x-layout>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal-scanner">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>
            <div class="col-6 d-flex justify-content-end">
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- datatable -->
        <table id="datatable" class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Harga Beli/bal (Rp)</th>
                    <th>Harga Jual/bal (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $item)
                <tr>
                    <td class="text-center">{{ $item->kode_produk }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->exp }}</td>
                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info btn-info-produk"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-info-produk"
                                data-id-produk="{{ $item->id }}">
                            <i class="fas fa-info"></i> Info
                        </button>
                        <button class="btn btn-sm btn-warning text-white btn-update-produk"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-update-produk"
                                data-id-produk="{{ $item->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete-produk"
                                data-id-produk="{{ $item->id }}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Scanner -->
<div class="modal fade" id="modal-scanner" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan Barcode</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div id="scanner"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add Produk -->
<div class="modal fade" id="modal-add-produk" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form id="form-add-produk">
        @csrf
        <div class="modal-body">
                    <div class="row input-produk-main">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode-produk">Barcode Produk</label>
                                <input type="text" class="form-control" name="kode_produk" id="kode-produk">
                            </div>

                            <div class="form-group">
                                <label for="nama-produk">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" id="nama-produk"
                                    placeholder="Nama Produk">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="tanggal-kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="date" class="form-control" name="exp" id="tanggal-kadaluarsa" min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" class="form-control" name="gambar" id="gambar">
                            </div>



                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="unit-kecil">Unit Kecil</label>
                                        <select name="unit_kecil" class="form-control" id="unit-kecil">

                                            @foreach (\App\Enums\UnitKecilProduk::values() as $item)

                                            <option value="{{ $item }}">{{ $item}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group">

                                        <label for="unit-besar">Unit Besar</label>
                                        <select name="unit_besar" class="form-control" id="unit-besar">

                                            @foreach (\App\Enums\UnitBesarProduk::values() as $item)

                                            <option value="{{ $item }}">{{ $item}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="tingkat_konversi">Jumlah <span class="unit-kecil"></span>/<span class="unit-besar"></span> </label>
                                        <input type="number" class="form-control" name="tingkat_konversi" id="tingkat_konversi" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga-beli">Harga Beli/<span class="unit-besar"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_beli" id="harga-beli" min="0">
                            </div>
                                </div>
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga-jual">Harga Jual/<span class="unit-besar"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_jual" id="harga-jual" min="0">
                            </div>
                                </div>
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga_jual_unit_kecil">Harga Jual/<span class="unit-kecil"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_jual_unit_kecil" id="harga_jual_unit_kecil" min="0">
                            </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row d-none input-produk-biaya">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="biaya-pemesanan">Biaya Pemesanan</label>
                                <input type="number" class="form-control" name="biaya_pemesanan" id="biaya-pemesanan"
                                    placeholder="Biaya Pemesanan" min="0">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="biaya-penyimpanan">Biaya Penyimpanan</label>
                                <input type="number" class="form-control" name="biaya_penyimpanan"
                                    id="biaya-penyimpanan" placeholder="Biaya Penyimpanan" min="0">
                            </div>
                        </div>
                    </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

</x-layout>

@push('scripts')

<script>
$(document).ready(function () {
    const updateUnitText = () => {
        $('.unit-besar').text($('#form-add-produk #unit-besar').val());
        $('.unit-kecil').text($('#form-add-produk #unit-kecil').val());
    };

    updateUnitText();

    $('#form-add-produk #unit-besar, #form-add-produk #unit-kecil').on('change', updateUnitText);

    $('.btn-show-form-biaya').click(function () {
        $('.input-produk-biaya').addClass('d-block');
        $('.input-produk-main').removeClass('d-flex').addClass('d-none');
        $(this).removeClass('d-block').addClass('d-none');
        $('.btn-group-biaya').addClass('d-block');
    });

    $('.btn-back-to-main-input').click(function () {
        $('.input-produk-main').addClass('d-flex');
        $('.input-produk-biaya').removeClass('d-block').addClass('d-none');
        $('.btn-show-form-biaya').addClass('d-block');
        $('.btn-group-biaya').removeClass('d-block').addClass('d-none');
    });

    const handleAjaxError = (error) => {
        let message = "Terjadi kesalahan";
        if (error.responseJSON?.message) {
            message = error.responseJSON.message;
        } else if (error.responseText) {
            message = error.responseText;
        } else if (error.statusText) {
            message = error.statusText;
        }
        showToast(message, 'warning', false);
    };

    $('#form-add-produk').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "{{ route('produk.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) {
                data.success ? showToast(data.message) : Swal.fire({ title: "Hubungi admin", icon: "danger" }).then(() => window.location.reload());
            },
            error: handleAjaxError
        });
    });

    $('#form-update-produk').on('submit', function (e) {
        e.preventDefault();
        const idProduk = $('#id-produk').val();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: `{{ route('produk.update', ':id') }}`.replace(':id', idProduk),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) { showToast(data.message); },
            error: handleAjaxError
        });
    });

    $('#datatable').on('click', '.btn-delete-produk', function () {
        const idProduk = $(this).data('id-produk');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('produk.destroy', ':id') }}`.replace(':id', idProduk),
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (data) {
                        if (data.success) showToast(data.message);
                    },
                    error: () => Swal.fire({ title: 'Produk gagal dihapus', icon: "error" }).then(() => window.location.reload())
                });
            }
        });
    });

    function populateProdukForm(form, produk) {
        form.find('#id-produk').val(produk.id);
        form.find('#kode-produk').val(produk.kode_produk);
        form.find('#nama-produk').val(produk.nama_produk);
        form.find('#harga-jual').val(produk.harga_jual);
        form.find('#harga-beli').val(produk.harga_beli);
        form.find('#biaya-penyimpanan').val(produk.biaya_penyimpanan?.biaya ?? '');
        form.find('#biaya-pemesanan').val(produk.biaya_pemesanan?.biaya ?? '');
        form.find('#deskripsi').val(produk.deskripsi);
        form.find('#tanggal-kadaluarsa').val(produk.exp);
        form.find('#harga_jual_unit_kecil').val(produk.harga_jual_unit_kecil);
        form.find('#tingkat_konversi').val(produk.tingkat_konversi);
        form.find('#unit-kecil').val(produk.unit_kecil);
        form.find('#unit-besar').val(produk.unit_besar);
        form.find('.unit-kecil').text(produk.unit_kecil);
        form.find('.unit-besar').text(produk.unit_besar);

        if (form.find('#gambar-produk').length) {
            form.find('#gambar-produk')
                .val(produk.gambar)
                .attr('src', "{{ asset('storage') }}/" + produk.gambar)
                .attr('alt', produk.nama_produk);
        }
    }

    $('#datatable').on('click', '.btn-update-produk', function () {
        const idProduk = $(this).data('id-produk');
        const formUpdateProduk = $('#form-update-produk');
        $.ajax({
            url: `{{ route('produk.show', '') }}/${idProduk}`,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) {
                populateProdukForm(formUpdateProduk, data.data);
            },
            error: function (error) {
                console.error(error);
                Swal.fire({ title: 'Produk gagal dimuat', icon: "error" }).then(() => window.location.reload());
            }
        });
    });

    $('#datatable').on('click', '.btn-info-produk', function () {
        const idProduk = $(this).data('id-produk');
        const modalInfoProduk = $('#modal-info-produk');
        $.ajax({
            url: `{{ route('produk.show', ':id') }}`.replace(':id', idProduk),
            method: 'GET',
            success: function (data) {
                populateProdukForm(modalInfoProduk, data.data);
            },
            error: function (error) {
                console.error(error);
                Swal.fire({ title: 'Data produk gagal didapatkan', icon: "error" }).then(() => window.location.reload());
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
// Fungsi untuk mulai scan barcode
function startScanner() {
    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.querySelector('#scanner'), // Menampilkan stream kamera di elemen ini
            constraints: {
                facingMode: "environment" // Menggunakan kamera belakang (untuk perangkat mobile)
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "upc_reader"]
        }
    }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start(); // Memulai pemindaian
        });

    // Event listener untuk mendapatkan hasil pemindaian
    Quagga.onDetected(function(result) {
        const barcode = result.codeResult.code;

        document.getElementById('kode-produk').value = barcode; // Menampilkan hasil scan

        $('#modal-scanner').modal('hide');

        $('#modal-add-produk').modal('show');



    });
}

// Mulai scanner
startScanner();
</script>

@endpush
