<?php

namespace App\Livewire\TeachingMaterial;

use App\Filament\Resources\SectionResource;
use App\Filament\Resources\TeachingMaterialResource;
use App\Filament\Resources\TeachingMaterialResource\Pages\EditTeachingMaterial;
use App\Models\Section;
use App\Models\TeachingMaterial;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ViewRecord;

class ListMaterial extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = TeachingMaterialResource::class;

    public Section $section;

    public function form_in()
    {
        return [

            \Filament\Forms\Components\Section::make()
                ->schema([

                    Hidden::make('section_id')
                        ->default($this->section->id),

                    Hidden::make('doc_type')
                        ->reactive()
                        ->default(1),

//                    Select::make('section_id')
//                        ->label('Section')
//                        ->options(function (callable $get) {
//                            return Section::pluck('name', 'id');
//                        })
//                        ->default()
//                        ->searchable()
//                        ->preload()
//                        ->required()
//                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->required()
                        //->hidden(fn(Get $get): bool => $get('doc_type') == 1)
                        ->maxLength(255),
                    //Forms\Components\TextInput::make('material_source')
                    Radio::make('material_source')
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

                    FileUpload::make('file')
                        ->inlineLabel(true)
                        ->label('File')
                        ->hidden(fn(Get $get): bool => !in_array($get('material_source'), ['video', 'other'])),
                    Textarea::make('content')
                        ->hidden(fn(Get $get): bool => !in_array($get('material_source'), ['url', 'content']))
                        ->columnSpanFull(),

                    Group::make()->schema([
                        Checkbox::make('unlimited_view')
                            ->reactive()
                            ->inline(false)
                            ->label('Allow unlimited view'),
                        TextInput::make('maximum_views')
                            ->hidden(fn(Get $get): bool => $get('unlimited_view'))
                            ->required()
                            ->numeric(),
                    ])->columns(4),
                    Toggle::make('prerequisite')
                        ->label('Make this a prerequisite.')
                        ->helperText("Students won't be able to move on to next lesson unless they complete this lesson.")
                        ->required(),

                    Toggle::make('published'),
                    Textarea::make('description')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('sort')
                        ->numeric()
                        ->required(),
                ]),
            \Filament\Forms\Components\Section::make()
                ->schema([
                    Radio::make('privacy_allow_access')
                        ->label('Allow Access on')
                        ->options([
                            "app" => "Both",
                            "both" => "App"
                        ])
                        ->inline(true),
                    Toggle::make('privacy_downloadable')
                        ->inline(true)
                        ->label('Downloadable.')
                        ->helperText("Allow students to download this material"),
                ])
                ->heading('Privacy')
        ];
    }

    public function form_assignment_in()
    {
        return [

            \Filament\Forms\Components\Section::make()
                ->schema([

                    Hidden::make('section_id')
                        ->default($this->section->id),
                    Hidden::make('doc_type')
                        ->default(2),

//                    Select::make('section_id')
//                        ->label('Section')
//                        ->options(function (callable $get) {
//                            return Section::pluck('name', 'id');
//                        })
//                        ->default()
//                        ->searchable()
//                        ->preload()
//                        ->required()
//                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->label('Assignment Title')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('maximum_marks')
                        ->numeric()
                        ->required(),

                    FileUpload::make('file')
                        ->inlineLabel(true)
                        ->label('File'),

                    TextInput::make('passing_percentage')
                        ->numeric()
                        ->required(),

                    Select::make('result_declaration')
                        ->options([
                            1 => "Declare immediately on test submission",
                            2 => "Declare later (You'll be given an option to declare)",
                        ])
                        ->required(),

                    Select::make('maximum_attempts')
                        ->options([
                            -1 => "Unlimited",
                            1 => 1,
                            2 => 2,
                            3 => 3,
                            4 => 4,
                            5 => 5,
                            6 => 6,
                            7 => 7,
                            8 => 8,
                            9 => 9,
                            10 => 10,
                        ])
                        ->required(),


                    DateTimePicker::make('start_submission')
                        ->native(false)
                        ->reactive()
                        ->default(now())
                        ->required(),
                    //->afterStateUpdated(fn ($state, callable $set) => $this->resetEndDate($state, $set)),
                    DateTimePicker::make('stop_submission')
                        ->native(false)
                        ->default(now())
                        ->minDate(fn(callable $get) => $get('start_submission'))
                        ->required(),


                    Toggle::make('prerequisite')
                        ->label('Make this a prerequisite . ')
                        ->helperText("Students won't be able to move on to next lesson unless they complete this lesson . ")
                        ->required(),

                    Textarea::make('description')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('sort')
                        ->numeric()
                        ->required(),
                ])->columns(2),
            \Filament\Forms\Components\Section::make()
                ->schema([
                    Radio::make('privacy_allow_access')
                        ->label('Allow Access on')
                        ->options([
                            "app" => "Both",
                            "both" => "App"
                        ])
                        ->inline(true),
                    Toggle::make('privacy_downloadable')
                        ->inline(true)
                        ->label('Downloadable.')
                        ->helperText("Allow students to download this material"),
                ])
                ->heading('Privacy')
        ];
    }

//    public function resetEndDate($state, $set)
//    {
//        if ($state->stop_submission && $state->stop_submission < $state) {
//            $set('stop_submission', null);
//        }
//    }

    public function displayDiscription($record)
    {
        if ($record->material_source == "other") {
            return $record->file;
        } else if ($record->material_source == "url") {
            return '<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.1"
   id="Layer_1"
   x="0px"
   y="0px"
   viewBox="0 0 71.412065 50"
   xml:space="preserve"
   inkscape:version="0.91 r13725"
   sodipodi:docname="YouTube_full-color_icon (2017).svg"
   width="35"
   height="25"><metadata
     id="metadata33"><rdf:RDF><cc:Work
         rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" /><dc:title></dc:title></cc:Work></rdf:RDF></metadata><defs
     id="defs31" /><sodipodi:namedview
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1"
     objecttolerance="10"
     gridtolerance="10"
     guidetolerance="10"
     inkscape:pageopacity="0"
     inkscape:pageshadow="2"
     inkscape:window-width="1366"
     inkscape:window-height="715"
     id="namedview29"
     showgrid="false"
     fit-margin-top="0"
     fit-margin-left="0"
     fit-margin-right="0"
     fit-margin-bottom="0"
     inkscape:zoom="1.3588925"
     inkscape:cx="-71.668263"
     inkscape:cy="39.237696"
     inkscape:window-x="-8"
     inkscape:window-y="-8"
     inkscape:window-maximized="1"
     inkscape:current-layer="Layer_1" /><style
     type="text/css"
     id="style3">
	.st0{fill:#FF0000;}
	.st1{fill:#FFFFFF;}
	.st2{fill:#282828;}
</style><g
     id="g5"
     transform="scale(0.58823529,0.58823529)"><path
       class="st0"
       d="M 118.9,13.3 C 117.5,8.1 113.4,4 108.2,2.6 98.7,0 60.7,0 60.7,0 60.7,0 22.7,0 13.2,2.5 8.1,3.9 3.9,8.1 2.5,13.3 0,22.8 0,42.5 0,42.5 0,42.5 0,62.3 2.5,71.7 3.9,76.9 8,81 13.2,82.4 22.8,85 60.7,85 60.7,85 c 0,0 38,0 47.5,-2.5 5.2,-1.4 9.3,-5.5 10.7,-10.7 2.5,-9.5 2.5,-29.2 2.5,-29.2 0,0 0.1,-19.8 -2.5,-29.3 z"
       id="path7"
       inkscape:connector-curvature="0"
       style="fill:#ff0000" /><polygon
       class="st1"
       points="80.2,42.5 48.6,24.3 48.6,60.7 "
       id="polygon9"
       style="fill:#ffffff" /></g></svg>';
//            video-camera


        }
    }

    public function table(Table $table): Table
    {
        return $table
            //->query(TeachingMaterial::query())
            ->relationship(fn(): HasMany => $this->section->teaching_material())
            ->inverseRelationship('section')
            ->defaultSort('sort')
            ->reorderable('sort')
            ->heading($this->section->name)
            ->headerActions([
                CreateAction::make('create')
                    ->label('Add Teaching Material')
                    //->mutateFormDataUsing(fn(callable $set) => $set('doc_type', 1))
                    ->form($this->form_in()),
                CreateAction::make('assignment')
                    ->label('Add Assignment')
                    //->mutateFormDataUsing(fn(callable $set) => $set('doc_type', 2))
                    ->form($this->form_assignment_in())

            ])
            ->columns([
                Tables\Columns\TextColumn::make('sort')
                    ->label(''),

                IconColumn::make('id')
                    ->label('')
                    ->icon('heroicon-o-document-text'),
                Tables\Columns\TextColumn::make('name')->label('')
                    ->description(fn(TeachingMaterial $record) => new HtmlString($this->displayDiscription($record))
                    ),
//                Tables\Columns\TextColumn::make('material_source')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('file')
//                    ->searchable(),
                /*Tables\Columns\IconColumn::make('unlimited_view')
                    ->boolean(),
                Tables\Columns\TextColumn::make('maximum_views')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('prerequisite')
                    ->boolean(),
                Tables\Columns\TextColumn::make('privacy_allow_access')
                    ->searchable(),
                Tables\Columns\IconColumn::make('privacy_downloadable')
                    ->boolean(),
                Tables\Columns\IconColumn::make('published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
            ])
            ->filters([
                //
            ])
            ->actions([

                Action::make('id')
                    ->icon('heroicon-o-eye')
                    ->label('')
                    ->url(fn(TeachingMaterial $record): string => route('filament.administrator.resources.teaching-materials.material',
                        [
                            'tenant' => Filament::getTenant(),
                            'record' => $record->id])),
                ActionGroup::make([
                    Tables\Actions\EditAction::make('material')
                        ->form(fn(TeachingMaterial $record) => $record->doc_type == 1 ? $this->form_in() : $this->form_assignment_in()),

                    Tables\Actions\EditAction::make('publish')
                        ->icon('heroicon-o-arrow-path')
                        ->label('Publish / UnPublish')
                        ->recordTitle('Publish / UnPublish')
                        ->form([
                            CheckboxList::make('batches')
                                ->relationship(titleAttribute: 'name')
                                ->searchable()
                                ->noSearchResultsMessage('No batch found.')
                                ->bulkToggleable()
                                ->getOptionLabelFromRecordUsing((fn(Model $record) => new HtmlString($record->additional_details))),
                        ]),
                    Tables\Actions\DeleteAction::make('delete')
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.teaching-material.list-material');
    }

    public static function getPages(): array
    {
        return [

            'edit' => EditTeachingMaterial::route('/{record}/edit'),
        ];
    }
}
