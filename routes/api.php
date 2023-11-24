<?php

use App\Http\Controllers\TimeslotsController;
use App\Http\Controllers\WorkersController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return new JsonResponse([
        'status' => 'success',
    ]);
});

Route::post('workers:search', [WorkersController::class, 'search']);

Route::post('timeslots', [TimeslotsController::class, 'create']);

