<?php

namespace App\Livewire\Table;

use App\Models\Produk;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PersediaanTable extends Component
{

    #[Computed]
    public function produkList() {
        return Produk::query()
            ->with('persediaan')
            ->paginate(10);
    }


    public function render()
    {
        return view('livewire.table.persediaan-table');
    }
}
