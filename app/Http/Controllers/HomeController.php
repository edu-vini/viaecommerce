<?php

namespace App\Http\Controllers;

use App\Services\EcommerceDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->getHost() === 'ecommerce.viasolutions.com.br') {
            return app(StorePortalController::class)->index($request);
        }
        $categories = EcommerceDataService::getCategories();
        $allProducts = EcommerceDataService::getProducts();

        $featuredProducts = array_filter($allProducts, fn ($p) => $p['is_featured']);
        $deals = array_filter($allProducts, fn ($p) => $p['is_deal']);
        $bestSellers = array_filter($allProducts, fn ($p) => $p['is_best_seller']);
        $newArrivals = array_filter($allProducts, fn ($p) => $p['is_new']);

        $heroBanners = EcommerceDataService::getBanners('home_hero');
        $featuredBanners = EcommerceDataService::getBanners('home_featured');

        return view('pages.home', [
            'categories' => $categories,
            'featuredProducts' => array_values($featuredProducts),
            'deals' => array_values($deals),
            'bestSellers' => array_values($bestSellers),
            'newArrivals' => array_values($newArrivals),
            'heroBanner' => $heroBanners[0] ?? null,
            'featuredBanner' => $featuredBanners[0] ?? null,
        ]);
    }
}
