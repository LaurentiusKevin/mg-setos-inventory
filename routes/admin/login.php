<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/login',
        'as' => 'admin.',
        'middleware' => ['guest']
    ],
    function () {
        Route::get('/',[LoginController::class,'index'])->name('view.login');
        Route::post('submit',[LoginController::class,'tryLogin'])->name('api.submit');
    }
);

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['auth']
    ],
    function () {
        Route::get('logout',[LoginController::class,'logout'])->name('logout');
        Route::post('ganti-password',[LoginController::class,'gantiPassword'])->name('api.ganti-password');
        Route::post('ganti-password/store',[LoginController::class,'storeNewPassword'])->name('api.store-new-password');
    }
);
