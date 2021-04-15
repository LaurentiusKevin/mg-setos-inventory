<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Admin\Stock\ProductListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ProductListController extends Controller
{
    private $service;

    public function __construct(ProductListService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.stock.product-list.index');
    }

    public function data()
    {
        try {
            return DataTables::of($this->service->data())
                ->addColumn('action', function ($data) {
                    return view('admin.master-data.satuan-product.action');
                })
                ->editColumn('image', '{{ url("admin/stock/product-list/api/get-image/".encrypt($image)) }}')
                ->editColumn('stock', '{{ number_format($stock) }}')
                ->editColumn('supplier_price', 'Rp {{ number_format($supplier_price) }}')
                ->editColumn('last_price', 'Rp {{ number_format($last_price) }}')
                ->editColumn('avg_price', 'Rp {{ number_format($avg_price) }}')
                ->editColumn('created_at', '{{ date("d-m-Y, H:i:s",strtotime($created_at)) }}')
                ->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ],500);
        }
    }

    public function indexCreate()
    {
        return view('admin.stock.product-list.create',$this->service->indexCreateData());
    }

    public function uploadImage(Request $request)
    {
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        return $this->service->uploadImage($file,$filename,$extension);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'satuan_id' => 'required',
            'department_id' => 'required'
        ]);

        $code = $request->get('code');
        $name = $request->get('name');
        $image = $request->get('image');
        $satuan_id = $request->get('satuan_id');
        $department_id = $request->get('department_id');
        $price = $request->get('price') ?? 0;
        $id = $request->get('id') ?? null;

        return $this->service->storeData($code,$name,$image,$satuan_id,$department_id,$price,$id);
    }

    public function getImage($file_path)
    {
        $file_path = decrypt($file_path);

        return response()->file(storage_path("app/public/{$file_path}"));
    }

    public function indexEdit($id)
    {
        return view('admin.stock.product-list.edit',$this->service->indexEditData($id));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->deleteData($id);
    }
}
