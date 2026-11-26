<?php

namespace Tests\Feature;

use Tests\TestCase;

class EcommerceVisualTest extends TestCase
{
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Hadassa Braz');
        $response->assertSee('Concept Atelier');
        $response->assertSee('Lançamentos Exclusivos');
        $response->assertSee('Os Mais Desejados');
        $response->assertSee('Compre por Categoria');
    }

    public function test_catalog_page_loads_with_filters(): void
    {
        $response = $this->get('/catalogo');
        $response->assertStatus(200);
        $response->assertSee('Coleção');
        $response->assertSee('Filtrar Coleção');

        $filtered = $this->get('/categoria/vestidos-midi');
        $filtered->assertStatus(200);
        $filtered->assertSee('Vestidos Midi');
    }

    public function test_predictive_search_api_returns_json(): void
    {
        $response = $this->getJson('/api/busca-preditiva?q=vestido');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'products',
            'categories'
        ]);
    }

    public function test_pdp_page_loads_with_gallery_and_variations(): void
    {
        $response = $this->get('/produto/vestido-midi-aurora-linho-puro');
        $response->assertStatus(200);
        $response->assertSee('Vestido Midi Aurora em Linho Puro com Cinto Faixa');
        $response->assertSee('Adicionar à Sacola');
        $response->assertSee('Tabela de Medidas');
        $response->assertSeeText('Ficha Técnica & Cuidados');
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
        $response->assertSee('Sacola de Compras');
        $response->assertSee('Resumo da Sacola');
    }

    public function test_checkout_page_loads_successfully(): void
    {
        $response = $this->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Checkout Seguro');
        $response->assertSee('Seus Dados Pessoais');
    }

    public function test_order_success_page_loads_with_timeline_and_pix(): void
    {
        $response = $this->get('/pedido/DEMO123');
        $response->assertStatus(200);
        $response->assertSee('Pedido Recebido com Sucesso!');
        $response->assertSee('Aguardando Pix');
        $response->assertSee('Chave Pix Copia e Cola');
    }
}
