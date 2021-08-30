<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @method static find(mixed $id)
 */
class Product extends Model
{
    use HasFactory,SoftDeletes;

    public function satuan()
    {
        return $this->hasOne(Satuan::class,'id','satuan_id');
    }

    public static function laporan($filter_tgl = null): Builder
    {
        $query = DB::table('products')
            ->select([
                'products.code',
                'products.name',
                'satuans.nama AS satuan',
                'products.supplier_price',
                'products.last_price',
                'products.avg_price',
                'products.created_at',
                'products.updated_at',
                DB::raw('SUM(pt.`in`) AS `in`'),
                DB::raw('SUM(pt.out) AS `out`'),
                DB::raw('SUM(COALESCE(pt.`in`,0))-SUM(COALESCE(pt.out,0)) AS stock')
            ])
            ->leftJoin(DB::raw('product_transactions pt'),'products.id','=','pt.product_id')
            ->leftJoin('satuans','products.satuan_id','=','satuans.id')
            ->groupBy('products.id');

        if ($filter_tgl !== null) {
            $query->where('pt.created_at','<=',$filter_tgl);
        }

        return $query;
    }
}
