<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/categoria/{categorySlug}', [CatalogController::class, 'index'])->name('catalog.category');
Route::get('/api/busca-preditiva', [CatalogController::class, 'predictiveSearch'])->name('api.search.predictive');

Route::get('/produto/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/api/calcular-frete', [ProductController::class, 'calculateShipping'])->name('api.shipping.calculate');

Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/processar', [CheckoutController::class, 'process'])->name('checkout.process');

Route::get('/pedido/{code}', [OrderController::class, 'show'])->name('order.show');
