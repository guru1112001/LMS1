<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Filament\Resources\UserResource;
use App\Models\Batch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Assignments extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'assignments';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public function getTitle(): string|Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "{$recordTitle} Assignments";
    }

    public function getBreadcrumb(): string
    {
        return 'Assignments';
    }

    public static function getNavigationLabel(): string
    {
        return 'Assignments';
    }

//    public function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\TextInput::make('name')
//                    ->required(),
//
//                // Forms\Components\Radio::make('published')
//                //     ->inlineLabel(true)
//
//                //     ->options([
//                //         '1' => 'Published',
//                //         '2' => 'UnPublished'
//                //     ]),
//
//            ])
//            ->columns(1);
//    }

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
                Tables\Columns\TextColumn::make('teaching_material_assignment.name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('obtained_marks')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\AttachAction::make()->label('Add Branches'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DetachAction::make()->label('Remove'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
