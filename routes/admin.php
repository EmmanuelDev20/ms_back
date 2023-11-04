<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/configuracion', [ConfigController::class, 'index'])->name('config.index');

Route::put('/configuracion/{config}', [ConfigController::class, 'update'])->name('config.update');

Route::get('/proyectos', [ProjectController::class, 'index'])->name('project.index');

Route::get('/proyectos/crear', [ProjectController::class, 'create'])->name('project.create');

Route::post('/proyectos', [ProjectController::class, 'store'])->name('project.store');

Route::get('/proyectos/{project}/editar', [ProjectController::class, 'edit'])->name('project.edit');

Route::put('/proyectos/{project}', [ProjectController::class, 'update'])->name('project.update');

Route::delete('/proyectos/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');

Route::get('/proyectos/{project}/images', [ProjectController::class, 'images'])->name('project.images');

Route::post('/proyectos/images', [ProjectController::class, 'storeimages'])->name('project.images.store');

// Route::put('/proyectos/{project}/images', [ProjectController::class, 'storeimages'])->name('project.images.update');
