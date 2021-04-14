<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductListRepository
{
    public function data($id = null)
    {
        $department_id = Auth::user()->department_id;
        $data = DB::table('products')
            ->select([
                'products.id',
                'satuans.nama AS satuan',
                'products.name',
                'departments.name AS department',
                'products.stock',
                'products.supplier_price',
                'products.last_price',
                'products.avg_price',
                'products.image',
                'products.created_at',
                'products.updated_at',
            ])
            ->leftJoin('departments','products.department_id','=','departments.id')
            ->join('satuans','products.satuan_id','=','satuans.id');

        if ($department_id !== null) $data->where('department_id','=',$department_id);

        return $id == null ? $data : $data->where('id','=',$id);
    }
}
