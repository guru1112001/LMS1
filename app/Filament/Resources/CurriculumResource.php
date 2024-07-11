<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurriculumResource\Pages;
use App\Filament\Resources\CurriculumResource\RelationManagers;
use App\Models\Curriculum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Ramsey\Collection\Collection;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class CurriculumResource extends Resource
{
    protected static ?string $model = Curriculum::class;

    protected static bool $isScopedToTenant = false;
    //protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationIcon = 'icon-curriculum';

    protected static ?string $navigationGroup = 'Curriculum';
    protected static ?string $pluralLabel = 'Curriculum';
    protected static ?string $label = 'Curriculum';
    protected static ?string $slug = 'curriculum';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('courses')
                            ->relationship('courses', 'name')
                            ->preload()
                            ->required()
                            ->multiple(),
                        Forms\Components\Textarea::make('short_description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->image()

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /*Tables\Columns\TextColumn::make('id')
                    ->sortable(),*/
                Tables\Columns\ImageColumn::make('image')
                    ->defaultImageUrl(url('/images/placeholder.jpg'))
                    ->square()
                    ->width(50),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Curriculum $record) => $record->short_description)
                    ->searchable(),
                Tables\Columns\TagsColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->color('primary'),
                // Tables\Columns\TextColumn::make('branches.name')
                //     ->label('Branches')
                //     ->listWithLineBreaks()
                //     ->bulleted()
                //     ->limitList(2)
                //     ->expandableLimitedList()
                //     ->searchable(),
            ])
            ->filters([
                // SelectFilter::make('branches')
                //     ->label('Branch')
                //     ->relationship('branches', 'name')
                //     ->searchable()
                //     ->preload(),
            ], FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])

            ->recordUrl(
                fn (Curriculum $record): string => route('filament.administrator.resources.curriculum.sections',
                    ['record'=>$record,'tenant'=>Filament::getTenant()])
            )
            /*->recordAction([

            ])*/
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
            'index' => Pages\ListCurriculum::route('/'),
            'create' => Pages\CreateCurriculum::route('/create'),
            'edit' => Pages\EditCurriculum::route('/{record}/edit'),
            'sections' => Pages\ViewSections::route('/{record}/sections'),
            'lessons' => Pages\ViewStudentSections::route('/{record}/lessons'),
        ];
    }
}
