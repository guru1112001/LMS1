<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use App\Models\Syllabus;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use function Symfony\Component\Mime\Test\Constraint\failureDescription;

class ManageSyllabi extends ManageRelatedRecords
{
    protected static string $resource = BatchResource::class;

    protected static string $relationship = 'syllabi';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public function getTitle(): string|Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage {$recordTitle} Syllabi";
    }

    public function getBreadcrumb(): string
    {
        return 'Syllabi';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Syllabi';
    }

    public function form_array()
    {
        return [
            Forms\Components\Select::make('day')
                ->label('Days')
                ->options(array_combine(range(1, 1000), range(1, 1000)))
            ,

//            Forms\Components\Select::make('batch_id')
//                ->label('Batch')
//                ->relationship('batch', 'name')
//            ,

            Forms\Components\Select::make('course_id')
                ->label('course')
                ->relationship('course', 'name')
            ,

            Forms\Components\TextInput::make('syllabus'),

            Forms\Components\TextInput::make('subject')
                ->label('Topics and Sub topics'),

            Forms\Components\Select::make('tutor_id')
                ->label('tutor')
                ->relationship('tutor', 'name')
            ,

            Forms\Components\DateTimePicker::make('date')
                ->native(false)
                ->displayFormat('d/m/Y')
                ->reactive()
            ,

            Forms\Components\Select::make('status')
                ->options([
                    'Not Completed' => 'Not Completed',
                    'Partially Done' => 'Partially Done',
                    'Completed' => 'Completed',
                ])->reactive()
            ,
            Forms\Components\Textarea::make('comments')
                ->hidden(fn(Forms\Get $get): bool => $get('status') != 'Not Completed')
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->form_array());
    }

//    public function infolist(Infolist $infolist): Infolist
//    {
//        return $infolist
//            ->columns(1)
//            ->schema([
//                TextEntry::make('day'),
//            ]);
//    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day')
            ->columns([
                Tables\Columns\TextColumn::make('day')
                    ->formatStateUsing(fn(Syllabus $record) => new HtmlString("Day " . $record->day))
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label(new HtmlString('SSTA <br> Topics and Sub topics'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('tutor.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'Not Completed' => 'Not Completed',
                        'Partially Done' => 'Partially Done',
                        'Completed' => 'Completed',
                    ])
                    ->afterStateUpdated(function () {
                        Notification::make()
                            ->success()
                            ->title('Status Updated')
                            ->send();
                    })
                    ->searchable(),
                    Tables\Columns\SelectColumn::make('comments')
                    ->options([
                        'Leave' => 'Leave',
                        'Into Discussion' => 'Into discussion',
                        'Holiday' => 'Holiday',
                    ])
                    ->afterStateUpdated(function () {
                        Notification::make()
                            ->success()
                            ->title('Comment Updated')
                            ->send();
                    })
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Syllabus'),
                //Tables\Actions\AttachAction::make()->label('Add Syllabus'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(false),
                Tables\Actions\ReplicateAction::make()->form(
                    $this->form_array()
                )->label(false),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    //Tables\Actions\DetachAction::make()->label('Remove'),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->groupedBulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
