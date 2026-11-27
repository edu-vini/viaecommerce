<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Customer extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'cpf',
        'default_zipcode',
        'default_address',
        'default_number',
        'default_complement',
        'default_neighborhood',
        'default_city',
        'default_state',
        'notes',
        'marketing_opt_in',
        'lgpd_consent_at',
        'anonymized_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'marketing_opt_in' => 'boolean',
            'lgpd_consent_at' => 'datetime',
            'anonymized_at' => 'datetime',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAnonymized(): bool
    {
        return $this->anonymized_at !== null;
    }

    /**
     * Create or update a customer from checkout/order payload.
     *
     * @param  array<string, mixed>  $data
     */
    public static function syncFromOrderData(array $data): self
    {
        $email = strtolower(trim((string) ($data['customer_email'] ?? '')));

        $customer = static::withTrashed()->firstOrNew([
            'email' => $email,
        ]);

        if ($customer->trashed()) {
            $customer->restore();
        }

        if ($customer->isAnonymized()) {
            return $customer;
        }

        $customer->fill([
            'name' => $data['customer_name'] ?? $customer->name ?? 'Cliente',
            'phone' => $data['customer_phone'] ?? $customer->phone,
            'cpf' => $data['customer_cpf'] ?? $customer->cpf,
            'default_zipcode' => $data['shipping_zipcode'] ?? $customer->default_zipcode,
            'default_address' => $data['shipping_address'] ?? $customer->default_address,
            'default_number' => $data['shipping_number'] ?? $customer->default_number,
            'default_complement' => $data['shipping_complement'] ?? $customer->default_complement,
            'default_neighborhood' => $data['shipping_neighborhood'] ?? $customer->default_neighborhood,
            'default_city' => $data['shipping_city'] ?? $customer->default_city,
            'default_state' => $data['shipping_state'] ?? $customer->default_state,
        ]);

        if (array_key_exists('marketing_opt_in', $data)) {
            $customer->marketing_opt_in = (bool) $data['marketing_opt_in'];
        }

        if (! $customer->lgpd_consent_at) {
            $customer->lgpd_consent_at = now();
        }

        $customer->save();

        return $customer;
    }

    /**
     * Anonymize personal data in compliance with LGPD Art. 18 while preserving fiscal order records.
     */
    public function anonymize(): void
    {
        $anonymizedLabel = "Titular Anonimizado #{$this->id}";
        $anonymizedEmail = "anonimizado-{$this->id}@lgpd.invalid";

        $this->update([
            'name' => $anonymizedLabel,
            'email' => $anonymizedEmail,
            'phone' => null,
            'cpf' => null,
            'default_zipcode' => null,
            'default_address' => null,
            'default_number' => null,
            'default_complement' => null,
            'default_neighborhood' => null,
            'default_city' => null,
            'default_state' => null,
            'notes' => null,
            'marketing_opt_in' => false,
            'anonymized_at' => now(),
        ]);

        $this->orders()->update([
            'customer_name' => $anonymizedLabel,
            'customer_email' => $anonymizedEmail,
            'customer_phone' => '',
            'customer_cpf' => null,
            'shipping_address' => 'Dados anonimizados (LGPD)',
            'shipping_number' => '',
            'shipping_complement' => null,
        ]);
    }

    /**
     * Build a structured data export for LGPD data portability/access requests.
     *
     * @return array<string, mixed>
     */
    public function exportLgpdData(): array
    {
        $this->loadMissing('orders.items');

        return [
            'exported_at' => now()->toIso8601String(),
            'lgpd_basis' => 'Art. 18 da Lei nº 13.709/2018 (Acesso e Portabilidade)',
            'customer' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'cpf' => $this->cpf,
                'address' => [
                    'zipcode' => $this->default_zipcode,
                    'street' => $this->default_address,
                    'number' => $this->default_number,
                    'complement' => $this->default_complement,
                    'neighborhood' => $this->default_neighborhood,
                    'city' => $this->default_city,
                    'state' => $this->default_state,
                ],
                'marketing_opt_in' => $this->marketing_opt_in,
                'lgpd_consent_at' => $this->lgpd_consent_at?->toIso8601String(),
                'anonymized_at' => $this->anonymized_at?->toIso8601String(),
                'created_at' => $this->created_at?->toIso8601String(),
            ],
            'orders' => $this->orders->map(fn (Order $order): array => [
                'code' => $order->code,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'total' => (float) $order->total,
                'created_at' => $order->created_at?->toIso8601String(),
                'items' => $order->items->map(fn (OrderItem $item): array => [
                    'title' => $item->product_title,
                    'variation' => $item->variation,
                    'quantity' => $item->quantity,
                    'total' => (float) $item->total,
                ])->all(),
            ])->all(),
        ];
    }
}
