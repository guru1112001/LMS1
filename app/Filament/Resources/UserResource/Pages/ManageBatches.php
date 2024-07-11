<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Filament\Resources\UserResource;
use App\Models\Batch;
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

class ManageBatches extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'batches';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public function getTitle(): string|Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage {$recordTitle} Batches";
    }

    public function getBreadcrumb(): string
    {
        return 'Batches';
    }

    public static function getNavigationLabel(): string
    {
        return 'Batches Enrolled';
    }

//    public function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\CheckboxList::make('name')
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


//    public function infolist(Infolist $infolist): Infolist
//    {
//        return $infolist
//            ->columns(1)
//            ->schema([
//                TextEntry::make('name'),
//
//            ]);
//    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn(Batch $record) => "Join On: " . $record->created_at->format('d M Y'))
                    ->label('Name')
//                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_end_date')
                    ->label('Start/End')
                    ->html(),
                Tables\Columns\TextColumn::make('calendar_count')
                    ->label('Total Classes')
                    ->html(),
//                Tables\Columns\TextColumn::make('created_at')
//                    ->label('Join On')
//                    ->searchable()
//                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Add To Batches')
                    ->modalHeading("Batches")
                    ->form(fn($livewire) => [
                        Forms\Components\CheckboxList::make('recordId')
                            ->options(Batch::where('team_id', Filament::getTenant()->id)->get()->mapWithKeys(function ($batch) {
                                return [$batch->id => new HtmlString($batch->additional_details)];
                            }))
                            ->searchable()
                            ->noSearchResultsMessage('No batch found.')
                            ->bulkToggleable()
                            ->default(fn() => $livewire->getOwnerRecord() ? $livewire->getOwnerRecord()->batches->pluck('id')->toArray() : [])
                            ->disableLabel() // To avoid showing the default label of checkbox
                            ->extraAttributes(['class' => 'checkbox-list-with-details'])
                    ])
                    ->action(function (array $data, $livewire) {
                        $user = $livewire->getOwnerRecord();
                        $user->batches()->sync($data['recordId']);
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
