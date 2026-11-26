<?php

namespace App\Http\Controllers;

use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null)
    {
        $categories = EcommerceDataService::getCategories();
        $products = EcommerceDataService::getProducts();

        $selectedCategory = $categorySlug ?? $request->query('categoria');
        $search = trim($request->query('busca', ''));
        $priceMin = $request->query('preco_min') ? (float) $request->query('preco_min') : null;
        $priceMax = $request->query('preco_max') ? (float) $request->query('preco_max') : null;
        $sort = $request->query('ordenacao', 'relevancia');

        // Filter by category
        if ($selectedCategory) {
            $products = array_filter($products, fn ($p) => $p['category_slug'] === $selectedCategory);
        }

        // Filter by search query
        if ($search !== '') {
            $products = array_filter($products, function ($p) use ($search) {
                return stripos($p['title'], $search) !== false
                    || stripos($p['brand'], $search) !== false
                    || stripos($p['category_name'], $search) !== false
                    || stripos($p['summary'], $search) !== false;
            });
        }

        // Filter by price range
        if ($priceMin !== null) {
            $products = array_filter($products, fn ($p) => $p['price'] >= $priceMin);
        }
        if ($priceMax !== null) {
            $products = array_filter($products, fn ($p) => $p['price'] <= $priceMax);
        }

        // Sorting
        $products = array_values($products);
        usort($products, function ($a, $b) use ($sort) {
            switch ($sort) {
                case 'menor_preco':
                    return $a['price'] <=> $b['price'];
                case 'maior_preco':
                    return $b['price'] <=> $a['price'];
                case 'mais_vendidos':
                    return ($b['is_best_seller'] ? 1 : 0) <=> ($a['is_best_seller'] ? 1 : 0);
                case 'desconto':
                    $discountA = $a['original_price'] - $a['price'];
                    $discountB = $b['original_price'] - $b['price'];

                    return $discountB <=> $discountA;
                default:
                    return $b['rating'] <=> $a['rating'];
            }
        });

        $currentCategoryObj = null;
        if ($selectedCategory) {
            foreach ($categories as $cat) {
                if ($cat['slug'] === $selectedCategory) {
                    $currentCategoryObj = $cat;
                    break;
                }
            }
        }

        return view('pages.catalog', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'currentCategoryObj' => $currentCategoryObj,
            'search' => $search,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'sort' => $sort,
            'totalCount' => count($products),
        ]);
    }

    public function predictiveSearch(Request $request)
    {
        $query = trim($request->query('q', ''));
        if (mb_strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'categories' => [],
            ]);
        }

        $allProducts = EcommerceDataService::getProducts();
        $allCategories = EcommerceDataService::getCategories();

        $matchingProducts = array_filter($allProducts, function ($p) use ($query) {
            return stripos($p['title'], $query) !== false
                || stripos($p['brand'], $query) !== false
                || stripos($p['category_name'], $query) !== false;
        });

        $matchingCategories = array_filter($allCategories, function ($c) use ($query) {
            return stripos($c['name'], $query) !== false;
        });

        return response()->json([
            'products' => array_slice(array_values($matchingProducts), 0, 5),
            'categories' => array_slice(array_values($matchingCategories), 0, 3),
        ]);
    }
}
