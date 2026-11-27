<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('pages.checkout');
    }

    public function process(Request $request)
    {
        $orderCode = 'EL-'.rand(1000, 9999);

        $cartItems = json_decode($request->input('cart_items', '[]'), true) ?: [];
        $subtotal = (float) $request->input('subtotal', 0);
        $discount = (float) $request->input('discount', 0);
        $shippingCost = (float) $request->input('shipping_cost', 0);
        $total = (float) $request->input('total', 0);

        $customerPayload = [
            'customer_name' => $request->input('name', 'Compradora Elegante'),
            'customer_email' => $request->input('email', 'cliente@elisie.com.br'),
            'customer_phone' => $request->input('phone', '(11) 98765-4321'),
            'customer_cpf' => $request->input('cpf'),
            'shipping_zipcode' => $request->input('cep', '01419-001'),
            'shipping_address' => $request->input('address', 'Alameda Lorena'),
            'shipping_number' => $request->input('number', '1240'),
            'shipping_complement' => $request->input('complement'),
            'shipping_neighborhood' => $request->input('neighborhood', 'Jardins'),
            'shipping_city' => $request->input('city', 'São Paulo'),
            'shipping_state' => $request->input('state', 'SP'),
            'marketing_opt_in' => $request->boolean('marketing_opt_in', false),
        ];

        $customer = Customer::syncFromOrderData($customerPayload);

        $dbOrder = Order::create([
            'customer_id' => $customer->id,
            'code' => $orderCode,
            'customer_name' => $customerPayload['customer_name'],
            'customer_email' => $customerPayload['customer_email'],
            'customer_phone' => $customerPayload['customer_phone'],
            'customer_cpf' => $customerPayload['customer_cpf'],
            'shipping_zipcode' => $customerPayload['shipping_zipcode'],
            'shipping_address' => $customerPayload['shipping_address'],
            'shipping_number' => $customerPayload['shipping_number'],
            'shipping_complement' => $customerPayload['shipping_complement'],
            'shipping_neighborhood' => $customerPayload['shipping_neighborhood'],
            'shipping_city' => $customerPayload['shipping_city'],
            'shipping_state' => $customerPayload['shipping_state'],
            'shipping_service' => $request->input('shipping_method', 'SEDEX'),
            'shipping_cost' => $shippingCost,
            'payment_method' => $request->input('payment_method', 'pix'),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon_code' => $request->input('coupon'),
            'total' => $total,
            'status' => 'pending',
            'status_notes' => 'Aguardando confirmação do pagamento.',
            'status_updated_at' => now(),
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $dbOrder->id,
                'product_id' => $item['id'] ?? null,
                'product_title' => $item['title'] ?? 'Peça Elisie',
                'variation' => $item['variation'] ?? null,
                'sku' => $item['sku'] ?? null,
                'price' => (float) ($item['price'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 1),
                'total' => (float) (($item['price'] ?? 0) * ($item['quantity'] ?? 1)),
                'image' => $item['image'] ?? null,
            ]);
        }

        return redirect()->route('order.show', ['code' => $orderCode]);
    }
}
