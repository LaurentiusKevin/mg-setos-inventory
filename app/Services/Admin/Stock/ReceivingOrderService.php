<?php

namespace App\Services\Admin\Stock;

use App\Helpers\CounterHelper;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\PurchaseOrderInfo;
use App\Models\PurchaseOrderProduct;
use App\Models\ReceivingOrderInfo;
use App\Models\ReceivingOrderProduct;
use App\Repositories\Admin\Stock\ReceivingOrderRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceivingOrderService
{
    private $repo;

    public function __construct(ReceivingOrderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getDataPoPending()
    {
        return PurchaseOrderInfo::with([
            'supplier',
            'received',
            'products' => function($query) {
                $query
                    ->with([
                        'product' => function ($querySatuan) {
                            $querySatuan->with('satuan');
                        },
                    ]);
            }])
            ->whereHas('products', function (Builder $query) {
                $query->where('quantity','>',DB::raw('quantity_received'));
            })
            ->withSum('products','quantity')
            ->withSum('received','quantity')
            ->where('total_item','<>',DB::raw('received_item'));
    }

    public function getDataPoPendingProducts($purchase_order_info_id)
    {
        return PurchaseOrderProduct::with([
                'product' => function ($querySatuan) {
                    $querySatuan->with('satuan');
                },
            ])
            ->where([
                ['purchase_order_info_id','=',$purchase_order_info_id],
                ['quantity','>',DB::raw('quantity_received')],
            ])
            ->get();
    }

    public function storeData($purchase_order_info_id,$product,$catatan)
    {
        $invoiceNumber = CounterHelper::getNewCode('RCV');
        $total_received_item = 0;
        $total_price = 0;

        try {
            DB::beginTransaction();
            $poInfo = PurchaseOrderInfo::find($purchase_order_info_id);
            foreach ($product AS $item) {
                $total_received_item += $item['quantity'];
                $total_price += $item['quantity'] * $item['price'];
                $poInfo->received_item += $item['quantity'];

                $poProduct = PurchaseOrderProduct::find($item['purchase_order_product_id']);
                $poProduct->quantity_received += $item['quantity'];
                $poProduct->save();
            }
            if ($poInfo->received_item == $poInfo->total_item) $poInfo->receive_completed_at = now();
            $poInfo->save();

            $rcvInfo = new ReceivingOrderInfo();
            $rcvInfo->user_id = Auth::id();
            $rcvInfo->purchase_order_info_id = $purchase_order_info_id;
            $rcvInfo->invoice_number = $invoiceNumber;
            $rcvInfo->supplier_invoice_number = '';
            $rcvInfo->total_price = $total_price;
            $rcvInfo->catatan = $catatan;
            $rcvInfo->save();

            foreach ($product AS $item) {
                $product = Product::find($item['product_id']);
                $product->stock += $item['quantity'];
                $product->last_price = $item['price'];
                $product->avg_price = ($product->avg_price + $item['price']) / 2;
                $product->save();

                $poProduct = PurchaseOrderProduct::find($item['purchase_order_product_id']);

                $rcvProduct = new ReceivingOrderProduct();
                $rcvProduct->receiving_order_info_id = $rcvInfo->id;
                $rcvProduct->purchase_order_info_id = $purchase_order_info_id;
                $rcvProduct->purchase_order_product_id = $poProduct->id;
                $rcvProduct->product_id = $item['product_id'];
                $rcvProduct->quantity = $item['quantity'];
                $rcvProduct->price = $item['price'];
                $rcvProduct->total_price = $item['quantity'] * $item['price'];
                $rcvProduct->save();

                $product_transaction = new ProductTransaction();
                $product_transaction->user_id = Auth::id();
                $product_transaction->product_id = $item['product_id'];
                $product_transaction->price = $item['price'];
                $product_transaction->in = $item['quantity'];
                $product_transaction->out = 0;
                $product_transaction->saldo = 0;
                $product_transaction->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'invoice_number' => $invoiceNumber,
                'invoice_pdf' => route('admin.stock.receiving-order.view.invoice',[$rcvInfo->id]),
                'redirect' => route('admin.stock.receiving-order.view.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function indexInfoData($id)
    {
        return [
            'info' => $this->repo->receivingOrderInfo($id)[0],
            'product' => $this->repo->receivingOrderProduct($id),
            'invoice_number' => $this->repo->receivingOrderInfo($id)[0]->invoice_number
        ];
    }
}
