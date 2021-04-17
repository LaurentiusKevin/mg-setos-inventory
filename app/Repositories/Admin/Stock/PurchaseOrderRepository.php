<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\DB;

class PurchaseOrderRepository
{
    public function purchaseOrderInfo($id = null)
    {
        $data = DB::table('purchase_order_infos')
            ->select([
                'purchase_order_infos.id',
                'purchase_order_infos.invoice_number',
                'purchase_order_infos.supplier_id',
                'suppliers.name AS supplier_name',
                'purchase_order_infos.total_item',
                'purchase_order_infos.received_item',
                'purchase_order_infos.total_price',
                'purchase_order_infos.catatan',
                'purchase_order_infos.created_at',
                'purchase_order_infos.updated_at',
                'purchase_order_infos.receive_completed_at',
                'purchase_order_infos.deleted_at',
            ])
            ->join('suppliers','purchase_order_infos.supplier_id','=','suppliers.id')
            ->orderBy('created_at','desc');

        return $id == null ? $data : $data->where('purchase_order_infos.id','=',$id);
    }
}
