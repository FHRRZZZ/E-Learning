<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Presensi;

class ResetAbsensi extends Command
{
    /**
     * Nama command yang dipanggil via artisan
     *
     * @var string
     */
    protected $signature = 'absensi:reset';

    /**
     * Deskripsi command
     *
     * @var string
     */
    protected $description = 'Reset status absensi harian setiap lewat jam 23:59';

    /**
     * Jalankan command
     */
    public function handle()
    {
        // Hapus data absensi sebelum hari ini
        $deleted = Presensi::whereDate('tanggal', '<', now()->toDateString())->delete();

        $this->info("Absensi lama berhasil dihapus: {$deleted} data dihapus.");
    }
}
