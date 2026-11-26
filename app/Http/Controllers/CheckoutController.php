<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('pages.checkout');
    }

    public function process(Request $request)
    {
        // Generate mock confirmed order
        $orderCode = 'VIA-'.strtoupper(Str::random(8));

        $orderData = [
            'code' => $orderCode,
            'customer_name' => $request->input('name', 'Comprador Exemplar'),
            'email' => $request->input('email', 'cliente@viaecommerce.com.br'),
            'payment_method' => $request->input('payment_method', 'pix'),
            'shipping_method' => $request->input('shipping_method', 'PAC'),
            'shipping_address' => $request->input('address', 'Av. Paulista, 1000 - Bela Vista, São Paulo - SP'),
            'shipping_cep' => $request->input('cep', '01310-100'),
            'items' => json_decode($request->input('cart_items', '[]'), true),
            'subtotal' => (float) $request->input('subtotal', 0),
            'discount' => (float) $request->input('discount', 0),
            'shipping_cost' => (float) $request->input('shipping_cost', 0),
            'total' => (float) $request->input('total', 0),
            'created_at' => now()->format('d/m/Y H:i'),
            'status' => 'aguardando_pagamento',
            'pix_code' => '00020126580014BR.GOV.BCB.PIX0136'.Str::uuid().'5204000053039865802BR5920ViaEcommerce Pagamentos6009Sao Paulo62070503***6304'.strtoupper(Str::random(4)),
        ];

        session(['latest_order_'.$orderCode => $orderData]);

        return redirect()->route('order.show', ['code' => $orderCode]);
    }
}
