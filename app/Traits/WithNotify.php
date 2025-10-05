<?php

namespace App\Traits;

trait WithNotify
{
    public function notifySuccess(string $message, bool $reload = false)
    {
        $this->dispatch('toast', variant: 'success', message: $message, reload: $reload);
    }

    public function notifyError(string $message)
    {
        $this->dispatch('toast', variant: 'error', message: $message);
    }

    public function notifyWarning(string $message)
    {
        $this->dispatch('toast', variant: 'warning', message: $message);
    }

    public function notifyInfo(string $message)
    {
        $this->dispatch('toast', variant: 'info', message: $message);
    }
}
