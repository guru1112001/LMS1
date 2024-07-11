<?php

namespace App\Filament\Resources\CurriculumResource\Pages;

use App\Filament\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCurriculum extends ListRecords
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTableQuery(): Builder
    {
        if(auth()->user()->is_tutor) {
        return parent::getTableQuery()
                //->primary('curriculum.id')
                ->select('curriculum.*')        
                ->join('batch_curriculum', 'curriculum.id', '=', 'batch_curriculum.curriculum_id')                
                ->where('batch_curriculum.tutor_id', auth()->user()->id);
        }

        return parent::getTableQuery();
    }
}
