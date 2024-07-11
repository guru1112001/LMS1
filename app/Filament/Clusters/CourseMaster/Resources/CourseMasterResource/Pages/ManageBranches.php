<?php

namespace App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages;

use App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageBranches extends ManageRelatedRecords
{
    protected static string $resource = CourseMasterResource::class;

    protected static string $relationship = 'branches';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage {$recordTitle} Branches";
    }

    public function getBreadcrumb(): string
    {
        return 'Branches';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Branches';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Radio::make('published')
                    ->inlineLabel(true)

                    ->options([
                        '1' => 'Published',
                        '2' => 'UnPublished'
                    ]),

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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->label('Add Branch'),
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
