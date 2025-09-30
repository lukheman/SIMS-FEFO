<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {

        return view('profile', [
            'page' => 'Profile',
            'user' => Auth::guard('reseller')->user() ?? Auth::guard('web')->user(),
        ]);

    }

    public function update(Request $request, $id)
    {
        // Determine the guard and model based on authenticated user
        $guard = Auth::guard('reseller')->check() ? 'reseller' : 'web';
        $model = $guard === 'reseller' ? Reseller::class : User::class;

        // Validate input based on the model
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:100', Rule::unique($guard === 'reseller' ? 'reseller' : 'users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
            'name' => ['required', 'string', 'max:100'],
            'phone' => [$guard === 'reseller' ? 'nullable' : 'required', 'string', 'max:15', Rule::unique($guard === 'reseller' ? 'reseller' : 'users', 'phone')->ignore($id)],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Find the user or reseller
        $user = $model::find($id);

        if (! $user) {
            return back()->with([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ]);
        }

        // Update basic fields
        $user->email = $validated['email'];
        $user->name = $validated['name'];

        $user->phone = $validated['phone'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $user->foto = $request->file('foto')->store('foto', 'public');
        }

        // Update password if provided
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        flash('Profil berhasil diperbarui.', 'success');

        return redirect()->route('profile.index');
    }
}
