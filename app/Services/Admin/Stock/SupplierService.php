<?php

namespace App\Services\Admin\Stock;

use App\Models\Satuan;
use App\Models\Supplier;
use App\Repositories\Admin\Stock\SupplierRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SupplierService
{
    private $repository;

    public function __construct(SupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function uploadImage($file,$filename,$extension)
    {
        try {
            $path = "supplier/image";

            $file_name = Uuid::uuid1()->toString();
            $new_file_name = "{$file_name}.{$extension}";

            Storage::putFileAs("public/{$path}",$file,$new_file_name);

            return response()->json([
                'status' => 'success',
                'file_path' => "{$path}/{$new_file_name}"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function storeData($name,$image,$phone,$address,$contact_person_name,$contact_person_phone,$info,$id = null)
    {
        try {
            DB::beginTransaction();

            if ($id == null) {
                $data = new Supplier();
                $data->logo = encrypt($image);
            } else {
                $data = Supplier::find($id);
                $data->logo = ($image == null) ? $data->logo : encrypt($image);
            }
            $data->name = $name;
            $data->phone = $phone;
            $data->address = $address;
            $data->contact_person_name = $contact_person_name;
            $data->contact_person_phone = $contact_person_phone;
            $data->info = $info;
            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect' => route('admin.master-data.supplier.view.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ],500);
        }
    }

    public function indexEditData($id)
    {
        return [
            'data' => Supplier::find($id),
            'satuan' => Satuan::all()
        ];
    }

    public function deleteData($id)
    {
        try {
            DB::beginTransaction();

            $data = Supplier::find($id);
            $data->delete();

            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ],500);
        }
    }
}
