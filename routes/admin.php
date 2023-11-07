<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::post('/hookNetlify', [HomeController::class, 'hookNetlify'])->name('hookNetlify');

Route::get('/configuracion', [ConfigController::class, 'index'])->name('config.index');

Route::put('/configuracion/{config}', [ConfigController::class, 'update'])->name('config.update');

Route::get('/proyectos', [ProjectController::class, 'index'])->name('project.index');

Route::get('/proyectos/crear', [ProjectController::class, 'create'])->name('project.create');

Route::post('/proyectos', [ProjectController::class, 'store'])->name('project.store');

Route::get('/proyectos/{project}/editar', [ProjectController::class, 'edit'])->name('project.edit');

Route::put('/proyectos/{project}', [ProjectController::class, 'update'])->name('project.update');

Route::delete('/proyectos/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');

Route::get('/proyectos/{project}/images', [ImagesController::class, 'index'])->name('images.index');

Route::post('/proyectos/{project}/images', [ImagesController::class, 'store'])->name('images.store');

Route::delete('/proyectos/{image}/images/{project}', [ImagesController::class, 'destroy'])->name('images.destroy');

Route::get('/locale/{locale}', function($locale) {
    App::setLocale($locale);
});


