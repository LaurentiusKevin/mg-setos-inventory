<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Services\Admin\Stock\SupplierService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    private $service;

    public function __construct(SupplierService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.master-data.supplier.index');
    }

    public function data()
    {
        try {
            return DataTables::of(Supplier::all())
                ->editColumn('logo', function ($data) {
                    return ($data->logo == null) ? url('icons/picture.svg') : url("admin/master-data/supplier/api/get-image/{$data->logo}");
                })
                ->editColumn('phone', function ($data) {
                    return ($data->phone == null) ? '-' : $data->phone;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.master-data.supplier.action');
                })
                ->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function indexCreate()
    {
        return view('admin.master-data.supplier.create');
    }

    public function uploadImage(Request $request)
    {
        $file = $request->file('logo');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        return $this->service->uploadImage($file,$filename,$extension);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $name = $request->get('name');
        $image = $request->get('image') ?? null;
        $phone = $request->get('phone') ?? null;
        $address = $request->get('address') ?? null;
        $contact_person_name = $request->get('contact_person_name') ?? null;
        $contact_person_phone = $request->get('contact_person_phone') ?? null;
        $info = $request->get('info') ?? null;
        $id = $request->get('id') ?? null;

        return $this->service->storeData($name,$image,$phone,$address,$contact_person_name,$contact_person_phone,$info,$id);
    }

    public function getImage($file_path)
    {
        $file_path = decrypt($file_path);

        if ($file_path !== null) {
            return response()->file(storage_path("app/public/{$file_path}"));
        } else {
            return response()->file(public_path('icons/picture.svg'));
        }
    }

    public function indexEdit($id)
    {
        return view('admin.master-data.supplier.edit',$this->service->indexEditData($id));
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
