<?php

namespace App\Filament\Resources\TeachingMaterialStatusResource\Pages;

use App\Filament\Resources\TeachingMaterialStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeachingMaterialStatus extends EditRecord
{
    protected static string $resource = TeachingMaterialStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
