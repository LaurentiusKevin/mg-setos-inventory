<?php

namespace App\Services\Admin\Purchasing;

use App\Helpers\CounterHelper;
use App\Models\InvoicingInfo;
use App\Models\InvoicingProduct;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Repositories\Admin\Purchasing\InvoicingRepository;
use App\Services\Admin\Stock\StoreRequisitionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoicingService
{
    private $repository;
    private $storeRequisitionService;

    public function __construct(InvoicingRepository $repository,
                                StoreRequisitionService $storeRequisitionService)
    {
        $this->repository = $repository;
        $this->storeRequisitionService = $storeRequisitionService;
    }

    public function getInvoicingInfo($f_status_selesai = null): Builder
    {
        return $this->repository->invoicingInfo($f_status_selesai);
    }

    public function indexInvoicingProcessData($id): array
    {
        $info = InvoicingInfo::find($id);

        return [
            'invoicing_info_id' => $id,
            'info_sr' => $this->storeRequisitionService->storeRequisitionInfo($info->store_requisition_info_id),
            'verificator' => $this->storeRequisitionService->getVerificationInfo($info->store_requisition_info_id)
        ];
    }

    public function getInvoicingProducts($store_requisition_info_id, $group = true): Collection
    {
        return $this->repository->invoicingProducts($store_requisition_info_id, $group);
    }

    public function store($product,$invoicing_info_id,$store_requisition_info_id)
    {
        try {
            DB::beginTransaction();
            $info = InvoicingInfo::find($invoicing_info_id);

            foreach ($product AS $item) {
                if ($item['quantity'] !== null && $item['quantity'] !== 0) {
                    $masterProduct = Product::find($item['product_id']);
                    $masterProduct->stock -= $item['quantity'];
                    $masterProduct->save();

                    $invoicingProduct = new InvoicingProduct();
                    $invoicingProduct->invoicing_info_id = $invoicing_info_id;
                    $invoicingProduct->store_requisition_product_id = $item['store_requisition_product_id'];
                    $invoicingProduct->product_id = $item['product_id'];
                    $invoicingProduct->quantity = $item['quantity'];
                    $invoicingProduct->price = $item['price'];
                    $invoicingProduct->user_id = Auth::id();
                    $invoicingProduct->save();

                    $querySaldo = DB::table('product_transactions')
                        ->select([
                            'product_id',
                            DB::raw('(sum(`in`) - sum(`out`)) AS saldo')
                        ])
                        ->where('product_id','=',$item['product_id'])
                        ->groupBy('product_id')
                        ->first();
                    $saldo = ($querySaldo->saldo ?? 0) - $item['quantity'];

                    $product_transaction = new ProductTransaction();
                    $product_transaction->user_id = Auth::id();
                    $product_transaction->product_id = $item['product_id'];
                    $product_transaction->invoice_number = $info->invoice_number;
                    $product_transaction->type = 2;
                    $product_transaction->price = $item['price'];
                    $product_transaction->in = 0;
                    $product_transaction->out = $item['quantity'];
                    $product_transaction->saldo = $saldo;
                    $product_transaction->save();
                }
            }

            $savedProducts = $this->getInvoicingProducts($store_requisition_info_id);
            $totalLeft = 0;
            foreach ($savedProducts AS $item) {
                $totalLeft += $item->quantity_max - $item->quantity_sent;
            }

            if ($totalLeft == 0) {
                $info->user_id = Auth::id();
                $info->invoice_number = CounterHelper::getNewCode('Inv');
                $info->completed_at = now();
                $info->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect' => route('admin.purchasing.invoicing.view.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => [
                    $th->getFile(),
                    $th->getLine()
                ]
            ]);
        }
    }

    public function indexDetailData($invoicing_info_id, $group = false)
    {
        return [
            'info' => $this->repository->invoicingInfo(null,$invoicing_info_id)->first(),
            'product' => $this->repository->invoicingProductInfo($invoicing_info_id, $group)
        ];
    }
}
