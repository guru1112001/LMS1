<?php

namespace App\Livewire\TeachingMaterialStatus;


use App\Models\TeachingMaterial;
use App\Models\TeachingMaterialStatus;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class SubmitAssignment extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount($teaching_material_id, $batch_id): void
    {
        $material_submission_status = TeachingMaterialStatus::where([
            'user_id' => auth()->user()->id,
            'batch_id' => $batch_id,
            'teaching_material_id' => $teaching_material_id
        ])->first();
        if($material_submission_status){
            //dd($material_submission_status);
            $this->form->fill($material_submission_status->toArray());
        } else {

            $s = $this->form->fill(
                [
                    'user_id' => auth()->user()->id,
                    'teaching_material_id' => $teaching_material_id,
                    'batch_id' => $batch_id
                ]
            );
            //dd($s);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id'),
                Forms\Components\Hidden::make('teaching_material_id'),
                Forms\Components\Hidden::make('batch_id'),
                Forms\Components\FileUpload::make('file')
                    ->deletable(false)
                    ->downloadable(),
            ])
            ->statePath('data')
            ->model(TeachingMaterialStatus::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = TeachingMaterialStatus::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'teaching_material_id' => $data['teaching_material_id'],
                'batch_id' => $data['batch_id'],
            ],
            [
                'file' => $data['file'] ?? null,
            ]
        );
        //$record = TeachingMaterialStatus::create($data);

        $this->form->model($record)
            ->saveRelationships();
    }

    public function delete($id)
    {
        $material_submission_status = TeachingMaterialStatus::find($id);

        if ($material_submission_status) {
            $material_submission_status->delete();

            session()->flash('message', 'Assignment deleted successfully.');
        } else {

            session()->flash('error', 'Assignment not found.');
        }
        return true;
//        $this->form->model($material_submission_status)
//            ->saveRelationships();
    }


    public function render(): View
    {
        return view('livewire.teaching-material-status.submit-assignment');
    }
}
