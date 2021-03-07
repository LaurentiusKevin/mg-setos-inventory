<?php

use App\Http\Controllers\Admin\SystemUtility\MenuController;
use App\Http\Controllers\Admin\SystemUtility\MenuGroupController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/system-utility/menu-group',
        'as' => 'admin.system-utility.menu-group.',
//        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[MenuGroupController::class,'index'])->name('index');
                Route::get('create',[MenuGroupController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[MenuGroupController::class,'editIndex'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[MenuGroupController::class,'data'])->name('data');
                Route::post('store',[MenuGroupController::class,'storeData'])->name('store');
                Route::post('store-edit',[MenuGroupController::class,'storeEdit'])->name('store-edit');
                Route::post('show-unshow',[MenuGroupController::class,'showUnshow'])->name('show-unshow');
                Route::post('delete',[MenuGroupController::class,'delete'])->name('delete');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/system-utility/menu',
        'as' => 'admin.system-utility.menu.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[MenuController::class,'index'])->name('view.index');
                Route::get('create',[MenuController::class,'createIndex'])->name('view.create');
                Route::get('edit/{id}',[MenuController::class,'editIndex'])->name('view.edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[MenuController::class,'data'])->name('data');
                Route::post('store',[MenuController::class,'storeNew'])->name('store');
                Route::post('store-edit',[MenuController::class,'storeEdit'])->name('store-edit');
                Route::post('show-unshow',[MenuController::class,'showUnshow'])->name('show-unshow');
                Route::post('delete',[MenuController::class,'delete'])->name('delete');
            }
        );
    }
);
