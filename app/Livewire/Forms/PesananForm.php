<?php

namespace App\Livewire\Forms;

use App\Models\Pesanan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PesananForm extends Form
{
    public ?Pesanan $pesanan = null;

    public ?int $jumlah;
    public $satuan;
    public bool $showMentokWarning = false;

    public function rules()
    {
        return [
            'jumlah' => ['required', 'integer', 'min:1'],
            'satuan' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'jumlah.required' => 'Jumlah pesanan harus diisi.',
            'jumlah.integer' => 'Jumlah pesanan harus berupa angka.',
            'jumlah.min' => 'Jumlah pesanan minimal 1.',
            
            'satuan.required' => 'Satuan pesanan harus ditentukan.',
            'satuan.boolean' => 'Format satuan tidak valid.',
        ];
    }

    public function update()
    {
        if (!$this->isStokCukup()) {
            return false;
        }
        $this->pesanan->update($this->validate());
        $this->reset();
        return true;
    }

    public function delete()
    {
        $this->pesanan->delete();
        $this->reset();
    }

    public function fillFromModel(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
        $this->pesanan->load('produk');

        $this->jumlah = $pesanan->jumlah;
        $this->satuan = $pesanan->satuan;
    }

    public function isStokCukup($tambahan = 0)
    {
        if (!isset($this->pesanan) || !$this->pesanan->produk) return false;
        
        $multiplier = $this->satuan ? 1 : $this->pesanan->produk->tingkat_konversi;
        $totalPcsRequested = ($this->jumlah + $tambahan) * $multiplier;
        
        return $this->pesanan->produk->totalPersediaan() >= $totalPcsRequested;
    }

    public function tambahJumlahPesanan()
    {
        if ($this->isStokCukup(1)) {
            $this->jumlah++;
            $this->showMentokWarning = false;
            return true;
        }
        $this->showMentokWarning = true;
        return false;
    }

    public function kurangiJumlahPesanan()
    {
        $this->showMentokWarning = false;
        if ($this->jumlah > 1) {
            $this->jumlah--;
            if (!$this->isStokCukup()) {
                $multiplier = $this->satuan ? 1 : $this->pesanan->produk->tingkat_konversi;
                $this->jumlah = floor($this->pesanan->produk->totalPersediaan() / $multiplier);
            }
        }
    }
}
