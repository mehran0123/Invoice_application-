<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CutomerController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('get_invoices',[InvoiceController::class,'getInvoices']);
Route::get('search_invoice',[InvoiceController::class,'getFilterInvoice']);
Route::get('create_invoice',[InvoiceController::class,'createInvoice']);
Route::get('customers',[CutomerController::class,'allCustomers']);
Route::post('add_invoice',[InvoiceController::class,'addInvoice']);
Route::get('show_invoice/{id}',[InvoiceController::class,'showInvoice']);
Route::get('edit_invoice/{id}',[InvoiceController::class,'editInvoice']);
Route::get('deleteInvoiceItem/{id}',[InvoiceController::class,'deleteInvoiceItem']);
Route::get('deleteInvoice/{id}',[InvoiceController::class,'deleteInvoice']);
Route::post('update_invoice/{id}',[InvoiceController::class,'updateInvoice']);



Route::get('/products',[ProductController::class,'allProducts']);
