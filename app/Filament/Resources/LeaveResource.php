<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Enums\LeaveStatus;
use Illuminate\Database\Eloquent\Builder;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Attendance';

    protected static ?string $pluralLabel = 'Leave Applications';
    protected static ?string $pluralModelLabel = 'Leave Applications';
    protected static ?string $modelLabel = 'Leave Application';

    /*protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ];*/

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->hiddenOn('create'),

                Forms\Components\Placeholder::make('number_of_leave_taken')
                    ->content(fn($record) => $record ? Leave::where('user_id', $record->user_id)
                        ->whereNot('id', $record->id)
                        ->where('status', LeaveStatus::Approved)->count() : '0')
                    ->hiddenOn('create')
                    ->extraAttributes(['class' => 'fi-badge fi-color-danger']),

                Forms\Components\Placeholder::make('start_date')
                    ->content(fn(Forms\Get $get) => Carbon::createFromDate($get('start_date'))->format('d/m/Y'))
                    ->hiddenOn('create'),

                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->firstDayOfWeek(7)
                    ->closeOnDateSelection()
                    ->minDate(fn(string $context) => $context !== 'create' ? today() : null)
                    ->reactive()
                    ->hiddenOn('edit')
                    //->hiddenOn('edit')
                    ->required(),

                Forms\Components\Placeholder::make('end_date')
                    ->content(fn(Forms\Get $get) => Carbon::createFromDate($get('end_date'))->format('d/m/Y'))
                    ->hiddenOn('create'),


                Forms\Components\DatePicker::make('end_date')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->firstDayOfWeek(7)
                    ->closeOnDateSelection()
                    ->minDate(fn(string $context) => $context !== 'create' ? today() : null)
                    ->reactive()
                    ->afterOrEqual('start_date')
                    //->hiddenOn('edit')
                    ->hiddenOn('edit')
                    ->validationMessages([
                        'after_or_equal' => 'The end date must be equal or after the start date.',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('reason')
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\ToggleButtons::make('status')
                    ->options(LeaveStatus::class)
                    ->inline()
                    ->hiddenOn('create')
                    ->columnSpan(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->hidden(auth()->user()->is_student),
                Tables\Columns\TextColumn::make('user.batchesstudents.name')
                    ->label('Batch')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->hidden(auth()->user()->is_student),
                Tables\Columns\TextColumn::make('user.domain.name')
                    ->hidden(auth()->user()->is_student),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                SelectFilter::make('status')
                    ->label('Leave')
                    ->options(LeaveStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $data['updated_by'] = auth()->id();
                        return $data;
                    })
                    ->after(function (Leave $record) {
                        $user = User::find($record->user_id);
                        Notification::make()
                            ->title("Your leave status is updated to " . $record->status . ' #' . $record->id)
                            ->actions([
                                Action::make('view')->button()->url(
                                    route('filament.administrator.resources.leaves.index', Filament::getTenant()) . '?tableSearch=' . $record->id),
                            ])
                            ->sendToDatabase($user);

                        event(new DatabaseNotificationsSent($user));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
            //->query(fn() => static::getEloquentQuery());
    }

//     public static function getEloquentQuery(): Builder
//     {
//         $query = parent::getEloquentQuery(); // TODO: Change the autogenerated stub
//         if (auth()->user()->is_tutor) {

//             $query->withoutGlobalScope('limited');
// //            $query->join('users', 'users.id', '=', 'user_id');
// //            $query->join('batch_curriculum', 'batches.id', '=', 'batch_curriculum.batch_id')
// //                ->where('batch_curriculum.tutor_id', auth()->user()->id);
//         }

//         return $query;
//     }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            //'create' => Pages\CreateLeave::route('/create'),
            //'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }

//
//    protected function mutateFormDataBeforeCreate(array $data): array
//    {
//        $data['user_id'] = auth()->id();
//
//        return $data;
//    }
}
