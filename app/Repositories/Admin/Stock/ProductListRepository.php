<?php

namespace App\Repositories\Admin\Stock;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductListRepository
{
    public function data($id = null, $idNotIn = null)
    {
        $department_id = Auth::user()->department_id;
        $data = DB::table('products')
            ->select([
                'products.id',
                'products.satuan_id',
                'products.department_id',
                'products.code',
                'products.name',
                'departments.name AS department',
                'products.stock',
                'satuans.nama AS satuan',
                'products.supplier_price',
                'products.last_price',
                'products.avg_price',
                'products.image',
                'products.created_at',
                'products.updated_at',
                'products.deleted_at',
            ])
            ->leftJoin('departments','products.department_id','=','departments.id')
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->whereNull('products.deleted_at');

        if ($department_id !== null) $data->where('department_id','=',$department_id);

        if ($idNotIn !== null) $data->whereNotIn('products.id',$idNotIn);

        return $id == null ? $data : $data->where('id','=',$id);
    }

    public function checkCode($code,$id = null)
    {
        $data = DB::table('products')
            ->where('code','=',$code)
            ->whereNull('deleted_at');

        if ($id !== null) $data->where('id','<>',$id);

        return $data->get()->count();
    }
}
