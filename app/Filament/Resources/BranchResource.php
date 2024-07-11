<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;
    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';


    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $label = 'Branch';
    protected static ?int $navigationSort = 100;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_number')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('country_id')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('city_id')
                            ->relationship('city', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('state_id')
                            ->relationship('state', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ]),


                        Forms\Components\TextInput::make('pincode')
                            ->required()
                            ->numeric()
                            ->maxLength(6),
                        Forms\Components\TextInput::make('website')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('online_branch')
                            ->helperText('Students will be added to this branch if they register or signup on your portal/app')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Toggle::make('allow_registration')
                            ->helperText('Allow online registration, if you want the students to register for the courses available in this branch through application or web portal.')
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Branch Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('#Students')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')->counts([
                    'users' => fn (Builder $query) => $query->where('is_active', true),
                ]),
                Tables\Columns\TextColumn::make('users_count')->counts('users')
                    ->label('#Teachers')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('contact_number')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('country.name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('city.name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('state.name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('pincode')
                //     ->numeric(),
                // Tables\Columns\TextColumn::make('website')
                //     ->searchable(),
                // Tables\Columns\IconColumn::make('online_branch')
                //     ->boolean(),
                // Tables\Columns\IconColumn::make('allow_registration')
                //     ->boolean(),
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
            'index' => Pages\ListBranches::route('/'),
            //'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
