<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Imports\AttendanceImporter;
use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['attendance_by'] = auth()->id();
                    return $data;
                }),
            Actions\ImportAction::make()
                ->importer(AttendanceImporter::class)
            ->visible(auth()->user()->is_admin)
        ];
    }
}
