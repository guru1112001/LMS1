<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Models\Curriculum;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Curriculum';

    protected static bool $isScopedToTenant = false;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Checkbox::make('move_next')
                            ->label('Make this section a prerequisite.'),

                        Forms\Components\Toggle::make('published'),
                        //->inlineLabel(true),
                        //->options(['1' => "Make this section a prerequisite."])
                        //->descriptions("Students won't be able to move on to next sections unless they complete this section."),
                        Forms\Components\Textarea::make('short_description')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('curriculum_id')
                            ->label('Curriculum')
                            ->options(function (callable $get) {
                                return Curriculum::pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public function getResourceForm(): Form
    {
        return Form::make()
            ->schema([
                // Existing fields for parent attributes...

                Forms\Components\Field::make('teaching_material')
                    ->relationship('teaching_material')
                    ->multiple()
                    ->columns([
                        Forms\Components\TextInput::make('name')->label('Child Name'),
                        // Add more child fields as needed
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn(Section $record) => $record->short_description)
                    ->searchable(),

                Tables\Columns\TextColumn::make('curriculum.name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('move_next')
                    ->label('Prerequisite')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('sort')
            ->filters([
                //
            ])
            ->defaultSort('sort')
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'view' => Pages\ViewSection::route('/{record}'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
