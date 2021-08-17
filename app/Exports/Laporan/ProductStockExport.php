<?php

namespace App\Exports\Laporan;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductStockExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithColumnFormatting
{
    use Exportable;

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Product::query()
            ->select([
                'products.code',
                'products.name',
                'products.stock',
                'satuans.nama AS satuan',
                'products.supplier_price',
                'products.last_price',
                'products.avg_price',
                'products.created_at',
                'products.updated_at'
            ])
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            [
                'Kode',
                'Nama',
                'Stok',
                'Satuan',
                'Harga',
                '',
                '',
                'Tgl Input',
                'Update Terakhir'
            ],
            [
                '',
                '',
                '',
                '',
                'Supplier',
                'Terakhir',
                'Rata-rata'
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            $row->stock,
            $row->satuan,
            $row->supplier_price,
            $row->last_price,
            $row->avg_price,
            Date::stringToExcel($row->created_at),
            Date::stringToExcel($row->updated_at)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:G1');
        $sheet->mergeCells('H1:H2');
        $sheet->mergeCells('I1:I2');

        $header = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];

        return [
            1 => $header,
            2 => $header,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 40,
            'C' => 10,
            'D' => 15,
            'E' => 18,
            'F' => 18,
            'G' => 18,
            'H' => 15,
            'I' => 15,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => 'dd/mm/yy, hh:mm',
            'I' => 'dd/mm/yy, hh:mm',
        ];
    }
}
