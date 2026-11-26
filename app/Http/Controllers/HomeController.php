<?php

namespace App\Services;

namespace App\Http\Controllers;

use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = EcommerceDataService::getCategories();
        $allProducts = EcommerceDataService::getProducts();

        $featuredProducts = array_filter($allProducts, fn($p) => $p['is_featured']);
        $deals = array_filter($allProducts, fn($p) => $p['is_deal']);
        $bestSellers = array_filter($allProducts, fn($p) => $p['is_best_seller']);
        $newArrivals = array_filter($allProducts, fn($p) => $p['is_new']);

        return view('pages.home', [
            'categories' => $categories,
            'featuredProducts' => array_values($featuredProducts),
            'deals' => array_values($deals),
            'bestSellers' => array_values($bestSellers),
            'newArrivals' => array_values($newArrivals),
        ]);
    }
}
