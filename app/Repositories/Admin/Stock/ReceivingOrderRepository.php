<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\DB;

class ReceivingOrderRepository
{
    public function poPending()
    {
        return DB::select("
            select poi.id,
                   poi.invoice_number,
                   poi.supplier_id,
                   s.name as supplier_name,
                   poi.total_item,
                   poi.received_item,
                   poi.total_price,
                   poi.catatan,
                   poi.created_at,
                   poi.updated_at,
                   poi.receive_completed_at
            from purchase_order_infos poi
                left join suppliers s on poi.supplier_id = s.id
            where total_item > received_item
              and poi.deleted_at is null
              and poi.receive_completed_at is null
        ");
    }
}
