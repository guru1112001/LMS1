<?php

namespace App\Filament\Resources;

use App\Filament\Exports\QuestionExporter;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\QuestionOption;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $isScopedToTenant = false;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('question_bank_id')
                    ->default(request('question_bank_id'))
                    ->required(),
                Forms\Components\Section::make('Add Paragraph')
                    ->schema([
                        Forms\Components\FileUpload::make('audio_file'),
                        Forms\Components\RichEditor::make('paragraph')
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Paragraph can be added for comprehension type questions where the question(s) have to be answered based on the information provided in the paragraph.')
                    ])->columns(2)
                    ->collapsible(true)
                    ->collapsed(),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\RichEditor::make('question')
                            ->required(),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('question_type')
                                    ->label('Question Type')
                                    ->relationship('question_bank_type', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->default((request('question_bank_id') &&
                                        QuestionBank::find(request('question_bank_id')))
                                        ? QuestionBank::find(request('question_bank_id'))->question_bank_type_id
                                        : '')
                                    ->required(),
//                                    ->options([
//                                        'MCQ - Single Correct' => 'MCQ - Single Correct',
//                                        'MCQ - Multiple Correct' => 'MCQ - Multiple Correct',
//                                        'Fill In The Correct' => 'Fill In The Correct',
//                                        'Subjective' => 'Subjective',
//                                        'True/False' => 'True/False',
//                                        'English Transcription' => 'English Transcription',
//                                    ])
                                Forms\Components\Placeholder::make('difficulty')
                                    ->content((request('question_bank_id') &&
                                        QuestionBank::find(request('question_bank_id')))
                                        ? QuestionBank::find(request('question_bank_id'))->formatted_difficulty
                                        : '')
                                    ->extraAttributes(['class' => 'inline-html-field']),
                                Forms\Components\Placeholder::make('topic')
                                    ->content((request('question_bank_id') &&
                                        QuestionBank::find(request('question_bank_id')))
                                        ? QuestionBank::find(request('question_bank_id'))->question_bank_chapter
                                        : '')
                                    ->extraAttributes(['class' => 'inline-html-field']),
//                                Forms\Components\Select::make('difficulty')
//                                    ->options([
//                                        'Beginner' => 'Beginner',
//                                        'Intermediate' => 'Intermediate',
//                                        'Difficult' => 'Difficult'
//                                    ])
//                                    ->required(),
//                                Forms\Components\TextInput::make('topic')
//                                    ->required()
//                                    ->maxLength(255),
                            ]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('marks')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('negative_marks')
                                    ->required()
                                    ->numeric()
                                    ->hidden(fn(Forms\Get $get): bool => $get('question_type') == 4)
                                    ->default(0),
                            ])->columns(2),
                        Forms\Components\TextInput::make('answer')
                            ->columnSpanFull()
                            ->hidden(fn(Forms\Get $get): bool => $get('question_type') != 3)
                            ->helperText(new HtmlString("Separate all the possible answers by '|'. For eg., if both 'Tamil Nadu' and 'TN' are correct, then write it as 'Tamil Nadu | TN'")),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Checkbox::make('check_capitalization'),
                                Forms\Components\Checkbox::make('check_punctuation')
                            ])
                            ->columns(4)
                            ->columnSpanFull()
                            ->hidden(fn(Forms\Get $get): bool => $get('question_type') != 6),

                        TableRepeater::make('questions_options')
                            ->addActionLabel('Add New Option')
                            ->relationship()
                            ->headers([
                                Header::make('option')->label('Options'),
                                Header::make('is_correct')->label('Correct Answer'),
                            ])
                            ->schema([
                                Forms\Components\RichEditor::make('option'),
                                Forms\Components\Checkbox::make('is_correct')
                                    ->label('Correct Answer')
                            ])
                            ->columnSpanFull()
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->reorderableWithDragAndDrop()
                            ->defaultItems(2)
                            ->hidden(fn(Forms\Get $get): bool => !in_array($get('question_type'), [1,2])),

                        TableRepeater::make('questions_options')
                            //->addActionLabel('Add New Option')
                            ->relationship()
                            ->headers([
                                Header::make('option')->label('Options'),
                                Header::make('is_correct')->label('Correct Answer'),
                            ])
                            ->schema([
                                Forms\Components\RichEditor::make('option'),
                                Forms\Components\Checkbox::make('is_correct')
                                    ->inlineLabel('Correct Answer')
                            ])
                            ->columnSpanFull()
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->reorderableWithDragAndDrop()
                            ->defaultItems(2)
                            ->maxItems(2)
                            ->minItems(2)
                            ->hidden(fn(Forms\Get $get): bool => $get('question_type') != 5),


                        Forms\Components\Section::make('Hint and Explanation')
                            ->schema([
                                Forms\Components\RichEditor::make('hint')
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('explanation')
                                    ->columnSpanFull(),
                            ])->collapsible(true)
                            ->collapsed(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('question')
                        ->description(fn(Question $record) => "Question: #" . $record->id, 'above')
                        ->columnSpan(4)
                        ->html(),
                    Tables\Columns\TextColumn::make('marks')
                        ->description('Mark', 'above')
                        ->prefix('+')
                        ->badge()
                        ->color('success')
                    ,
                    Tables\Columns\TextColumn::make('negative_marks')
                        ->description('Negative', 'above')
                        ->prefix('-')
                        ->badge()
                        ->color('danger'),

                    //->description(fn(Question $record) =>  $record->question),
//                    Tables\Columns\TextColumn::make('question_type')
//                        ->label('Batch Name')
//                        ->description(fn(Question $record) => "Admitted Students: " . $record->questions_options->count())
//                        ->weight(FontWeight::SemiBold)
//                        ->searchable(),
                ])->columnSpanFull(),
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('questions_options.option')
                            ->html()
                            ->listWithLineBreaks()
                            ->bulleted(),
                        Tables\Columns\TextColumn::make('questions_options.is_correct')
                            ->formatStateUsing(fn(string $state) => $state ? 'true' : 'false')
                            //->html()
                            ->listWithLineBreaks()
                    ]),
//                    Tables\Columns\TextColumn::make('difficulty')
//                        ->searchable(),
//                    Tables\Columns\TextColumn::make('topic')
//                        ->searchable(),
//
//                    Tables\Columns\TextColumn::make('created_at')
//                        ->dateTime()
//                        ->sortable()
//                        ->toggleable(isToggledHiddenByDefault: true),
//                    Tables\Columns\TextColumn::make('updated_at')
//                        ->dateTime()
//                        ->sortable()
//                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                    ->columnSpan(3)
                    ->collapsible(),
            ])
//            ->headerActions([
//                Tables\Actions\ExportBulkAction::make()
//                    ->exporter(QuestionExporter::class)
//            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(QuestionExporter::class)
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(request('question_bank_id'), function (Builder $query) {
                $query->where('question_bank_id', request('question_bank_id'));
            });
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
