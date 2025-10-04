<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PresensiController extends Controller
{
    public function index()
    {
        $users = User::all();

        // Kita generate URL QR code untuk tiap user berdasarkan presensiId()
        foreach ($users as $user) {
            $presensiId = $user->presensiId();
            // Contoh API QR code generator yang gratis dan mudah: api.qrserver.com
            $user->qrcode_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$presensiId}";
        }

        return view('presensi.index', compact('users'));
    }
}
