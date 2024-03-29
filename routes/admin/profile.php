<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin/profile',
        'as' => 'admin.profile.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[ProfileController::class,'index'])->name('index');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
                Route::post('store-profile',[ProfileController::class,'storeProfile'])->name('store-profile');
                Route::post('store-password',[ProfileController::class,'storePassword'])->name('store-password');

                Route::post('telegram-test-message',[ProfileController::class,'sendTestMessage'])->name('telegram-test-message');
            }
        );
    }
);

Route::post('admin/profile/api/generate-telegram',[ProfileController::class,'generateTelegram'])->name('admin.profile.api.generate-telegram');
Route::get('admin/profile/api/generate-telegram',[ProfileController::class,'generateTelegram'])->name('admin.profile.api.generate-telegram');
