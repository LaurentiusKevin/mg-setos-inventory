<?php

use App\Http\Controllers\Admin\Laporan\InvoicingPerDepartmentController;
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
