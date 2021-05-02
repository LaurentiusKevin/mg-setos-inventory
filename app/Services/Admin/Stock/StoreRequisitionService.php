<?php

namespace App\Services\Admin\Stock;

use App\Helpers\CounterHelper;
use App\Models\InvoicingInfo;
use App\Models\InvoicingProduct;
use App\Models\PenggunaanBarangProduct;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\StoreRequisitionInfo;
use App\Models\StoreRequisitionNotes;
use App\Models\StoreRequisitionProducts;
use App\Models\StoreRequisitionVerification;
use App\Repositories\Admin\Stock\StoreRequisitionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreRequisitionService
{
    private $repository;

    public function __construct(StoreRequisitionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function indexData()
    {
        return [
            'department' => $this->repository->department()->get()
        ];
    }

    public function getData()
    {
        return $this->repository->storeRequisitionInfo();
    }

    public function getProductList($selected_product_id)
    {
        return $this->repository->productList($selected_product_id);
    }

    public function statusVerification($store_requisition_info_id)
    {
        return $this->repository->unVerified($store_requisition_info_id)->count();
    }

    public function getVerificationInfo($store_requisition_info_id): Collection
    {
        return $this->repository->verified($store_requisition_info_id);
    }

    public function store($department_id,$product,$info_penggunaan,$catatan)
    {
        try {
            $total_item = 0;
            $total_price = 0;
            $nullValue = 0;
            foreach ($product AS $item) {
                $total_item += $item['quantity'];
                $total_price += $item['quantity'] * $item['price'];

                if ($item['quantity'] == null || $item['quantity'] < 1) $nullValue += 1;
                if ($item['price'] == null || $item['price'] < 1) $nullValue += 1;
            }

            if ($nullValue > 0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Harga dan Quantity wajib diisi!'
                ]);
            } else {
                DB::beginTransaction();

                $invoice_number = CounterHelper::getNewCode('SR');

                $info = new StoreRequisitionInfo();
                $info->user_id = Auth::id();
                $info->department_id = $department_id;
                $info->info_penggunaan = $info_penggunaan;
                $info->total_price = $total_price;
                $info->total_item = $total_item;
                $info->catatan = $catatan;
                $info->save();

                foreach ($product AS $item) {
                    $sr_product = new StoreRequisitionProducts();
                    $sr_product->store_requisition_info_id = $info->id;
                    $sr_product->product_id = $item['product_id'];
                    $sr_product->quantity = $item['quantity'];
                    $sr_product->price = $item['price'];
                    $sr_product->user_id = Auth::id();
                    $sr_product->save();
                }

                foreach ($this->repository->verificator($department_id) AS $item) {
                    $verification = new StoreRequisitionVerification();
                    $verification->store_requisition_info_id = $info->id;
                    $verification->user_id = $item->id;
                    $verification->save();
                }

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'invoice_number' => $invoice_number,
                    'invoice_pdf' => route('admin.stock.store-requisition.view.invoice',[$info->id]),
                    'redirect' => route('admin.stock.store-requisition.view.index')
                ]);
            }
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

    public function storeEdit($store_requisition_info_id,$department_id,$product,$info_penggunaan,$catatan)
    {
        try {
            DB::beginTransaction();

            $total_item = 0;
            $total_price = 0;
            $nullValue = 0;
            $listID = [];

            foreach ($product AS $item) {
                array_push($listID,$item['store_requisition_product_id']);

                $total_item += $item['quantity'];
                $total_price += $item['quantity'] * $item['price'];

                if ($item['quantity'] == null || $item['quantity'] < 1) $nullValue += 1;
                if ($item['price'] == null || $item['price'] < 1) $nullValue += 1;
            }

            if ($nullValue > 0) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Harga dan Quantity wajib diisi!'
                ]);
            }

            if ($this->repository->setDeletedProductNotIn($store_requisition_info_id,$listID) !== true) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Gagal menghapus produk'
                ]);
            }

            $info = StoreRequisitionInfo::find($store_requisition_info_id);
            $info->user_id = Auth::id();
            $info->department_id = $department_id;
            $info->info_penggunaan = $info_penggunaan;
            $info->total_price = $total_price;
            $info->total_item = $total_item;
            $info->catatan = $catatan;
            $info->save();

            foreach ($product AS $item) {
                if ($item['store_requisition_product_id'] !== null) {
                    $sr_product = StoreRequisitionProducts::find($item['store_requisition_product_id']);
                } else {
                    $sr_product = new StoreRequisitionProducts();
                }
                $sr_product->store_requisition_info_id = $info->id;
                $sr_product->product_id = $item['product_id'];
                $sr_product->quantity = $item['quantity'];
                $sr_product->price = $item['price'];
                $sr_product->user_id = 2;

                if ($sr_product->isDirty()) $sr_product->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'invoice_number' => $info->invoice_number,
                'invoice_pdf' => route('admin.stock.store-requisition.view.invoice',[$info->id]),
                'redirect' => route('admin.stock.store-requisition.view.index')
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

    public function storeRequisitionInfo($store_requisition_info_id)
    {
        return $this->repository->storeRequisitionInfo($store_requisition_info_id)->first();
    }

    public function indexEditData($store_requisition_info_id)
    {
        return [
            'info' => $this->storeRequisitionInfo($store_requisition_info_id),
            'catatan' => $this->repository->catatan($store_requisition_info_id),
            'department' => $this->repository->department()->get()
        ];
    }

    public function getStoredProduct($store_requisition_info_id,$edit_process = false)
    {
        return $this->repository->storeRequisitionProduct($store_requisition_info_id,$edit_process)->get();
    }

    public function indexInfoData($store_requisition_info_id)
    {
        return [
            'info' => $this->repository->storeRequisitionInfo($store_requisition_info_id)->first(),
            'product' => $this->repository->storeRequisitionProduct($store_requisition_info_id)->get(),
            'catatan' => $this->repository->catatan($store_requisition_info_id),
            'verificator' => $this->repository->verified($store_requisition_info_id)
        ];
    }

    public function storeVerification($store_requisition_info_id)
    {
        try {
            DB::beginTransaction();

            $data = StoreRequisitionVerification::where([
                ['store_requisition_info_id','=',$store_requisition_info_id],
                ['user_id','=',Auth::id()],
            ])->first();
            $data->verified_at = now();
            $data->save();

            $checkVerification = StoreRequisitionVerification::query()
                ->where('store_requisition_info_id','=',$store_requisition_info_id)
                ->whereNull('verified_at')
                ->get();

            if ($checkVerification->count() == 0) {
                StoreRequisitionInfo::query()
                    ->update([
                        'verified_at' => now()
                    ]);

                $sr_info = StoreRequisitionInfo::find($store_requisition_info_id);

                $invoicing_info = new InvoicingInfo();
                $invoicing_info->store_requisition_info_id = $store_requisition_info_id;
                $invoicing_info->user_id = $sr_info->user_id;
                $invoicing_info->info_penggunaan = $sr_info->info_penggunaan;
                $invoicing_info->total_item = $sr_info->total_item;
                $invoicing_info->total_price = $sr_info->total_price;
                $invoicing_info->catatan = $sr_info->catatan;
                $invoicing_info->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect' => route('admin.stock.store-requisition.view.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function storeCatatan($store_requisition_info_id,$catatan)
    {
        try {
            DB::beginTransaction();

            $data = new StoreRequisitionNotes();
            $data->store_requisition_info_id = $store_requisition_info_id;
            $data->user_id = Auth::id();
            $data->catatan = $catatan;
            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
