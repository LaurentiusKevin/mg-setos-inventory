<?php

use App\Http\Controllers\Admin\MasterData\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/master-data/user-role',
        'as' => 'admin.master-data.user-role.',
//        'middleware' => ['auth']
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
        'prefix' => 'admin/system-utility/user-sistem',
        'as' => 'admin.system-utility.user-sistem.',
//        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[UserAplikasiController::class,'index'])->name('index');
                Route::get('create',[UserAplikasiController::class,'createIndex'])->name('create');
                Route::get('edit/{id}',[UserAplikasiController::class,'editIndex'])->name('edit');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('data',[UserAplikasiController::class,'data'])->name('data');
                Route::post('store',[UserAplikasiController::class,'store'])->name('store');
                Route::post('store-edit',[UserAplikasiController::class,'editSubmit'])->name('store-edit');
                Route::post('delete',[UserAplikasiController::class,'delete'])->name('delete');
                Route::post('reset-password',[UserAplikasiController::class,'resetPassword'])->name('reset-password');
            }
        );
    }
);
