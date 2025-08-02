<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;

    public function __construct($province = null, $start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $query = Report::query();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Email Pelapor',
            'Dilaporkan Pada Tanggal',
            'Deskripsi Pengaduan',
            'URL Gambar',
            'Lokasi (Desa, Kecamatan, Kabupaten, Provinsi)',
            'Jumlah Voting',
            'Status Pengaduan',
            'Proses Tanggapan',
            'Staff Terkait',
        ];
    }

    public function map($report): array
    {
        $response = $report->responses()->first(); // Ambil tanggapan pertama jika ada.

        return [
            $report->user->email,
            $report->created_at->translatedFormat('j F Y'),
            $report->description,
            $report->image,
            "{$report->village}, {$report->subdistrict}, {$report->regency}, {$report->province}",
            $report->voting,
            $report->responses() ? 'DONE' : 'PENDING',
            $response ? $response->response_status : '', // Status tanggapan
            $response && $response->staff ? $response->staff->name : '', // Nama staf jika ada
        ];
    }
}
