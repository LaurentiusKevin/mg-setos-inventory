<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/login',
        'as' => 'admin.'
    ],
    function () {
        Route::group(
            [
                'middleware' => ['guest']
            ],
            function () {
                Route::get('/',[LoginController::class,'index'])->name('view.login');
                Route::post('submit',[LoginController::class,'tryLogin'])->name('api.submit');
            }
        );

        Route::group(
            [
                'middleware' => ['auth']
            ],
            function () {
                Route::get('logout',[LoginController::class,'logout'])->name('api.logout');
                Route::post('ganti-password',[LoginController::class,'gantiPassword'])->name('api.ganti-password');
                Route::post('ganti-password/store',[LoginController::class,'storeNewPassword'])->name('api.store-new-password');
            }
        );
    }
);
