<?php

namespace App\Filament\Resources\TeachingMaterialStatusResource\Pages;

use App\Filament\Resources\TeachingMaterialStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTeachingMaterialStatuses extends ListRecords
{
    protected static string $resource = TeachingMaterialStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    public function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereNotNull('file');
    }
}
