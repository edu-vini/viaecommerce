<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\StoreSetting;
use App\Models\Tenant;
use App\Models\User;
use App\Services\EcommerceDataService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Platform SuperAdmin (Central)
        User::withoutGlobalScopes()->updateOrCreate(
            ['tenant_id' => null, 'email' => 'superadmin@viasolutions.com.br'],
            [
                'name' => 'SuperAdmin ViaSolutions',
                'password' => 'superadmin123',
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // 0.1 Default Tenant (Elisie Concept)
        $baseDomain = config('tenancy.base_domain', 'viasolutions.com.br');
        $tenant = Tenant::updateOrCreate(
            ['id' => 'elisie'],
            [
                'name' => 'Elisie Concept',
                'slug' => 'elisie',
                'email' => 'atendimento@elisie.com.br',
                'phone' => '(11) 99842-1200',
                'plan' => 'enterprise',
                'status' => 'active',
                'is_active' => true,
            ]
        );

        $tenant->domains()->firstOrCreate([
            'domain' => "elisie.{$baseDomain}",
        ]);

        tenancy()->initialize($tenant);

        // 1. Admin User (Tenant)
        User::updateOrCreate(
            ['email' => 'admin@elisie.com.br'],
            [
                'name' => 'Administrador Elisie',
                'password' => 'admin123',
                'is_super_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // 2. Store Settings / Branding
        StoreSetting::updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'store_name' => 'Elisie Concept',
                'slogan' => 'Moderna • Elegante • Modesta',
                'logo_path' => 'images/elisie-logo-bordeaux.png',
                'favicon_path' => 'images/elisie-logo-bordeaux.png',
                'contact_email' => 'atendimento@elisie.com.br',
                'contact_phone' => '(11) 99842-1200',
                'contact_whatsapp' => '5511998421200',
                'contact_address' => 'Av. Paulista, 1800 - Jardins, São Paulo - SP',
                'social_instagram' => '@elisieconcept',
                'social_facebook' => 'elisieconcept',
                'social_pinterest' => 'elisieconcept',
                'social_tiktok' => '@elisieconcept',
                'active_theme' => 'default',
                'primary_color' => '#720403',
                'primary_hover' => '#580302',
                'primary_light' => '#fdf8f8',
                'secondary_color' => '#1c1917',
                'accent_color' => '#c49a45',
                'accent_hover' => '#a88132',
                'surface_color' => '#faf8f5',
                'border_color' => '#eee5db',
            ]
        );

        // 3. Categories with Hierarchy (Parent & Subcategories)
        // Root Parent Categories
        $parentFashion = Category::updateOrCreate(
            ['slug' => 'moda-feminina'],
            [
                'name' => 'Moda Feminina Autoral',
                'icon' => 'sparkles',
                'description' => 'Alta costura, cortes refinados e tecidos nobres para a mulher moderna',
                'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $parentAlfaiataria = Category::updateOrCreate(
            ['slug' => 'alfaiataria-fina'],
            [
                'name' => 'Alfaiataria & Estruturados',
                'icon' => 'briefcase',
                'description' => 'Blazers, spencers e calças com forro de cetim e acabamento clássico',
                'image' => 'https://images.unsplash.com/photo-1548624149-f9b1859aa9d0?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Subcategories from Service
        $categoriesData = EcommerceDataService::getDefaultCategories();
        $categoryMap = [];

        foreach ($categoriesData as $idx => $cat) {
            $parentId = in_array($cat['slug'], ['alfaiataria', 'conjuntos']) ? $parentAlfaiataria->id : $parentFashion->id;

            $model = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'parent_id' => $parentId,
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                    'image' => $cat['featured_image'],
                    'is_active' => true,
                    'sort_order' => $idx + 10,
                ]
            );

            $categoryMap[$cat['slug']] = $model->id;
        }

        // 4. Products & Variations
        $productsData = EcommerceDataService::getDefaultProducts();

        foreach ($productsData as $prod) {
            $categoryId = $categoryMap[$prod['category_slug']] ?? null;

            $product = Product::updateOrCreate(
                ['slug' => $prod['slug']],
                [
                    'category_id' => $categoryId,
                    'title' => $prod['title'],
                    'sku' => $prod['variations']['options'][0]['sku'] ?? ('SKU-'.$prod['id']),
                    'brand' => $prod['brand'] ?? 'Elisie',
                    'badge' => $prod['badge'] ?? null,
                    'price' => $prod['price'],
                    'original_price' => $prod['original_price'] ?? null,
                    'cost_price' => round($prod['price'] * 0.42, 2),
                    'stock' => $prod['stock'] ?? 10,
                    'is_featured' => $prod['is_featured'] ?? false,
                    'is_best_seller' => $prod['is_best_seller'] ?? false,
                    'is_new' => $prod['is_new'] ?? false,
                    'is_deal' => $prod['is_deal'] ?? false,
                    'is_active' => true,
                    'rating' => $prod['rating'] ?? 5.0,
                    'reviews_count' => $prod['reviews_count'] ?? 10,
                    'model_info' => $prod['model_info'] ?? null,
                    'summary' => $prod['summary'] ?? null,
                    'description' => $prod['description'] ?? null,
                    'images' => $prod['images'] ?? [],
                    'colors' => $prod['colors'] ?? [],
                    'specifications' => $prod['specifications'] ?? [],
                    'measurement_table' => $prod['measurement_table'] ?? [],
                ]
            );

            // Variations
            if (isset($prod['variations']['options']) && is_array($prod['variations']['options'])) {
                foreach ($prod['variations']['options'] as $v) {
                    ProductVariation::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'sku' => $v['sku'],
                        ],
                        [
                            'type' => $prod['variations']['type'] ?? 'Tamanho',
                            'label' => $v['label'],
                            'price_delta' => $v['price_delta'] ?? 0.00,
                            'stock' => $v['stock'] ?? 5,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // 5. Banners
        Banner::updateOrCreate(
            ['title' => 'Coleção Aurora 2026: A Pureza do Linho & Ouro'],
            [
                'subtitle' => 'Peças fluidas confeccionadas em pura fibra natural, modelagens modestas que valorizam a postura com sofisticação atemporal.',
                'badge' => 'ALTA COSTURA AUTORAL',
                'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=80',
                'button_text' => 'Descobrir Coleção',
                'button_link' => '/catalogo',
                'position' => 'home_hero',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Banner::updateOrCreate(
            ['title' => 'Alfaiataria Exclusiva em Crepe e Tweed'],
            [
                'subtitle' => 'Blazers e conjuntos estruturados pensados para mulheres que lideram com distinção e elegância.',
                'badge' => 'EDIÇÃO LIMITADA',
                'image' => 'https://images.unsplash.com/photo-1548624149-f9b1859aa9d0?auto=format&fit=crop&w=1920&q=80',
                'button_text' => 'Ver Alfaiataria',
                'button_link' => '/categoria/alfaiataria',
                'position' => 'home_featured',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // 6. Orders, Customers & Items
        foreach ([$order1 ?? null] as $_) {
            // Synced below per order
        }
        $customer1 = Customer::syncFromOrderData([
            'customer_name' => 'Isabela Fontes Guimarães',
            'customer_email' => 'isabela.guimaraes@exemplo.com.br',
            'customer_phone' => '(11) 98765-4321',
            'customer_cpf' => '341.892.408-12',
            'shipping_zipcode' => '01419-001',
            'shipping_address' => 'Alameda Lorena',
            'shipping_number' => '1240',
            'shipping_complement' => 'Apto 82',
            'shipping_neighborhood' => 'Jardins',
            'shipping_city' => 'São Paulo',
            'shipping_state' => 'SP',
            'marketing_opt_in' => true,
        ]);

        $order1 = Order::updateOrCreate(
            ['code' => 'EL-8291'],
            [
                'customer_id' => $customer1->id,
                'customer_name' => 'Isabela Fontes Guimarães',
                'customer_email' => 'isabela.guimaraes@exemplo.com.br',
                'customer_phone' => '(11) 98765-4321',
                'customer_cpf' => '341.892.408-12',
                'shipping_zipcode' => '01419-001',
                'shipping_address' => 'Alameda Lorena',
                'shipping_number' => '1240',
                'shipping_complement' => 'Apto 82',
                'shipping_neighborhood' => 'Jardins',
                'shipping_city' => 'São Paulo',
                'shipping_state' => 'SP',
                'shipping_service' => 'SEDEX Premium',
                'shipping_cost' => 0.00,
                'tracking_code' => 'BR892183921SP',
                'payment_method' => 'pix',
                'subtotal' => 849.80,
                'discount' => 42.49,
                'coupon_code' => 'PRIMEIRACOMPRA',
                'total' => 807.31,
                'status' => 'shipped',
                'status_notes' => 'Pacote postado na agência central dos Correios às 14:30. Código de rastreio ativo.',
                'status_updated_at' => now()->subDay(),
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order1->id, 'product_title' => 'Vestido Midi Aurora em Linho Puro com Cinto Faixa'],
            [
                'variation' => 'Tamanho: M (40) | Cor: Off-White Linho',
                'sku' => 'VES-AUR-M',
                'price' => 489.90,
                'quantity' => 1,
                'total' => 489.90,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=700&q=75',
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order1->id, 'product_title' => 'Camisa em Seda Pura com Gola Laço e Pregas Manuais'],
            [
                'variation' => 'Tamanho: M (40) | Cor: Marfim Pérola',
                'sku' => 'CAM-SEDA-M',
                'price' => 359.90,
                'quantity' => 1,
                'total' => 359.90,
                'image' => 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=700&q=75',
            ]
        );

        // Order 2: In separation
        $customer2 = Customer::syncFromOrderData([
            'customer_name' => 'Helena Ribeiro Castro',
            'customer_email' => 'helena.castro@exemplo.com.br',
            'customer_phone' => '(21) 97123-8899',
            'customer_cpf' => '219.043.112-90',
            'shipping_zipcode' => '22041-001',
            'shipping_address' => 'Rua Barata Ribeiro',
            'shipping_number' => '540',
            'shipping_complement' => 'Cobertura 01',
            'shipping_neighborhood' => 'Copacabana',
            'shipping_city' => 'Rio de Janeiro',
            'shipping_state' => 'RJ',
            'marketing_opt_in' => true,
        ]);

        $order2 = Order::updateOrCreate(
            ['code' => 'EL-9034'],
            [
                'customer_id' => $customer2->id,
                'customer_name' => 'Helena Ribeiro Castro',
                'customer_email' => 'helena.castro@exemplo.com.br',
                'customer_phone' => '(21) 97123-8899',
                'customer_cpf' => '219.043.112-90',
                'shipping_zipcode' => '22041-001',
                'shipping_address' => 'Rua Barata Ribeiro',
                'shipping_number' => '540',
                'shipping_complement' => 'Cobertura 01',
                'shipping_neighborhood' => 'Copacabana',
                'shipping_city' => 'Rio de Janeiro',
                'shipping_state' => 'RJ',
                'shipping_service' => 'SEDEX Express',
                'shipping_cost' => 28.50,
                'tracking_code' => null,
                'payment_method' => 'credit_card',
                'subtotal' => 529.00,
                'discount' => 0.00,
                'coupon_code' => null,
                'total' => 557.50,
                'status' => 'preparing',
                'status_notes' => 'Peça separada do estoque físico, passando pela conferência de costura e embalagem aromática de luxo.',
                'status_updated_at' => now()->subHours(3),
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order2->id, 'product_title' => 'Blazer Alfaiataria Duplo Botão em Tweed Francês'],
            [
                'variation' => 'Tamanho: P (38) | Cor: Tweed Areia com Dourado',
                'sku' => 'BLAZ-TWEED-P',
                'price' => 529.00,
                'quantity' => 1,
                'total' => 529.00,
                'image' => 'https://images.unsplash.com/photo-1548624149-f9b1859aa9d0?auto=format&fit=crop&w=700&q=75',
            ]
        );

        // Order 3: Pending payment
        $customer3 = Customer::syncFromOrderData([
            'customer_name' => 'Camila Vasconcelos',
            'customer_email' => 'camila.vasconcelos@exemplo.com.br',
            'customer_phone' => '(31) 99234-5566',
            'customer_cpf' => '102.394.887-54',
            'shipping_zipcode' => '30130-100',
            'shipping_address' => 'Rua da Bahia',
            'shipping_number' => '980',
            'shipping_complement' => 'Sala 402',
            'shipping_neighborhood' => 'Centro',
            'shipping_city' => 'Belo Horizonte',
            'shipping_state' => 'MG',
            'marketing_opt_in' => false,
        ]);

        $order3 = Order::updateOrCreate(
            ['code' => 'EL-9102'],
            [
                'customer_id' => $customer3->id,
                'customer_name' => 'Camila Vasconcelos',
                'customer_email' => 'camila.vasconcelos@exemplo.com.br',
                'customer_phone' => '(31) 99234-5566',
                'customer_cpf' => '102.394.887-54',
                'shipping_zipcode' => '30130-100',
                'shipping_address' => 'Rua da Bahia',
                'shipping_number' => '980',
                'shipping_complement' => 'Sala 402',
                'shipping_neighborhood' => 'Centro',
                'shipping_city' => 'Belo Horizonte',
                'shipping_state' => 'MG',
                'shipping_service' => 'PAC',
                'shipping_cost' => 22.00,
                'tracking_code' => null,
                'payment_method' => 'pix',
                'subtotal' => 459.90,
                'discount' => 22.99,
                'coupon_code' => 'BEMVINDA10',
                'total' => 458.91,
                'status' => 'pending',
                'status_notes' => 'Aguardando confirmação do Pix (expira em 30 minutos).',
                'status_updated_at' => now()->subMinutes(15),
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order3->id, 'product_title' => 'Vestido Poá Heritage em Crepe Georgette Evasê'],
            [
                'variation' => 'Tamanho: M (40) | Cor: Fundo Preto com Poá Branco',
                'sku' => 'VES-POA-M',
                'price' => 459.90,
                'quantity' => 1,
                'total' => 459.90,
                'image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=700&q=75',
            ]
        );

        EcommerceDataService::clearCache();

        tenancy()->end();
    }
}
