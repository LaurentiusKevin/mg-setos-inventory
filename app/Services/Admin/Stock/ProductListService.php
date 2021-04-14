<?php

namespace App\Services\Admin\Stock;

use App\Models\Department;
use App\Models\Product;
use App\Models\Satuan;
use App\Repositories\Admin\Stock\ProductListRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ProductListService
{
    private $repository;

    public function __construct(ProductListRepository $repository)
    {
        $this->repository = $repository;
    }

    public function data()
    {
        return $this->repository->data();
    }

    public function indexCreateData()
    {
        return [
            'satuan' => Satuan::all(),
            'department' => Department::all()
        ];
    }

    public function uploadImage($file,$filename,$extension)
    {
        try {
            $new_file_name = Uuid::uuid1()->toString();
            Storage::putFileAs('public/product/image',$file,"{$new_file_name}.{$extension}");

            return response()->json([
                'status' => 'success',
                'file_path' => "product/image/{$new_file_name}.{$extension}"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function storeData($name,$image,$satuan_id,$department_id,$price,$id = null)
    {
        try {
            DB::beginTransaction();

            if ($id == null) {
                $data = new Product();
                $data->stock = 0;
                $data->avg_price = $price;
                $data->last_price = $price;
                $data->image = $image;
            } else {
                $data = Product::find($id);
                $data->image = ($image == null) ? $data->image : $image;
            }
            $data->supplier_price = $price;
            $data->satuan_id = $satuan_id;
            $data->department_id = $department_id;
            $data->name = $name;
            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect' => route('admin.stock.product-list.view.index')
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
            'data' => Product::find($id),
            'satuan' => Satuan::all(),
            'department' => Department::all()
        ];
    }

    public function deleteData($id)
    {
        try {
            DB::beginTransaction();

            $data = Product::find($id);
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
