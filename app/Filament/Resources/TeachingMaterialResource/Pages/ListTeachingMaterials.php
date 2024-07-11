<?php

namespace App\Filament\Resources\TeachingMaterialResource\Pages;

use App\Filament\Resources\TeachingMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachingMaterials extends ListRecords
{
    protected static string $resource = TeachingMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make()
//                ->mutateFormDataUsing(function (array $data) {
//                    if($data['unlimited_view'])
//                        $data['maximum_views'] = 0;
//                    return $data;
//                }),
        ];
    }
}
