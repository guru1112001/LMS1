<?php

namespace App\Filament\Resources\BatchStudentResource\Pages;

use App\Filament\Resources\BatchStudentResource;
use App\Filament\Resources\TeachingMaterialResource\Widgets\TeachingMaterial;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Section;
use App\Models\TeachingMaterialStatus;
use App\Tables\Columns\CustomActionColumn;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Table;
use Filament\Forms;
use LaraZeus\Accordion\Forms\Accordions;
use function Laravel\Prompts\alert;

class ViewBatch extends EditRecord
    //implements HasForms
{
    //use InteractsWithForms;

    protected static string $resource = BatchStudentResource::class;

    protected static string $view = 'filament.resources.my-courses.list';

    public $batch = null;
    public $course = null;
    public $section = null;
    public $assignmentSubmissionAlert = false;
    public $sections = [];
    public $curriculum = null;
    public $view_material = null;
    public $percentageCompleted = 0;
    public $curriculums = [];
    public $topics = [];
    public $materialStatus = [];

    public function getSubHeading(): string
    {
        return "Sumedha Institute of Technology";
    }

    public function getTitle(): string
    {
        return "";
    }

    public function updatePercentage()
    {

        $totalCount = 0;
        if($this->sections) {
            $totalCount = $this->sections->sum(function ($section) {
                return $section->teaching_material->count();
            });
        }

        $completed = $this->materialStatus->count();

        $this->percentageCompleted = ($totalCount > 0) ? ($completed / $totalCount) * 100 : 0;
        if($this->percentageCompleted > 100 )
            $this->percentageCompleted = 100;
    }

    public function form(Form $form): Form
    {
        $this->batch = $this->record->id;
        $this->course = $this->record->course_package;

        if ($this->course) {

            //$this->curriculums = $this->record->course_package->curriculums;
            $this->curriculums = $this->record->teaching_materials_curriculums;

            if ($this->curriculums) {
                $firstCurriculums = $this->curriculums->first();
                if ($firstCurriculums) {
                    $this->curriculum = $firstCurriculums->id;
                    //$this->sections = $firstCurriculums->sections;
                }
            }

            $tmp_curriculum = $this->curriculum;

            $dataa = [];
            if($tmp_curriculum)
                $dataa = $this->curriculums->pluck('name', 'id')->toArray();

            //dd($this->curriculums->pluck('name','id')->toArray());
            return $form
                ->schema([

//                    \LaraZeus\Accordion\Infolists\Accordions::make('Options')
//                        ->activeAccordion(2)
//                        ->isolated()
//
//                        ->accordions([
//                            \LaraZeus\Accordion\Infolists\Accordion::make('main-data')
//                                ->columns()
//                                ->label('User Details')
//                                ->icon('iconpark-commentone')
//                                ->schema([
//                                    TextInput::make('name')->required(),
//                                    TextInput::make('email')->required(),
//                                ]),
//                        ]),

                    Forms\Components\Select::make('curriculum')
                        ->label(false)
                        ->options($dataa)
                        ->selectablePlaceholder(false)
                        // ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $this->updatedSection($state);
                            //$this->section = [];
                            //$set('sections', []);
                        })->columnSpanFull()
                    //->hidden(fn(Forms\Get $get): bool => !$get('copy_from_existing_course')),
                ]);
        }
    }

    public function mount(int|string $record): void
    {
        parent::mount($record); // TODO: Change the autogenerated stub
        $this->batch = $this->record->id;
        $this->course = $this->record->course_package;
        //$this->materialStatus = TeachingMaterialStatus::where('user_id', auth()->user()->id)->get();
        $this->updateMaterialStatus();

        if ($this->course) {

            $this->curriculums = $this->record->teaching_materials_curriculums;

            if ($this->curriculums) {
                $firstCurriculums = $this->curriculums->first();
                if ($firstCurriculums) {
                    $this->curriculum = $firstCurriculums->id;
                    //$this->sections = $firstCurriculums->sections;
                    $this->updatedSection($this->curriculum);
                }
            }
        }
        $this->updatePercentage();
    }

    public function updatedSection($curriculum_id)
    {
        $batchId = $this->record->id;
        //dd($batchId);
        if (empty($curriculum_id)) {
            $this->sections = [];
        } else {
            $firstCurriculum = $this->curriculums->where('id', $curriculum_id)->first();
            //$this->sections = $firstCurriculums->sections;
            $this->sections = Section::where('curriculum_id', $firstCurriculum->id)
                ->whereHas('batches', function($query) use ($batchId) {
                    $query->where('batch_id', $batchId);
                })
            ->get();
        }
    }

    public function updateMaterialStatus()
    {
        $this->materialStatus = TeachingMaterialStatus::where('user_id', auth()->user()->id)
            ->where('batch_id',$this->record->id)
            ->get();
    }

    public function selectMaterial($materialId)
    {

        $this->updateMaterialStatus();

        //dd($this->materialStatus);
        //$nextMaterial = null;
        //$nextSection = null;
        $found = false;

        foreach ($this->sections->sortBy('sort') as $section) {
            foreach ($section->teaching_material->sortBy('sort') as $material) {
                /*if ($found) {
                    $nextMaterial = $material;
                    $nextSection = $section;
                    break 2; // Exit both loops
                }*/
                if($material->prerequisite == 1
                    && $this->materialStatus->where('teaching_material_id', $material->id)->count() == 0
                    && $material->id != $materialId)
                {
                    //dd($material->id, $this->materialStatus->where('teaching_material_id', $material->id)->count());
                    break 2; // Exit both loops
                }
                if ($material->id == $materialId) {
                    $found = true;
                }
            }
        }

        if($found)
            $this->view_material = \App\Models\TeachingMaterial::find($materialId);
        else
        {
//            Notification::make()
//                ->title('Please Complete the previos exercise')
//                ->sendToDatabase(auth()->user());
//
//            event(new DatabaseNotificationsSent(auth()->user()));

            Notification::make()
                ->title('Unable to open!')
                ->body('Please Complete the previous exercise')
                ->danger()
                ->color('danger')
                ->send();

            $this->view_material = \App\Models\TeachingMaterial::find($material->id);
        }


    }

    public function completeMaterial($materialId)
    {
        $submitted_material = \App\Models\TeachingMaterial::find($materialId);

        $this->assignmentSubmissionAlert = false;
        if($submitted_material->doc_type == 2) {
            $material_submission_status = TeachingMaterialStatus::where([
                'user_id' => auth()->user()->id,
                'batch_id' => $this->batch,
                'teaching_material_id' => $materialId
            ])
            ->count();
            if($material_submission_status == 0) {
                $this->assignmentSubmissionAlert = true;
                return false;
            }
        }

        TeachingMaterialStatus::updateOrCreate(
            [
                'user_id' => auth()->user()->id,
                'batch_id' => $this->batch,
                'teaching_material_id' => $materialId
            ]
        );

        $this->updateMaterialStatus();

       // $this->materialStatus = TeachingMaterialStatus::where('user_id', auth()->user()->id)->get();


        $nextMaterial = null;
        $nextSection = null;
        $found = false;

        foreach ($this->sections->sortBy('sort') as $section) {
            foreach ($section->teaching_material->sortBy('sort') as $material) {
                if ($found) {
                    $nextMaterial = $material;
                    $nextSection = $section;
                    break 2; // Exit both loops
                }
                if ($material->id == $materialId) {
                    $found = true;
                }
            }
        }
        if ($nextMaterial) {
            //$this->updatedSection($nextSection->id); // Get the next section ID
            $this->view_material = \App\Models\TeachingMaterial::find($nextMaterial->id);
        }

//        $next = 0;
//
//        foreach ($this->sections as $section) {
//            if ($next == 1) {
//                break;
//            }
//            foreach ($section->teaching_material as $material) {
//
//                if ($next == 1) {
//                    //dd($old, $this->view_material);
//                    break;
//                }
//
//                if ($material->id == $materialId) {
//                    $next = 1;
//                }
//            }
//        }
//        if ($next == 1)
//            $this->view_material = \App\Models\TeachingMaterial::find($material->id);

        //dd($this->sections);

        $this->updatePercentage();
    }

    /*public function selectCurriculum()
    {
        //dd($this->curriculum);
        $firstCurriculums = $this->curriculums->where('id', $this->curriculum)->first();
        $this->sections = $firstCurriculums->sections;
        //$this->curriculum = $this->curriculums->where('id', $curriculumId)->first();
    }*/

//    public function getFormSchema(): array
//    {
//        return [
//            Forms\Components\Select::make('curriculum')
//                ->options($this->curriculums->pluck('name', 'id')),
//        ];
//    }


}