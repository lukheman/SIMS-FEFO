<?php

namespace App\Traits;

trait WithModal
{
    public function openModal(string $id)
    {
        $this->dispatch('openModal', id: $id);
    }

    public function closeModal(string $id)
    {
        $this->dispatch('closeModal', id: $id);
    }
}
