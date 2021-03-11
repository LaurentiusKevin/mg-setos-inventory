<?php

use App\Http\Controllers\Admin\Stock\ProductListController;
use Illuminate\Support\Facades\Route;

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
