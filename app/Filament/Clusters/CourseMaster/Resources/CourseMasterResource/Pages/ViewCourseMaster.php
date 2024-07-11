<?php

namespace App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource\Pages;

use App\Filament\Clusters\CourseMaster\Resources\CourseMasterResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewCourseMaster extends ViewRecord
{
    protected static string $resource = CourseMasterResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Course */
        $record = $this->getRecord();

        return $record->name;
    }

    protected function getActions(): array
    {
        return [];
    }
}
