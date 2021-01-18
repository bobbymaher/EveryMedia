<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();
// we get redirected here after registration @todo redirect to /
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/upload', [App\Http\Controllers\UploadController::class, 'fileUpload'])->name('file.upload.post');


Route::delete('/delete', [App\Http\Controllers\FileController::class, 'delete'])->name('file.delete');
Route::put('/rename', [App\Http\Controllers\FileController::class, 'rename'])->name('file.rename');
