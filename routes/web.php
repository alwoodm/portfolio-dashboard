<?php

use App\Http\Controllers\ViewerModeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('admin/viewer', [ViewerModeController::class, 'start'])
    ->name('viewer.start');

Route::get('admin/viewer/exit', [ViewerModeController::class, 'stop'])
    ->name('viewer.stop');
