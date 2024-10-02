<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/files', [FileController::class, 'index']);
Route::get('/files/create', [FileController::class, 'create']);
Route::post('/files/store', [FileController::class, 'store'])->name('file.upload.post');
Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');