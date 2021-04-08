<?php

use App\Http\Controllers\Admin\Stock\ProductListController;
use App\Http\Controllers\Admin\Stock\PurchaseOrderController;
use App\Http\Controllers\Admin\Stock\ReceivingOrderController;
use App\Http\Controllers\Admin\Stock\SupplierController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/stock/supplier',
        'as' => 'admin.stock.supplier.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[SupplierController::class,'index'])->name('index');
                Route::get('create',[SupplierController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[SupplierController::class,'indexEdit'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[SupplierController::class,'data'])->name('data');
                Route::post('upload-image',[SupplierController::class,'uploadImage'])->name('upload-image');
                Route::get('get-image/{file_path}',[SupplierController::class,'getImage'])->name('get-image');
                Route::post('store',[SupplierController::class,'store'])->name('store');
                Route::post('delete',[SupplierController::class,'delete'])->name('delete');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/stock/product-list',
        'as' => 'admin.stock.product-list.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[ProductListController::class,'index'])->name('index');
                Route::get('create',[ProductListController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[ProductListController::class,'indexEdit'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[ProductListController::class,'data'])->name('data');
                Route::post('upload-image',[ProductListController::class,'uploadImage'])->name('upload-image');
                Route::get('get-image/{file_path}',[ProductListController::class,'getImage'])->name('get-image');
                Route::post('store',[ProductListController::class,'store'])->name('store');
                Route::post('delete',[ProductListController::class,'delete'])->name('delete');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/stock/purchase-order',
        'as' => 'admin.stock.purchase-order.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[PurchaseOrderController::class,'index'])->name('index');
                Route::get('create',[PurchaseOrderController::class,'indexCreate'])->name('create');
                Route::get('info/{id}',[PurchaseOrderController::class,'indexInfo'])->name('edit');
                Route::get('invoice/{id}',[PurchaseOrderController::class,'indexPdf'])->name('invoice');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[PurchaseOrderController::class,'data'])->name('data');
                Route::post('product-list',[PurchaseOrderController::class,'getProductList'])->name('product-list');
                Route::post('store',[PurchaseOrderController::class,'store'])->name('store');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/stock/receiving-order',
        'as' => 'admin.stock.receiving-order.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[ReceivingOrderController::class,'index'])->name('index');
                Route::get('create',[ReceivingOrderController::class,'indexCreate'])->name('create');
                Route::get('info/{id}',[ReceivingOrderController::class,'indexInfo'])->name('edit');
                Route::get('invoice/{id}',[ReceivingOrderController::class,'indexPdf'])->name('invoice');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[ReceivingOrderController::class,'data'])->name('data');
                Route::post('po-pending',[ReceivingOrderController::class,'dataPoPending'])->name('po-pending');
                Route::post('po-pending/products',[ReceivingOrderController::class,'dataPoPendingProducts'])->name('po-pending.products');
                Route::post('product-list',[ReceivingOrderController::class,'getProductList'])->name('product-list');
                Route::post('store',[ReceivingOrderController::class,'store'])->name('store');
            }
        );
    }
);
