<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Form;

class ProfileForm extends Form
{
    public $user = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?string $phone = null;
    public ?string $alamat = null;
    public $foto;

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'password' => ['nullable', 'min:4'],
            'phone' => ['nullable', 'max:15'],
            'alamat' => ['nullable', 'max:255'],
            'foto' => ['nullable', 'image'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal :max karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal :max karakter.',

            'password.min' => 'Password minimal :min karakter.',

            'phone.max' => 'Nomor telepon maksimal :max digit.',

            'alamat.max' => 'Alamat maksimal :max karakter.',

            'foto.image' => 'File foto harus berupa gambar (jpg, png, dll).',
        ];
    }

    public function update()
    {
        $validated = $this->validate();
        $user = getActiveUser();

        $updates = [];

        if ($this->name !== $user->name) {
            $updates['name'] = $this->name;
        }

        // Email uniqueness check (manual, kecuali dirinya sendiri)
        if ($this->email !== $user->email) {
            $emailExists = getActiveUser()->where('email', $this->email)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($emailExists) {
                $this->addError('email', 'Email sudah terdaftar, silakan gunakan email lain.');
                return false;
            }

            $updates['email'] = $this->email;
        }

        if (! empty($this->password)) {
            $updates['password'] = Hash::make($this->password);
        }

        if ($this->phone !== $user->phone) {
            // pastikan phone unik
            if ($this->phone && getActiveUser()->where('phone', $this->phone)->where('id', '!=', $user->id)->exists()) {
                $this->addError('phone', 'Nomor telepon sudah digunakan.');
                return false;
            }
            $updates['phone'] = $this->phone;
        }

        if ($this->alamat !== $user->alamat) {
            $updates['alamat'] = $this->alamat;
        }

        if ($this->foto) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $this->foto->store('photos', 'public');
            $updates['foto'] = $path;
        }

        if (! empty($updates)) {
            $user->update($updates);
            return true;
        }

        return true;
    }

    public function fillFromUser($user)
    {
        $this->user = $user;

        $this->name   = $user->name ?? '';
        $this->email  = $user->email ?? '';
        $this->phone  = $user->phone ?? null;
        $this->alamat = $user->alamat ?? null;
        // foto tidak perlu diisi dengan file, cukup path string untuk ditampilkan preview
        $this->foto   = $user->foto ?? null;
    }
}
