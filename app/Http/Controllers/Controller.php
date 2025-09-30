<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    public function index()
    {
        return view(Auth::user()->role.'.'.'index', ['page' => 'Dashboard']);
    }
}
