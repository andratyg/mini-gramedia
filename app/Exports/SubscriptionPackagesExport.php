<?php

namespace App\Exports;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// style interfaces
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SubscriptionPackagesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection(): Collection
    {
        return SubscriptionPackage::withCount([
            'books',
            'subscriptionPackageUsers as active_users_count' => function ($query) {
                $query->whereDate('expired_date', '>=', now()->toDateString());
            }
        ])->get();
    }

    public function headings(): array
    {
        return [
            ['Data Paket Langganan dengan Buku dan Pengguna'],
            [
                'Nomor',
                'Nama Paket Langganan',
                'Harga',
                'Jumlah Buku',
                'Pelanggan Aktif',
            ],
        ];
    }

    private $no = 0;

    public function map($package): array
    {
        return [
            ++$this->no,
            $package->name,
            'Rp ' . number_format($package->price, 0, ',', '.'),
            $package->books_count,
            $package->active_users_count > 0 ? $package->active_users_count : '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Merge cell judul pada baris 1 (A1:E1) dan posisikan di tengah
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $totalRows = $this->collection()->count() + 2;

        return [
            // Judul (Baris 1) ukuran font seperti tag h2 (18pt) dan bold
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 18,
                ],
            ],

            // Header kolom (Baris 2) tebal dengan warna abu-abu
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],

            // Border pada seluruh rentang sel tabel A2 sampai E{totalRows}
            "A2:E{$totalRows}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
