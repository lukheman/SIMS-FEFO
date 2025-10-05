<?php

use App\Enums\Role;
use Illuminate\Support\Facades\Auth;

if (! function_exists('getActiveGuard')) {
    function getActiveGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
}

if (! function_exists('getActiveUser')) {
    function getActiveUser()
    {
        $guard = getActiveGuard();

        $user = Auth::guard($guard)->user();

        if($user) {
            return $user;
        }


        return null;
    }
}

if (! function_exists('getActiveUserId')) {
    function getActiveUserId()
    {
        $guard = getActiveGuard();
        $user = getActiveUser();

        if($guard === 'pengguna') {
            return $user->id;
        } elseif($guard === 'siswa') {
            return $user->id_siswa;
        }

        return null;
    }
}
