<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\StoreSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelAndBrandingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@elisie.com.br')->first();
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Elisie');
    }

    public function test_admin_can_authenticate_with_credentials(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $test = Livewire::test(Login::class)
            ->set('data.email', 'admin@elisie.com.br')
            ->set('data.password', 'admin123')
            ->call('authenticate');

        $test->assertHasNoErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_category_resource_loads_with_hierarchical_data(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Categorias & Subcategorias');
        $response->assertSee('Vestidos Midi');
    }

    public function test_product_resource_loads_with_catalog_and_variations(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Produtos & Catálogo');
        $response->assertSee('Vestido Midi Aurora');
    }

    public function test_banner_resource_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/banners');
        $response->assertStatus(200);
        $response->assertSee('Banners & Destaques');
    }

    public function test_order_resource_loads_and_shows_orders(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('Pedidos');
        $response->assertSee('EL-8291');
    }

    public function test_order_status_can_be_updated_manually(): void
    {
        $order = Order::where('code', 'EL-8291')->first();
        $this->assertNotNull($order);

        $order->update([
            'status' => 'delivered',
            'status_notes' => 'Entregue em mãos para a cliente.',
            'status_updated_at' => now(),
        ]);

        $this->assertEquals('delivered', $order->fresh()->status);
        $this->assertEquals('Entregue', $order->fresh()->status_label);

        // Verify storefront displays updated status
        $response = $this->get('/pedido/EL-8291');
        $response->assertStatus(200);
        $response->assertSee('Entregue em mãos para a cliente.');
    }

    public function test_branding_management_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/manage-branding');
        $response->assertStatus(200);
        $response->assertSee('Customização Visual da Loja (Branding)');
        $response->assertSee('Paletas de Cores Pré-Cadastradas');
        $response->assertSee('Minimal Dark');
        $response->assertSee('Clean Blue');
        $response->assertSee('Emerald Green');
        $response->assertSee('Sunset Orange');
        $response->assertSee('Pastel Pink');
    }

    public function test_store_setting_custom_palette_is_persisted(): void
    {
        $setting = StoreSetting::current();
        $setting->update([
            'primary_color' => '#18181b',
            'primary_hover' => '#27272a',
            'active_theme' => 'minimal_dark',
        ]);

        $this->assertEquals('#18181b', $setting->fresh()->primary_color);
        $this->assertEquals('minimal_dark', $setting->fresh()->active_theme);
    }
}
