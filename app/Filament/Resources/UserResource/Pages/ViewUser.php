<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

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
