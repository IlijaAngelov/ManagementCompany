<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/import', function () {
//     return view('import');
// });

Route::post('/import', [ShiftController::class, 'importShifts'])->name('import');


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

