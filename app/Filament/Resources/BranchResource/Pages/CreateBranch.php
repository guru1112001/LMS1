<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use App\Models\Branch;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBranch extends CreateRecord
{
    protected static string $resource = BranchResource::class;
    protected static bool $isScopedToTenant = false;

    protected function afterCreate(): void
    {
        auth()->user()->teams()->attach($this->record);
        //$this->record->users()->sync(->id);
    }
}
