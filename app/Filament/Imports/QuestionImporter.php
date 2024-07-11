<?php

namespace App\Filament\Imports;

use App\Models\Question;
use App\Models\QuestionOption;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class QuestionImporter extends Importer
{
    protected static ?string $model = Question::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('question_bank_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('audio_file')
                ->rules(['max:255']),
            ImportColumn::make('paragraph'),
            ImportColumn::make('question')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('question_type')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
//            ImportColumn::make('difficulty')
//                ->requiredMapping()
//                ->rules(['required', 'max:255']),
//            ImportColumn::make('topic')
//                ->requiredMapping()
//                ->rules(['required', 'max:255']),
            ImportColumn::make('marks')
                ->requiredMapping()
                ->numeric()
                ->rules(['required']),
            ImportColumn::make('negative_marks')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('hint'),
            ImportColumn::make('explanation'),
            ImportColumn::make('answer'),
            ImportColumn::make('check_capitalization')
                ->boolean(),
            ImportColumn::make('check_punctuation')
                ->boolean(),
            ImportColumn::make('option_1'),
            ImportColumn::make('option_2'),
            ImportColumn::make('option_3'),
            ImportColumn::make('option_4'),
            ImportColumn::make('option_5'),
            ImportColumn::make('option_6'),
            ImportColumn::make('option_7'),
            ImportColumn::make('option_8'),
            ImportColumn::make('option_9'),
            ImportColumn::make('option_10'),
            ImportColumn::make('correct_answer'),
        ];
    }

    public function resolveRecord(): ?Question
    {
        // return Question::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Question();
    }

    public function saveRecord(): void
    {

        $recordOpt = clone $this->record;
        unset($this->record->option_1);
        unset($this->record->option_2);
        unset($this->record->option_3);
        unset($this->record->option_4);
        unset($this->record->option_5);
        unset($this->record->option_6);
        unset($this->record->option_7);
        unset($this->record->option_8);
        unset($this->record->option_9);
        unset($this->record->option_10);
        unset($this->record->correct_answer);

        ////$this->record->ans
        $success = $this->record->save();

        if ($success) {
            for ($i = 1; $i <= 10; $i++) {
                $option = 'option_' . $i;
                if (!empty($recordOpt->$option)) {
                    $correct = in_array($i, explode(",",$recordOpt->correct_answer));
                    QuestionOption::create([
                        'question_id' => $this->record->id,
                        'option' => $recordOpt->$option,
                        'is_correct' => $correct
                    ]);
                }
            }
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your question import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
