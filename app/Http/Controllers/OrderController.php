<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(string $code)
    {
        $order = session('latest_order_' . $code);

        if (!$order) {
            // Provide sensible fallback demo order if accessed directly
            $order = [
                'code' => $code,
                'customer_name' => 'Comprador Exemplar',
                'email' => 'cliente@viaecommerce.com.br',
                'payment_method' => 'pix',
                'shipping_method' => 'ViaExpress (Entrega Rápida)',
                'shipping_address' => 'Av. Paulista, 1000, Apto 42 - Bela Vista, São Paulo - SP',
                'shipping_cep' => '01310-100',
                'items' => [
                    [
                        'id' => 1,
                        'title' => 'Smart TV 55" 4K UHD Crystal HDR com Alexa',
                        'price' => 2499.00,
                        'quantity' => 1,
                        'variation' => 'Voltagem: Bivolt (110V/220V)',
                        'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=600&q=80',
                    ]
                ],
                'subtotal' => 2499.00,
                'discount' => 124.95,
                'shipping_cost' => 0.00,
                'total' => 2374.05,
                'created_at' => now()->format('d/m/Y H:i'),
                'status' => 'aguardando_pagamento',
                'pix_code' => '00020126580014BR.GOV.BCB.PIX0136e4b98687-3532-474c-83b5-779899f8fba35204000053039865802BR5920ViaEcommerce Pagamentos6009Sao Paulo62070503***6304E8A2',
            ];
        }

        return view('pages.order-success', [
            'order' => $order
        ]);
    }
}
