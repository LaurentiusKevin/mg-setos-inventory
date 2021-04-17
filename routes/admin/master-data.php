<?php

use App\Http\Controllers\Admin\MasterData\DepartmentController;
use App\Http\Controllers\Admin\MasterData\SatuanProductController;
use App\Http\Controllers\Admin\MasterData\StoreRequisitionVerificatorController;
use App\Http\Controllers\Admin\MasterData\UserAplikasiController;
use App\Http\Controllers\Admin\MasterData\UserRoleController;
use App\Http\Controllers\Admin\Stock\ProductListController;
use App\Http\Controllers\Admin\Stock\SupplierController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/master-data/user-role',
        'as' => 'admin.master-data.user-role.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[UserRoleController::class,'index'])->name('index');
                Route::get('create',[UserRoleController::class,'createIndex'])->name('create');
                Route::get('edit/{id}',[UserRoleController::class,'editIndex'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[UserRoleController::class,'data'])->name('data');
                Route::post('store',[UserRoleController::class,'store'])->name('store');
                Route::post('store-edit',[UserRoleController::class,'editSubmit'])->name('store-edit');
                Route::post('delete',[UserRoleController::class,'delete'])->name('delete');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/master-data/user-aplikasi',
        'as' => 'admin.master-data.user-aplikasi.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[UserAplikasiController::class,'index'])->name('index');
                Route::get('create',[UserAplikasiController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[UserAplikasiController::class,'indexEdit'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[UserAplikasiController::class,'data'])->name('data');
                Route::post('store',[UserAplikasiController::class,'store'])->name('store');
                Route::post('delete',[UserAplikasiController::class,'delete'])->name('delete');
                Route::post('reset-password',[UserAplikasiController::class,'resetPassword'])->name('reset-password');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/master-data/satuan-produk',
        'as' => 'admin.master-data.satuan-produk.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[SatuanProductController::class,'index'])->name('index');
                Route::get('create',[SatuanProductController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[SatuanProductController::class,'indexEdit'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[SatuanProductController::class,'data'])->name('data');
                Route::post('store',[SatuanProductController::class,'store'])->name('store');
                Route::post('delete',[SatuanProductController::class,'delete'])->name('delete');
                Route::post('reset-password',[SatuanProductController::class,'resetPassword'])->name('reset-password');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/master-data/department',
        'as' => 'admin.master-data.department.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[DepartmentController::class,'index'])->name('index');
                Route::get('create',[DepartmentController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[DepartmentController::class,'indexEdit'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[DepartmentController::class,'data'])->name('data');
                Route::post('store',[DepartmentController::class,'store'])->name('store');
                Route::post('delete',[DepartmentController::class,'delete'])->name('delete');
                Route::post('reset-password',[DepartmentController::class,'resetPassword'])->name('reset-password');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/master-data/sr-verificator',
        'as' => 'admin.master-data.sr-verificator.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[StoreRequisitionVerificatorController::class,'index'])->name('index');
                Route::post('create',[StoreRequisitionVerificatorController::class,'indexCreate'])->name('create');
                Route::get('edit/{id}',[StoreRequisitionVerificatorController::class,'indexEdit'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[StoreRequisitionVerificatorController::class,'data'])->name('data');
                Route::post('list-user',[StoreRequisitionVerificatorController::class,'listUser'])->name('list-user');
                Route::post('store',[StoreRequisitionVerificatorController::class,'store'])->name('store');
                Route::post('delete',[StoreRequisitionVerificatorController::class,'delete'])->name('delete');
                Route::post('reset-password',[StoreRequisitionVerificatorController::class,'resetPassword'])->name('reset-password');
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'admin/master-data/supplier',
        'as' => 'admin.master-data.supplier.',
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
        'prefix' => 'admin/master-data/product-list',
        'as' => 'admin.master-data.product-list.',
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
