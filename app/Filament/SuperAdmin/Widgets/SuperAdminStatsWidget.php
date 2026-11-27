<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuperAdminStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)
            ->whereNotIn('status', ['paused', 'suspended'])
            ->count();
        $pausedTenants = $totalTenants - $activeTenants;

        $totalOrders = Order::withoutGlobalScopes()->count();
        $totalRevenue = (float) Order::withoutGlobalScopes()
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        $totalProducts = Product::withoutGlobalScopes()->count();

        return [
            Stat::make('Empresas Cadastradas', (string) $totalTenants)
                ->description("{$activeTenants} ativas • {$pausedTenants} pausadas")
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),

            Stat::make('Lojas Ativas', (string) $activeTenants)
                ->description('Operando em subdomínios dinâmicos')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pedidos na Plataforma', (string) $totalOrders)
                ->description("{$totalProducts} produtos cadastrados")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Volume Transacionado (GMV)', 'R$ '.number_format($totalRevenue, 2, ',', '.'))
                ->description('Soma de pedidos não cancelados')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
