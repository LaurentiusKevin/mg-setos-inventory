<?php

namespace App\Repositories\Admin\Purchasing;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoicingRepository
{
    public function invoicingInfo($f_status_selesai = null, $id = null): Builder
    {
        $data = DB::table(DB::raw('invoicing_infos ii'))
            ->select([
                'ii.id',
                'ii.store_requisition_info_id',
                'ii.user_id',
                'd.name AS department_name',
                'sri.invoice_number AS invoice_number_sr',
                'ii.invoice_number AS invoice_number_invoicing',
                'ii.info_penggunaan',
                'ii.total_item',
                'ii.total_price',
                'ii.catatan',
                'u.name AS penginput_invoicing',
                'u2.name AS penginput_sr',
                'sri.verified_at',
                'ii.completed_at',
                'ii.created_at',
                'ii.updated_at',
                'ii.deleted_at'
            ])
            ->leftJoin(DB::raw('store_requisition_infos sri'),'ii.store_requisition_info_id','=','sri.id')
            ->leftJoin(DB::raw('departments d'),'sri.department_id','=','d.id')
            ->leftJoin(DB::raw('users u'),'ii.user_id','=','u.id')
            ->leftJoin(DB::raw('users u2'),'sri.user_id','=','u2.id');

        if ($f_status_selesai !== null) {
            if ($f_status_selesai == 0) {
                $data->whereNull('ii.completed_at');
            } else {
                $data->whereNotNull('ii.completed_at');
            }
        }

        if ($id !== null) $data->where('ii.id','=',$id);

        return $data;
    }

    public function invoicingProducts($store_requisition_info_id, $group = true): Collection
    {
        $data = DB::table(DB::raw('store_requisition_products srp'))
            ->select([
                'srp.id AS store_requisition_product_id',
                'store_requisition_info_id',
                'srp.product_id',
                'p.last_price',
                'p.code AS product_code',
                'p.name AS product_name',
                'p.stock AS product_stock',
                's.nama AS satuan',
                'srp.quantity AS quantity_max',
                DB::raw('sum(coalesce(ip.quantity,0)) AS quantity_sent'),
                'srp.price',
                'srp.user_id',
                'u.name AS penginput',
                'srp.created_at',
                'srp.updated_at',
                'srp.deleted_at',
                'ip.created_at AS invoicing_created_at',
            ])
            ->join(DB::raw('products p'),'srp.product_id','=','p.id')
            ->join(DB::raw('satuans s'),'p.satuan_id','=','s.id')
            ->leftJoin(DB::raw('invoicing_products ip'),'srp.id','=','ip.store_requisition_product_id')
            ->leftJoin(DB::raw('users u'),'srp.user_id','=','u.id')
            ->whereNull('ip.deleted_at')
            ->where('store_requisition_info_id','=',$store_requisition_info_id)
            ->groupBy('srp.id')
            ->groupBy('srp.created_at')
            ->groupBy('srp.product_id');

        if ($group == false) $data->groupBy('ip.id');

        return $data->get();
    }

    public function invoicingProductInfo($store_requisition_info_id, $group = false)
    {
        $data = DB::table(DB::raw('invoicing_products ip'))
            ->join(DB::raw('store_requisition_products srp'),'ip.store_requisition_product_id','=','srp.id')
            ->join(DB::raw('products p'),'ip.product_id','=','p.id')
            ->join(DB::raw('satuans s'),'p.satuan_id','=','s.id')
            ->join(DB::raw('users u'),'ip.user_id','=','u.id')
            ->where('ip.invoicing_info_id','=',$store_requisition_info_id)
            ->whereNull('ip.deleted_at');

        if ($group == false) {
            $data
                ->select([
                    'srp.store_requisition_info_id',
                    'srp.id AS store_requisition_product_id',
                    'p.id AS product_id',
                    'p.last_price',
                    'p.code AS product_code',
                    'p.name AS product_name',
                    'p.stock AS product_stock',
                    's.nama AS satuan',
                    'srp.quantity AS quantity_max',
                    'ip.quantity AS quantity_sent',
                    'ip.price',
                    'ip.user_id',
                    'u.name AS penginput',
                    'ip.created_at AS invoicing_created_at',
                ])
                ->orderBy('ip.created_at');
        } else {
            $data
                ->select([
                    'p.id AS product_id',
                    'p.last_price',
                    'p.code AS product_code',
                    'p.name AS product_name',
                    'p.stock AS product_stock',
                    's.nama AS satuan',
                    DB::raw('SUM(ip.quantity) AS quantity_sent'),
                    DB::raw('SUM(ip.quantity*ip.price) AS total_price'),
                    DB::raw('GROUP_CONCAT(u.name) AS penginput'),
                    DB::raw('GROUP_CONCAT(ip.created_at) AS invoicing_created_at'),
                ])
                ->groupBy('p.id')
                ->orderBy('p.code');
        }

        return $data->get();
    }
}
