<?php

namespace App\Filament\Resources\BatchStudentResource\Pages;

use App\Filament\Resources\BatchStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBatches extends ListRecords
{
    protected static string $resource = BatchStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
