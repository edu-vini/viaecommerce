<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function show(string $code)
    {
        try {
            $dbOrder = Order::with('items')->where('code', $code)->first();
        } catch (\Throwable) {
            $dbOrder = null;
        }

        if ($dbOrder) {
            $order = [
                'code' => $dbOrder->code,
                'customer_name' => $dbOrder->customer_name,
                'email' => $dbOrder->customer_email,
                'payment_method' => $dbOrder->payment_method,
                'shipping_method' => $dbOrder->shipping_service,
                'shipping_address' => "{$dbOrder->shipping_address}, {$dbOrder->shipping_number}".($dbOrder->shipping_complement ? " ({$dbOrder->shipping_complement})" : '')." - {$dbOrder->shipping_neighborhood}, {$dbOrder->shipping_city} - {$dbOrder->shipping_state}",
                'shipping_cep' => $dbOrder->shipping_zipcode,
                'tracking_code' => $dbOrder->tracking_code,
                'status' => $dbOrder->status,
                'status_label' => $dbOrder->status_label,
                'status_notes' => $dbOrder->status_notes,
                'status_updated_at' => $dbOrder->status_updated_at ? $dbOrder->status_updated_at->format('d/m/Y H:i') : null,
                'items' => $dbOrder->items->map(fn ($it) => [
                    'id' => $it->product_id,
                    'title' => $it->product_title,
                    'price' => (float) $it->price,
                    'quantity' => (int) $it->quantity,
                    'variation' => $it->variation,
                    'image' => $it->image ?: 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=600&q=80',
                ])->toArray(),
                'subtotal' => (float) $dbOrder->subtotal,
                'discount' => (float) $dbOrder->discount,
                'shipping_cost' => (float) $dbOrder->shipping_cost,
                'total' => (float) $dbOrder->total,
                'created_at' => $dbOrder->created_at->format('d/m/Y H:i'),
                'pix_code' => '00020126580014BR.GOV.BCB.PIX0136e4b98687-3532-474c-83b5-779899f8fba35204000053039865802BR5920ElisieConcept6009Sao Paulo62070503***6304E8A2',
            ];
        } else {
            $order = session('latest_order_'.$code);

            if (! $order) {
                // Fallback demo
                $order = [
                    'code' => $code,
                    'customer_name' => 'Mariana Silveira',
                    'email' => 'mariana.silveira@email.com',
                    'payment_method' => 'pix',
                    'shipping_method' => 'Sedex Express',
                    'shipping_address' => 'Av. Paulista, 1500, Apto 82 - Bela Vista, São Paulo - SP',
                    'shipping_cep' => '01310-100',
                    'tracking_code' => 'BR892183921SP',
                    'status' => 'pending',
                    'status_label' => 'Aguardando Pagamento',
                    'status_notes' => 'Aguardando confirmação do pagamento via Pix.',
                    'status_updated_at' => now()->format('d/m/Y H:i'),
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
                    'pix_code' => '00020126580014BR.GOV.BCB.PIX0136e4b98687-3532-474c-83b5-779899f8fba35204000053039865802BR5920ElisieConcept6009Sao Paulo62070503***6304E8A2',
                ];
            }
        }

        return view('pages.order-success', [
            'order' => $order,
        ]);
    }
}
