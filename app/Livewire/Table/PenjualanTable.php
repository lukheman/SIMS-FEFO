<?php

namespace App\Livewire\Table;

use App\Models\Mutasi;
use App\Traits\WithConfirmation;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Penjualan')]
class PenjualanTable extends Component
{
    use WithPagination;
    use WithConfirmation;
    use WithNotify;

    public ?Mutasi $penjualan;

    public function deletePenjualan($id): void
    {
        abort(403, 'Aksi ini sudah tidak didukung.');
    }

    #[Computed]
    public function penjualanList() {
        return \App\Models\Pesanan::query()
            ->with(['produk', 'transaksi'])
            ->whereHas('transaksi', function ($q) {
                $q->whereNotIn('status', ['pending', 'dibatalkan']);
            })
            // Join to sort by transaksi tanggal could be complex, we just sort by created_at of pesanan since they are created alongside transaksi
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.table.penjualan-table');
    }
}
