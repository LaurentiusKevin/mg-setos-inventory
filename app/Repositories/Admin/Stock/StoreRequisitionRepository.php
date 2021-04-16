<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreRequisitionRepository
{
    public function department()
    {
        $department = DB::table('departments')
            ->whereNull('deleted_at');

        $user_department = Auth::user()->department_id;

        return ($user_department !== null) ? $department->where('id','=',$user_department) : $department;
    }
    public function storeRequisitionInfo($id = null)
    {
        $data = DB::table('store_requisition_infos')
            ->select([
                'store_requisition_infos.id',
                'store_requisition_infos.user_id',
                'store_requisition_infos.department_id',
                'departments.name AS department',
                'users.name AS penginput',
                'store_requisition_infos.invoice_number',
                'store_requisition_infos.info_penggunaan',
                'store_requisition_infos.total_item',
                'store_requisition_infos.total_price',
                'store_requisition_infos.catatan',
                'store_requisition_infos.verified_at',
                'store_requisition_infos.created_at',
                'store_requisition_infos.updated_at',
                'store_requisition_infos.deleted_at',
            ])
            ->leftJoin('departments','store_requisition_infos.department_id','=','departments.id')
            ->leftJoin('users','store_requisition_infos.user_id','=','users.id')
            ->whereNull('store_requisition_infos.deleted_at');

        return ($id !== null) ? $data->where('store_requisition_infos.id','=',$id) : $data;
    }

    public function storeRequisitionProduct($store_requisition_info_id)
    {
        return DB::table('store_requisition_products')
            ->select([
                'store_requisition_products.id',
                'store_requisition_products.store_requisition_info_id',
                'store_requisition_products.product_id',
                'store_requisition_products.quantity',
                'store_requisition_products.price',
                'products.code AS product_code',
                'products.name AS product_name',
                'products.stock AS product_stock',
                'products.supplier_price',
                'products.last_price',
                'products.avg_price',
                'satuans.nama AS satuan',
                'store_requisition_products.created_at',
                'store_requisition_products.updated_at',
                'store_requisition_products.deleted_at',
            ])
            ->leftJoin('products','store_requisition_products.product_id','=','products.id')
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->whereNull('store_requisition_products.deleted_at')
            ->where('store_requisition_info_id','=',$store_requisition_info_id);
    }

    public function productList($selected_product_id = null)
    {
        $data = DB::table('products')
            ->select([
                'products.id',
                'products.satuan_id',
                'products.code',
                'products.name',
                'products.stock',
                'satuans.nama AS satuan',
                'products.supplier_price',
                'products.last_price',
                'products.avg_price',
                'products.image',
                'products.created_at',
                'products.updated_at',
                'products.deleted_at'
            ])
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->whereNull('deleted_at');

        if ($selected_product_id !== null) {
            $data->whereNotIn('products.id',$selected_product_id);
        }

        return $data;
    }

    public function verificator($department_id)
    {
        return DB::table('store_requisition_verificators')
            ->select([
                'users.id',
                'users.username',
                'users.role_id',
                'users.name',
                'users.email',
                'users.email_verified_at',
                'users.password',
                'users.department_id',
                'users.remember_token',
                'users.created_at',
                'users.updated_at',
                'users.deleted_at',
            ])
            ->leftJoin('users','store_requisition_verificators.user_id','=','users.id')
            ->whereNull('users.department_id')
            ->orWhere('users.department_id','=',$department_id)
            ->get();
    }

    public function statusVerification($store_requisition_info_id)
    {
        return DB::table('store_requisition_verifications')
            ->whereNull('verified_at')
            ->where([
                ['store_requisition_info_id','=',$store_requisition_info_id],
                ['user_id','=',Auth::id()],
            ])->get();
    }

    public function catatan($store_requisition_info_id)
    {
        return DB::table('store_requisition_notes')
            ->where('store_requisition_info_id','=',$store_requisition_info_id)
            ->get();
    }
}
