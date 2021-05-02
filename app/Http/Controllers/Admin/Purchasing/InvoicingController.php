<?php

namespace App\Http\Controllers\Admin\Purchasing;

use App\Http\Controllers\Controller;
use App\Services\Admin\Purchasing\InvoicingService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InvoicingController extends Controller
{
    private $service;

    public function __construct(InvoicingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.purchasing.invoicing.index');
    }

    public function data(Request $request)
    {
        try {
            $f_status_selesai = $request->get('f_status_selesai');

            return DataTables::of($this->service->getInvoicingInfo($f_status_selesai))
                ->addColumn('action', function ($data) {
                    return view('admin.purchasing.invoicing.action',[
                        'data' => $data
                    ]);
                })
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function indexInvoicingProcess($id)
    {
        return view('admin.purchasing.invoicing.process',$this->service->indexInvoicingProcessData($id));
    }

    public function getProducts(Request $request)
    {
        $request->validate([
            'store_requisition_info_id' => 'required'
        ]);

        $store_requisition_info_id = $request->get('store_requisition_info_id');

        return $this->service->getInvoicingProducts($store_requisition_info_id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'invoicing_info_id' => 'required',
            'store_requisition_info_id' => 'required',
        ]);

        $product = $request->get('product');
        $invoicing_info_id = $request->get('invoicing_info_id');
        $store_requisition_info_id = $request->get('store_requisition_info_id');

        return $this->service->store($product,$invoicing_info_id,$store_requisition_info_id);
    }

    public function indexDetail($id)
    {
        return view('admin.purchasing.invoicing.info',$this->service->indexDetailData($id));
    }
}
