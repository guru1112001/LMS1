<?php

namespace App\Filament\Clusters\CourseMaster\Resources;

use App\Filament\Clusters\CourseMaster;
use App\Filament\Clusters\CourseMaster\Resources\QuestionBankResource\Pages;
use App\Filament\Clusters\CourseMaster\Resources\QuestionBankResource\RelationManagers;
use App\Models\QuestionBank;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionBankResource extends Resource
{
    protected static ?string $model = QuestionBank::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = CourseMaster::class;
    protected static ?int $navigationSort = 0;
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('question_bank_subject_id')
                    ->label('Subject')
                    ->relationship('curriculum', 'name')
                    ->searchable()
                    ->preload(),
//                    ->createOptionForm([
//                        Forms\Components\TextInput::make('name')
//                            ->required(),
//                    ]),
                Forms\Components\TextInput::make('question_bank_chapter')
                    ->label('Question Topic')
                    ->maxLength(255)
                    ->required(),
//                Forms\Components\Select::make('question_bank_chapter_id')
//                    ->label('Chapter')
//                    ->relationship('question_bank_chapter', 'name')
//                    ->searchable()
//                    ->preload()
//                    ->createOptionForm([
//                        Forms\Components\TextInput::make('name')
//                            ->required(),
//                    ]),
                Forms\Components\Select::make('question_bank_difficulty_id')
                    ->label('Difficulty Level')
                    ->relationship('question_bank_difficulty', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ]),
                Forms\Components\Select::make('question_bank_type_id')
                    ->label('Question Type')
                    ->relationship('question_bank_type', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ]),
                /* Forms\Components\Select::make('question_bank_type_id')
                     ->label('Question Type')
                     ->options([
                         "1" => "MCQ - Single Correct",
                         "2" => "MCQ - Multiple Correct",
                         "3" => "Fill The Blank",
                         "4" => "Subjective",
                         "5" => "True/False",
                         "6" => "English Transcript"
                     ])
                     ->searchable()
                     ->preload(),*/
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('QB Name')
                    ->description(fn(QuestionBank $record) => "QB ID:" . $record->id)
                    ->searchable(),
                Tables\Columns\TextColumn::make('curriculum.name')
                    ->label('Subject')
                    ->numeric(),
                Tables\Columns\TextColumn::make('question_bank_chapter')
                    ->label('Question Topic'),
                Tables\Columns\TextColumn::make('question_bank_difficulty.name')
                    ->label('Difficulty')
                    ->numeric(),
                Tables\Columns\TextColumn::make('question_bank_type.name')
                    ->label('Question Type')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(
                fn(QuestionBank $record): string => route('filament.administrator.resources.questions.index',
                    ['question_bank_id' => $record, 'tenant' => Filament::getTenant()])
            )
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
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
            //QuestionBankResource::getRelations()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionBanks::route('/'),
            //'questions' => Pages\ManageQuestions::route('/'),
            //'create' => Pages\CreateQuestionBank::route('/create'),
            //'edit' => Pages\EditQuestionBank::route('/{record}/edit'),
            //'view' => Pages\ManageQuestions::route('/{record}/questions'),
            //'view' => RelationManagers\QuestionsRelationManager::route('/{record}/questions'),
        ];
    }
}
