<?php

namespace Tests\Feature;

use Tests\TestCase;

class EcommerceVisualTest extends TestCase
{
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ViaEcommerce');
        $response->assertSee('Ofertas Relâmpago do Dia');
        $response->assertSee('Mais Vendidos da Semana');
        $response->assertSee('Compre por Categoria');
    }

    public function test_catalog_page_loads_with_filters(): void
    {
        $response = $this->get('/catalogo');
        $response->assertStatus(200);
        $response->assertSee('Catálogo');
        $response->assertSee('Filtrar Resultados');

        $filtered = $this->get('/categoria/eletronicos');
        $filtered->assertStatus(200);
        $filtered->assertSee('Eletrônicos & Smart TVs');
    }

    public function test_predictive_search_api_returns_json(): void
    {
        $response = $this->getJson('/api/busca-preditiva?q=smart');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'products',
            'categories'
        ]);
    }

    public function test_pdp_page_loads_with_gallery_and_variations(): void
    {
        $response = $this->get('/produto/smart-tv-55-4k-uhd-crystal-hdr');
        $response->assertStatus(200);
        $response->assertSee('Smart TV 55" 4K UHD Crystal HDR com Alexa');
        $response->assertSee('Adicionar ao Carrinho');
        $response->assertSee('Simular Frete');
        $response->assertSeeText('Ficha Técnica & Especificações');
    }

    public function test_shipping_calculation_api(): void
    {
        $response = $this->postJson('/api/calcular-frete', ['cep' => '01310-100']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'cep',
            'options' => [
                '*' => ['name', 'deadline', 'price', 'price_formatted']
            ]
        ]);
    }

    public function test_cart_page_loads_successfully(): void
    {
        $response = $this->get('/carrinho');
        $response->assertStatus(200);
        $response->assertSee('Carrinho de Compras');
        $response->assertSee('Resumo do Pedido');
    }

    public function test_checkout_page_loads_successfully(): void
    {
        $response = $this->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Checkout Seguro');
        $response->assertSee('Identificação do Comprador');
    }

    public function test_order_success_page_loads_with_timeline_and_pix(): void
    {
        $response = $this->get('/pedido/DEMO123');
        $response->assertStatus(200);
        $response->assertSee('Pedido Recebido com Sucesso!');
        $response->assertSee('Aguardando Pagamento');
        $response->assertSee('Chave Pix Copia e Cola');
    }
}
