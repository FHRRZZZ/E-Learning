<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class UserPresensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Presensi::where('user_id', $user->id)->orderBy('tanggal', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $presensis = $query->paginate(10);

        return view('user.presensi.index', compact('presensis'));
    }
}
