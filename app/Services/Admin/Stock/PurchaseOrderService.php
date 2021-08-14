<?php

namespace App\Services\Admin\Stock;

use App\Helpers\CounterHelper;
use App\Models\Product;
use App\Models\PurchaseOrderInfo;
use App\Models\PurchaseOrderProduct;
use App\Models\Supplier;
use App\Repositories\Admin\Stock\PurchaseOrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderService
{
    private $repository;
    private $products;

    public function __construct(PurchaseOrderRepository $repository, ProductListService $products)
    {
        $this->repository = $repository;
        $this->products = $products;
    }

    public function data()
    {
        return $this->repository->purchaseOrderInfo();
    }

    public function getProduct($idNotIn = null)
    {
        return $this->products->data(null,$idNotIn);
    }

    public function indexCreateData()
    {
        $supplier = Supplier::all()->toArray();
        array_unshift($supplier,[
            'id' => '',
            'name' => '-- Pilih Supplier --'
        ]);

        foreach ($supplier AS $item) {
            $item['text'] = $item['name'];
        }

        return [
            'supplier' => base64_encode(json_encode($supplier))
        ];
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

    public function storeData($supplier_id,$product,$catatan)
    {
        try {
            DB::beginTransaction();

            $total_price = 0;
            $total_item = 0;
            foreach ($product AS $item) {
                $total_price += $item['quantity'] * $item['price'];
                $total_item += $item['quantity'];
            }

            $invoiceNumber = CounterHelper::getNewCode('PO');

            $info = new PurchaseOrderInfo();
            $info->invoice_number = $invoiceNumber;
            $info->supplier_id = $supplier_id;
            $info->total_price = $total_price;
            $info->total_item = $total_item;
            $info->catatan = $catatan;
            $info->save();

            foreach ($product AS $item) {
                $poProduct = new PurchaseOrderProduct();
                $poProduct->purchase_order_info_id = $info->id;
                $poProduct->product_id = $item['product_id'];
                $poProduct->quantity = $item['quantity'];
                $poProduct->price = $item['price'];
                $poProduct->total_price = $item['quantity'] * $item['price'];
                $poProduct->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'invoice_number' => $invoiceNumber,
                'invoice_pdf' => route('admin.purchasing.purchase-order.view.invoice',[$info->id]),
                'redirect' => route('admin.purchasing.purchase-order.view.index')
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
        try {
            $data = PurchaseOrderInfo::with(
                [
                    'supplier',
                    'products' => function($query)
                    {
                        $query->with([
                            'product' => function ($querySatuan) {
                                $querySatuan->with('satuan');
                            },
                        ]);
                    },
                ])
                ->withSum('products','quantity')
                ->where('id','=',$id)
                ->first();
            return [
                'data' => $data,
                'invoice_number' => $data->invoice_number
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteData($id)
    {
        try {
            DB::beginTransaction();

            PurchaseOrderInfo::query()->where('id','=',$id)->delete();

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
