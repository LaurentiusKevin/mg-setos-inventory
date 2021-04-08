<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingOrderInfo extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y, H:i:s',
        'updated_at' => 'datetime:d-m-Y, H:i:s'
    ];

    public function products()
    {
        return $this->hasMany(ReceivingOrderProduct::class,'receiving_order_info_id','id');
    }

    public function supplier()
    {
        return $this->hasOneThrough(
            Supplier::class,
            PurchaseOrderInfo::class,
            'id',
            'id',
            'purchase_order_info_id',
            'supplier_id'
        );
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
