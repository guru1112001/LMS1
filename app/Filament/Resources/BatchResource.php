<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BatchResource extends Resource
    implements HasShieldPermissions
{
    protected static ?string $model = Batch::class;

    //protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationIcon = 'icon-batches';

    protected static ?string $navigationGroup = 'Batches';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->is_student ? false : true;
    }

   public static function getPermissionPrefixes(): array
   {
       return [
           'view',
           'view_any',
           'create',
           'update',
           'delete',
           'delete_any',
           'publish'
       ];
   }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Details')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('team_id')
                                            ->label('Branch (Center)')
                                            ->relationship('branch', 'name')
                                            ->preload()
                                            ->required()
                                            ->afterStateUpdated(fn(callable $set) => $set('course_package_id', null))
                                            ->reactive(),
                                        Forms\Components\Select::make('course_package_id')
                                            ->relationship('course_package', 'name')
                                            /*->options(function (callable $get) {
                                                $branch = Branch::with('courses')->find($get('branch_id'));
                                                if ($branch) {
                                                    return $branch->courses->pluck('name', 'id');
                                                }
                                            })*/
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Batch Name')
                                            ->required()
                                            ->maxLength(255),


                                        TableRepeater::make('curriculums')
                                            ->relationship()
                                            ->headers([
                                                Header::make('curriculum_id')->label('Subject'),
                                                Header::make('user_id')->label('Tutor'),
                                            ])
                                            ->schema([
                                                Select::make('curriculum_id')
                                                    ->options(\App\Models\Curriculum::all()->pluck('name', 'id'))
                                                    //->relationship('curriculums', 'name')
                                                    ->label('Subject')
                                                    ->required()
                                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                                Select::make('tutor_id')
                                                    ->options(\App\Models\User::where('role_id', 7)->pluck('name', 'id'))
                                                    ->required()
                                                    ->label('Branch'),
                                            ])
                                            ->columnSpanFull()
                                            ->defaultItems(3),
//                                        Forms\Components\Select::make('courses')
//                                            ->relationship('courses', 'name')
//                                            ->preload()
//                                            ->required()
//                                            ->multiple(),

                                        Forms\Components\Select::make('manager_id')
                                            ->label('Branch Manager')
                                            ->options(function () {
                                                return User::where('role_id', 7)->pluck('name', 'id');
                                            })
                                            //->relationship('user', 'name')
                                            ->preload()
                                            ->required(),
                                        Forms\Components\DatePicker::make('start_date')
                                            ->displayFormat('d/m/Y')
                                            ->required(),
                                        Forms\Components\DatePicker::make('end_date')
                                            ->displayFormat('d/m/Y')
                                            ->required(),
                                    ])->columns(2),
                            ]),
                        Tabs\Tab::make('Advance Setting')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Toggle::make('allow_edit_class_time')
                                            ->label('Allow teacher to edit Class Time')
                                            ->columnSpanFull()
                                            ->required(),

                                        Forms\Components\Toggle::make('allow_edit_class_date')
                                            ->label('Allow teacher to edit Class Date')
                                            ->columnSpanFull()
                                            ->required()
                                    ])->hiddenOn('create')
                                    ->columns(2),
                            ])->hiddenOn('create')
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Batch Name')
                    ->description(fn(Batch $record) => "Admitted Students: " . $record->students->count())
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                Tables\Columns\TextColumn::make('course_package.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('curriculums.curriculum.name')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable(),

                // Tables\Columns\TextColumn::make('branch.name')
                //     ->numeric()
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('user.name')
                //     ->label('Branch Manager')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('start_end_date')
                    ->label('Start/End')
                    ->html(),
                // Tables\Columns\TextColumn::make('end_date')
                //     ->date()
                //     ->sortable(),
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
//                SelectFilter::make('course')
//                    ->relationship('course', 'name')
//                    ->searchable()
//                    ->preload(),
                Filter::make('start_date')
                    ->form([
                        Grid::make()
                            ->schema([
                                DatePicker::make('start_from'),
                                DatePicker::make('start_until'),
                            ])
                    ])
                    ->columnSpan(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    }),
                SelectFilter::make('user')
                    ->label('Branch Manager')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

            ], FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(auth()->user()->is_tutor),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->hidden(!auth()->user()->is_admin),
            ]);
    }

//     public static function getEloquentQuery(): Builder
//     {
//         return parent::getEloquentQuery()
// //            ->when(auth()->check() && auth()->user()->is_tutor, function ($query) {
// //                $query->select('batches.*', 'batches.id as batchid')
// //                    ->join('batch_curriculum', 'batches.id', '=', 'batch_curriculum.batch_id')
// //                    ->where('batch_curriculum.tutor_id', auth()->user()->id);
// //            })
//             /*
//             ->whereHas('user', function ($query) {
//                 $query->where('role', 'tutor');
//             })
//             ->with(['user', 'curriculums'])*/ ;
//     }

    public static function getPages(): array
    {
        $pages =  [
            'index' => Pages\ListBatches::route('/'),
            'students' => Pages\ManageStundents::route('/{record}/students'),
            'syllabi' => Pages\ManageSyllabi::route('/{record}/syllabi'),
            //'create' => Pages\CreateBatch::route('/create'),            
            'edit' => Pages\EditBatch::route('/{record}/edit'),
            'view' => Pages\ViewBatch::route('/{record}'),
        ];

        if(auth()->check() && !auth()->user()->is_admin)
        {
            //unset($pages['view']);
            unset($pages['edit']);
        }

        return $pages;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewBatch::class,
            Pages\EditBatch::class,
            Pages\ManageStundents::class,
            Pages\ManageSyllabi::class,
        ]);
    }
}
