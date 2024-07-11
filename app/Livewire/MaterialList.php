<?php

namespace App\Livewire;

use App\Filament\Resources\TeachingMaterialResource;
use App\Models\Section;
use App\Models\TeachingMaterial;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MaterialList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected $section = "";
    protected $section_name = "";

    public function mount($selectedSectionId, $selectedSectionName)
    {
        //dd('d');
        $this->section = $selectedSectionId;
        $this->section_name = $selectedSectionName;
    }

    public function getTablePage(): string
    {
        return TeachingMaterialResource\Pages\ListTeachingMaterials::class;
    }

    protected function getTableQuery()
    {

        return TeachingMaterial::query()->where('section_id', $this->section);
        //return Section::where('id', $this->record->id);
    }

//    public function unmount()
//    {
//        $this->section = "";
//        $this->section_name = "";
//    }

    public function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }


    protected function getTableActions(): array
    {
        return [
            Action::make('id')
                ->icon('heroicon-o-eye')
                ->label('')
                ->url(fn(TeachingMaterial $record): string => route('filament.administrator.resources.teaching-materials.material',
                    [
                        'tenant' => Filament::getTenant(),
                        'record' => $record->id]))
        ];
    }

    private function form()
    {
        return [

            \Filament\Forms\Components\Section::make()
                ->schema([

                    Select::make('section_id')
                        ->label('Section')
                        ->options(function (callable $get) {
                            return Section::pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->required()
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

    public function table(Table $table): Table
    {
        return $table
            ->heading($this->section_name)
            ->headerActions([
                CreateAction::make()->form($this->form())
            ])
            ->actions([
                ActionGroup::make([
                    \Filament\Tables\Actions\EditAction::make('material')->form($this->form()),
                ]),
                Action::make('id')
                    ->icon('heroicon-o-eye')
                    ->label('')
                    ->url(fn(TeachingMaterial $record): string => route('filament.administrator.resources.teaching-materials.material',
                        [
                            'tenant' => Filament::getTenant(),
                            'record' => $record->id]))
            ])
            ->defaultSort('sort')
            //->reorderable('sort')
            ->paginated(false)
            ->query(TeachingMaterial::query()->where('section_id', $this->section))
            ->columns([

//                Tables\Columns\TextColumn::make('name')
//                    ->description(fn (Curriculum $record) => $record->short_description)
//                    ->searchable(),
                TextColumn::make('sort')
                    ->label(''),
                IconColumn::make('id')
                    ->label('')
                    ->icon('heroicon-o-document-text'),
                TextColumn::make('name')->label('')
                    ->description(fn(TeachingMaterial $record) => $record->file),
            ])
            ->filters([
                // ...
            ])/*
            ->actions([
                // ...
            ])*/
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.material-list');
    }
}
