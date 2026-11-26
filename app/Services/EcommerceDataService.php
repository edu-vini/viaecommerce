<?php

namespace App\Services;

class EcommerceDataService
{
    public static function getCategories(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Eletrônicos & Smart TVs',
                'slug' => 'eletronicos',
                'icon' => 'tv',
                'description' => 'As melhores Smart TVs, áudio e vídeo de alta fidelidade',
                'count' => 14,
                'featured_image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'id' => 2,
                'name' => 'Smartphones & Acessórios',
                'slug' => 'smartphones',
                'icon' => 'smartphone',
                'description' => 'Smartphones 5G de última geração e acessórios premium',
                'count' => 18,
                'featured_image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'id' => 3,
                'name' => 'Informática & Gamer',
                'slug' => 'informatica',
                'icon' => 'laptop',
                'description' => 'Notebooks potentes, periféricos mecânicos e monitores ultra-wide',
                'count' => 22,
                'featured_image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'id' => 4,
                'name' => 'Casa Inteligente & Eletro',
                'slug' => 'casa-inteligente',
                'icon' => 'home',
                'description' => 'Automação residencial, cafeteiras e eletrodomésticos modernos',
                'count' => 12,
                'featured_image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'id' => 5,
                'name' => 'Moda & Calçados',
                'slug' => 'moda',
                'icon' => 'shirt',
                'description' => 'Estilo contemporâneo, calçados casuais e vestuário esportivo',
                'count' => 16,
                'featured_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'id' => 6,
                'name' => 'Esporte & Lazer',
                'slug' => 'esporte',
                'icon' => 'activity',
                'description' => 'Smartwatches, garrafas térmicas e equipamentos fitness',
                'count' => 9,
                'featured_image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=600&q=80'
            ],
        ];
    }

    public static function getProducts(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Smart TV 55" 4K UHD Crystal HDR com Alexa',
                'slug' => 'smart-tv-55-4k-uhd-crystal-hdr',
                'category_slug' => 'eletronicos',
                'category_name' => 'Eletrônicos & Smart TVs',
                'brand' => 'VisionTech',
                'badge' => 'OFERTA DO DIA',
                'price' => 2499.00,
                'original_price' => 2999.00,
                'rating' => 4.9,
                'reviews_count' => 142,
                'installments' => 10,
                'installment_value' => 249.90,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 18,
                'images' => [
                    'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1461151304267-38535e780c79?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1509281373149-e957c6296406?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    'type' => 'Voltagem',
                    'options' => [
                        ['label' => 'Bivolt (110V/220V)', 'sku' => 'TV-55-BIVOLT', 'price_delta' => 0, 'stock' => 12],
                        ['label' => '220V Especial', 'sku' => 'TV-55-220V', 'price_delta' => 0, 'stock' => 6]
                    ]
                ],
                'summary' => 'Painel Crystal 4K sem bordas aparentes, comando de voz Alexa integrado e taxa de atualização suave de 120Hz.',
                'description' => 'A Smart TV VisionTech 55" redefine sua sala de estar com resolução 4K real, contraste dinâmico HDR10+ e sistema de som imersivo Dolby Audio. Conecte seus consoles favoritos através de 3 portas HDMI 2.1 de baixa latência e controle tudo por voz via Alexa ou Google Assistant.',
                'specifications' => [
                    'Tela' => '55 polegadas LED 4K UHD (3840 x 2160)',
                    'Taxa de Atualização' => '120Hz com VRR e ALLM',
                    'Conectividade' => 'Wi-Fi 5 dual-band, Bluetooth 5.2, 3x HDMI, 2x USB',
                    'Sistema Operacional' => 'VisionOS com Netflix, Prime Video, Disney+ e Max',
                    'Consumo de Energia' => 'Selo Procel A (Super Econômica)',
                    'Garantia' => '12 meses pelo fabricante'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Smartphone Ultra Pro 5G 256GB Câmera 108MP',
                'slug' => 'smartphone-ultra-pro-5g-256gb',
                'category_slug' => 'smartphones',
                'category_name' => 'Smartphones & Acessórios',
                'brand' => 'NexusMobile',
                'badge' => 'MAIS VENDIDO',
                'price' => 3199.00,
                'original_price' => 3799.00,
                'rating' => 4.8,
                'reviews_count' => 389,
                'installments' => 12,
                'installment_value' => 266.58,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 25,
                'images' => [
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1580910051074-3eb694886505?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?auto=format&fit=crop&w=1000&q=80',
                ],
                'variations' => [
                    'type' => 'Cor',
                    'options' => [
                        ['label' => 'Grafite Titânio', 'sku' => 'NEX-5G-GRAFITE', 'price_delta' => 0, 'stock' => 14],
                        ['label' => 'Azul Meia-Noite', 'sku' => 'NEX-5G-AZUL', 'price_delta' => 0, 'stock' => 8],
                        ['label' => 'Branco Polar', 'sku' => 'NEX-5G-BRANCO', 'price_delta' => 50.00, 'stock' => 3]
                    ]
                ],
                'summary' => 'Processador Octa-Core de 4nm, tela AMOLED 120Hz com 2000 nits e carregamento ultrarrápido de 67W.',
                'description' => 'Desempenho incomparável para multitarefas, fotos em alta definição noturna e bateria que dura mais de 28 horas contínuas. Acompanha carregador rápido na caixa e capa protetora de silicone fosco.',
                'specifications' => [
                    'Processador' => 'Snapdragon 8 Gen 2 (4nm Octa-Core)',
                    'Memória RAM' => '12GB LPDDR5X (+ 8GB RAM Virtual)',
                    'Armazenamento' => '256GB UFS 4.0 ultra veloz',
                    'Câmeras' => 'Tripla: 108MP principal + 12MP Ultra-wide + 8MP Teleobjetiva 3x',
                    'Bateria' => '5000 mAh com carga de 0 a 100% em 34 minutos',
                    'Resistência' => 'Certificação IP68 (à prova d’água e poeira)'
                ]
            ],
            [
                'id' => 3,
                'title' => 'Fone de Ouvido Bluetooth Noise Cancelling Over-Ear',
                'slug' => 'fone-ouvido-bluetooth-noise-cancelling-over-ear',
                'category_slug' => 'eletronicos',
                'category_name' => 'Eletrônicos & Smart TVs',
                'brand' => 'AcousticPure',
                'badge' => 'LANÇAMENTO',
                'price' => 789.90,
                'original_price' => 999.00,
                'rating' => 4.95,
                'reviews_count' => 97,
                'installments' => 8,
                'installment_value' => 98.74,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => false,
                'is_new' => true,
                'is_deal' => true,
                'stock' => 40,
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=1000&q=80'
                ],
                'variations' => [
                    'type' => 'Acabamento',
                    'options' => [
                        ['label' => 'Preto Fosco Stealth', 'sku' => 'FONE-ANC-BLACK', 'price_delta' => 0, 'stock' => 25],
                        ['label' => 'Prata Alumínio Escovado', 'sku' => 'FONE-ANC-SILVER', 'price_delta' => 30.00, 'stock' => 15]
                    ]
                ],
                'summary' => 'Cancelamento de ruído ativo inteligente de -42dB, drivers de neodímio de 40mm e 60 horas de autonomia.',
                'description' => 'Isole-se do mundo e mergulhe em sua playlist com graves profundos e agudos cristalinos. Conforto anatômico com almofadas de espuma viscoelástica respirável.',
                'specifications' => [
                    'Autonomia' => 'Até 60h com ANC desligado e 45h com ANC ativo',
                    'Driver' => '40mm Dinâmico de Titânio',
                    'Conexão' => 'Bluetooth 5.3 com suporte multiponto (2 aparelhos simultâneos)',
                    'Microfones' => '4 microfones beamforming com supressão de vento',
                    'Peso' => '245g ultra leve'
                ]
            ],
            [
                'id' => 4,
                'title' => 'Notebook Gamer Core i7 16GB RTX 4060 1TB SSD',
                'slug' => 'notebook-gamer-core-i7-rtx-4060-1tb-ssd',
                'category_slug' => 'informatica',
                'category_name' => 'Informática & Gamer',
                'brand' => 'ApexGaming',
                'badge' => 'DESTAQUE',
                'price' => 6499.00,
                'original_price' => 7299.00,
                'rating' => 4.9,
                'reviews_count' => 64,
                'installments' => 12,
                'installment_value' => 541.58,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => false,
                'is_new' => true,
                'is_deal' => false,
                'stock' => 9,
                'images' => [
                    'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=1000&q=80'
                ],
                'variations' => [
                    'type' => 'Configuração de Teclado',
                    'options' => [
                        ['label' => 'ABNT2 com Ç RGB 4 Zonas', 'sku' => 'NOTE-RTX-ABNT2', 'price_delta' => 0, 'stock' => 7],
                        ['label' => 'US Internacional RGB', 'sku' => 'NOTE-RTX-US', 'price_delta' => 0, 'stock' => 2]
                    ]
                ],
                'summary' => 'Placa de vídeo NVIDIA GeForce RTX 4060 8GB GDDR6, tela IPS 165Hz e resfriamento líquido térmico.',
                'description' => 'Projetado para streamers, gamers exigentes e profissionais de renderização 3D. Execute os lançamentos mais pesados em taxa de quadros competitiva e máxima fidelidade gráfica com Ray Tracing e DLSS 3.5.',
                'specifications' => [
                    'Processador' => 'Intel Core i7-13700H (14 núcleos, até 5.0 GHz)',
                    'Vídeo' => 'NVIDIA GeForce RTX 4060 8GB GDDR6 TGP 140W',
                    'Tela' => '15.6" Full HD (1920x1080) IPS 165Hz 100% sRGB',
                    'Memória' => '16GB DDR5 5200MHz (Expansível até 64GB)',
                    'Armazenamento' => '1TB SSD NVMe M.2 Gen4 (leitura 7000MB/s)',
                    'Sistema' => 'Windows 11 Home Original'
                ]
            ],
            [
                'id' => 5,
                'title' => 'Cafeteira Expresso Automática com Moedor de Grãos',
                'slug' => 'cafeteira-expresso-automatica-moedor-graos',
                'category_slug' => 'casa-inteligente',
                'category_name' => 'Casa Inteligente & Eletro',
                'brand' => 'AromaBarista',
                'badge' => 'MAIS DESEJADO',
                'price' => 1899.90,
                'original_price' => 2299.00,
                'rating' => 4.75,
                'reviews_count' => 112,
                'installments' => 10,
                'installment_value' => 189.99,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 14,
                'images' => [
                    'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=1000&q=80'
                ],
                'variations' => [
                    'type' => 'Voltagem',
                    'options' => [
                        ['label' => '110V / 127V', 'sku' => 'CAF-AUTO-110V', 'price_delta' => 0, 'stock' => 9],
                        ['label' => '220V', 'sku' => 'CAF-AUTO-220V', 'price_delta' => 0, 'stock' => 5]
                    ]
                ],
                'summary' => 'Pressão italiana de 19 Bar, moedor de cerâmica integrado com 12 níveis e vaporizador de leite cremoso.',
                'description' => 'Desfrute do aroma do café fresco moído na hora com toque de um botão. Prepare Ristretto, Espresso, Lungo e Cappuccino com textura aveludada.',
                'specifications' => [
                    'Bomba' => '19 Bar de pressão importada da Itália',
                    'Capacidade Reservatório' => '1.8 Litros removível',
                    'Reservatório de Grãos' => '250g com vedação hermética protetora',
                    'Função Limpeza' => 'Ciclo de autoenxágue e descalcificação automática',
                    'Potência' => '1450W'
                ]
            ],
            [
                'id' => 6,
                'title' => 'Tênis Running Performance Pro com Placa de Carbono',
                'slug' => 'tenis-running-performance-pro-placa-carbono',
                'category_slug' => 'moda',
                'category_name' => 'Moda & Calçados',
                'brand' => 'VelocitySport',
                'badge' => 'ALTA PERFORMANCE',
                'price' => 599.90,
                'original_price' => 799.00,
                'rating' => 4.88,
                'reviews_count' => 205,
                'installments' => 6,
                'installment_value' => 99.98,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => false,
                'stock' => 32,
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=1000&q=80'
                ],
                'variations' => [
                    'type' => 'Tamanho (BR)',
                    'options' => [
                        ['label' => 'Tam 39', 'sku' => 'TENIS-CARB-39', 'price_delta' => 0, 'stock' => 6],
                        ['label' => 'Tam 40', 'sku' => 'TENIS-CARB-40', 'price_delta' => 0, 'stock' => 10],
                        ['label' => 'Tam 41', 'sku' => 'TENIS-CARB-41', 'price_delta' => 0, 'stock' => 8],
                        ['label' => 'Tam 42', 'sku' => 'TENIS-CARB-42', 'price_delta' => 0, 'stock' => 5],
                        ['label' => 'Tam 43', 'sku' => 'TENIS-CARB-43', 'price_delta' => 0, 'stock' => 3]
                    ]
                ],
                'summary' => 'Entressola com superespuma PEBA e placa rígida de carbono integral para propulsão em maratonas.',
                'description' => 'Construído para atletas que buscam quebrar seus recordes pessoais. O tecido superior em mesh de filamento único garante respirabilidade térmica máxima mesmo sob calor intenso.',
                'specifications' => [
                    'Drop' => '8mm (Calcanhar 38mm / Ponta 30mm)',
                    'Peso' => '198g (Tamanho 40)',
                    'Solado' => 'Borracha de alta aderência continental em pista molhada',
                    'Indicação' => 'Treinos de ritmo e provas de 5k a 42k'
                ]
            ],
            [
                'id' => 7,
                'title' => 'Smartwatch Titanium GPS AMOLED com ECG e Oxímetro',
                'slug' => 'smartwatch-titanium-gps-amoled-ecg',
                'category_slug' => 'esporte',
                'category_name' => 'Esporte & Lazer',
                'brand' => 'PulseWatch',
                'badge' => 'NOVO',
                'price' => 1199.00,
                'original_price' => 1499.00,
                'rating' => 4.7,
                'reviews_count' => 78,
                'installments' => 10,
                'installment_value' => 119.90,
                'pix_discount_percent' => 5,
                'is_featured' => false,
                'is_best_seller' => false,
                'is_new' => true,
                'is_deal' => true,
                'stock' => 17,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=1000&q=80'
                ],
                'variations' => [
                    'type' => 'Pulseira',
                    'options' => [
                        ['label' => 'Silicone Preto Esportivo', 'sku' => 'WATCH-SILICONE', 'price_delta' => 0, 'stock' => 11],
                        ['label' => 'Couro Legítimo Marrom', 'sku' => 'WATCH-COUROMARROM', 'price_delta' => 70.00, 'stock' => 6]
                    ]
                ],
                'summary' => 'Caixa em titânio aeroespacial, vidro de safira inquebrável e bateria de até 14 dias de uso contínuo.',
                'description' => 'Monitoramento de saúde contínuo 24/7 com eletrocardiograma (ECG), oxímetro de pulso SpO2, sensor de temperatura corporal e mais de 100 modalidades esportivas reconhecidas automaticamente.',
                'specifications' => [
                    'Caixa' => '46mm em Titânio Grau 2',
                    'Tela' => '1.43" AMOLED com Always-on Display e 1500 nits',
                    'Resistência' => '5 ATM (mergulho até 50 metros)',
                    'Bateria' => '530 mAh (até 14 dias em modo econômico)',
                    'GPS' => 'Dual-Band GNSS de 5 satélites integrados'
                ]
            ],
            [
                'id' => 8,
                'title' => 'Monitor Gamer Curvo 34" WQHD 165Hz HDR 1ms',
                'slug' => 'monitor-gamer-curvo-34-wqhd-165hz',
                'category_slug' => 'informatica',
                'category_name' => 'Informática & Gamer',
                'brand' => 'ApexGaming',
                'badge' => 'OFERTA IMPERDÍVEL',
                'price' => 2899.00,
                'original_price' => 3499.00,
                'rating' => 4.92,
                'reviews_count' => 83,
                'installments' => 12,
                'installment_value' => 241.58,
                'pix_discount_percent' => 5,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new' => false,
                'is_deal' => true,
                'stock' => 11,
                'images' => [
                    'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1000&q=80'
                ],
                'variations' => [
                    'type' => 'Voltagem',
                    'options' => [
                        ['label' => 'Fonte Bivolt Automática', 'sku' => 'MON-34-BIVOLT', 'price_delta' => 0, 'stock' => 11]
                    ]
                ],
                'summary' => 'Curvatura imersiva 1500R, proporção UltraWide 21:9 e taxa de resposta ultrarrápida de 1ms MPRT.',
                'description' => 'Aumente seu campo de visão nos jogos e multiplique sua produtividade no trabalho com uma área útil 33% maior que monitores tradicionais. Suporte com ajuste completo de altura, inclinação e giro.',
                'specifications' => [
                    'Painel' => '34" VA Curvo 1500R WQHD (3440 x 1440)',
                    'Frequência' => '165Hz com AMD FreeSync Premium e G-Sync Compatible',
                    'Tempo de Resposta' => '1ms (MPRT) / 4ms (GtG)',
                    'Conexões' => '2x DisplayPort 1.4, 2x HDMI 2.0, Hub USB 3.0',
                    'Recursos' => 'HDR400, Modo Leitura sem cintilação (Flicker-Free)'
                ]
            ]
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
