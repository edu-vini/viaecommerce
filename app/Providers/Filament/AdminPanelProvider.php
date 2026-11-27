<?php

namespace App\Providers\Filament;

use App\Http\Middleware\InitializeStoreTenancy;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            ->brandName(fn (): string => tenant('name') ? tenant('name').' Admin' : 'Elisie Boutique Admin')
            ->favicon(asset('images/elisie-logo-bordeaux.png'))
            ->colors([
                'primary' => [
                    50 => '#fdf2f2',
                    100 => '#fde8e8',
                    200 => '#fbd5d5',
                    300 => '#f8b4b4',
                    400 => '#f08080',
                    500 => '#720403', // Elisie Bordeaux
                    600 => '#580302',
                    700 => '#470202',
                    800 => '#3a0202',
                    900 => '#2d0101',
                    950 => '#1a0101',
                ],
                'gold' => [
                    50 => '#fcfaf5',
                    100 => '#f7f2e4',
                    200 => '#eee3c3',
                    300 => '#e3ce97',
                    400 => '#d5b565',
                    500 => '#c49a45',
                    600 => '#aa7f33',
                    700 => '#88622b',
                    800 => '#714f29',
                    900 => '#5f4326',
                    950 => '#372312',
                ],
            ])
            ->navigationGroups([
                'Catálogo & Coleções',
                'Vendas & Pedidos',
                'Personalização & Loja',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                InitializeStoreTenancy::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
