<div>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('reseller.keranjang') }}" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="mb-0 fw-bold">Checkout Keranjang</h3>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Detail Pengiriman dan Pesanan -->
        <div class="col-12 col-lg-8">
            
            <!-- Section Alamat -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Alamat Pengiriman</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fw-semibold">{{ $user->name }} | {{ $user->phone ?? 'Belum ada nomor HP' }}</p>
                            <p class="mb-0 text-muted">{{ $user->alamat ?? 'Alamat belum diatur. Silakan perbarui profil Anda.' }}</p>
                        </div>
                        <a href="{{ route('profile') }}" class="btn btn-secondary rounded-pill px-3">Ubah</a>
                    </div>
                </div>
            </div>

            <!-- Section Produk -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i> Detail Produk</h5>
                </div>
                <div class="card-body px-4">
                    
                    @foreach($pesananList as $pesanan)
                        @php
                            $harga = $pesanan->satuan == 1 ? $pesanan->produk->harga_jual_unit_kecil : $pesanan->produk->harga_jual;
                            $namaSatuan = $pesanan->satuan == 1 ? $pesanan->produk->unit_kecil : $pesanan->produk->unit_besar;
                        @endphp
                        <div class="d-flex p-3 bg-light rounded-3 mb-3">
                            <div class="me-3 bg-white rounded border d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; overflow: hidden;">
                                @if($pesanan->produk->gambar)
                                    <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}" alt="{{ $pesanan->produk->nama_produk }}" class="img-fluid" style="object-fit: contain;">
                                @else
                                    <i class="bi bi-image text-muted fs-3"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                <h6 class="fw-bold mb-1">{{ $pesanan->produk->nama_produk }}</h6>
                                <p class="text-muted small mb-2">Satuan: <strong>{{ $namaSatuan }}</strong></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Jumlah: {{ $pesanan->jumlah }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Pesan untuk Penjual (Opsional)</label>
                            <input wire:model="pesanUntukPenjual" type="text" class="form-control" placeholder="Tinggalkan catatan untuk pesanan ini...">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Ringkasan Pembayaran -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i> Ringkasan Belanja</h5>
                </div>
                <div class="card-body px-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Metode Pembayaran</label>
                        <select wire:model="metode_pembayaran" class="form-select border-primary shadow-sm">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cod">Cash on Delivery (COD)</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal Produk</span>
                        <span class="fw-semibold">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>

                    <hr class="text-muted">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Total Tagihan</span>
                        <span class="fs-4 fw-bold text-primary">Rp {{ number_format($this->totalPembayaran, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button wire:click="buatPesanan" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Buat Pesanan Sekarang
                        </button>
                        <a href="{{ route('reseller.keranjang') }}" class="btn btn-light rounded-pill py-2 text-muted fw-semibold">
                            Batal
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
