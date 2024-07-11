<?php

namespace App\Filament\Resources\AuthenticationLogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AuthenticationLogResource;

class ListAuthenticationLogs extends ListRecords
{
    protected static string $resource = AuthenticationLogResource::class;
}
