<?php

use App\Http\Controllers\Admin\Laporan\InvoicingPerDepartmentController;
use App\Http\Controllers\Admin\Laporan\MutasiStockController;
use App\Http\Controllers\Admin\Laporan\ProductStockController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/laporan/invoicing-per-department',
        'as' => 'admin.laporan.invoicing-per-department.',
        'middleware' => ['auth']
    ],
    function () {
        Route::get('/',[InvoicingPerDepartmentController::class,'index'])->name('index');
        Route::post('datatable',[InvoicingPerDepartmentController::class,'datatable'])->name('datatable');
        Route::get('export-excel',[InvoicingPerDepartmentController::class,'exportExcel'])->name('export-excel');
    }
);

Route::group(
    [
        'prefix' => 'admin/laporan/mutasi-stock',
        'as' => 'admin.laporan.mutasi-stock.',
        'middleware' => ['auth']
    ],
    function () {
        Route::get('/',[MutasiStockController::class,'index'])->name('index');
        Route::post('datatable',[MutasiStockController::class,'datatable'])->name('datatable');
        Route::post('product-list',[MutasiStockController::class,'productList'])->name('product-list');
        Route::get('export-excel',[MutasiStockController::class,'exportExcel'])->name('export-excel');
    }
);

Route::group(
    [
        'prefix' => 'admin/laporan/product-stock',
        'as' => 'admin.laporan.product-stock.',
        'middleware' => ['auth']
    ],
    function () {
        Route::get('/',[ProductStockController::class,'index'])->name('index');
        Route::post('datatable',[ProductStockController::class,'datatable'])->name('datatable');
        Route::get('export-excel',[ProductStockController::class,'exportExcel'])->name('export-excel');
    }
);
