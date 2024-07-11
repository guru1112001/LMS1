<?php

namespace App\Livewire;

use App\Filament\Resources\TeachingMaterialResource;
use App\Models\TeachingMaterial;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SubmitAssignment extends Component implements HasForms
{
    use InteractsWithForms;

    public TeachingMaterial $post;

    public $title;
    public $content;

    public $name;
    public $email;

    public function mount(): void
    {
        $this->postForm->fill([
            'title' => $this->post->title,
            'content' => $this->post->content,
        ]);
    }

    protected function getPostFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\MarkdownEditor::make('content'),
        ];
    }

    public function savePost(): void
    {
        $this->post->update(
            $this->postForm->getState(),
        );
    }

    protected function getForms(): array
    {
        return [
            'postForm' => $this->makeForm()
                ->schema($this->getPostFormSchema())
                ->model($this->post),
        ];
    }

    //protected static string $resource = TeachingMaterialResource::class;

    /*public $file;
    public TeachingMaterial $record;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->required(),
            // Add other fields for the existing form here
        ];
    }

    public function submitSecondForm()
    {
        $this->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        if ($this->file instanceof TemporaryUploadedFile) {
            $path = $this->file->store('documents', 'public');

//            // Save file information to a separate table
//            \App\Models\TeachingMaterialStatus::create([
//                'record_id' => $this->recordId,
//                'path' => $path,
//            ]);
        }

        session()->flash('success', 'File uploaded successfully!');
    }

    public function mount($record)
    {
        $this->recordId = $record;

        $this->form = $this->makeForm()
            ->schema($this->getFormSchema())
            ->model(TeachingMaterial::class);

    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->model(TeachingMaterial::class)

        ];
    }
    /*protected function makeForm()
    {
        return Forms\ComponentContainer::make([
            $this->getFormSchema()
        ])
            //->schema($this->getFormSchema())
            ->statePath('form')
            ->inline();
    }*/


    public function render()
    {
        return view('livewire.submit-assignment');
    }
}
