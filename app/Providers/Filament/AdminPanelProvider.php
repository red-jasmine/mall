<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RedJasmine\FilamentCard\FilamentCardPlugin;
use RedJasmine\FilamentOrder\FilamentOrderPlugin;
use RedJasmine\FilamentProduct\Clusters\Product\Resources\ProductResource;
use Redjasmine\FilamentProduct\FilamentProductPlugin;
use Firefly\FilamentBlog\Blog;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel) : Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                         'primary' => Color::Amber,
                     ])

            //->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([
                        Pages\Dashboard::class,
                    ])
            ->resources([

                        ])
            ->widgets([
                          Widgets\AccountWidget::class,
                          Widgets\FilamentInfoWidget::class,
                      ])
            ->middleware([
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
                             ])
            ->databaseNotifications()

            ->sidebarWidth('10rem')
//            ->topNavigation()
            ->maxContentWidth(MaxWidth::Full)
            ->plugins([

                          FilamentProductPlugin::make(),
                          FilamentCardPlugin::make(),
                          FilamentOrderPlugin::make(),
                      ]);
    }
}
