<?php

namespace App\Filament\Resources\CalendarResource\Widgets;

use App\Filament\Resources\CalendarResource;
use App\Models\Holiday;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use App\Models\Calendar;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Actions;

class CalendarWidget extends FullCalendarWidget
{
    //protected static string $view = 'filament.resources.calendar-resource.widgets.calendar-widget';
    //protected static string $resource = CalendarResource::class;


    public Model|string|null $model = Calendar::class;

    protected function headerActions(): array
    {
        if (auth()->user()->is_admin) {
            return [
                //Actions\CreateAction::make()
            ];
        }
        return [];
    }

    protected function modalActions(): array
    {
        if (auth()->user()->is_admin) {
            return [
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ];
        }
        return [];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $calendar = Calendar::where('calendars.start_time', '>=', $fetchInfo['start'])
            ->where('calendars.end_time', '<=', $fetchInfo['end'])
            // ->when(auth()->user()->is_student || auth()->user()->is_tutor, function ($q) {
            //     $q->whereIn('calendars.batch_id', auth()->user()->batches()->pluck('batches.id'));
            // })
            ->get()
            ->map(function (Calendar $task) {
                return [
                    'id' => $task->id,
                    'title' => $task->curriculum ? $task->curriculum->name : '',
                    'start' => $task->start_time,
                    'end' => $task->end_time,
                ];
            })->toArray();
        $holidays = Holiday::where('date', '>=', $fetchInfo['start'])
            ->where('date', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Holiday $task) {
                return [
                    'id' => $task->id,
                    'title' => "Holiday: " . $task->name,
                    'start' => $task->date,
                    'end' => $task->date,
                    'url' => "#",
                    //'shouldOpenUrlInNewTab' => true
                ];
            })->toArray();

        return array_merge($calendar, $holidays);

//        return Calendar::where('start_time', '>=', $fetchInfo['start'])
//            ->where('end_time', '<=', $fetchInfo['end'])
//            ->leftJoin('holidays', function($join) {
//                $join->on('calendars.start_time', '=', 'holidays.date')
//                    ->orOn('calendars.end_time', '=', 'holidays.date');
//            })
//            ->select('calendars.id', 'calendars.subject', 'calendars.start_time', 'calendars.end_time',
//                'holidays.name as holiday_name', 'holidays.date as holiday_date')
//            ->get()
//            ->map(function ($task) {
//                return [
//                    'id' => $task->id,
//                    'title' => $task->subject,
//                    'start' => $task->start_time,
//                    'end' => $task->end_time,
//                    'holiday_name' => $task->holiday_name ?? null, // Assuming 'name' is stored as 'holiday_name' in the Holiday table
//                    'holiday_date' => $task->holiday_date ?? null  // Assuming 'date' is stored as 'holiday_date' in the Holiday table
//                ];
//            })
//            ->toArray();

    }

    public function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Select::make('team_id')->relationship('team', 'name')->label('Branch')->required(),

                    Select::make('batch_id')->relationship('batch', 'name')->label('Batch')->required(),

                ]),
            Select::make('tutor_id')->relationship('tutor', 'name')->label('Tutor')->required(),

            Select::make('curriculum_id')
                ->relationship('curriculum', 'name')->required(),

            Select::make('classroom_id')->relationship('classroom', 'name')->label('Classroom')->required(),

            Grid::make()
                ->schema([
                    DateTimePicker::make('start_time')->required(),

                    DateTimePicker::make('end_time')->required(),
                ]),
        ];
    }

    public static function canView(): bool
    {
        return false;
    }
}
