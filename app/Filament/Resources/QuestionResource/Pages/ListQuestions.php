<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Exports\QuestionExporter;
use App\Filament\Imports\QuestionImporter;
use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn(): string => QuestionResource::getUrl('create', [
                    'question_bank_id' => !empty(request('question_bank_id')) ? request('question_bank_id') : 0])),

            Actions\ImportAction::make()
                ->importer(QuestionImporter::class),
            Actions\ExportAction::make()
                ->exporter(QuestionExporter::class),
        ];
    }
}
