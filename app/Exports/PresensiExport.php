<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PresensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelas;
    protected $tanggal;

    public function __construct($kelas = null, $tanggal = null)
    {
        $this->kelas = $kelas;
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        $query = Presensi::with('user')->orderBy('tanggal', 'desc');

        if ($this->kelas) {
            $query->whereHas('user', function ($q) {
                $q->where('kelas', $this->kelas);
            });
        }

        if ($this->tanggal) {
            $query->whereDate('tanggal', $this->tanggal);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kelas',
            'Tanggal',
            'Jam',
            'Status',
            'Keterangan'
        ];
    }

    public function map($presensi): array
    {
        return [
            $presensi->user->name ?? '-',
            $presensi->user->kelas ?? '-',
            $presensi->tanggal->format('d-m-Y'),
            $presensi->jam,
            ucfirst($presensi->aksi),
            $presensi->keterangan ?? '-',
        ];
    }
}
