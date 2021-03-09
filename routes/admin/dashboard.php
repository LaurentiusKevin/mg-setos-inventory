<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['auth']
    ],
    function () {
        Route::group(
            ['as' => 'view.'],
            function () {
                Route::get('/',[DashboardController::class,'index'])->name('index');
            }
        );

        Route::group(
            ['prefix' => 'api', 'as' => 'api.'],
            function () {
            }
        );
    }
);
