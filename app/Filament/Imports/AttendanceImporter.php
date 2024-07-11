<?php

namespace App\Filament\Imports;

use App\Models\Attendance;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AttendanceImporter extends Importer
{
    protected static ?string $model = Attendance::class;

    protected function beforeSave(): void
    {
        $user = User::where('registration_number', $this->data['registration_number'])->first();
        if ($user)
            $this->data['user_id'] = $user->id;


        unset($this->data['registration_number']);

    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('date')
                ->requiredMapping()
                /*->fillRecordUsing(function (Attendance $record, string $state): void {
                    //$dateObj = \DateTime::createFromFormat('d/m/Y', $state);
                    //$formattedDate = $dateObj->format('Y-m-d');
                    $record->date = '2025-10-22';
                })*/
                ->rules(['required', 'date']),
            ImportColumn::make('registration_number')
                ->fillRecordUsing(function (Attendance $record, string $state): void {
                    $user = User::where('registration_number', $state)->first();
                    if ($user)
                        $record->user_id = $user->id;
                })
                ->requiredMapping()
                //->numeric()
                ->rules(['required']),
            ImportColumn::make('attendance_by')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('team_id')
                ->numeric()
                ->rules(['integer']),
        ];
    }

    public function resolveRecord(): ?Attendance
    {
        // return Attendance::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Attendance();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your attendance import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
