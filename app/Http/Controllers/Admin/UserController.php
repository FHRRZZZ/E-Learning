<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $kelasOptions = ['1', '2', '3', '4', '5', '6'];

        $transformed = $users->getCollection()->transform(function ($user) {
            $user->presensi_id = $user->id;
            $user->qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={$user->presensi_id}";
            return $user;
        });

        $users->setCollection($transformed);

        return view('admin.users.index', compact('users', 'kelasOptions'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        Log::debug('Admin User store payload', $request->only(['name','email','nisn','kelas','role']));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,user',
            'password' => 'required|string|min:8|confirmed',
            'nisn' => 'nullable|string|unique:users,nisn',
            'kelas' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
            'nisn' => 'nullable|string|unique:users,nisn,' . $user->id,
            'kelas' => 'nullable|string|max:50',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->nisn = $request->nisn;
        $user->kelas = $request->kelas;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
