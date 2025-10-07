<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        // Validate input based on role
        $validated = $request->validate([
            'role' => ['required', 'in:reseller,kurir'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique($request->role === 'reseller' ? 'reseller' : 'users', 'email')],
            'phone' => [$request->role === 'reseller' ? 'nullable' : 'required', 'string', 'max:15', Rule::unique('users', 'phone')->when($request->role !== 'reseller', fn ($query) => $query)],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        // Determine model and table based on role
        $model = $request->role === 'reseller' ? \App\Models\Reseller::class : \App\Models\User::class;

        // Create user or reseller
        $model::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'phone' => $request->role === 'reseller' ? null : $validated['phone'],
        ]);

        flash('Registrasi berhasil, silakan login.', 'success');

        return to_route('login');
    }

    public function registrasi()
    {
        return view('registrasi');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function showLoginForm()
    {

        if (Auth::check()) {
            return redirect()->route(getActiveUser()->role->value.'.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login sebagai User (guard: web)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            return match ($user->role) {
                Role::ADMINGUDANG => redirect()->route('admingudang.index'),
                Role::KASIR => redirect()->route('kasir.index'),
                Role::PIMPINAN => redirect()->route('pimpinan.index'),
                Role::KURIR => redirect()->route('kurir.index'),
                default => redirect()->route('login'),
            };
        }

        // Coba login sebagai Reseller (guard: reseller)
        if (Auth::guard('reseller')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = getActiveUser();

            if ($user->role === Role::RESELLER) {
                return redirect()->route('reseller.index');
            }
        }

        flash('Email atau password salah', 'danger');

        return back();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('reseller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
