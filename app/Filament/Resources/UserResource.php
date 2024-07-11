<?php

namespace App\Filament\Resources;

use App\Models\Batch;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\MaxWidth;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Qualification;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Enums\FiltersLayout;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    //protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationIcon = 'icon-student';

    protected static ?string $navigationGroup = 'User';

    protected static ?int $navigationSort = -2;

    protected static bool $isScopedToTenant = false;
    protected static ?array $countryCodes = null;


//    public static function infolist(Infolist $infolist): Infolist
//    {
//        return $infolist
//        ->schema([
//            Infolists\Components\TextEntry::make('name'),
//        ]);
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->downloadable()
                            ->columns(2)
                    ])->columns(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('role_id')
                            ->relationship('role', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->hiddenOn('edit'),
                        Forms\Components\TextInput::make('registration_number')
                            ->hidden(fn (Forms\Get $get): bool => $get('role_id') != 6)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        Group::make()->schema([
                            Select::make('country_code')
                                ->options(config('country-codes'))
                                ->label('Country Code')
                                ->required()
                                ->searchable()
                                ->extraAttributes(['style' => 'width: 150px;']), // Apply custom width,
                            Forms\Components\TextInput::make('phone')
                                ->label('Contact Number')
                                ->required()
                                ->numeric()
                                ->unique()
                                ->maxLength(10),
                        ])->columns(2),
                        Forms\Components\Select::make('gender')
                            ->options(['Male' => 'Male', 'Female' => 'Female']),
                        Forms\Components\DatePicker::make('birthday')
                            //->maxDate(now()->subYear(15))
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->firstDayOfWeek(7)
                            ->hiddenOn('create'),
                        /*Forms\Components\DateTimePicker::make('email_verified_at'),*/
                        Forms\Components\TextInput::make('password')
                            //->label(fn(string $context) => $context === 'create' ? 'Password' : 'Change Password')
                            ->hiddenOn('edit')
                            ->password()
                            // ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            // ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),

                    ])->columns(2),

                Forms\Components\Section::make()
                    ->schema([

                        TableRepeater::make('qualification')
                            ->addActionLabel('Add New Qualification')
                            ->headers([
                                Header::make('qualification')
                                    ->label('Qualification')
                                    ->width('250px'),
                                Header::make('year')->label('Year'),
                                Header::make('institute_name')->label('College / School Name'),
                                Header::make('percentage')->label('Percentage (%)'),
                                Header::make('Action')->label('Action'),

                            ])
                            ->schema([
                                Forms\Components\Select::make('qualification_id')
                                    ->relationship('qualification', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('year')
                                    ->numeric()
                                    ->numeric()
                                    ->maxLength(4)
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('institute_name')
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('percentage')
                                    ->numeric()
                                    ->maxLength(3)
                                    ->rules('max:100')
                                    ->columnSpan(2)
                            ])
                            ->stackAt(MaxWidth::Medium)
                            ->columnSpan(2)
                            ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()),

                        //                        Forms\Components\Select::make('qualification_id')
                        //                            ->relationship('qualification', 'name')
                        //                            ->searchable()
                        //                            ->preload()
                        //                            ->createOptionForm([
                        //                                Forms\Components\TextInput::make('name')
                        //                                    ->required(),
                        //                            ]),
                        //
                        //                        Forms\Components\TextInput::make('year_of_passed_out')
                        //                            ->numeric()
                        //                            ->maxLength(4),

                        Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        /*->relationship('city', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                        ])*/

                        Forms\Components\Select::make('state_id')
                            ->relationship('state', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),


                        Forms\Components\TextInput::make('pincode')
                            ->numeric()
                            ->maxLength(6),
                        // Forms\Components\TextInput::make('school')
                        //     ->maxLength(255)

                    ])->hiddenOn('create')->columns(2),

                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\TextInput::make('aadhaar_number')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('linkedin_profile')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('upload_resume')
                            ->downloadable()
                            ->inlineLabel(true)
                            ->downloadable()
                            ->label('Resume'),
                        Forms\Components\FileUpload::make('upload_aadhar')
                            ->downloadable()
                            ->label('Aadhar')
                            ->downloadable()
                            ->inlineLabel(true),
                    ])->hiddenOn('create'),

                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\TextInput::make('parent_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('parent_name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('parent_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('parent_aadhar')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('parent_occupation')
                            ->maxLength(255),


                        Forms\Components\Textarea::make('residential_address')
                            ->columnSpanFull(),
                    ])->hiddenOn('create')->columns(2),


                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\Select::make('designation_id')
                            ->relationship('designation', 'name')
                            ->searchable()
                            ->preload()
                            ->hidden(fn (Forms\Get $get): bool => $get('role_id') == 6)
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('domain_id')
                            ->relationship('domain', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),

                    ])->hiddenOn('create')->columns(2),

            ]);
    }

    public static function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return User::query()->whereHas('teams', function ($query) {
            $query->where('id', Filament::getTenant());
        })->where('role_id', 6);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')->label('Avatar')
                    ->rounded(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
//            ->filters([
//
//            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('batch')
                    ->label('Batch')
                    ->relationship('batches', 'name')
                    ->searchable()
                    ->preload(),
            ],FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label('')
                //                    ->mutateFormDataUsing(function (array $data) {
                //                        if(!empty($data['password'])) {
                //                            $data['password'] = Hash::make($data['password']);
                //                        } else {
                //                          unset($data['password']);
                //                        }
                //                        return $data;
                //                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

//    public static function getRelations(): array
//    {
//        return [
//            //
//        ];
//    }

    public static function getPages(): array
    {
        $pages = [
            'index' => Pages\ListUsers::route('/'),
            'batches' => Pages\ManageBatches::route('/{record}/batches'),
            'assignments' => Pages\Assignments::route('/{record}/assignments'),
            'branches' => Pages\ManageBranches::route('/{record}/branches'),
            // 'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'changepassword' => Pages\ChangePassword::route('/{record}/changepassword'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];

        return $pages;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $pages = [
            //Pages\ListUsers::class,
            Pages\ViewUser::class,
            Pages\EditUser::class,
            Pages\ChangePassword::class,
            Pages\ManageBatches::class,
            Pages\ManageBranches::class,
            Pages\Assignments::class,
        ];

        if ($page->getRecord()->role_id == 6) {
            unset($pages[4]);
        }
        return $page->generateNavigationItems($pages);
    }

    public static function relations(): array
    {
        return [
            Assignments::class,
        ];
    }
}
