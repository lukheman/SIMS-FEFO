<?php

namespace App\Traits;

trait WithConfirmation
{
    public function deleteConfirmation(string $message)
    {

        $this->dispatch('deleteConfirmation', message: $message);

    }
}
