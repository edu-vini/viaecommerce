<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorePortalController extends Controller
{
    /**
     * Display the central ecommerce portal listing all active storefronts with live previews.
     */
    public function index(Request $request): View
    {
        if (tenancy()->initialized) {
            tenancy()->end();
        }

        $baseDomain = (string) config('tenancy.base_domain', 'viasolutions.com.br');
        $scheme = $request->getScheme();

        $tenants = Tenant::query()
            ->with('domains')
            ->where('is_active', true)
            ->whereNotIn('status', ['paused', 'suspended'])
            ->orderBy('name')
            ->get();

        $tenantIds = $tenants->pluck('id')->all();

        $settingsByTenant = StoreSetting::withoutGlobalScopes()
            ->whereIn('tenant_id', $tenantIds)
            ->get()
            ->keyBy('tenant_id');

        $bannersByTenant = Banner::withoutGlobalScopes()
            ->whereIn('tenant_id', $tenantIds)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('tenant_id');

        $productCountsByTenant = Product::withoutGlobalScopes()
            ->whereIn('tenant_id', $tenantIds)
            ->where('is_active', true)
            ->selectRaw('tenant_id, count(*) as total')
            ->groupBy('tenant_id')
            ->pluck('total', 'tenant_id');

        $categoryCountsByTenant = Category::withoutGlobalScopes()
            ->whereIn('tenant_id', $tenantIds)
            ->where('is_active', true)
            ->selectRaw('tenant_id, count(*) as total')
            ->groupBy('tenant_id')
            ->pluck('total', 'tenant_id');

        $productsByTenant = Product::withoutGlobalScopes()
            ->whereIn('tenant_id', $tenantIds)
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->get(['id', 'tenant_id', 'title', 'price', 'images'])
            ->groupBy('tenant_id');

        $stores = $tenants->map(function (Tenant $tenant) use (
            $baseDomain,
            $scheme,
            $settingsByTenant,
            $bannersByTenant,
            $productCountsByTenant,
            $categoryCountsByTenant,
            $productsByTenant,
        ): array {
            /** @var StoreSetting|null $setting */
            $setting = $settingsByTenant->get($tenant->id);
            $tenantBanners = $bannersByTenant->get($tenant->id, collect());
            $heroBanner = $tenantBanners->firstWhere('position', 'home_hero') ?? $tenantBanners->first();
            $tenantProducts = $productsByTenant->get($tenant->id, collect())->take(3);

            $primaryDomain = $tenant->domains->first()?->domain;
            $domain = $primaryDomain ?: "{$tenant->slug}.{$baseDomain}";
            $url = "{$scheme}://{$domain}";

            $storeName = $setting?->store_name ?: $tenant->name;
            $slogan = $setting?->slogan ?: 'Loja online ViaSolutions';
            $logoUrl = $this->resolveAssetUrl($setting?->logo_path);

            $heroImage = $this->resolveAssetUrl($heroBanner?->image);
            if (! $heroImage && $tenantProducts->isNotEmpty()) {
                $firstProductImages = $tenantProducts->first()?->images;
                $firstProductImage = is_array($firstProductImages) ? ($firstProductImages[0] ?? null) : null;
                $heroImage = $this->resolveAssetUrl($firstProductImage);
            }

            $sampleProducts = $tenantProducts->map(function (Product $product): array {
                $images = $product->images;
                $firstImage = is_array($images) ? ($images[0] ?? null) : null;

                return [
                    'title' => $product->title,
                    'price' => (float) $product->price,
                    'image' => $this->resolveAssetUrl($firstImage),
                ];
            })->values()->all();

            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'plan' => $tenant->plan ?? 'starter',
                'domain' => $domain,
                'url' => $url,
                'store_name' => $storeName,
                'slogan' => $slogan,
                'logo_url' => $logoUrl,
                'primary_color' => $setting?->primary_color ?: '#0f172a',
                'accent_color' => $setting?->accent_color ?: '#c49a45',
                'surface_color' => $setting?->surface_color ?: '#faf8f5',
                'hero_image' => $heroImage,
                'hero_title' => $heroBanner?->title ?: $storeName,
                'products_count' => (int) ($productCountsByTenant[$tenant->id] ?? 0),
                'categories_count' => (int) ($categoryCountsByTenant[$tenant->id] ?? 0),
                'sample_products' => $sampleProducts,
            ];
        })->values()->all();

        return view('pages.portal', [
            'stores' => $stores,
            'superAdminUrl' => url('/superadmin'),
        ]);
    }

    protected function resolveAssetUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
