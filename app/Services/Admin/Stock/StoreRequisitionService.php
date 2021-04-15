<?php

namespace App\Services\Admin\Stock;

use App\Helpers\CounterHelper;
use App\Models\PenggunaanBarangProduct;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\StoreRequisitionInfo;
use App\Models\StoreRequisitionNotes;
use App\Models\StoreRequisitionProducts;
use App\Models\StoreRequisitionVerification;
use App\Repositories\Admin\Stock\StoreRequisitionRepository;
use Illuminate\Database\Eloquent\Model;
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
        $department = DB::table('departments')
            ->whereNull('deleted_at');

        $user_department = Auth::user()->department_id;
        if ($user_department !== null) $department->where('id','=',$user_department);

        return [
            'department' => $department->get()
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
        return $this->repository->statusVerification($store_requisition_info_id)->count();
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
                $info->invoice_number = $invoice_number;
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
                'details' => $th
            ]);
        }
    }

    public function indexInfoData($store_requisition_info_id)
    {
        return [
            'info' => $this->repository->storeRequisitionInfo($store_requisition_info_id)->first(),
            'product' => $this->repository->storeRequisitionProduct($store_requisition_info_id)->get(),
            'catatan' => $this->repository->catatan($store_requisition_info_id)
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
