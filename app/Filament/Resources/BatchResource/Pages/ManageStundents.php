<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ManageStundents extends ManageRelatedRecords
{
    protected static string $resource = BatchResource::class;

    protected static string $relationship = 'students';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static bool $isLazy = false;

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
        return $form->schema([
            Tables\Table::make('related')
                ->columns([
                    Tables\Columns\TextColumn::make('name'),
                    Tables\Columns\TextColumn::make('status')
                ])
                ->actions([
                    Tables\Actions\LinkAction::make('select')
                        ->action(fn ($record) => $this->attachRecord($record)),
                    Tables\Actions\LinkAction::make('deselect')
                        ->action(fn ($record) => $this->detachRecord($record)),
                ])
                ->records($this->getAvailableRecords())
        ]);
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
                    //->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
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
//            ->headerActions([
//                Tables\Actions\AttachAction::make()->label('Add Students'),
//            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Add Students')
                    ->modalHeading("Students")
                    ->form(fn($livewire) => [
                        //dd($livewire->getOwnerRecord()->students()),
                        Forms\Components\CheckboxList::make('recordId')
                            ->options(User::join('team_user','team_user.user_id','users.id')
                                ->where('role_id',6)
                                ->where('team_user.team_id', Filament::getTenant()->id)
                                ->get()->mapWithKeys(function ($student) {
                                    //dd($student);
                                    return [$student->user_id => new HtmlString($student->additional_details)];
                                })
                            )
                            ->searchable()
                            ->noSearchResultsMessage('No student found.')
                            ->bulkToggleable()
                            ->default(fn() => $livewire->getOwnerRecord() ? $livewire->getOwnerRecord()
                                ->students->pluck('id')->toArray() : [])
                            ->disableLabel() // To avoid showing the default label of checkbox
                            ->extraAttributes(['class' => 'checkbox-list-with-details'])
                    ])
                    ->action(function (array $data, $livewire) {
                        //dd($data);
                        $batch = $livewire->getOwnerRecord();
                        $batch->students()->sync($data['recordId']);
                    })
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DetachAction::make()->label('Remove'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
