<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FileUploadController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


#Route::post('/upload', [ProductController::class, 'upload'])->name('upload');

Route::get('/', [FileUploadController::class, 'showForm']);
Route::post('/upload', [FileUploadController::class, 'uploadFile']);
