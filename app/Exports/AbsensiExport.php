<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return Absensi::with(['karyawan.jabatan'])
            ->when($this->request->filled('tanggal_dari'), function ($query) {
                $query->whereDate('tanggal', '>=', $this->request->tanggal_dari);
            })
            ->when($this->request->filled('tanggal_sampai'), function ($query) {
                $query->whereDate('tanggal', '<=', $this->request->tanggal_sampai);
            })
            ->when($this->request->filled('karyawan_id'), function ($query) {
                $query->where('karyawan_id', $this->request->karyawan_id);
            })
            ->when($this->request->filled('status'), function ($query) {
                $query->where('status', $this->request->status);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Karyawan',
            'Jabatan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
        ];
    }

    public function map($item): array
    {
        static $no = 1;

        return [
            $no++,
            $item->karyawan->nik ?? '-',
            $item->karyawan->nama ?? '-',
            optional($item->karyawan->jabatan)->nama_jabatan ?? '-',
            $item->tanggal->format('d-m-Y'),
            $item->jam_masuk ?? '-',
            $item->jam_keluar ?? '-',
            ucfirst($item->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4F81BD']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4F81BD']],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);

                $sheet->getStyle('A1:H' . $sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFB0B0B0'],
                        ],
                    ],
                ]);

                $sheet->freezePane('A2');
                $sheet->getStyle('A1:H1')->getFont()->setSize(12);
                $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('E:H')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
