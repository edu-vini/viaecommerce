<?php

namespace App\Http\Controllers;

class OrderController extends Controller
{
    public function show(string $code)
    {
        $order = session('latest_order_'.$code);

        if (! $order) {
            // Provide sensible fallback demo order for fashion store
            $order = [
                'code' => $code,
                'customer_name' => 'Mariana Silveira',
                'email' => 'mariana.silveira@email.com',
                'payment_method' => 'pix',
                'shipping_method' => 'Sedex Express',
                'shipping_address' => 'Av. Paulista, 1500, Apto 82 - Bela Vista, São Paulo - SP',
                'shipping_cep' => '01310-100',
                'items' => [
                    [
                        'id' => 1,
                        'title' => 'Vestido Midi Aurora em Linho Puro com Cinto Faixa',
                        'price' => 489.90,
                        'quantity' => 1,
                        'variation' => 'Tamanho: M (40) | Cor: Off-White Linho',
                        'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=600&q=80',
                    ],
                ],
                'subtotal' => 489.90,
                'discount' => 24.50,
                'shipping_cost' => 0.00,
                'total' => 465.40,
                'created_at' => now()->format('d/m/Y H:i'),
                'status' => 'aguardando_pagamento',
                'pix_code' => '00020126580014BR.GOV.BCB.PIX0136e4b98687-3532-474c-83b5-779899f8fba35204000053039865802BR5920Hadassa Concept6009Sao Paulo62070503***6304E8A2',
            ];
        }

        return view('pages.order-success', [
            'order' => $order,
        ]);
    }
}
