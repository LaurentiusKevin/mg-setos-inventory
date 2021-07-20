<?php

namespace App\Exports\Laporan;

use App\Models\InvoicingInfo;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InvoicingPerDepartmentExport implements WithMultipleSheets
{
    use Exportable;

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
    * @return array
     */
    public function sheets(): array
    {
        $department = InvoicingInfo::getLaporanSummaryPerDepartment($this->start_date,$this->end_date)->orderBy('d.name')->get();

        $sheet[] = new InvoicingSummaryPerDepartmentExport($this->start_date,$this->end_date);
        foreach ($department AS $item) {
            $sheet[] = new InvoicingDetailPerDepartmentExport($item->department_id,$this->start_date,$this->end_date);
        }

        return $sheet;
    }
}
