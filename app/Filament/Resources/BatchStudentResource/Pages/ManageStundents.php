<?php

namespace App\Filament\Resources\BatchStudentResource\Pages;

use App\Filament\Resources\BatchStudentResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageStundents extends ManageRelatedRecords
{
    protected static string $resource = BatchStudentResource::class;

    protected static string $relationship = 'students';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage {$recordTitle} Students";
    }

    public function getBreadcrumb(): string
    {
        return 'Students';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Students';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance_count')
                    ->label('attendance')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->label('Add Students'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()->label('Remove'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
