<?php

namespace App\Filament\Resources\BatchStudentResource\Pages;

use App\Filament\Resources\BatchStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatch extends EditRecord
{
    protected static string $resource = BatchStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

//
//    protected function afterSave(): void
//    {
//        dd($this);
//    }
}
