<?php

namespace App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages;

use App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseMaster extends ListRecords
{
    protected static string $resource = CourseMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['is_live_course'] = !empty($data['is_live_course']) ? 1 : 0;
                    $data['allow_course_complete'] = !empty($data['allow_course_complete']) ? 1 : 0;
                    $data['copy_from_existing_course'] = !empty($data['copy_from_existing_course']) ? 1 : 0;
                    $data['course_unenrolling'] = !empty($data['course_unenrolling']) ? 1 : 0;
                    $data['content_access_after_completion'] = !empty($data['content_access_after_completion']) ? 1 : 0;
                    return $data;
                }),
        ];
    }
}
