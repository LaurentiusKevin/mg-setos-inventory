<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\DB;

class ReceivingOrderRepository
{
    public function receivingOrderInfo($receiving_order_infos_id)
    {
        return DB::select("
            select roi.id AS receiving_order_infos_id,
                   roi.user_id,
                   roi.purchase_order_info_id,
                   poi.invoice_number AS invoice_number_po,
                   roi.invoice_number,
                   roi.supplier_invoice_number,
                   s.name AS supplier_name,
                   s.logo AS supplier_logo,
                   s.phone AS supplier_phone,
                   s.address AS supplier_address,
                   s.contact_person_name AS supplier_contact_person_name,
                   s.contact_person_phone AS supplier_contact_person_phone,
                   s.info AS supplier_info,
                   roi.total_price,
                   roi.catatan,
                   roi.created_at,
                   roi.updated_at
            from
                receiving_order_infos roi
                    left join purchase_order_infos poi on roi.purchase_order_info_id = poi.id
                    left join suppliers s on poi.supplier_id = s.id
            where roi.id = ?
        ",[$receiving_order_infos_id]);
    }

    public function receivingOrderProduct($receiving_order_infos_id)
    {
        return DB::select("
            select rop.id AS receiving_order_products_id,
                   rop.receiving_order_info_id,
                   rop.purchase_order_info_id,
                   rop.purchase_order_product_id,
                   rop.product_id,
                   rop.quantity,
                   rop.price,
                   rop.total_price,
                   p.name,
                   p.stock,
                   p.last_price,
                   p.avg_price,
                   p.image,
                   s.nama as satuan
            from receiving_order_products rop
                left join products p on rop.product_id = p.id
                left join satuans s on p.satuan_id = s.id
            where rop.receiving_order_info_id = ?
        ",[$receiving_order_infos_id]);
    }
}
