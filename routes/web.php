<?php

use App\Http\Controllers\AgregadonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
   
    Route::get('admin/consulta-orders/', [InicioController::class, 'pedidos'])->name('admin.consulta');

    Route::get('admin/', [InicioController::class, 'index'])->name('admin');
    Route::get('admin/order-{order:id}/edit', [InicioController::class, 'show'])->name('admin.show.order');

    Route::get('admin/cocina', [OrderController::class, 'cocina'])->name('admin.cocina');
    Route::get('admin/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('admin/orders/{mesa}/create', [OrderController::class, 'create'])->name('admin.orders.create')->middleware('verifiedmesa');
    Route::get('admin/orders/{order}/', [OrderController::class, 'show'])->name('admin.orders.show')->middleware('verifiedempresa');
    Route::get('admin/productos', [ProductoController::class, 'index'])->name('admin.productos');
    Route::get('admin/clientes', [ClientController::class, 'index'])->name('admin.clientes');
    Route::get('admin/agregados', [AgregadonController::class, 'index'])->name('admin.agregados');
    Route::get('admin/categorias', [CategoryController::class, 'index'])->name('admin.categorias');
    Route::get('admin/cupones', [CuponController::class, 'index'])->name('admin.cupones');
    Route::get('admin/locations', [LocationController::class, 'index'])->name('admin.locations');
    Route::get('admin/empresa', [EmpresaController::class, 'index'])->name('admin.empresa');
    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('admin/roles', [UserController::class, 'roles'])->name('admin.roles');
    Route::get('admin/permisos', [UserController::class, 'permisos'])->name('admin.permisos');
    Route::get('admin/empresa/configuracion', [EmpresaController::class, 'configuracion'])->name('admin.empresa.configuracion');
    Route::get('admin/comprobantes', [ComprobanteController::class, 'index'])->name('admin.comprobantes');
});
