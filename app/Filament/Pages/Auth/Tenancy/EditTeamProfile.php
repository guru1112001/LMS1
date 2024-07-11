<?php
namespace App\Filament\Pages\Auth\Tenancy;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
    //protected static string $view = 'filament.app.pages.tenancy.edit-team-profile';

    public static function getLabel(): string
    {
        return 'Team profile';
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('slug'),
            ]);
    }

    public static function getRouteName(?string $panel = null): string
    {
        $panel = $panel ? Filament::getPanel($panel) : Filament::getCurrentPanel();

         $routeName = 'pages.' . static::getRelativeRouteName();
        //$routeName = 'tenant.' . static::getRelativeRouteName(); // here is the change I've made 
        $routeName = static::prependClusterRouteBaseName($routeName);
//dd($routeName);
        return $panel->generateRouteName($routeName);
    }
}