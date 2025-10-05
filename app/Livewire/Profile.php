<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\ProfileForm;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public ?ProfileForm $form;

    public function mount() {
        $user = getActiveUser();
        $this->form->fillFromUser($user);
    }

    public function edit()
    {
        if ($this->form->update()) {

            $this->dispatch('toast', message: 'Berhasil menyimpan perubahan profile');
        }

    }

    public function render()
    {
        return view('livewire.profile');
    }
}
