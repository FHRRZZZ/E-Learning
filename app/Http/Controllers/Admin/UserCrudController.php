<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserCrudController extends Controller
{
    // Tampilkan daftar user dengan paginasi dan data QR code
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        $transformed = $users->getCollection()->transform(function ($user) {
            $user->presensi_id = $user->presensiId();
            $user->qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={$user->presensi_id}";
            return $user;
        });

        $users->setCollection($transformed);

        return view('admin.users.index', compact('users'));
    }

    // Tampilkan form edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Proses update data user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'nisn' => 'nullable|string|unique:users,nisn,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    // (Optional) Method untuk create, store, destroy bisa ditambahkan sesuai kebutuhan
}
