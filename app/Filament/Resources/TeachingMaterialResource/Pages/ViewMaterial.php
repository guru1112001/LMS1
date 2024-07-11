<?php

namespace App\Filament\Resources\TeachingMaterialResource\Pages;

use App\Filament\Resources\TeachingMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewMaterial extends ViewRecord
{
    protected static string $resource = TeachingMaterialResource::class;

    protected static string $view = 'filament.resources.sections.pdf';

    public function getTitle() : string | Htmlable
    {
        return "";
    }



}
