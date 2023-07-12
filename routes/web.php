<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutorialsController;
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

Route::get('/', [TutorialsController::class,'index']);
Route::post('/save', [TutorialsController::class,'create']);
Route::post('/upload', [TutorialsController::class,'store']);
Route::get('/get-data', [TutorialsController::class,'show']);
Route::post('/update', [TutorialsController::class,'update']);
Route::post('/delete', [TutorialsController::class,'destroy']);

