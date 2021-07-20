<?php

namespace App\Exports\Laporan;

use App\Models\Department;
use App\Models\InvoicingInfo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicingDetailPerDepartmentExport implements FromCollection, WithTitle, WithHeadings, WithMapping, WithColumnFormatting, WithColumnWidths, WithStyles
{
    protected $department_id;
    protected $start_date;
    protected $end_date;

    public function __construct(int $department_id = null, string $start_date = null, string $end_date = null)
    {
        $this->department_id = $department_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return InvoicingInfo::getLaporanDetailPerDepartment($this->department_id,$this->start_date,$this->end_date)->get();
    }

    public function title(): string
    {
        return Department::find($this->department_id)->name;
    }

    public function prepareRows($rows)
    {
        $total = new \stdClass();
        $total->invoice_number_sr = 'TOTAL';
        $total->invoice_number_invoicing = null;
        $total->department_name = null;
        $total->info_penggunaan = null;
        $total->catatan = null;
        $total->total_price = null;
        $total->total_price = 0;
        $total->penginput = null;
        $total->completed_at = null;

        foreach ($rows AS $item) {
            $total->total_price += $item->total_price;
        }

        array_push($rows,$total);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'NO SR',
            'NO INVOICING',
            'DEPARTEMEN',
            'INFO PENGGUNAAN',
            'CATATAN',
            'PENGELUARAN',
            'PENGINPUT',
            'TANGGAL SELESAI'
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
            $row->invoice_number_sr,
            $row->invoice_number_invoicing,
            $row->department_name,
            $row->info_penggunaan,
            $row->catatan,
            $row->total_price,
            $row->penginput,
            Date::stringToExcel($row->completed_at),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)',
            'H' => 'dd/mm/yyyy hh:mm:ss'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 35,
            'E' => 35,
            'F' => 25,
            'G' => 15,
            'H' => 20
        ];
    }
}
