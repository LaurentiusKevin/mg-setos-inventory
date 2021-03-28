<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderInfo extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d M Y, H:i:s',
    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }

    public function products()
    {
        return $this->hasMany(PurchaseOrderProduct::class,'purchase_order_info_id','id');
    }

    public function received()
    {
        return $this->hasManyThrough(
            ReceivingOrderProduct::class,
            ReceivingOrderInfo::class,
            'purchase_order_info_id',
            'receiving_order_info_id',
            'id',
            'id'
        );
    }
}
