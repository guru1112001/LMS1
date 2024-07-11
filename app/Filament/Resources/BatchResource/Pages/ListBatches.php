<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class ListBatches extends ListRecords
{
    protected static string $resource = BatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

      
    // public function getTableRecordKey(Model $record): string
    // {
    //     return 'id';
    // }

    public function getTableQuery(): Builder
    {
        if(auth()->user()->is_tutor) {
        return parent::getTableQuery()
                //->primary('curriculum.id')
                ->select('batches.*')            
                ->join('batch_curriculum', 'batches.id', '=', 'batch_curriculum.batch_id')
                    ->where('batch_curriculum.tutor_id', auth()->user()->id);
        }
        return parent::getTableQuery();
    }
}
