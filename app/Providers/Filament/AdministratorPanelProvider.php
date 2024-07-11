<?php

namespace App\Providers\Filament;

use App\Http\Middleware\ApplyTenantScopes;
use App\Livewire\MyCustomPersonalInfo;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\MaxWidth;
//use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Tenancy\EditTeamProfile;
use App\Livewire\CustomPersonalInfo;
// use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Models\Team;
use Filament\Navigation\MenuItem;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
//use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;

class AdministratorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->viteTheme('resources/css/filament/administrator/theme.css')
            ->id('administrator')
            ->path('administrator')
            ->login()
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->font('Poppins')
            //->profile(EditTeamProfile::class)
            //->profile(isSimple: false)
            ->plugins([
                FilamentFullCalendarPlugin::make(),
                TableLayoutTogglePlugin::make()
                    ->setDefaultLayout('grid')
                    ->persistLayoutInLocalStorage(true) // allow user to keep his layout preference in his local storage
                    ->shareLayoutBetweenPages(false) // allow all tables to share the layout option (requires persistLayoutInLocalStorage to be true)
                    ->displayToggleAction() // used to display the toogle button automatically, on the desired filament hook (defaults to table bar)
                    ->listLayoutButtonIcon('heroicon-o-list-bullet')
                    ->gridLayoutButtonIcon('heroicon-o-squares-2x2'),
				\BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                BreezyCore::make()
                ->myProfile(
                    shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                    shouldRegisterNavigation: true, // Adds a main navigation item for the My Profile page (default = false)
                    navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                    hasAvatars: true, // Enables the avatar upload form component (default = false)
                    slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                )
                    ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
                    ->myProfileComponents([CustomPersonalInfo::class])

                    ->myProfileComponents([
                    // 'personal_info' => CustomPersonalInfo::class,
                   'personal_info' => MyCustomPersonalInfo::class, // replaces UpdatePassword component with your own.
                    // 'two_factor_authentication' => ,
                    // 'sanctum_tokens' =>
                ])
                ->enableTwoFactorAuthentication(
                    force: false, // force the user to enable 2FA before they can use the application (default = false)
                    //action: CustomTwoFactorPage::class // optionally, use a custom 2FA page
                )
			])
            ->colors([
                'primary' => Color::Orange,
                'secondary' => Color::Blue,
            ])
            ->brandLogo(asset('images/sumedha.png'))
            ->brandLogoHeight('3rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
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
            ->maxContentWidth(MaxWidth::Full)
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(Team::class)
            /*->tenantMiddleware([
                ApplyTenantScopes::class,
            ], isPersistent: true)*/
            //->tenantRegistration(RegisterTeam::class)
            //->tenantProfile(EditTeamProfile::class)
            // ->tenantMenuItems([
            //     'profile' => MenuItem::make()->label('Edit team profile'),
            //     // ...
            // ])
            ;
    }
}
