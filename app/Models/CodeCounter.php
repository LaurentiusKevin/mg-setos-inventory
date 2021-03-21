<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tahun',
        'bulan',
        'counter'
    ];
}
