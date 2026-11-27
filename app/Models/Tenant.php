<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasDomains;

    public $incrementing = false;

    protected $keyType = 'string';

    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Columns stored directly on the tenants table rather than inside the JSON data column.
     *
     * @return array<int, string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'document',
            'email',
            'phone',
            'plan',
            'status',
            'is_active',
            'paused_at',
            'pause_reason',
            'trial_ends_at',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'paused_at' => 'datetime',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active && ! in_array($this->status, ['paused', 'suspended'], true);
    }

    public function pause(?string $reason = null): void
    {
        $this->update([
            'is_active' => false,
            'status' => 'paused',
            'paused_at' => now(),
            'pause_reason' => $reason,
        ]);
    }

    public function activate(): void
    {
        $this->update([
            'is_active' => true,
            'status' => 'active',
            'paused_at' => null,
            'pause_reason' => null,
        ]);
    }

    public function primaryDomain(): ?string
    {
        return $this->domains()->value('domain');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'tenant_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'tenant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'tenant_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'tenant_id');
    }

    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class, 'tenant_id');
    }

    public function storeSettings(): HasMany
    {
        return $this->hasMany(StoreSetting::class, 'tenant_id');
    }
}
