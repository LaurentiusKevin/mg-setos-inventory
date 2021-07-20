<?php

namespace App\Exports\Laporan;

use App\Models\InvoicingInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicingSummaryPerDepartmentExport implements FromCollection, WithTitle, WithHeadings, WithMapping, WithColumnFormatting, WithColumnWidths, WithStyles, WithEvents
{
    use RegistersEventListeners;

    protected $start_date;
    protected $end_date;

    public function __construct(string $start_date = null, string $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return InvoicingInfo::getLaporanSummaryPerDepartment($this->start_date,$this->end_date)->get();
    }

    public function prepareRows($rows)
    {
        $total = new \stdClass();
        $total->department_name = 'TOTAL';
        $total->total_price = 0;

        foreach ($rows AS $item) {
            $total->total_price += $item->total_price;
        }

        array_push($rows,$total);

        return $rows;
    }

    public function title(): string
    {
        return 'TOTAL PENGELUARAN';
    }

    public function headings(): array
    {
        return [
            'DEPARTEMEN',
            'TOTAL PENGELUARAN'
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function map($row): array
    {
        return [
            $row->department_name,
            $row->total_price
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
        ];
    }
}
