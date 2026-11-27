<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\Tenant;

class StoreTemplateService
{
    /**
     * Select options for the SuperAdmin tenant creation form.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'none' => 'Sem padrão (Apenas cores Via Solutions)',
            'fashion' => 'Moda & Vestuário (Terracota Chic)',
            'electronics' => 'Eletrônicos & Tech (Via Solutions Tech)',
            'beauty' => 'Beleza, Cosméticos & Skincare (Rosé Marsala)',
            'home_decor' => 'Casa, Decoração & Utilidades (Botânico)',
            'supplements' => 'Saúde, Suplementos & Fitness (Noir Energia)',
        ];
    }

    /**
     * All segment templates with visual identity, colors, banners, categories, and demo products.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        $viaPreset = StoreSetting::presets()['via_solutions'];
        $presets = StoreSetting::presets();

        return [
            'none' => [
                'label' => 'Sem padrão',
                'active_theme' => 'via_solutions',
                'slogan' => 'Sua loja online • Tecnologia Via Solutions',
                'colors' => $viaPreset,
                'banner' => null,
                'categories' => [],
                'products' => [],
            ],

            'fashion' => [
                'label' => 'Moda & Vestuário',
                'active_theme' => 'sunset_orange',
                'slogan' => 'Estilo • Conforto • Tendência',
                'colors' => $presets['sunset_orange'],
                'banner' => [
                    'title' => 'Coleção Essencial: Cortes Nobres & Leveza',
                    'subtitle' => 'Peças versáteis em linho, algodão egípcio e alfaiataria contemporânea para todas as ocasiões.',
                    'badge' => 'NOVA COLEÇÃO',
                    'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=75',
                    'button_text' => 'Ver Coleção',
                    'button_link' => '/catalogo',
                ],
                'categories' => [
                    [
                        'name' => 'Vestidos & Conjuntos',
                        'slug' => 'vestidos-conjuntos',
                        'icon' => 'sparkles',
                        'description' => 'Modelagens fluidas e conjuntos coordenados',
                        'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Camisaria & Alfaiataria',
                        'slug' => 'camisaria-alfaiataria',
                        'icon' => 'briefcase',
                        'description' => 'Blazers, camisas e calças de corte impecável',
                        'image' => 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Acessórios & Bolsas',
                        'slug' => 'acessorios-bolsas',
                        'icon' => 'shopping-bag',
                        'description' => 'Bolsas estruturadas, cintos e lenços',
                        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=600&q=75',
                    ],
                ],
                'products' => [
                    [
                        'category_slug' => 'vestidos-conjuntos',
                        'title' => 'Vestido Midi Linho Natural com Cinto',
                        'slug' => 'vestido-midi-linho-natural',
                        'sku' => 'MOD-VES-01',
                        'price' => 349.90,
                        'original_price' => 419.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Vestido midi em linho respirável com caimento leve e cinto removível.',
                        'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'camisaria-alfaiataria',
                        'title' => 'Camisa Algodão Egípcio Oversized',
                        'slug' => 'camisa-algodao-egipcio-oversized',
                        'sku' => 'MOD-CAM-02',
                        'price' => 239.90,
                        'original_price' => 289.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => false,
                        'summary' => 'Camisa clássica de toque macio com acabamento premium.',
                        'image' => 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'vestidos-conjuntos',
                        'title' => 'Conjunto Alfaiataria Pantalona Off-White',
                        'slug' => 'conjunto-alfaiataria-pantalona',
                        'sku' => 'MOD-CON-03',
                        'price' => 489.00,
                        'original_price' => 569.00,
                        'is_featured' => true,
                        'is_new' => false,
                        'is_best_seller' => true,
                        'summary' => 'Conjunto elegante de colete estruturado e calça pantalona.',
                        'image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'acessorios-bolsas',
                        'title' => 'Bolsa Tiracolo Couro Legítimo Caramelo',
                        'slug' => 'bolsa-tiracolo-couro-caramelo',
                        'sku' => 'MOD-BOL-04',
                        'price' => 389.90,
                        'original_price' => 449.90,
                        'is_featured' => false,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Bolsa estruturada com alça ajustável e ferragens douradas.',
                        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=700&q=75',
                    ],
                ],
            ],

            'electronics' => [
                'label' => 'Eletrônicos & Tech',
                'active_theme' => 'via_solutions',
                'slogan' => 'Tecnologia • Performance • Inovação',
                'colors' => $viaPreset,
                'banner' => [
                    'title' => 'Setup & Tecnologia de Alta Performance',
                    'subtitle' => 'Áudio sem fio, periféricos mecânicos e wearables originais com garantia nacional e envio imediato.',
                    'badge' => 'LANÇAMENTOS TECH',
                    'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=75',
                    'button_text' => 'Explorar Tech',
                    'button_link' => '/catalogo',
                ],
                'categories' => [
                    [
                        'name' => 'Áudio & Fones',
                        'slug' => 'audio-fones',
                        'icon' => 'sparkles',
                        'description' => 'Headphones ANC, fones TWS e caixas de som',
                        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Periféricos & Setup',
                        'slug' => 'perifericos-setup',
                        'icon' => 'briefcase',
                        'description' => 'Teclados mecânicos, mouses e webcams 4K',
                        'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Smartwatches & Gadgets',
                        'slug' => 'smartwatches-gadgets',
                        'icon' => 'clock',
                        'description' => 'Relógios inteligentes e carregadores rápidos',
                        'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=75',
                    ],
                ],
                'products' => [
                    [
                        'category_slug' => 'audio-fones',
                        'title' => 'Headphone Bluetooth Studio ANC Pro',
                        'slug' => 'headphone-bluetooth-studio-anc',
                        'sku' => 'TEC-AUD-01',
                        'price' => 649.90,
                        'original_price' => 799.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Cancelamento ativo de ruído, 40h de bateria e carregamento USB-C.',
                        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'perifericos-setup',
                        'title' => 'Teclado Mecânico Compacto Wireless 75%',
                        'slug' => 'teclado-mecanico-wireless-75',
                        'sku' => 'TEC-TEC-02',
                        'price' => 429.90,
                        'original_price' => 499.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Switches táteis silenciosos, conexão tri-mode e keycaps PBT.',
                        'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'smartwatches-gadgets',
                        'title' => 'Smartwatch AMOLED GPS & Saúde',
                        'slug' => 'smartwatch-amoled-gps-saude',
                        'sku' => 'TEC-SMT-03',
                        'price' => 549.00,
                        'original_price' => 699.00,
                        'is_featured' => true,
                        'is_new' => false,
                        'is_best_seller' => true,
                        'summary' => 'Tela AMOLED 1.43", monitoramento cardíaco 24h e resistência 5ATM.',
                        'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'perifericos-setup',
                        'title' => 'Mouse Ergonômico Wireless 12000 DPI',
                        'slug' => 'mouse-ergonomico-wireless',
                        'sku' => 'TEC-MOU-04',
                        'price' => 249.90,
                        'original_price' => 299.90,
                        'is_featured' => false,
                        'is_new' => true,
                        'is_best_seller' => false,
                        'summary' => 'Sensor óptico de precisão com bateria recarregável de longa duração.',
                        'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=700&q=75',
                    ],
                ],
            ],

            'beauty' => [
                'label' => 'Beleza, Cosméticos & Skincare',
                'active_theme' => 'pastel_pink',
                'slogan' => 'Autocuidado • Beleza • Bem-Estar',
                'colors' => $presets['pastel_pink'],
                'banner' => [
                    'title' => 'Rotina de Skincare & Beleza Natural',
                    'subtitle' => 'Ativos dermatológicos, séruns antioxidantes e fragrâncias exclusivas livres de parabenos.',
                    'badge' => 'DERMOCOSMÉTICOS',
                    'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1200&q=75',
                    'button_text' => 'Ver Produtos',
                    'button_link' => '/catalogo',
                ],
                'categories' => [
                    [
                        'name' => 'Skincare Facial',
                        'slug' => 'skincare-facial',
                        'icon' => 'sparkles',
                        'description' => 'Séruns, hidratantes e proteção solar',
                        'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Perfumaria & Corpo',
                        'slug' => 'perfumaria-corpo',
                        'icon' => 'heart',
                        'description' => 'Eau de Parfum e loções hidratantes',
                        'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Maquiagem & Kits',
                        'slug' => 'maquiagem-kits',
                        'icon' => 'star',
                        'description' => 'Kits presenteáveis e acessórios de beleza',
                        'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=75',
                    ],
                ],
                'products' => [
                    [
                        'category_slug' => 'skincare-facial',
                        'title' => 'Sérum Facial Vitamina C 15% + Ácido Ferúlico',
                        'slug' => 'serum-facial-vitamina-c-15',
                        'sku' => 'BEL-SER-01',
                        'price' => 139.90,
                        'original_price' => 169.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Ilumina, uniformiza o tom da pele e combate os radicais livres.',
                        'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'perfumaria-corpo',
                        'title' => 'Eau de Parfum Floral Botânico 100ml',
                        'slug' => 'eau-de-parfum-floral-100ml',
                        'sku' => 'BEL-PER-02',
                        'price' => 279.90,
                        'original_price' => 329.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Notas de jasmim, bergamota e sândalo com alta fixação.',
                        'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'skincare-facial',
                        'title' => 'Creme Hidratante Ácido Hialurônico Triplo',
                        'slug' => 'creme-acido-hialuronico-triplo',
                        'sku' => 'BEL-HID-03',
                        'price' => 119.00,
                        'original_price' => 149.00,
                        'is_featured' => true,
                        'is_new' => false,
                        'is_best_seller' => true,
                        'summary' => 'Hidratação profunda 24h com textura leve e toque seco.',
                        'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'maquiagem-kits',
                        'title' => 'Kit Pincéis Profissionais Cerdas Macias 10 Peças',
                        'slug' => 'kit-pinceis-profissionais-10-pecas',
                        'sku' => 'BEL-KIT-04',
                        'price' => 159.90,
                        'original_price' => 199.90,
                        'is_featured' => false,
                        'is_new' => true,
                        'is_best_seller' => false,
                        'summary' => 'Estojo completo com cerdas sintéticas veganas de alta precisão.',
                        'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=700&q=75',
                    ],
                ],
            ],

            'home_decor' => [
                'label' => 'Casa, Decoração & Utilidades',
                'active_theme' => 'emerald_green',
                'slogan' => 'Conforto • Design • Aconchego',
                'colors' => $presets['emerald_green'],
                'banner' => [
                    'title' => 'Design & Aconchego para Transformar seu Lar',
                    'subtitle' => 'Peças autorais em cerâmica, iluminação minimalista e têxteis naturais com entrega para todo o Brasil.',
                    'badge' => 'CASA & DESIGN',
                    'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1200&q=75',
                    'button_text' => 'Explorar Casa',
                    'button_link' => '/catalogo',
                ],
                'categories' => [
                    [
                        'name' => 'Sala & Decoração',
                        'slug' => 'sala-decoracao',
                        'icon' => 'home',
                        'description' => 'Almofadas, mantas e objetos escultóricos',
                        'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Cozinha & Mesa Posta',
                        'slug' => 'cozinha-mesa-posta',
                        'icon' => 'sparkles',
                        'description' => 'Cerâmicas artesanais e jogos americanos',
                        'image' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Iluminação & Aromas',
                        'slug' => 'iluminacao-aromas',
                        'icon' => 'sun',
                        'description' => 'Luminárias, velas aromáticas e difusores',
                        'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=600&q=75',
                    ],
                ],
                'products' => [
                    [
                        'category_slug' => 'cozinha-mesa-posta',
                        'title' => 'Jogo de Cerâmica Artesanal Matte 6 Peças',
                        'slug' => 'jogo-ceramica-artesanal-6-pecas',
                        'sku' => 'CAS-CER-01',
                        'price' => 329.90,
                        'original_price' => 389.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Pratos e bowls em grés queimado em alta temperatura.',
                        'image' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'iluminacao-aromas',
                        'title' => 'Luminária de Mesa Minimalista LED Dimerizável',
                        'slug' => 'luminaria-mesa-minimalista-led',
                        'sku' => 'CAS-LUM-02',
                        'price' => 259.00,
                        'original_price' => 319.00,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Luz quente aconchegante com base em alumínio escovado.',
                        'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'sala-decoracao',
                        'title' => 'Manta Tricô Algodão Natural para Sofá',
                        'slug' => 'manta-trico-algodao-natural',
                        'sku' => 'CAS-MAN-03',
                        'price' => 189.90,
                        'original_price' => 229.90,
                        'is_featured' => true,
                        'is_new' => false,
                        'is_best_seller' => true,
                        'summary' => 'Toque macio e trama artesanal em 100% algodão.',
                        'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'iluminacao-aromas',
                        'title' => 'Vela Aromática Cera Vegetal Alecrim & Cedro',
                        'slug' => 'vela-aromatica-alecrim-cedro',
                        'sku' => 'CAS-VEL-04',
                        'price' => 94.90,
                        'original_price' => 119.90,
                        'is_featured' => false,
                        'is_new' => true,
                        'is_best_seller' => false,
                        'summary' => 'Queima limpa por até 45 horas em pote de vidro reutilizável.',
                        'image' => 'https://images.unsplash.com/photo-1602874801007-bd458bb1b8b6?auto=format&fit=crop&w=700&q=75',
                    ],
                ],
            ],

            'supplements' => [
                'label' => 'Saúde, Suplementos & Fitness',
                'active_theme' => 'minimal_dark',
                'slogan' => 'Energia • Saúde • Resultados',
                'colors' => [
                    'primary_color' => '#18181b',
                    'primary_hover' => '#27272a',
                    'primary_light' => '#fafafa',
                    'secondary_color' => '#09090b',
                    'accent_color' => '#ea580c',
                    'accent_hover' => '#c2410c',
                    'surface_color' => '#fdfdfd',
                    'border_color' => '#e4e4e7',
                ],
                'banner' => [
                    'title' => 'Alta Performance & Nutrição Esportiva',
                    'subtitle' => 'Proteínas puras, creatina monohidratada e pré-treinos com laudo de pureza e entrega expressa.',
                    'badge' => 'NUTRIÇÃO ESPORTIVA',
                    'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1200&q=75',
                    'button_text' => 'Ver Suplementos',
                    'button_link' => '/catalogo',
                ],
                'categories' => [
                    [
                        'name' => 'Proteínas & Aminoácidos',
                        'slug' => 'proteinas-aminoacidos',
                        'icon' => 'bolt',
                        'description' => 'Whey Isolado, Concentrado e BCAAs',
                        'image' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Pré-Treino & Creatina',
                        'slug' => 'pre-treino-creatina',
                        'icon' => 'fire',
                        'description' => 'Força, foco e explosão muscular',
                        'image' => 'https://images.unsplash.com/photo-1579722821272-0f6c3d4d8d65?auto=format&fit=crop&w=600&q=75',
                    ],
                    [
                        'name' => 'Vitaminas & Acessórios',
                        'slug' => 'vitaminas-acessorios',
                        'icon' => 'heart',
                        'description' => 'Multivitamínicos, ômega-3 e coqueteleiras',
                        'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=600&q=75',
                    ],
                ],
                'products' => [
                    [
                        'category_slug' => 'proteinas-aminoacidos',
                        'title' => 'Whey Protein Isolado 900g Baunilha Bourbon',
                        'slug' => 'whey-protein-isolado-900g',
                        'sku' => 'FIT-WHE-01',
                        'price' => 219.90,
                        'original_price' => 269.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => '27g de proteína por dose, zero adição de açúcar e rápida absorção.',
                        'image' => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'pre-treino-creatina',
                        'title' => 'Creatina 100% Pura Monohidratada 300g',
                        'slug' => 'creatina-pura-monohidratada-300g',
                        'sku' => 'FIT-CRE-02',
                        'price' => 99.90,
                        'original_price' => 129.90,
                        'is_featured' => true,
                        'is_new' => true,
                        'is_best_seller' => true,
                        'summary' => 'Auxilia no aumento de força e desempenho em treinos de alta intensidade.',
                        'image' => 'https://images.unsplash.com/photo-1579722821272-0f6c3d4d8d65?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'pre-treino-creatina',
                        'title' => 'Pré-Treino Focus Energy 300g Frutas Vermelhas',
                        'slug' => 'pre-treino-focus-energy-300g',
                        'sku' => 'FIT-PRE-03',
                        'price' => 119.00,
                        'original_price' => 149.00,
                        'is_featured' => true,
                        'is_new' => false,
                        'is_best_seller' => true,
                        'summary' => 'Beta-alanina, cafeína e arginina para máxima disposição.',
                        'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=700&q=75',
                    ],
                    [
                        'category_slug' => 'vitaminas-acessorios',
                        'title' => 'Coqueteleira Térmica Inox 700ml Matte',
                        'slug' => 'coqueteleira-termica-inox-700ml',
                        'sku' => 'FIT-COQ-04',
                        'price' => 89.90,
                        'original_price' => 109.90,
                        'is_featured' => false,
                        'is_new' => true,
                        'is_best_seller' => false,
                        'summary' => 'Aço inoxidável parede dupla com mixer silencioso integrado.',
                        'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=700&q=75',
                    ],
                ],
            ],
        ];
    }

    /**
     * Apply the selected segment template (or blank store) to a newly created tenant.
     */
    public static function applyToTenant(Tenant $tenant, string $templateKey = 'none'): void
    {
        $templates = static::all();
        $template = $templates[$templateKey] ?? $templates['none'];
        $colors = $template['colors'];

        StoreSetting::withoutGlobalScopes()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'tenant_id' => $tenant->id,
                'store_name' => $tenant->name,
                'slogan' => $template['slogan'],
                'logo_path' => 'images/via-solutions-logo.png',
                'favicon_path' => 'images/via-solutions-logo.png',
                'contact_email' => $tenant->email ?? "contato@{$tenant->slug}.com.br",
                'contact_phone' => $tenant->phone ?? '',
                'active_theme' => $template['active_theme'],
                'primary_color' => $colors['primary_color'],
                'primary_hover' => $colors['primary_hover'],
                'primary_light' => $colors['primary_light'],
                'secondary_color' => $colors['secondary_color'],
                'accent_color' => $colors['accent_color'],
                'accent_hover' => $colors['accent_hover'],
                'surface_color' => $colors['surface_color'],
                'border_color' => $colors['border_color'],
            ]
        );

        if ($templateKey === 'none') {
            return;
        }

        $wasInitialized = tenancy()->initialized;
        $previousTenant = $wasInitialized ? tenant() : null;

        tenancy()->initialize($tenant);

        try {
            if (! empty($template['banner'])) {
                $banner = $template['banner'];
                Banner::create([
                    'tenant_id' => $tenant->id,
                    'title' => $banner['title'],
                    'subtitle' => $banner['subtitle'],
                    'badge' => $banner['badge'],
                    'image' => $banner['image'],
                    'button_text' => $banner['button_text'],
                    'button_link' => $banner['button_link'],
                    'position' => 'home_hero',
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
            }

            /** @var array<string, int> $categoryIdsBySlug */
            $categoryIdsBySlug = [];
            foreach ($template['categories'] as $index => $catData) {
                $category = Category::create([
                    'tenant_id' => $tenant->id,
                    'name' => $catData['name'],
                    'slug' => $catData['slug'],
                    'icon' => $catData['icon'] ?? 'sparkles',
                    'description' => $catData['description'] ?? null,
                    'image' => $catData['image'] ?? null,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
                $categoryIdsBySlug[$catData['slug']] = $category->id;
            }

            foreach ($template['products'] as $prodData) {
                $categoryId = $categoryIdsBySlug[$prodData['category_slug']] ?? null;
                Product::create([
                    'tenant_id' => $tenant->id,
                    'category_id' => $categoryId,
                    'title' => $prodData['title'],
                    'slug' => $prodData['slug'],
                    'sku' => $prodData['sku'],
                    'brand' => $tenant->name,
                    'price' => $prodData['price'],
                    'original_price' => $prodData['original_price'] ?? null,
                    'stock' => 25,
                    'is_featured' => (bool) ($prodData['is_featured'] ?? false),
                    'is_new' => (bool) ($prodData['is_new'] ?? false),
                    'is_best_seller' => (bool) ($prodData['is_best_seller'] ?? false),
                    'is_deal' => false,
                    'is_active' => true,
                    'summary' => $prodData['summary'] ?? null,
                    'description' => $prodData['summary'] ?? null,
                    'images' => ! empty($prodData['image']) ? [$prodData['image']] : [],
                ]);
            }

            EcommerceDataService::clearCache();
        } finally {
            tenancy()->end();
            if ($previousTenant) {
                tenancy()->initialize($previousTenant);
            }
        }
    }
}
