<?php

namespace App\Filament\Clusters\CourseMaster\Resources\QuestionBankResource\Pages;

use App\Filament\Clusters\CourseMaster\Resources\QuestionBankResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionBank extends EditRecord
{
    protected static string $resource = QuestionBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
