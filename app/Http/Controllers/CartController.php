<?php

namespace App\Http\Controllers;

use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $allProducts = EcommerceDataService::getProducts();
        $recommendedProducts = array_slice($allProducts, 0, 4);

        return view('pages.cart', [
            'recommendedProducts' => $recommendedProducts
        ]);
    }
}
