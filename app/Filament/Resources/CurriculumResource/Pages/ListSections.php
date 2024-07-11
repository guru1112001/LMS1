<?php

namespace App\Filament\Resources\CurriculumResource\Pages;

use App\Filament\Resources\CurriculumResource;
use App\Models\Section;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class ListSections extends ListRecords
    //implements HasTable
{
    //use InteractsWithTable;

    protected static string $resource = CurriculumResource::class;

    protected static string $view = 'filament.resources.sections.list';

    /*protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }*/

    public function table(Table $table): Table
    {
        return $table
            ->query(Section::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
