<?php

namespace App\Services\Admin\Stock;

use App\Helpers\CounterHelper;
use App\Models\Product;
use App\Models\PurchaseOrderInfo;
use App\Models\PurchaseOrderProduct;
use App\Models\Supplier;
use App\Repositories\Admin\Stock\PurchaseOrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderService
{
    private $repository;

    public function __construct(PurchaseOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function indexCreateData()
    {
        $supplier = Supplier::all()->toArray();
        array_unshift($supplier,[
            'id' => '',
            'text' => '-- Pilih Supplier --'
        ]);

        return [
            'supplier' => json_encode($supplier)
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
            foreach ($product AS $item) $total_price += $item['quantity'] * $item['price'];

            $invoiceNumber = CounterHelper::getNewCode('PO');

            $info = new PurchaseOrderInfo();
            $info->invoice_number = $invoiceNumber;
            $info->supplier_id = $supplier_id;
            $info->total_price = $total_price;
            $info->catatan = $catatan;
            $info->save();

            foreach ($product AS $item) {
                $product = Product::find($item['product_id']);
                $product->last_price = $item['price'];
                $product->avg_price = $product->avg_price == null ? $item['price'] : (($product->avg_price + $item['price']) / 2);
                $product->save();

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
                'invoice_pdf' => route('admin.stock.purchase-order.view.invoice',[$info->id]),
                'redirect' => route('admin.stock.purchase-order.view.index')
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
            'data' => PurchaseOrderInfo::with(
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
                ->first()
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
