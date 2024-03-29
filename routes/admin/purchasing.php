<?php

use App\Http\Controllers\Admin\Purchasing\InvoicingController;
use App\Http\Controllers\Admin\Stock\PenggunaanBarangController;
use App\Http\Controllers\Admin\Stock\PurchaseOrderController;
use App\Http\Controllers\Admin\Stock\ReceivingOrderController;
use App\Http\Controllers\Admin\Stock\StoreRequisitionController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/purchasing/purchase-order',
        'as' => 'admin.purchasing.purchase-order.',
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
                Route::post('delete',[PurchaseOrderController::class,'delete'])->name('delete');
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

//Route::group(
//    [
//        'prefix' => 'admin/stock/penggunaan-barang',
//        'as' => 'admin.stock.penggunaan-barang.',
//        'middleware' => ['auth']
//    ],
//    function () {
//        Route::group(
//            ['as' => 'view.'],
//            function () {
//                Route::get('/',[PenggunaanBarangController::class,'index'])->name('index');
//                Route::get('create',[PenggunaanBarangController::class,'indexCreate'])->name('create');
//                Route::get('info/{id}',[PenggunaanBarangController::class,'indexInfo'])->name('edit');
//                Route::get('invoice/{id}',[PenggunaanBarangController::class,'indexPdf'])->name('invoice');
//            }
//        );
//
//        Route::group(
//            ['prefix' => 'api', 'as' => 'api.'],
//            function () {
//                Route::post('data',[PenggunaanBarangController::class,'data'])->name('data');
//                Route::post('po-pending',[PenggunaanBarangController::class,'dataPoPending'])->name('po-pending');
//                Route::post('po-pending/products',[PenggunaanBarangController::class,'dataPoPendingProducts'])->name('po-pending.products');
//                Route::post('product-list',[PenggunaanBarangController::class,'getProductList'])->name('product-list');
//                Route::post('store',[PenggunaanBarangController::class,'store'])->name('store');
//            }
//        );
//    }
//);

Route::group(
    [
        'prefix' => 'admin/stock/store-requisition',
        'as' => 'admin.stock.store-requisition.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[StoreRequisitionController::class,'index'])->name('index');
                Route::get('create',[StoreRequisitionController::class,'indexCreate'])->name('create');
                Route::get('info/{id}',[StoreRequisitionController::class,'indexInfo'])->name('info');
                Route::get('edit/{id}',[StoreRequisitionController::class,'indexEdit'])->name('edit');
                Route::get('verification/{id}',[StoreRequisitionController::class,'indexVerification'])->name('verification');
                Route::get('invoice/{id}',[StoreRequisitionController::class,'indexPdf'])->name('invoice');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[StoreRequisitionController::class,'data'])->name('data');
                Route::post('po-pending',[StoreRequisitionController::class,'dataPoPending'])->name('po-pending');
                Route::post('po-pending/products',[StoreRequisitionController::class,'dataPoPendingProducts'])->name('po-pending.products');
                Route::post('product-list',[StoreRequisitionController::class,'getProductList'])->name('product-list');
                Route::post('stored-product',[StoreRequisitionController::class,'getStoredProduct'])->name('stored-product');
                Route::post('store',[StoreRequisitionController::class,'store'])->name('store');
                Route::post('store-edit',[StoreRequisitionController::class,'storeEdit'])->name('store-edit');
                Route::post('store-catatan',[StoreRequisitionController::class,'storeCatatan'])->name('store-catatan');
                Route::post('store-verification',[StoreRequisitionController::class,'storeVerification'])->name('store-verification');
                Route::post('delete',[StoreRequisitionController::class,'delete'])->name('delete');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/purchasing/invoicing',
        'as' => 'admin.purchasing.invoicing.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[InvoicingController::class,'index'])->name('index');
                Route::get('detail/{id}',[InvoicingController::class,'indexDetail'])->name('detail');
                Route::get('process/{id}',[InvoicingController::class,'indexInvoicingProcess'])->name('info');
//                Route::get('edit/{id}',[InvoicingController::class,'indexEdit'])->name('edit');
//                Route::get('verification/{id}',[InvoicingController::class,'indexVerification'])->name('verification');
                Route::get('invoice/details/{id}',[InvoicingController::class,'indexDetailPdf'])->name('invoice-details');
                Route::get('invoice/summary/{id}',[InvoicingController::class,'indexSummaryPdf'])->name('invoice-summary');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[InvoicingController::class,'data'])->name('data');
                Route::post('products',[InvoicingController::class,'getProducts'])->name('products');
//                Route::post('po-pending',[InvoicingController::class,'dataPoPending'])->name('po-pending');
//                Route::post('po-pending/products',[InvoicingController::class,'dataPoPendingProducts'])->name('po-pending.products');
//                Route::post('product-list',[InvoicingController::class,'getProductList'])->name('product-list');
//                Route::post('stored-product',[InvoicingController::class,'getStoredProduct'])->name('stored-product');
                Route::post('store',[InvoicingController::class,'store'])->name('store');
//                Route::post('store-catatan',[InvoicingController::class,'storeCatatan'])->name('store-catatan');
//                Route::post('store-verification',[InvoicingController::class,'storeVerification'])->name('store-verification');
                Route::post('delete',[InvoicingController::class,'delete'])->name('delete');
            }
        );
    }
);
