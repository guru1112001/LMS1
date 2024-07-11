<?php

namespace App\Filament\Clusters\CourseMaster\Resources;

use App\Filament\Clusters\CourseMaster;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;


class CourseMasterResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $cluster = CourseMaster::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    //protected static ?string $navigationGroup = 'Courses';
    protected static ?string $label = 'Courses';

    protected static ?int $navigationSort = 0;

    public static function getLabel(): string
    {
        return "Courses";
    }


    //protected static ?string $navigationGroup = 'Curriculum';
    //protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static bool $isScopedToTenant = false;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->inlineLabel(true)
                            ->required()
                            ->maxLength(255),

                        Forms\Components\CheckboxList::make('is_live_course')
                            ->label('Is Live Course')
                            ->inlineLabel(true)
                            ->options(['1' => 'This will be a LIVE course'])
                            ->descriptions(['1' => new HtmlString("Live courses let's you create your class schedules and let your
                            students join you for live learning. <br>You need to have a Zoom account connected with Edmingle to create live courses.")]),

                        // Forms\Components\CheckboxList::make('sub_categories')
                        //     ->inlineLabel(true)
                        //     ->relationship('sub_categories', 'name')
                        //     ->columns(2)
                        //     ->gridDirection('row')
                        //     ->searchable(),

                        Forms\Components\CheckboxList::make('copy_from_existing_course')
                            ->label('Subject Modules')
                            ->inlineLabel(true)
                            ->live()
                            ->options(['1' => 'I want to choose existing course content for this course program.'])
                            ->descriptions(['1' => new HtmlString("Keep this checkbox <strong>ON</strong> if you want to attach existing courses to this program.
                                <br>Keeping it <strong>OFF</strong> will create a new blank course and attach to this program. Don't worry, you can always attach other existing/new courses later.")])
                            ->hidden(fn (string $context): bool => $context !== 'create'),

                        Forms\Components\Select::make('curriculums')
                            ->inlineLabel(true)
                            ->relationship('curriculums', 'name')
                            ->searchable()
                            ->preload()
                            ->multiple()
                            ->hidden(fn (Forms\Get $get): bool => !$get('copy_from_existing_course')),

                        Forms\Components\Radio::make('course_type')
                            ->inlineLabel(true)
                            ->default(1)
                            ->options([
                                '1' => 'Online Only',
                                '2' => 'Classroom Program'
                            ])
                            ->descriptions([
                                '1' => '(Select this if this package is intended only for online coaching.)',
                                '2' => '(Select this if the students can enrol in this package for classroom program at your institution.)',
                            ]),


                        Forms\Components\CheckboxList::make('allow_course_complete')
                            ->label('Course Completion')
                            ->inlineLabel(true)
                            ->options(['1' => 'Allow Student to mark course as complete.'])
                            ->hidden(fn (string $context): bool => $context !== 'create'),


                        Forms\Components\Textarea::make('short_description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->inlineLabel(true)
                            ->hidden(fn (string $context): bool => $context === 'create'),

                        /*Forms\Components\Toggle::make('allow_course_complete')
                            ->label('Allow Course Complete')
                            ->helperText("Allow Student to mark course as complete."),*/
                    ])->heading(function (string $context) {
                        return $context !== 'create' ? 'Basic Details' : '';
                    }),
                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\CheckboxList::make('course_unenrolling')
                            ->label('Course Unenrolling')
                            ->inlineLabel(true)
                            ->options(['1' => 'Allow enrolled students to Unenroll from this course'])
                            ->descriptions(['1' => new HtmlString("If this setting is on, the students who enrolls in this course will also be able to unenroll themselves. Please note that this setting will NOT process any automated fee refunds.")]),

                        Forms\Components\CheckboxList::make('content_access_after_completion')
                            ->label('Content Access After Completion')
                            ->inlineLabel(true)
                            ->options(['1' => 'Allow enrolled students to access course content even after the batch running in this course is marked as completed.']),

                        Forms\Components\CheckboxList::make('allow_course_complete')
                            ->label('Course Completion')
                            ->inlineLabel(true)
                            ->options(['1' => 'Allow Student to mark course as complete.'])

                    ])->heading('Other Settings')
                    ->hiddenOn('create'),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('online_sale_url')
                            ->label('Pretty Name')
                            ->helperText('The URL at which the package will be available for online sale.')
                            ->inlineLabel(true)
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image')
                            ->inlineLabel(true)
                            ->label('Display Image')
                            ->image(),
                        Forms\Components\TextInput::make('promo_video_url')
                            ->label('Promo Video URL')
                            ->url()
                            ->inlineLabel(true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('overview')
                            ->inlineLabel(true)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_package')
                            ->required()
                    ])
                    ->heading('Course Online Display')
                    ->hiddenOn('create'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->limit(50),
                Tables\Columns\TextColumn::make('formatted_course_type')
                    ->label('Course type')
                    ->searchable(),

                // Tables\Columns\TextColumn::make('sub_categories.name')
                //     ->label('Sub Categories')
                //     ->listWithLineBreaks()
                //     ->bulleted()
                //     ->limitList(2)
                //     ->expandableLimitedList()
                //     ->searchable(),

                /*Tables\Columns\TextColumn::make('branches.name')
                    ->label('Branches')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable(),*/

                /*Tables\Columns\IconColumn::make('allow_course_complete')
                    ->boolean(),
                Tables\Columns\TextColumn::make('formatted_allow_course_complete')
                    ->label('Allow course complete')
                    ->searchable(),*/
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
                /*SelectFilter::make('branches')
                    ->label('Branch')
                    ->relationship('branches', 'name')
                    ->searchable()
                    ->preload(),*/
                SelectFilter::make('course_type')
                    ->options([
                        1 => 'Online Only',
                        2 => 'Classroom Program'
                    ])
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sub_categories')
                    ->label('Sub Category')
                    ->relationship('sub_categories', 'name')
                    ->searchable()
                    ->preload(),
            ], FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                /*Action::make('edd')
                    ->url(fn (Course $record): string => route('filament.administrator.resources.courses.curriculums',
                        ['record'=>$record,'tenant'=>Filament::getTenant()]))
                    ->label('Curriculum'),*/
                //Tables\Actions\CreateAction::make(),
            ])
//            ->recordUrl(
//                fn (Course $record): string => route('filament.administrator.resources.courses.curriculums',
//                    ['record'=>$record,'tenant'=>Filament::getTenant()])
//            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages\ListCourseMaster::route('/'),
            //'create' => \App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages\CreateCourseMaster::route('/create'),
            //'edit' => \App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages\EditCourseMaster::route('/{record}/edit'),
            //'curriculums' => \App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages\ManageCurriculumns::route('/{record}/curriculums'),

            /*'index' => Pages\ListCourses::route('/'),
            'curriculums' => Pages\ManageCurriculumns::route('/{record}/curriculums'),
            //'branches' => Pages\ManageBranches::route('/{record}/branches'),
            //  'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
            'view' => Pages\ViewCourse::route('/{record}'),*/
        ];
    }

    /*public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ManageCurriculumns::class,
            Pages\ViewCourse::class,
            Pages\EditCourse::class,

            //Pages\ManageBranches::class,
        ]);
    }*/
}
