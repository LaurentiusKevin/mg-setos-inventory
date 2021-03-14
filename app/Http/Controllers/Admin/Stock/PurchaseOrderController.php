<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Services\Admin\Stock\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    private $service;

    public function __construct(PurchaseOrderService $service)
    {
        $this->service = $service;
    }
}
