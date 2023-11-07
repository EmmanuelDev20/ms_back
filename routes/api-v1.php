<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/config', [ConfigController::class, 'index_api'])->name('api.config.index');

Route::get('/proyectos', [ProjectController::class, 'index_api'])->name('api.projects.index');

Route::get('/proyectos/{project}', [ProjectController::class, 'show_api'])->name('api.projects.show');
