<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\TenantResource\Pages;

use App\Filament\SuperAdmin\Resources\TenantResource;
use App\Models\Tenant;
use App\Models\User;
use App\Services\StoreTemplateService;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function afterCreate(): void
    {
        /** @var Tenant $tenant */
        $tenant = $this->record;

        // Provision default subdomain if no domain was added manually
        if ($tenant->domains()->count() === 0) {
            $baseDomain = config('tenancy.base_domain', 'viasolutions.com.br');
            $tenant->domains()->create([
                'domain' => "{$tenant->slug}.{$baseDomain}",
            ]);
        }

        $rawState = $this->form->getRawState();
        $templateKey = (string) ($rawState['template'] ?? 'none');

        StoreTemplateService::applyToTenant($tenant, $templateKey);

        // Optionally provision initial store admin user
        $adminEmail = $rawState['admin_email'] ?? null;
        $adminPassword = $rawState['admin_password'] ?? null;

        if (filled($adminEmail) && filled($adminPassword)) {
            User::withoutGlobalScopes()->create([
                'tenant_id' => $tenant->id,
                'name' => filled($rawState['admin_name'] ?? null) ? $rawState['admin_name'] : $tenant->name.' Admin',
                'email' => $adminEmail,
                'password' => $adminPassword,
                'is_super_admin' => false,
                'email_verified_at' => now(),
            ]);
        }
    }
}
