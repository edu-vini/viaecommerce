<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\SuperAdmin\Resources\TenantResource\Pages\CreateTenant;
use App\Filament\SuperAdmin\Resources\TenantResource\Pages\ListTenants;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\Tenant;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MultiTenancyAndSuperAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected Tenant $elisieTenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->superAdmin = User::withoutGlobalScopes()
            ->where('email', 'superadmin@viasolutions.com.br')
            ->firstOrFail();

        $this->elisieTenant = Tenant::findOrFail('elisie');
    }

    public function test_dynamic_subdomain_resolves_correct_tenant_and_isolates_data(): void
    {
        $novaEmpresa = Tenant::create([
            'id' => 'novaempresa',
            'name' => 'Nova Empresa Store',
            'slug' => 'novaempresa',
            'email' => 'contato@novaempresa.com.br',
            'plan' => 'pro',
            'status' => 'active',
            'is_active' => true,
        ]);
        $novaEmpresa->domains()->create(['domain' => 'novaempresa.viasolutions.com.br']);

        tenancy()->initialize($novaEmpresa);

        StoreSetting::create([
            'store_name' => 'Nova Empresa Store',
            'slogan' => 'Tecnologia & Estilo',
            'contact_email' => 'contato@novaempresa.com.br',
        ]);

        $cat = Category::create([
            'name' => 'Eletrônicos Exclusivos',
            'slug' => 'eletronicos-exclusivos',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $cat->id,
            'title' => 'Fone Studio Pro NovaEmpresa',
            'slug' => 'fone-studio-pro-novaempresa',
            'sku' => 'NOVA-FONE-01',
            'price' => 599.90,
            'stock' => 10,
            'is_active' => true,
            'is_featured' => true,
            'is_new' => true,
            'is_best_seller' => true,
        ]);

        tenancy()->end();

        // Tenant A (Elisie) sees its own store & products, never Tenant B's
        $elisieResponse = $this->get('http://elisie.viasolutions.com.br/');
        $elisieResponse->assertStatus(200);
        $elisieResponse->assertSee('Elisie Concept');
        $elisieResponse->assertDontSee('Fone Studio Pro NovaEmpresa');

        // Tenant B (NovaEmpresa) sees its own store & products, never Tenant A's
        $novaResponse = $this->get('http://novaempresa.viasolutions.com.br/');
        $novaResponse->assertStatus(200);
        $novaResponse->assertSee('Nova Empresa Store');
        $novaResponse->assertSee('Fone Studio Pro NovaEmpresa');
        $novaResponse->assertDontSee('Vestido Midi Aurora');

        // Direct model-level scoping check
        tenancy()->initialize($novaEmpresa);
        $this->assertSame(1, Product::count());
        $this->assertSame(0, Order::count());
        tenancy()->end();
    }

    public function test_paused_tenant_returns_503_and_reactivating_restores_access(): void
    {
        $this->elisieTenant->pause('Fatura em aberto');

        $pausedResponse = $this->get('http://elisie.viasolutions.com.br/');
        $pausedResponse->assertStatus(503);
        $pausedResponse->assertSee('Loja Pausada');
        $pausedResponse->assertSee('Fatura em aberto');

        $pausedAdminResponse = $this->get('http://elisie.viasolutions.com.br/admin/login');
        $pausedAdminResponse->assertStatus(503);

        $this->elisieTenant->activate();

        $activeResponse = $this->get('http://elisie.viasolutions.com.br/');
        $activeResponse->assertStatus(200);
        $activeResponse->assertSee('Elisie Concept');
    }

    public function test_superadmin_panel_is_restricted_to_central_domains_and_superadmin_users(): void
    {
        // Central domain: SuperAdmin can access
        $this->actingAs($this->superAdmin)
            ->get('http://localhost/superadmin')
            ->assertStatus(200);

        $this->actingAs($this->superAdmin)
            ->get('http://ecommerce.viasolutions.com.br/superadmin')
            ->assertStatus(200);

        $this->actingAs($this->superAdmin)
            ->get('http://localhost/superadmin/tenants')
            ->assertStatus(200)
            ->assertSee('Elisie Concept');

        // Central domain: Tenant admin cannot access SuperAdmin
        $tenantAdmin = User::withoutGlobalScopes()
            ->where('email', 'admin@elisie.com.br')
            ->firstOrFail();

        $this->actingAs($tenantAdmin)
            ->get('http://localhost/superadmin')
            ->assertStatus(403);

        // Tenant subdomain: SuperAdmin route is blocked (404)
        $this->actingAs($this->superAdmin)
            ->get('http://elisie.viasolutions.com.br/superadmin')
            ->assertStatus(404);
    }

    public function test_superadmin_can_create_pause_and_activate_tenant_via_resource(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setCurrentPanel(Filament::getPanel('superadmin'));

        Livewire::test(CreateTenant::class)
            ->fillForm([
                'name' => 'Boutique Lumina',
                'slug' => 'lumina',
                'id' => 'lumina',
                'email' => 'contato@lumina.com.br',
                'plan' => 'pro',
                'status' => 'active',
                'is_active' => true,
                'admin_name' => 'Gestora Lumina',
                'admin_email' => 'admin@lumina.com.br',
                'admin_password' => 'lumina123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $lumina = Tenant::find('lumina');
        $this->assertNotNull($lumina);
        $this->assertDatabaseHas('domains', [
            'tenant_id' => 'lumina',
            'domain' => 'lumina.viasolutions.com.br',
        ]);
        $this->assertDatabaseHas('store_settings', [
            'tenant_id' => 'lumina',
            'store_name' => 'Boutique Lumina',
            'active_theme' => 'via_solutions',
            'primary_color' => '#004274',
            'accent_color' => '#00a86b',
        ]);
        $this->assertDatabaseHas('users', [
            'tenant_id' => 'lumina',
            'email' => 'admin@lumina.com.br',
        ]);

        // Pause via table action
        Livewire::test(ListTenants::class)
            ->callTableAction('pause', $lumina, data: [
                'pause_reason' => 'Manutenção programada',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertFalse($lumina->fresh()->isActive());
        $this->assertSame('Manutenção programada', $lumina->fresh()->pause_reason);

        // Activate via table action
        Livewire::test(ListTenants::class)
            ->callTableAction('activate', $lumina->fresh())
            ->assertHasNoTableActionErrors();

        $this->assertTrue($lumina->fresh()->isActive());

        // Blank store ("none") has zero demo products and does not leak Elisie products
        tenancy()->initialize($lumina);
        $this->assertSame(0, Product::count());
        $this->assertSame(0, Category::count());
        tenancy()->end();

        $luminaResponse = $this->get('http://lumina.viasolutions.com.br/');
        $luminaResponse->assertStatus(200);
        $luminaResponse->assertSee('Boutique Lumina');
        $luminaResponse->assertDontSee('Vestido Midi Aurora');
    }

    public function test_superadmin_can_create_tenant_with_segment_template(): void
    {
        $this->actingAs($this->superAdmin);
        Filament::setCurrentPanel(Filament::getPanel('superadmin'));

        Livewire::test(CreateTenant::class)
            ->fillForm([
                'name' => 'ViaTech Store',
                'slug' => 'viatech',
                'id' => 'viatech',
                'email' => 'contato@viatech.com.br',
                'template' => 'electronics',
                'plan' => 'pro',
                'status' => 'active',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $viatech = Tenant::findOrFail('viatech');
        tenancy()->initialize($viatech);

        $this->assertSame(3, Category::count());
        $this->assertSame(4, Product::count());
        $this->assertNotNull(Product::where('slug', 'headphone-bluetooth-studio-anc')->first());
        tenancy()->end();

        $response = $this->get('http://viatech.viasolutions.com.br/');
        $response->assertStatus(200);
        $response->assertSee('ViaTech Store');
        $response->assertSee('Headphone Bluetooth Studio ANC Pro');
        $response->assertDontSee('Vestido Midi Aurora');
    }
}
