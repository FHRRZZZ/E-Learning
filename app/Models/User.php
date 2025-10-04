<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // pastikan ini ada jika kamu pakai role
        'nisn',
        'kelas',
        'img',

    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Generate custom presensi ID:
     * Format: tahun sekarang + id user (2 digit)
     * Contoh: 202501 untuk user dengan id 1 di tahun 2025
     */
    public function presensiId()
    {
        // Jika nisn ada, gunakan nisn, kalau tidak fallback ke id
        if (!empty($this->nisn)) {
            return $this->nisn;
        }
        $year = date('Y');
        return $year . str_pad($this->id, 2, '0', STR_PAD_LEFT);
    }

    // app/Models/User.php

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }


}
