<?php

namespace App\Http\Controllers;

use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = EcommerceDataService::findBySlug($slug);

        if (! $product) {
            abort(404, 'Produto não encontrado');
        }

        $allProducts = EcommerceDataService::getProducts();
        $relatedProducts = array_filter($allProducts, function ($p) use ($product) {
            return $p['id'] !== $product['id'] && $p['category_slug'] === $product['category_slug'];
        });

        if (empty($relatedProducts)) {
            $relatedProducts = array_filter($allProducts, fn ($p) => $p['id'] !== $product['id']);
        }

        // Installments Table Calculation (up to 12x without interest)
        $installmentsTable = [];
        for ($i = 1; $i <= $product['installments']; $i++) {
            $val = $product['price'] / $i;
            $installmentsTable[] = [
                'times' => $i,
                'value' => number_format($val, 2, ',', '.'),
                'total' => number_format($product['price'], 2, ',', '.'),
                'interest' => 'sem juros',
            ];
        }

        $pixPrice = $product['price'] * (1 - ($product['pix_discount_percent'] / 100));

        return view('pages.pdp', [
            'product' => $product,
            'relatedProducts' => array_slice(array_values($relatedProducts), 0, 4),
            'installmentsTable' => $installmentsTable,
            'pixPrice' => $pixPrice,
        ]);
    }

    public function calculateShipping(Request $request)
    {
        $cep = preg_replace('/\D/', '', $request->input('cep', ''));

        if (strlen($cep) !== 8) {
            return response()->json([
                'error' => 'Por favor, informe um CEP válido com 8 dígitos.',
            ], 422);
        }

        // Simulated accurate Brazilian shipping calculations
        return response()->json([
            'cep' => substr($cep, 0, 5).'-'.substr($cep, 5),
            'options' => [
                [
                    'name' => 'Correios PAC',
                    'deadline' => '5 a 7 dias úteis',
                    'price' => 19.90,
                    'price_formatted' => 'R$ 19,90',
                ],
                [
                    'name' => 'Correios Sedex',
                    'deadline' => '2 a 3 dias úteis',
                    'price' => 34.50,
                    'price_formatted' => 'R$ 34,50',
                ],
                [
                    'name' => 'ViaExpress (Entrega Rápida)',
                    'deadline' => 'Até 24h úteis',
                    'price' => 45.00,
                    'price_formatted' => 'R$ 45,00',
                    'badge' => 'Mais Rápido',
                ],
            ],
        ]);
    }
}
