<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
//            ImportColumn::make('email_verified_at')
//                ->rules(['email', 'datetime']),
            ImportColumn::make('phone')
                ->requiredMapping()
                ->rules(['max:255']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['max:255']),
            ImportColumn::make('role_id')
                ->numeric()
                ->rules(['integer']),

            ImportColumn::make('registration_number')
                ->rules(['max:255']),
            ImportColumn::make('birthday')
                ->rules(['date']),
            ImportColumn::make('contact_number')
                ->rules(['max:255']),
            ImportColumn::make('gender')
                ->rules(['max:255']),
            ImportColumn::make('qualification_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('year_of_passed_out')
                ->rules(['max:255']),
            ImportColumn::make('address'),
            ImportColumn::make('city')
                ->rules(['max:255']),
            ImportColumn::make('state_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('pincode')
                ->rules(['max:255']),
            ImportColumn::make('school')
                ->rules(['max:255']),
            ImportColumn::make('aadhaar_number')
                ->rules(['number','max:12', 'min:12','digit:12']),
            ImportColumn::make('linkedin_profile')
                ->rules(['max:255']),
            ImportColumn::make('upload_resume')
                ->rules(['max:255']),
            ImportColumn::make('upload_aadhar')
                ->rules(['max:255']),
            ImportColumn::make('upload_picture')
                ->rules(['max:255']),
            ImportColumn::make('upload_marklist'),
            ImportColumn::make('upload_agreement'),
            ImportColumn::make('parent_name')
                ->rules(['max:255']),
            ImportColumn::make('parent_contact')
                ->rules(['max:255']),
            ImportColumn::make('parent_email')
                ->rules(['email', 'max:255']),
            ImportColumn::make('parent_aadhar')
                ->rules(['max:255']),
            ImportColumn::make('parent_occupation')
                ->rules(['max:255']),
            ImportColumn::make('residential_address'),
            ImportColumn::make('designation_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('experience')
                ->rules(['max:255']),
            ImportColumn::make('domain_id')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('subject'),
            ImportColumn::make('is_active')
                ->boolean()
                ->rules(['boolean']),
        ];
    }

    public function resolveRecord(): ?User
    {
         return User::firstOrNew([
             // Update existing records, matching them by `$this->data['column_name']`
             'email' => $this->data['email'],
        ]);

        //return new User();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        $this->record->teams()->syncWithoutDetaching([1]);

    }

}
