<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewLesson extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Course */
        $record = $this->getRecord();

        return $record->name;
    }

    /*protected function getActions(): array
    {
        return [];
    }*/
}
