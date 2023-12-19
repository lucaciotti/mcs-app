<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('auth.login');
// })->middleware(['auth']);

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/products', [App\Http\Controllers\ProductsController::class, 'index'])->name('products');
    Route::get('/warehouses', [App\Http\Controllers\WarehouseController::class, 'index'])->name('warehouses');
    Route::get('/warehouses/{id}/ubications', [App\Http\Controllers\WarehouseController::class, 'indexUbic'])->name('ubications');

    Route::get('/config/inventory/sesions', [App\Http\Controllers\InventoryController::class, 'confSessions'])->name('inventory_sessions');
    Route::get('/inventory/tickets', [App\Http\Controllers\InventoryController::class, 'invTickets'])->name('inventory_tickets');
    Route::get('/inventory/measurements', [App\Http\Controllers\InventoryController::class, 'invMeasurements'])->name('inventory_measurements');
});

Route::name('user::')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class)->middleware(['auth']);
    Route::get('/actLike/{id}', [App\Http\Controllers\UserController::class, 'actLike'])->name('actLike')->middleware(['auth']);
    Route::get('/resetPassword/{id}', [App\Http\Controllers\UserController::class, 'sendResetPassword'])->name('resetPassword');
});