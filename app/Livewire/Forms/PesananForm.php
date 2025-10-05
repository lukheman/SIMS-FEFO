<?php

namespace App\Livewire\Forms;

use App\Models\Pesanan;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PesananForm extends Form
{
    public ?Pesanan $pesanan;

    public ?int $jumlah;
    public $satuan;

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
        $this->pesanan->update($this->validate());
        $this->reset();
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

    public function tambahJumlahPesanan()
    {
        $this->jumlah++;
    }

    public function kurangiJumlahPesanan()
    {
        if ($this->jumlah > 1) {
            $this->jumlah--;
        }
    }
}
