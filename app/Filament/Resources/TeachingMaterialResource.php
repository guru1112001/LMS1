<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingMaterialResource\Pages;
use App\Filament\Resources\TeachingMaterialResource\RelationManagers;
use App\Models\Section;
use App\Models\TeachingMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeachingMaterialResource extends Resource
{
    protected static ?string $model = TeachingMaterial::class;

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

                        Forms\Components\Select::make('section_id')
                            ->label('Section')
                            ->options(function (callable $get) {
                                return Section::pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        //Forms\Components\TextInput::make('material_source')
                        Forms\Components\Radio::make('material_source')
                            ->options([
                                "video" => "Video",
                                "other" => "Other File",
                                "url" => "External URL / Embed Code",
                                "content" => "Text / HTML"
                            ])
                            ->descriptions([
                                "video" => "MP4/webm.",
                                "other" => "PDF, Image, Audio, PPT, XLS, ZIP, Other.",
                                "url" => "Link to a web page, youtube video link, etc.",
                                "content" => "Useful for putting direct HTML, embeddable code and formatted text."
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\FileUpload::make('file')
                            ->inlineLabel(true)
                            ->label('File')
                            ->hidden(fn (Forms\Get $get): bool => !in_array($get('material_source'), ['video', 'other'])),
                        Forms\Components\Textarea::make('content')
                            ->hidden(fn (Forms\Get $get): bool => !in_array($get('material_source'), ['url', 'content']))
                            ->columnSpanFull(),

                        Forms\Components\Group::make()->schema([
                            Forms\Components\Checkbox::make('unlimited_view')
                                ->reactive()
                                ->inline(false)
                                ->label('Allow unlimited view'),
                            Forms\Components\TextInput::make('maximum_views')
                                ->hidden(fn (Forms\Get $get): bool => $get('unlimited_view'))
                                ->required()
                                ->numeric(),
                        ])->columns(4),
                        Forms\Components\Toggle::make('prerequisite')
                            ->label('Make this a prerequisite.')
                            ->helperText("Students won't be able to move on to next lesson unless they complete this lesson.")
                            ->required(),

                        Forms\Components\Toggle::make('published'),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull()
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Radio::make('privacy_allow_access')
                            ->label('Allow Access on')
                            ->options([
                                "app" => "Both",
                                "both" => "App"
                            ])
                            ->inline(true),
                        Forms\Components\Toggle::make('privacy_downloadable')
                            ->inline(true)
                            ->label('Downloadable.')
                            ->helperText("Allow students to download this material"),
                    ])
                    ->heading('Privacy')
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                Tables\Columns\TextColumn::make('section.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('section.curriculum.name')
                ->searchable(),
                // Tables\Columns\TextColumn::make('material_source')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('file')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('maximum_views')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('prerequisite')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('privacy_allow_access')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('privacy_downloadable')
                //     ->numeric()
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
                //
            ])
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
            'index' => Pages\ListTeachingMaterials::route('/'),
            //'create' => Pages\CreateTeachingMaterial::route('/create'),
            'view' => Pages\ViewTeachingMaterial::route('/{record}'),
            'material' => Pages\ViewMaterial::route('/{record}/material'),
            'edit' => Pages\EditTeachingMaterial::route('/{record}/edit'),
        ];
    }
}
