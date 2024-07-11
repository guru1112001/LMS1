<?php

namespace App\Filament\Exports;

use App\Models\Question;
use App\Models\QuestionOption;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class QuestionExporter extends Exporter
{
    protected static ?string $model = Question::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('question_bank_id'),
            ExportColumn::make('audio_file'),
            ExportColumn::make('paragraph'),
            ExportColumn::make('question'),
            ExportColumn::make('question_type'),
            ExportColumn::make('difficulty'),
            ExportColumn::make('topic'),
            ExportColumn::make('marks'),
            ExportColumn::make('negative_marks'),
            ExportColumn::make('hint'),
            ExportColumn::make('explanation'),
            ExportColumn::make('answer')->formatState(),
            ExportColumn::make('check_capitalization'),
            ExportColumn::make('check_punctuation'),
            // ExportColumn::make('questions_options.option_1'),
            // ExportColumn::make('questions_options.option_2'),
            ExportColumn::make('option_1')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->first();
                return $option ? $option->option : '';
            }),


            ExportColumn::make('option_2')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(1)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_3')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(2)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_4')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(3)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_5')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(4)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_6')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(5)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_7')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(6)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_8')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(7)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_9')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(8)->first();
                return $option ? $option->option : '';
            }),

            ExportColumn::make('option_10')->formatStateUsing(function ($record) {
                $option = QuestionOption::where('question_id', $record->id)->skip(9)->first();
                return $option ? $option->option : '';
            }),




            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }




    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your question export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
