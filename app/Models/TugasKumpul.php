<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasKumpul extends Model
{
    use HasFactory;

    protected $table = 'tugas_kumpuls'; // Nama tabel di database

    protected $fillable = [
        'tugas_id',
        'user_id',
        'file',
        'nilai',
        'komentar',
    ];

    // Relasi ke model Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
