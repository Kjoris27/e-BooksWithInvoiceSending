<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

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

Route::get('/home', [BookController::class, 'index']);
Route::get('/shopping-cart', [BookController::class, 'bookCart'])->name('shopping.cart');
Route::get('/book/{id}', [BookController::class, 'addBooktoCart'])->name('addbook.to.cart');
// Route::patch('/update-shopping-cart', [BookController::class, 'updateCart'])->name('update.sopping.cart');
Route::delete('/delete-cart-product', [BookController::class, 'deleteProduct'])->name('delete.cart.product');
Route::get('/checkout', [BookController::class, 'checkout'])->name('checkout');
// Route::get('/send-invoice', [BookController::class, 'sendInvoiceForm'])->name('send.invoice');
// Route::post('/send-invoice', [BookController::class, 'sendInvoice'])->name('send.invoice.post');
Route::post('/send-invoice', [BookController::class, 'sendInvoice'])->name('send-invoice');
Route::get('/send-invoice-form', [BookController::class, 'showInvoiceForm'])->name('send-invoice-form');
Route::post('/sendBillingEmail', [BookController::class, 'sendBillingEmail'])->name('sendBillingEmail');
Route::post('/process-checkout', [BookController::class, 'processCheckout'])->name('process.checkout');
