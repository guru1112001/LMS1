<?php

namespace App\Livewire\TeachingMaterial;

use App\Models\TeachingMaterialStatus;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class UploadAssignment extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public TeachingMaterialStatus $record;

    public function mount(): void
    {
        //$this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file')
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
    }

    public function render(): View
    {
        return view('livewire.teaching-material.upload-assignment');
    }
}
