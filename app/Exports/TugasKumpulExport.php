<?php

namespace App\Exports;

use App\Models\TugasKumpul;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TugasKumpulExport implements FromCollection, WithHeadings
{
    protected $kelas;

    public function __construct($kelas = null)
    {
        $this->kelas = $kelas;
    }

    public function collection()
    {
        $query = TugasKumpul::with(['tugas', 'user']);

        if ($this->kelas) {
            $query->whereHas('tugas', function ($q) {
                $q->where('kelas', $this->kelas);
            });
        }

        return $query->get()->map(function ($item) {
            return [
                'judul_tugas' => $item->tugas->judul,
                'nama_siswa' => $item->user->name,
                'file_tugas' => basename($item->file),
                'nilai' => $item->nilai ?? '-',
                'komentar' => $item->komentar ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Judul Tugas',
            'Nama Siswa',
            'File Tugas',
            'Nilai',
            'Komentar',
        ];
    }
}
