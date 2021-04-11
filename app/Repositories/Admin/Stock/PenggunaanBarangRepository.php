<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\DB;

class PenggunaanBarangRepository
{
    public function penggunaanBarangInfo($penggunaan_barang_info_id = null)
    {
        $data = DB::table('penggunaan_barang_infos')
            ->select([
                'penggunaan_barang_infos.id',
                'penggunaan_barang_infos.user_id',
                'penggunaan_barang_infos.invoice_number',
                'penggunaan_barang_infos.info_penggunaan',
                'penggunaan_barang_infos.total_item',
                'penggunaan_barang_infos.catatan',
                'penggunaan_barang_infos.created_at',
                'users.name AS penginput',
            ])
            ->leftJoin('users','penggunaan_barang_infos.user_id','=','users.id')
            ->whereNull('penggunaan_barang_infos.deleted_at');

        if ($penggunaan_barang_info_id !== null) $data->where('penggunaan_barang_infos.id','=',$penggunaan_barang_info_id);

        return $data;
    }

    public function penggunaanBarangProduct($penggunaan_barang_info_id)
    {
        return DB::table('penggunaan_barang_products')
            ->select([
                'penggunaan_barang_products.id',
                'penggunaan_barang_products.penggunaan_barang_info_id',
                'penggunaan_barang_products.product_id',
                'products.name AS product_name',
                'products.last_price',
                'products.avg_price',
                'penggunaan_barang_products.quantity',
                'satuans.nama AS satuan',
                'penggunaan_barang_products.created_at',
                'penggunaan_barang_products.updated_at',
                'penggunaan_barang_products.deleted_at'
            ])
            ->leftJoin('products','penggunaan_barang_products.product_id','=','products.id')
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->where('penggunaan_barang_info_id','=',$penggunaan_barang_info_id);
    }

    public function productList($selected_product_id = null)
    {
        $data = DB::table('products')
            ->select([
                'products.id',
                'products.satuan_id',
                'products.name',
                'products.stock',
                'satuans.nama AS satuan',
                'products.last_price',
                'products.avg_price',
                'products.image',
                'products.created_at',
                'products.updated_at',
                'products.deleted_at'
            ])
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->whereNull('deleted_at')
            ->where('stock','>',0);

        if ($selected_product_id !== null) {
            $data->whereNotIn('products.id',$selected_product_id);
        }

        return $data;
    }
}
