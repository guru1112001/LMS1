<?php

namespace App\Filament\Resources\TeachingMaterialResource\Pages;

use App\Filament\Resources\TeachingMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeachingMaterial extends ViewRecord
{
    protected static string $resource = TeachingMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
