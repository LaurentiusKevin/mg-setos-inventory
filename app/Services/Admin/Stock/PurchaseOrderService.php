<?php

namespace App\Services\Admin\Stock;

use App\Repositories\Admin\Stock\PurchaseOrderRepository;

class PurchaseOrderService
{
    private $repository;

    public function __construct(PurchaseOrderRepository $repository)
    {
        $this->repository = $repository;
    }
}
