<?php

namespace App\Filament\Resources;

use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Tables;
use App\Models\Calendar;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\BelongsToSelect;
use App\Filament\Resources\CalendarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CalendarResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;

class CalendarResource extends Resource
{
    protected static ?string $model = Calendar::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Course Schedule';
    protected static ?string $label = 'Course Schedule';
    protected static ?string $pluralLabel = 'Course Schedule';
    protected static ?string $pluralModelLabel = 'Course Schedule';

    public static function getNavigationGroup(): ?string
    {
        return auth()->user()->is_student ? 'My Courses' : 'Course Schedule';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Select::make('team_id')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('batch_id', null))
                            ->relationship('team', 'name')->label('Branch'),
                        Forms\Components\DatePicker::make('start_day')
                            ->required()
                            //->displayFormat('d/m/Y')
                            ->default(now())
                            //->native(false)
                            ->label('Date')->hiddenOn('edit'),

                        Select::make('curriculum_id')
                            ->relationship('curriculum', 'name')
                            ->label('Subject')
                            ->required()
                            ->hiddenOn('create'),
                        /*
                                                TextInput::make('subject')
                                                    ->label('Subject'),*/


                        Select::make('batch_id')
                            ->relationship('batch', 'name')
                            ->required()
                            ->label('Batch')->hiddenOn('create'),

                        Select::make('tutor_id')
                            ->required()
                            ->relationship('tutor', 'name')->label('Tutor')->hiddenOn('create'),


                        Select::make('classroom_id')
                            ->required()
                            ->relationship('classroom', 'name')
                            ->label('Classroom')->hiddenOn('create'),
                        Forms\Components\DateTimePicker::make('start_time')
                            ->required()
                            ->seconds(false)
                            ->native(false)
                            ->label('Start Time')->hiddenOn('create'),
                        Forms\Components\DateTimePicker::make('end_time')
                            ->required()
                            ->seconds(false)
                            ->native(false)
                            ->label('End Time')->hiddenOn('create'),
                    ])->columns(2)
                    ->hiddenOn('create'),


                Forms\Components\Section::make()
                    ->schema([
                        Select::make('team_id')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn(callable $set) => $set('batch_id', null))
                            ->relationship('team', 'name')->label('Branch'),
                        Forms\Components\DatePicker::make('start_day')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            //->native(false)
                            ->label('Date'),
                    ])->columns(7)
                    ->hiddenOn('edit'),

                Forms\Components\Section::make()
                    ->schema([
                        TableRepeater::make('schedule')
                            ->headers([
                                Header::make('curriculum_id')->label('Subject'),
                                Header::make('batch_id')->label('Batch'),
                                Header::make('tutor_id')->label('Tutor'),
                                Header::make('classroom_id')->label('Classroom'),
                                Header::make('start_time')->label('Start Time'),
                                Header::make('end_time')->label('End Time'),
                            ])
                            ->schema([

                                Select::make('curriculum_id')
                                    ->relationship('curriculum', 'name')
                                    ->label('Subject')
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                /*
                                                        TextInput::make('subject')
                                                            ->label('Subject'),*/


                                Select::make('batch_id')
                                    ->relationship('batch', 'name')
                                    ->required()
                                    ->label('Batch'),

                                Select::make('tutor_id')
                                    ->required()
                                    ->relationship('tutor', 'name')->label('Tutor'),


                                Select::make('classroom_id')
                                    ->required()
                                    ->relationship('classroom', 'name')
                                    ->label('Classroom'),
                                Forms\Components\TimePicker::make('start_time')
                                    ->required()
                                    ->displayFormat('H:i')
                                    ->seconds(false)
                                    ->native(false)
                                    ->default(now())
                                    ->label('Start Time'),
                                Forms\Components\TimePicker::make('end_time')
                                    ->required()
                                    ->displayFormat('H:i')
                                    ->seconds(false)
                                    ->native(false)
                                    ->default(now())
                                    ->label('End Time'),
                            ])
                            ->markAsRequired()
                            ->defaultItems(3)
                            /*->columns(7)
                            ->columnSpan(8)*/
                            ->reorderable(false)
                        //->deletable(false)
                        //->itemLabel(fn(array $state): ?string => $state['name'] ?? null),

                    ])->hiddenOn('edit')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->relationship('holidays')
            ->columns([
                TextColumn::make('batch.name')->label('Batch'),
                TextColumn::make('curriculum.name')->label('Subject'),
                TextColumn::make('team.name')->label('Branch')
                ->hidden(auth()->user()->is_student),
                TextColumn::make('tutor.name')->label('Tutor'),
                TextColumn::make('classroom.name')->label('Classroom'),
                TextColumn::make('start_time')->label('Start Time')->sortable()->dateTime('d-m-Y h:i'),
                TextColumn::make('end_time')->label('End Time')->sortable()->dateTime('d-m-Y h:i'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendars::route('/'),
            'create' => Pages\CreateCalendar::route('/create'),
            //'edit' => Pages\EditCalendar::route('/{record}/edit'),
        ];
    }
}
