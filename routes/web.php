<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyDashboardController;

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

Route::any('/', [MyDashboardController::class, 'index']);
Route::get('/customerImportFromExcel', [MyDashboardController::class, 'customerImportFromExcel'])->name('customerImportFromExcel');
Route::post('/postCustomerImportFromExcel', [MyDashboardController::class, 'postCustomerImportFromExcel'])->name('postCustomerImportFromExcel');
Route::get('/distributorImportFromExcel', [MyDashboardController::class, 'distributorImportFromExcel'])->name('distributorImportFromExcel');
Route::post('/postDistributorImportFromExcel', [MyDashboardController::class, 'postDistributorImportFromExcel'])->name('postDistributorImportFromExcel');
Route::get('/orderImportFromExcel', [MyDashboardController::class, 'orderImportFromExcel'])->name('orderImportFromExcel');
Route::post('/postOrderImportFromExcel', [MyDashboardController::class, 'postOrderImportFromExcel'])->name('postOrderImportFromExcel');
