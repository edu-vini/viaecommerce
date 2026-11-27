<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class StoreSetting extends Model
{
    use BelongsToTenant, HasFactory;

    /** @var array<string, self> */
    protected static array $cachedInstances = [];

    protected static function tenantKey(): string
    {
        return (string) (tenant('id') ?? 'central');
    }

    protected static function cacheKey(): string
    {
        return 'store_settings.current.'.static::tenantKey();
    }

    protected static function booted(): void
    {
        static::saved(function () {
            unset(static::$cachedInstances[static::tenantKey()]);
            Cache::forget(static::cacheKey());
        });
        static::deleted(function () {
            unset(static::$cachedInstances[static::tenantKey()]);
            Cache::forget(static::cacheKey());
        });
    }

    public static function clearInstanceCache(): void
    {
        static::$cachedInstances = [];
    }

    protected $fillable = [
        'tenant_id',
        'store_name',
        'slogan',
        'logo_path',
        'favicon_path',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'contact_address',
        'social_instagram',
        'social_facebook',
        'social_pinterest',
        'social_tiktok',
        'active_theme',
        'primary_color',
        'primary_hover',
        'primary_light',
        'secondary_color',
        'accent_color',
        'accent_hover',
        'surface_color',
        'border_color',
    ];

    /**
     * Get the single store settings instance for the active tenant.
     */
    public static function current(): self
    {
        $tenantKey = static::tenantKey();

        if (isset(static::$cachedInstances[$tenantKey])) {
            return static::$cachedInstances[$tenantKey];
        }

        $isElisie = tenant('id') === 'elisie' || tenant('id') === null;
        $viaPreset = static::presets()['via_solutions'];

        $defaults = $isElisie
            ? [
                'store_name' => tenant('name') ?? 'Elisie Concept',
                'slogan' => 'Moderna • Elegante • Modesta',
                'contact_email' => tenant('email') ?? 'atendimento@elisie.com.br',
                'contact_phone' => tenant('phone') ?? '(11) 99842-1200',
                'contact_whatsapp' => '5511998421200',
                'contact_address' => 'Av. Paulista, 1800 - Jardins, São Paulo - SP',
                'social_instagram' => '@elisieconcept',
                'social_facebook' => 'elisieconcept',
                'social_pinterest' => 'elisieconcept',
                'social_tiktok' => '@elisieconcept',
                'active_theme' => 'default',
                'primary_color' => '#720403',
                'primary_hover' => '#580302',
                'primary_light' => '#fdf8f8',
                'secondary_color' => '#1c1917',
                'accent_color' => '#c49a45',
                'accent_hover' => '#a88132',
                'surface_color' => '#faf8f5',
                'border_color' => '#eee5db',
            ]
            : [
                'store_name' => tenant('name') ?? 'Minha Loja',
                'slogan' => 'Sua loja online • Tecnologia Via Solutions',
                'logo_path' => 'images/via-solutions-logo.png',
                'favicon_path' => 'images/via-solutions-logo.png',
                'contact_email' => tenant('email') ?? 'contato@viasolutions.com.br',
                'contact_phone' => tenant('phone') ?? '',
                'active_theme' => 'via_solutions',
                'primary_color' => $viaPreset['primary_color'],
                'primary_hover' => $viaPreset['primary_hover'],
                'primary_light' => $viaPreset['primary_light'],
                'secondary_color' => $viaPreset['secondary_color'],
                'accent_color' => $viaPreset['accent_color'],
                'accent_hover' => $viaPreset['accent_hover'],
                'surface_color' => $viaPreset['surface_color'],
                'border_color' => $viaPreset['border_color'],
            ];

        if (app()->environment('testing')) {
            return static::$cachedInstances[$tenantKey] = static::firstOrCreate([], $defaults);
        }

        try {
            return static::$cachedInstances[$tenantKey] = Cache::remember(static::cacheKey(), 3600, function () use ($defaults) {
                return static::firstOrCreate([], $defaults);
            });
        } catch (\Throwable $e) {
            return new static($defaults);
        }
    }

    public static function clearCache(): void
    {
        unset(static::$cachedInstances[static::tenantKey()]);
        Cache::forget(static::cacheKey());
    }

    /**
     * Preset palettes available in 1-click
     */
    public static function presets(): array
    {
        return [
            'via_solutions' => [
                'name' => 'Sem padrão (Cores Via Solutions)',
                'primary_color' => '#004274',
                'primary_hover' => '#002f54',
                'primary_light' => '#f0f6fa',
                'secondary_color' => '#0f172a',
                'accent_color' => '#00a86b',
                'accent_hover' => '#008756',
                'surface_color' => '#f8fafc',
                'border_color' => '#e2e8f0',
            ],
            'default' => [
                'name' => 'Elisie Bordeaux (Original)',
                'primary_color' => '#720403',
                'primary_hover' => '#580302',
                'primary_light' => '#fdf8f8',
                'secondary_color' => '#1c1917',
                'accent_color' => '#c49a45',
                'accent_hover' => '#a88132',
                'surface_color' => '#faf8f5',
                'border_color' => '#eee5db',
            ],
            'minimal_dark' => [
                'name' => 'Minimal Dark / Noir Elegance',
                'primary_color' => '#18181b',
                'primary_hover' => '#27272a',
                'primary_light' => '#fafafa',
                'secondary_color' => '#09090b',
                'accent_color' => '#720403',
                'accent_hover' => '#580302',
                'surface_color' => '#fdfdfd',
                'border_color' => '#e4e4e7',
            ],
            'clean_blue' => [
                'name' => 'Clean Blue (Royal Navy)',
                'primary_color' => '#1e3a8a',
                'primary_hover' => '#172554',
                'primary_light' => '#eff6ff',
                'secondary_color' => '#0f172a',
                'accent_color' => '#38bdf8',
                'accent_hover' => '#0284c7',
                'surface_color' => '#f8fafc',
                'border_color' => '#e2e8f0',
            ],
            'emerald_green' => [
                'name' => 'Emerald Green (Luxo Botânico)',
                'primary_color' => '#064e3b',
                'primary_hover' => '#022c22',
                'primary_light' => '#ecfdf5',
                'secondary_color' => '#062016',
                'accent_color' => '#d4af37',
                'accent_hover' => '#b38f24',
                'surface_color' => '#f7fcf9',
                'border_color' => '#d1fae5',
            ],
            'sunset_orange' => [
                'name' => 'Sunset Orange (Terracota Chic)',
                'primary_color' => '#9a3412',
                'primary_hover' => '#7c2d12',
                'primary_light' => '#fff7ed',
                'secondary_color' => '#271b14',
                'accent_color' => '#ea580c',
                'accent_hover' => '#c2410c',
                'surface_color' => '#fffaf5',
                'border_color' => '#fed7aa',
            ],
            'pastel_pink' => [
                'name' => 'Pastel Pink / Rosé Marsala',
                'primary_color' => '#8e3646',
                'primary_hover' => '#722735',
                'primary_light' => '#fdf8f8',
                'secondary_color' => '#2c1a1f',
                'accent_color' => '#c49a45',
                'accent_hover' => '#a88132',
                'surface_color' => '#fefafb',
                'border_color' => '#ebd9dc',
            ],
        ];
    }
}
