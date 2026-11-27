<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerCrmAndLgpdTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Tenant $elisieTenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->elisieTenant = Tenant::findOrFail('elisie');
        $this->admin = User::withoutGlobalScopes()
            ->where('tenant_id', 'elisie')
            ->where('email', 'admin@elisie.com.br')
            ->firstOrFail();
    }

    public function test_checkout_creates_and_links_customer_within_active_tenant(): void
    {
        $response = $this->post('http://elisie.viasolutions.com.br/checkout/processar', [
            'name' => 'Mariana Silveira',
            'email' => 'mariana.silveira@exemplo.com.br',
            'phone' => '(11) 99111-2233',
            'cpf' => '123.456.789-00',
            'cep' => '01310-100',
            'address' => 'Av. Paulista',
            'number' => '1000',
            'neighborhood' => 'Bela Vista',
            'city' => 'São Paulo',
            'state' => 'SP',
            'payment_method' => 'pix',
            'subtotal' => 299.90,
            'total' => 299.90,
            'marketing_opt_in' => true,
            'cart_items' => json_encode([]),
        ]);

        $response->assertRedirect();

        $customer = Customer::withoutGlobalScopes()
            ->where('tenant_id', 'elisie')
            ->where('email', 'mariana.silveira@exemplo.com.br')
            ->first();

        $this->assertNotNull($customer);
        $this->assertTrue($customer->marketing_opt_in);
        $this->assertNotNull($customer->lgpd_consent_at);

        $order = Order::withoutGlobalScopes()
            ->where('customer_id', $customer->id)
            ->first();

        $this->assertNotNull($order);
        $this->assertSame('elisie', $order->tenant_id);
    }

    public function test_customers_are_isolated_between_tenants(): void
    {
        $otherTenant = Tenant::create([
            'id' => 'outraloja',
            'name' => 'Outra Loja',
            'slug' => 'outraloja',
            'plan' => 'starter',
            'status' => 'active',
            'is_active' => true,
        ]);

        tenancy()->initialize($otherTenant);
        Customer::create([
            'name' => 'Cliente Exclusivo Outra Loja',
            'email' => 'exclusivo@outraloja.com.br',
        ]);
        tenancy()->end();

        tenancy()->initialize($this->elisieTenant);
        $this->assertNull(Customer::where('email', 'exclusivo@outraloja.com.br')->first());
        $this->assertNotNull(Customer::where('email', 'isabela.guimaraes@exemplo.com.br')->first());
        tenancy()->end();
    }

    public function test_admin_can_list_edit_view_orders_and_export_lgpd_data(): void
    {
        tenancy()->initialize($this->elisieTenant);
        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $customer = Customer::where('email', 'isabela.guimaraes@exemplo.com.br')->firstOrFail();

        $this->get('http://elisie.viasolutions.com.br/admin/customers')
            ->assertStatus(200)
            ->assertSee('Isabela Fontes Guimarães');

        Livewire::test(EditCustomer::class, ['record' => $customer->getRouteKey()])
            ->fillForm([
                'phone' => '(11) 99999-0000',
                'notes' => 'Cliente VIP - prefere entrega no período da tarde.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('(11) 99999-0000', $customer->fresh()->phone);

        Livewire::test(OrdersRelationManager::class, [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ])->assertCanSeeTableRecords($customer->orders);

        $export = $customer->fresh()->exportLgpdData();
        $this->assertSame('Isabela Fontes Guimarães', $export['customer']['name']);
        $this->assertCount(1, $export['orders']);
        $this->assertSame('EL-8291', $export['orders'][0]['code']);

        tenancy()->end();
    }

    public function test_lgpd_anonymization_clears_pii_on_customer_and_orders(): void
    {
        tenancy()->initialize($this->elisieTenant);
        $this->actingAs($this->admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $customer = Customer::where('email', 'helena.castro@exemplo.com.br')->firstOrFail();
        $order = $customer->orders()->firstOrFail();

        Livewire::test(ListCustomers::class)
            ->callTableAction('anonymize', $customer)
            ->assertHasNoTableActionErrors();

        $freshCustomer = $customer->fresh();
        $freshOrder = $order->fresh();

        $this->assertTrue($freshCustomer->isAnonymized());
        $this->assertNull($freshCustomer->phone);
        $this->assertNull($freshCustomer->cpf);
        $this->assertStringStartsWith('anonimizado-', $freshCustomer->email);

        $this->assertSame('', $freshOrder->customer_phone);
        $this->assertNull($freshOrder->customer_cpf);
        $this->assertSame('Dados anonimizados (LGPD)', $freshOrder->shipping_address);
        $this->assertSame('EL-9034', $freshOrder->code);
        $this->assertEquals(557.50, (float) $freshOrder->total);

        tenancy()->end();
    }
}
