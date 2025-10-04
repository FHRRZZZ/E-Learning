<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $presensis = Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('user.presensi.index', compact('presensis'));
    }
}
