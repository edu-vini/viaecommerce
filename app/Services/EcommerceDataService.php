<?php

namespace App\Services;

class EcommerceDataService
{
    public static function getCategories(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Vestidos Midi',
                'slug' => 'vestidos-midi',
                'icon' => 'sparkles',
                'description' => 'Modelagens evasê, fluidas e estruturadas com caimento impecável',
                'count' => 24,
                'featured_image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'id' => 2,
                'name' => 'Alfaiataria & Blazers',
                'slug' => 'alfaiataria',
                'icon' => 'briefcase',
                'description' => 'Peças estruturadas com forro acetinado e cortes contemporâneos',
                'count' => 18,
                'featured_image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'id' => 3,
                'name' => 'Conjuntos Exclusivos',
                'slug' => 'conjuntos',
                'icon' => 'layers',
                'description' => 'Combinações sofisticadas de saias, calças e blusas em perfeita harmonia',
                'count' => 16,
                'featured_image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'id' => 4,
                'name' => 'Vestidos Longos & Festa',
                'slug' => 'vestidos-longos',
                'icon' => 'heart',
                'description' => 'Elegância sublime para ocasiões especiais e celebrações marcantes',
                'count' => 12,
                'featured_image' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'id' => 5,
                'name' => 'Saias & Chemises',
                'slug' => 'saias',
                'icon' => 'bookmark',
                'description' => 'Modelagens plissadas, evasê e chemises em tecidos nobres e atemporais',
                'count' => 20,
                'featured_image' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'id' => 6,
                'name' => 'Camisas & Blusas Finas',
                'slug' => 'blusas-camisas',
                'icon' => 'sun',
                'description' => 'Chiffon, seda e algodão egípcio com golas laço e botões delicados',
                'count' => 15,
                'featured_image' => 'https://images.unsplash.com/photo-1534126511673-b6899657816a?auto=format&fit=crop&w=600&q=80',
            ],
        ];
    }

    public static function getProducts(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Vestido Midi Aurora em Linho Puro com Cinto Faixa',
                'slug' => 'vestido-midi-aurora-linho-puro',
                'category_slug' => 'vestidos-midi',
                'category_name' => 'Vestidos Midi',
                'brand' => 'Elisie',
                'badge' => 'NOVA COLEÇÃO',
                'price' => 489.90,
                'original_price' => 569.00,
                'rating' => 4.95,
                'reviews_count' => 184,
                'installments' => 6,
                'installment_value' => 81.65,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => true,
                'is_deal' => true,
                'stock' => 15,
                'model_info' => 'A modelo mede 1,73m, tem 86cm de busto, 68cm de cintura e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Off-White Linho', 'hex' => '#F4EFEB'],
                    ['name' => 'Terracota Suave', 'hex' => '#9B5B44'],
                    ['name' => 'Verde Oliva', 'hex' => '#5C6B52'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'VES-AURORA-PP', 'price_delta' => 0, 'stock' => 3],
                        ['label' => 'P (38)', 'sku' => 'VES-AURORA-P', 'price_delta' => 0, 'stock' => 6],
                        ['label' => 'M (40)', 'sku' => 'VES-AURORA-M', 'price_delta' => 0, 'stock' => 4],
                        ['label' => 'G (42)', 'sku' => 'VES-AURORA-G', 'price_delta' => 0, 'stock' => 2],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'PP (36)', 'bust' => '84-88 cm', 'waist' => '64-68 cm', 'hip' => '92-96 cm', 'length' => '118 cm'],
                    ['size' => 'P (38)', 'bust' => '88-92 cm', 'waist' => '68-72 cm', 'hip' => '96-100 cm', 'length' => '120 cm'],
                    ['size' => 'M (40)', 'bust' => '92-96 cm', 'waist' => '72-76 cm', 'hip' => '100-104 cm', 'length' => '121 cm'],
                    ['size' => 'G (42)', 'bust' => '96-102 cm', 'waist' => '76-82 cm', 'hip' => '104-110 cm', 'length' => '122 cm'],
                    ['size' => 'GG (44)', 'bust' => '102-108 cm', 'waist' => '82-88 cm', 'hip' => '110-116 cm', 'length' => '123 cm'],
                ],
                'summary' => 'Modelagem evasê atemporal com caimento fluido, decote modesto e cinto faixa removível com fivela forrada no mesmo tecido.',
                'description' => 'O Vestido Midi Aurora une a nobreza do linho puro com a suavidade do forro em algodão toque de seda. Seus recortes verticais alongam a silhueta com elegância discreta e feminina. Ideal para momentos solenes, cultos, eventos executivos e celebrações ao ar livre.',
                'specifications' => [
                    'Tecido Principal' => '70% Linho Misto Premium, 30% Viscose Suíça',
                    'Forro Interno' => '100% Algodão Toque de Seda (zero transparência)',
                    'Fechamento' => 'Zíper invisível nas costas com colchete de segurança',
                    'Acabamento' => 'Bainha invisível e botões decorativos forrados à mão',
                    'Garantia' => 'Primeira troca 100% gratuita em até 30 dias',
                ],
            ],
            [
                'id' => 2,
                'title' => 'Conjunto Alfaiataria Florença com Blazer Estruturado e Calça Pantalona',
                'slug' => 'conjunto-alfaiataria-florenca-pantalona',
                'category_slug' => 'alfaiataria',
                'category_name' => 'Alfaiataria & Blazers',
                'brand' => 'Elisie',
                'badge' => 'BEST SELLER',
                'price' => 649.00,
                'original_price' => 749.00,
                'rating' => 4.98,
                'reviews_count' => 220,
                'installments' => 6,
                'installment_value' => 108.16,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 12,
                'model_info' => 'A modelo mede 1,74m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1548624149-f9b1859aa9d0?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Fendi Nude', 'hex' => '#C5B9AC'],
                    ['name' => 'Preto Clássico', 'hex' => '#1A1A1A'],
                    ['name' => 'Azul Serenity', 'hex' => '#9BB7D4'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'CONJ-FLOR-PP', 'price_delta' => 0, 'stock' => 2],
                        ['label' => 'P (38)', 'sku' => 'CONJ-FLOR-P', 'price_delta' => 0, 'stock' => 5],
                        ['label' => 'M (40)', 'sku' => 'CONJ-FLOR-M', 'price_delta' => 0, 'stock' => 3],
                        ['label' => 'G (42)', 'sku' => 'CONJ-FLOR-G', 'price_delta' => 0, 'stock' => 2],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '90 cm', 'waist' => '70 cm', 'hip' => '98 cm', 'length' => '110 cm (calça)'],
                    ['size' => 'M (40)', 'bust' => '94 cm', 'waist' => '74 cm', 'hip' => '102 cm', 'length' => '112 cm (calça)'],
                    ['size' => 'G (42)', 'bust' => '100 cm', 'waist' => '80 cm', 'hip' => '108 cm', 'length' => '113 cm (calça)'],
                ],
                'summary' => 'O ápice da elegância executiva. Blazer alongado com forro de cetim premium e calça pantalona de cós alto estruturado.',
                'description' => 'Confeccionado em crepe de alfaiataria com elastano que não amarrota facilmente. Caimento de passarela, lapela chanfrada sofisticada e bolsos embutidos. Transmita autoridade e refinamento em qualquer ambiente.',
                'specifications' => [
                    'Tecido' => 'Crepe de Alfaiataria Florença (96% Poliéster Premium, 4% Elastano)',
                    'Forro do Blazer' => 'Cetim Toque Suave com acabamento acetinado',
                    'Modelagem da Calça' => 'Pantalona de alfaiataria com pregas frontais e bolso faca',
                    'Cuidados' => 'Lavar à seco ou ciclo delicado com sabão neutro',
                ],
            ],
            [
                'id' => 3,
                'title' => 'Vestido Longo Sublime em Chiffon com Manga Evasê',
                'slug' => 'vestido-longo-sublime-chiffon',
                'category_slug' => 'vestidos-longos',
                'category_name' => 'Vestidos Longos & Festa',
                'brand' => 'Elisie',
                'badge' => 'EDIÇÃO LIMITADA',
                'price' => 589.00,
                'original_price' => 689.00,
                'rating' => 4.96,
                'reviews_count' => 95,
                'installments' => 6,
                'installment_value' => 98.16,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => false,
                'is_new' => true,
                'is_deal' => true,
                'stock' => 8,
                'model_info' => 'A modelo mede 1,76m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Verde Esmeralda', 'hex' => '#1F4738'],
                    ['name' => 'Bordeaux Imperial', 'hex' => '#5C1D27'],
                    ['name' => 'Azul Marinho Real', 'hex' => '#141E30'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'P (38)', 'sku' => 'VES-SUBLIME-P', 'price_delta' => 0, 'stock' => 3],
                        ['label' => 'M (40)', 'sku' => 'VES-SUBLIME-M', 'price_delta' => 0, 'stock' => 3],
                        ['label' => 'G (42)', 'sku' => 'VES-SUBLIME-G', 'price_delta' => 0, 'stock' => 2],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '88-92 cm', 'waist' => '68-72 cm', 'hip' => 'Evase Livre', 'length' => '145 cm'],
                    ['size' => 'M (40)', 'bust' => '92-96 cm', 'waist' => '72-76 cm', 'hip' => 'Evase Livre', 'length' => '147 cm'],
                    ['size' => 'G (42)', 'bust' => '96-102 cm', 'waist' => '76-82 cm', 'hip' => 'Evase Livre', 'length' => '148 cm'],
                ],
                'summary' => 'Fluidez etérea em chiffon com caimento romântico e modesto. Saia ampla três marias e mangas evasê esvoaçantes.',
                'description' => 'Perfeito para convidadas de casamento, formaturas e celebrações inesquecíveis. O forro duplo em malha com toque de seda proporciona conforto total e segurança sem marcar nada.',
                'specifications' => [
                    'Tecido' => 'Chiffon Suíço com trama fechada suave',
                    'Forro' => 'Duplo forro em helanca light especial',
                    'Comprimento' => 'Longo clássico (145cm)',
                    'Detalhes' => 'Punhos delicados com elástico suave e decote fechado',
                ],
            ],
            [
                'id' => 4,
                'title' => 'Chemise Midi Atemporal com Botões de Madrepérola',
                'slug' => 'chemise-midi-atemporal-madreperola',
                'category_slug' => 'saias',
                'category_name' => 'Saias & Chemises',
                'brand' => 'Elisie',
                'badge' => 'ESSENCIAL',
                'price' => 429.90,
                'original_price' => 499.00,
                'rating' => 4.92,
                'reviews_count' => 147,
                'installments' => 5,
                'installment_value' => 85.98,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => false,
                'stock' => 18,
                'model_info' => 'A modelo mede 1,72m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Bege Areia Natural', 'hex' => '#D8CCBD'],
                    ['name' => 'Branco Neve', 'hex' => '#FFFFFF'],
                    ['name' => 'Listrado Azul & Branco', 'hex' => '#B8CBE0'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'CHEM-ATEM-PP', 'price_delta' => 0, 'stock' => 4],
                        ['label' => 'P (38)', 'sku' => 'CHEM-ATEM-P', 'price_delta' => 0, 'stock' => 6],
                        ['label' => 'M (40)', 'sku' => 'CHEM-ATEM-M', 'price_delta' => 0, 'stock' => 5],
                        ['label' => 'G (42)', 'sku' => 'CHEM-ATEM-G', 'price_delta' => 0, 'stock' => 3],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '90 cm', 'waist' => '74 cm', 'hip' => '98 cm', 'length' => '115 cm'],
                    ['size' => 'M (40)', 'bust' => '94 cm', 'waist' => '78 cm', 'hip' => '102 cm', 'length' => '116 cm'],
                    ['size' => 'G (42)', 'bust' => '100 cm', 'waist' => '84 cm', 'hip' => '108 cm', 'length' => '117 cm'],
                ],
                'summary' => 'A versatilidade da chemise clássica reinterpretada com elegância refinada e gola camiseiro impecável.',
                'description' => 'Pode ser usada tanto solta para um look casual chic com rasteiras quanto acinturada com a faixa para reuniões e cultos. Confeccionada em tricoline acetinada de toque macio.',
                'specifications' => [
                    'Composição' => '97% Algodão Nobre Fio 60, 3% Elastano',
                    'Botões' => 'Madrepérola natural personalizada',
                    'Bolsos' => 'Laterais embutidos funcionais',
                    'Mangas' => 'Manga 3/4 com martingale para dobrar com charme',
                ],
            ],
            [
                'id' => 5,
                'title' => 'Saia Midi Plissada Sublime com Cós Alto de Alfaiataria',
                'slug' => 'saia-midi-plissada-sublime',
                'category_slug' => 'saias',
                'category_name' => 'Saias & Chemises',
                'brand' => 'Elisie',
                'badge' => 'O QUERIDINHO',
                'price' => 329.90,
                'original_price' => 389.00,
                'rating' => 4.97,
                'reviews_count' => 310,
                'installments' => 4,
                'installment_value' => 82.47,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 22,
                'model_info' => 'A modelo mede 1,71m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Champanhe Dourado', 'hex' => '#E6D7C3'],
                    ['name' => 'Preto Clássico', 'hex' => '#1A1A1A'],
                    ['name' => 'Rosa Quartzo', 'hex' => '#D8B7BA'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'SAIA-PLIS-PP', 'price_delta' => 0, 'stock' => 5],
                        ['label' => 'P (38)', 'sku' => 'SAIA-PLIS-P', 'price_delta' => 0, 'stock' => 8],
                        ['label' => 'M (40)', 'sku' => 'SAIA-PLIS-M', 'price_delta' => 0, 'stock' => 6],
                        ['label' => 'G (42)', 'sku' => 'SAIA-PLIS-G', 'price_delta' => 0, 'stock' => 3],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '-', 'waist' => '68-72 cm', 'hip' => 'Plissado Livre', 'length' => '82 cm'],
                    ['size' => 'M (40)', 'bust' => '-', 'waist' => '72-76 cm', 'hip' => 'Plissado Livre', 'length' => '83 cm'],
                    ['size' => 'G (42)', 'bust' => '-', 'waist' => '76-82 cm', 'hip' => 'Plissado Livre', 'length' => '84 cm'],
                ],
                'summary' => 'Plissado permanente em máquina alemã que não desfaz com as lavagens. Caimento volumoso com leveza incomparável.',
                'description' => 'A saia midi plissada é uma peça curinga indispensável. Combine com blusas de seda para eventos elegantes ou t-shirts de algodão nobre com tênis casual para o dia a dia.',
                'specifications' => [
                    'Tecido' => 'Crepe Plissé Première com brilho acetinado sutil',
                    'Forro' => 'Forro integral até a altura do joelho',
                    'Cós' => 'Estruturado na frente e elástico discreto nas costas',
                ],
            ],
            [
                'id' => 6,
                'title' => 'Camisa em Seda Pura com Gola Laço Romântica',
                'slug' => 'camisa-seda-pura-gola-laco',
                'category_slug' => 'blusas-camisas',
                'category_name' => 'Camisas & Blusas Finas',
                'brand' => 'Elisie',
                'badge' => 'EXCLUSIVIDADE',
                'price' => 379.90,
                'original_price' => 439.00,
                'rating' => 4.9,
                'reviews_count' => 88,
                'installments' => 4,
                'installment_value' => 94.97,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => false,
                'is_new' => true,
                'is_deal' => false,
                'stock' => 10,
                'model_info' => 'A modelo mede 1,73m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1534126511673-b6899657816a?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Off-White Puro', 'hex' => '#FAF8F5'],
                    ['name' => 'Rosa Chá Suave', 'hex' => '#E8C5C8'],
                    ['name' => 'Verde Sálvia', 'hex' => '#A3B19B'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'CAM-LACO-PP', 'price_delta' => 0, 'stock' => 2],
                        ['label' => 'P (38)', 'sku' => 'CAM-LACO-P', 'price_delta' => 0, 'stock' => 4],
                        ['label' => 'M (40)', 'sku' => 'CAM-LACO-M', 'price_delta' => 0, 'stock' => 3],
                        ['label' => 'G (42)', 'sku' => 'CAM-LACO-G', 'price_delta' => 0, 'stock' => 1],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '90 cm', 'waist' => '78 cm', 'hip' => '-', 'length' => '62 cm'],
                    ['size' => 'M (40)', 'bust' => '94 cm', 'waist' => '82 cm', 'hip' => '-', 'length' => '63 cm'],
                    ['size' => 'G (42)', 'bust' => '100 cm', 'waist' => '88 cm', 'hip' => '-', 'length' => '64 cm'],
                ],
                'summary' => 'A clássica gola laço que confere delicadeza e sofisticação imediata a qualquer combinação de alfaiataria.',
                'description' => 'Toque suave como uma carícia na pele. Botões forrados manualmente nos punhos e detalhe de pregas sutis nos ombros que emolduram o colo com recato e nobreza.',
                'specifications' => [
                    'Tecido' => 'Musseline de Seda com toque aveludado',
                    'Gola' => 'Faixa estendida permitindo laço volumoso ou nós soltos',
                    'Punhos' => 'Três botões forrados de acabamento artesanal',
                ],
            ],
            [
                'id' => 7,
                'title' => 'Vestido Midi Evasê Poá com Decote V Delicado e Manga Princesa',
                'slug' => 'vestido-midi-evase-poa-manga-princesa',
                'category_slug' => 'vestidos-midi',
                'category_name' => 'Vestidos Midi',
                'brand' => 'Elisie',
                'badge' => 'ROMÂNTICO',
                'price' => 459.90,
                'original_price' => 529.00,
                'rating' => 4.94,
                'reviews_count' => 160,
                'installments' => 5,
                'installment_value' => 91.98,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 14,
                'model_info' => 'A modelo mede 1,73m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Fundo Preto com Poá Branco', 'hex' => '#1A1A1A'],
                    ['name' => 'Fundo Baunilha com Poá Café', 'hex' => '#F2ECE4'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'VES-POA-PP', 'price_delta' => 0, 'stock' => 3],
                        ['label' => 'P (38)', 'sku' => 'VES-POA-P', 'price_delta' => 0, 'stock' => 5],
                        ['label' => 'M (40)', 'sku' => 'VES-POA-M', 'price_delta' => 0, 'stock' => 4],
                        ['label' => 'G (42)', 'sku' => 'VES-POA-G', 'price_delta' => 0, 'stock' => 2],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '88-92 cm', 'waist' => '68-72 cm', 'hip' => '96-100 cm', 'length' => '119 cm'],
                    ['size' => 'M (40)', 'bust' => '92-96 cm', 'waist' => '72-76 cm', 'hip' => '100-104 cm', 'length' => '120 cm'],
                    ['size' => 'G (42)', 'bust' => '96-102 cm', 'waist' => '76-82 cm', 'hip' => '104-110 cm', 'length' => '121 cm'],
                ],
                'summary' => 'A estampa poá clássica dos anos 50 com modelagem evasê contemporânea e manga princesa sutil.',
                'description' => 'Fluidez impressionante em crepe georgette levemente encorpado. Caimento perfeito que valoriza a cintura de maneira graciosa e modesta.',
                'specifications' => [
                    'Tecido' => 'Crepe Georgette Premium com estampa poá exclusiva',
                    'Forro' => 'Forro 100% viscose hipoalergênico',
                    'Decote' => 'Decote V modesto com costura de sustentação',
                ],
            ],
            [
                'id' => 8,
                'title' => 'Blazer Alfaiataria Duplo Botão em Tweed Francês',
                'slug' => 'blazer-alfaiataria-duplo-botao-tweed',
                'category_slug' => 'alfaiataria',
                'category_name' => 'Alfaiataria & Blazers',
                'brand' => 'Elisie',
                'badge' => 'ALTA COSTURA',
                'price' => 529.00,
                'original_price' => 619.00,
                'rating' => 4.96,
                'reviews_count' => 74,
                'installments' => 6,
                'installment_value' => 88.16,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 9,
                'model_info' => 'A modelo mede 1,75m e veste tamanho P.',
                'images' => [
                    'https://images.unsplash.com/photo-1548624149-f9b1859aa9d0?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1000&q=80',
                ],
                'colors' => [
                    ['name' => 'Tweed Areia com Dourado', 'hex' => '#D5C7B5'],
                    ['name' => 'Tweed Preto & Branco', 'hex' => '#2A2A2A'],
                ],
                'variations' => [
                    'type' => 'Tamanho',
                    'options' => [
                        ['label' => 'PP (36)', 'sku' => 'BLAZ-TWEED-PP', 'price_delta' => 0, 'stock' => 2],
                        ['label' => 'P (38)', 'sku' => 'BLAZ-TWEED-P', 'price_delta' => 0, 'stock' => 4],
                        ['label' => 'M (40)', 'sku' => 'BLAZ-TWEED-M', 'price_delta' => 0, 'stock' => 2],
                        ['label' => 'G (42)', 'sku' => 'BLAZ-TWEED-G', 'price_delta' => 0, 'stock' => 1],
                    ],
                ],
                'measurement_table' => [
                    ['size' => 'P (38)', 'bust' => '92 cm', 'waist' => '76 cm', 'hip' => '-', 'length' => '68 cm'],
                    ['size' => 'M (40)', 'bust' => '96 cm', 'waist' => '80 cm', 'hip' => '-', 'length' => '69 cm'],
                    ['size' => 'G (42)', 'bust' => '102 cm', 'waist' => '86 cm', 'hip' => '-', 'length' => '70 cm'],
                ],
                'summary' => 'Textura nobre de tweed clássico parisiense com botões de brasão em banho ouro velho.',
                'description' => 'A peça de terceira peça definitiva para transformar qualquer combinação simples em um look de extrema distinção e classe. Ombreiras delicadas estruturam a postura.',
                'specifications' => [
                    'Tecido' => 'Tweed Bouclé Importado com fios acetinados',
                    'Botões' => 'Botões em relevo de brasão acabamento dourado fosco',
                    'Forro' => 'Forro completo em cetim encorpado',
                ],
            ],
        ];
    }

    public static function findBySlug(string $slug): ?array
    {
        $products = self::getProducts();
        foreach ($products as $product) {
            if ($product['slug'] === $slug) {
                return $product;
            }
        }

        return null;
    }

    public static function findById(int $id): ?array
    {
        $products = self::getProducts();
        foreach ($products as $product) {
            if ($product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }
}
