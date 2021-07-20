<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Exports\Laporan\InvoicingPerDepartmentExport;
use App\Helpers\StylePhpSpreadsheetHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\InvoicingInfo;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\DataTables;

class InvoicingPerDepartmentController extends Controller
{
    public function index()
    {
        $department = Department::all();
        return view('admin.laporan.invoicing-per-department',[
            'department' => $department
        ]);
    }

    public function datatable(Request $request)
    {
        try {
            $department_id = $request->get('department_id');
            $start_date = $request->has('start_date') ? $request->get('start_date') : null;
            $end_date = $request->has('end_date') ? $request->get('end_date').' 23:59:59' : null;

            return DataTables::of(InvoicingInfo::getLaporanDetailPerDepartment($department_id,$start_date,$end_date))
                ->editColumn('completed_at',function ($data) {
                    return date('d-m-Y, H:i:s',strtotime($data->completed_at));
                })
                ->toJson();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function exportExcel(Request $request)
    {
        $start_date = $request->has('start_date') ? $request->get('start_date') : null;
        $end_date = $request->has('end_date') ? $request->get('end_date').' 23:59:59' : null;

//        $data = InvoicingInfo::getLaporanDetailPerDepartment(null,$start_date,$end_date)->get();
//        $summary = InvoicingInfo::getLaporanSummaryPerDepartment($start_date,$end_date)->get();

        $file_name = 'laporan_invoicing_'.date('Ymd',strtotime($start_date)).'-'.date('Ymd',strtotime($end_date));

        return (new InvoicingPerDepartmentExport(null,$start_date,$end_date))->download("{$file_name}.xlsx");

//        try {
//            $spreadsheet = new Spreadsheet();
//
//            /** START SUMMARY */
//            $sheet = $spreadsheet->getActiveSheet()->setTitle('Summary');
//
//            $sheet->getColumnDimension('A')->setWidth(20);
//            $sheet->getColumnDimension('B')->setWidth(25);
//            // START HEADER
//            $sheet->getStyle("B:B")->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
//            $head = [
//                'DEPARTEMEN',
//                'TOTAL PENGELUARAN',
//            ];
//            $sheet->fromArray($head,'','A1');
//            // END HEADER
//            // START DATA
//            $startRow = 1;
//            $sumPrice = 0;
//            foreach ($summary AS $item) {
//                $startRow++;
//                $sumPrice += $item->total_price;
//                $sheet->fromArray([
//                    $item->department_name,
//                    $item->total_price
//                ],'',"A{$startRow}");
//            }
//            // END DATA
//            $startRow++;
//            $sheet->fromArray([
//                'TOTAL',
//                $sumPrice
//            ],'',"A{$startRow}");
//            $sheet->getStyle("A1:B1")->applyFromArray(StylePhpSpreadsheetHelper::tHead());
//            $sheet->getStyle("A2:B{$startRow}")->applyFromArray(StylePhpSpreadsheetHelper::tBody());
//            $sheet->getStyle("A{$startRow}:B{$startRow}")->applyFromArray(StylePhpSpreadsheetHelper::bold());
//            /** END SUMMARY */
//
//            /** START Per Department */
//            foreach ($summary AS $item) {
//                $startRow = 1;
//                $sumPrice = 0;
//                $sheet = $spreadsheet->createSheet()->setTitle($item->department_name);
//
//                $sheet->getStyle("F:F")->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
//                $sheet->getStyle("H")->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm:ss');
//
//                $sheet->getColumnDimension('A')->setWidth(20);
//                $sheet->getColumnDimension('B')->setWidth(20);
//                $sheet->getColumnDimension('C')->setWidth(20);
//                $sheet->getColumnDimension('D')->setWidth(35);
//                $sheet->getColumnDimension('E')->setWidth(35);
//                $sheet->getColumnDimension('F')->setWidth(25);
//                $sheet->getColumnDimension('G')->setWidth(15);
//                $sheet->getColumnDimension('H')->setWidth(20);
//
//                // HEADER
//                $sheet->fromArray([
//                    'NO SR',
//                    'NO INVOICING',
//                    'DEPARTEMEN',
//                    'INFO PENGGUNAAN',
//                    'CATATAN',
//                    'PENGELUARAN',
//                    'PENGINPUT',
//                    'TANGGAL SELESAI',
//                ],'','A1');
//
//                // DATA
//                foreach ($data AS $itemData) {
//                    if ($itemData->department_name == $item->department_name) {
//                        $startRow++;
//                        $sumPrice += $item->total_price;
//                        $sheet->fromArray([
//                            $itemData->invoice_number_sr,
//                            $itemData->invoice_number_invoicing,
//                            $itemData->department_name,
//                            $itemData->info_penggunaan,
//                            $itemData->catatan,
//                            $itemData->total_price,
//                            $itemData->penginput,
//                            Date::stringToExcel($itemData->completed_at),
//                        ],'',"A{$startRow}");
//                    }
//                }
//                $startRow++;
//                $sheet->setCellValue("A{$startRow}",'TOTAL');
//                $sheet->setCellValue("E{$startRow}",$sumPrice);
//                $sheet->mergeCells("A{$startRow}:D{$startRow}");
//                $sheet->mergeCells("F{$startRow}:H{$startRow}");
//                $sheet->getStyle("A1:H1")->applyFromArray(StylePhpSpreadsheetHelper::tHead());
//                $sheet->getStyle("A2:H{$startRow}")->applyFromArray(StylePhpSpreadsheetHelper::tBody());
//                $sheet->getStyle("A{$startRow}:H{$startRow}")->applyFromArray(StylePhpSpreadsheetHelper::bold());
//            }
//
//            $writer = new Xlsx($spreadsheet);
//            $response =  new StreamedResponse(
//                function () use ($writer) {
//                    $writer->save('php://output');
//                }
//            );
//
//            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
//            $response->headers->set('Content-Disposition', 'attachment;filename="invoicing_per_department_'.date('Ymd',strtotime($start_date)).'-'.date('Ymd',strtotime($end_date)).'.xlsx"');
//            $response->headers->set('Cache-Control','max-age=0');
//            return $response;
//        } catch (\Throwable $th) {
//            dd($th);
//        }
    }
}
