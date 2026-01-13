<?php

use App\Http\Controllers\ViewerModeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/viewer', [ViewerModeController::class, 'start'])
    ->name('viewer.start');

Route::get('admin/viewer/exit', [ViewerModeController::class, 'stop'])
    ->name('viewer.stop');
