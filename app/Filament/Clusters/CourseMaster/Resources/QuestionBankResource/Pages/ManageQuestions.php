<?php

namespace App\Filament\Clusters\CourseMaster\Resources\QuestionBankResource\Pages;

use App\Filament\Clusters\CourseMaster\Resources\QuestionBankResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageQuestions extends ManageRelatedRecords
{
    protected static string $resource = QuestionBankResource::class;

    protected static string $relationship = 'questions';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->form(fn(Form $form) => (
                $form->schema([
                    TextInput::make('name')
                        ->required()
                ])
            )),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
            ])
            ->columns(1);
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Actions\CreateAction::make(),
                //Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                //DetachAction::make()->label('Remove'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
