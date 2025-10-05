<?php

namespace App\Livewire\Forms;

use App\Models\Produk;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProdukForm extends Form
{
    public ?Produk $produk = null;

    public string $nama_produk = '';
    public string $kode_produk = '';
    public string $harga_beli = '';
    public string $harga_jual = '';
    public int $lead_time = 0;
    public string $deskripsi = '';
    public ?string $gambar = null;
    public ?string $exp = null;
    public string $harga_jual_unit_kecil = '';
    public int $tingkat_konversi = 0;
    public string $unit_kecil = '';
    public string $unit_besar = '';

    protected function rules(): array
    {
        return [
            'nama_produk' => 'required|string|max:100',
            'kode_produk' => [
                'required',
                'string',
                'max:20',
                Rule::unique('produk', 'kode_produk')->ignore($this->produk),
            ],
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'lead_time' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string',
            'exp' => 'nullable|date',
            'harga_jual_unit_kecil' => 'required|numeric|min:0',
            'tingkat_konversi' => 'required|integer|min:0',
            'unit_kecil' => 'required|string',
            'unit_besar' => 'required|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.max' => 'Nama produk maksimal 100 karakter.',

            'kode_produk.required' => 'Kode produk wajib diisi.',
            'kode_produk.max' => 'Kode produk maksimal 20 karakter.',
            'kode_produk.unique' => 'Kode produk sudah terdaftar.',

            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',

            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',

            'harga_jual_unit_kecil.required' => 'Harga jual unit kecil wajib diisi.',
            'harga_jual_unit_kecil.numeric' => 'Harga jual unit kecil harus berupa angka.',

            'tingkat_konversi.required' => 'Tingkat konversi wajib diisi.',
            'tingkat_konversi.integer' => 'Tingkat konversi harus berupa angka.',

            'unit_kecil.required' => 'Unit kecil wajib dipilih.',
            'unit_besar.required' => 'Unit besar wajib dipilih.',
        ];
    }

    public function store()
    {
        Produk::create($this->validate());
        $this->reset();
    }

    public function update()
    {
        $this->produk->update($this->validate());
        $this->reset();
    }

    public function fillFromModel(Produk $produk) {

        $this->produk = $produk;


        $this->nama_produk = $produk->nama_produk;
        $this->kode_produk = $produk->kode_produk;
        $this->harga_beli = $produk->harga_beli;
        $this->harga_jual = $produk->harga_jual;
        $this->lead_time = $produk->lead_time;
        $this->deskripsi = $produk->deskripsi;
        $this->gambar = $produk->gambar;
        $this->exp = $produk->exp;
        $this->harga_jual_unit_kecil = $produk->harga_jual_unit_kecil;
        $this->tingkat_konversi = $produk->tingkat_konversi;
        $this->unit_kecil = $produk->unit_kecil;
        $this->unit_besar = $produk->unit_besar;

    }

    public function delete()
    {
        $this->produk->delete();
        $this->reset();
    }
}
