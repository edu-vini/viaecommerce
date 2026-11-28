<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Internal\CaddyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\InitializeStoreTenancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/internal/caddy/ask', [CaddyController::class, 'ask'])->name('internal.caddy.ask');

Route::middleware([InitializeStoreTenancy::class])->group(function () {
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

    // Fallback for native POST submissions to /admin/login (prevents 405 when JS is blocked or delayed)
    Route::post('/admin/login', function (Request $request) {
        $data = $request->input('data', []);
        $email = $data['email'] ?? $request->input('email');
        $password = $data['password'] ?? $request->input('password');
        $remember = (bool) ($data['remember'] ?? $request->input('remember', false));

        if ($email && $password && Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        return redirect('/admin/login')->withErrors([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    })->middleware(['web'])->name('admin.login.post');
});
