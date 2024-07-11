<?php

namespace App\Filament\Resources\CalendarResource\Pages;

use App\Filament\Resources\CalendarResource;
use App\Models\Calendar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCalendar extends CreateRecord
{
    protected static string $resource = CalendarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $dataA  = [];
        foreach ($data['schedule'] as $key => $datum)
        {
            $datum['team_id'] = $data['team_id'];
            $datum['start_time'] = $data['start_day'].' '.$datum['start_time'];
            $datum['end_time'] = $data['start_day'].' '.$datum['end_time'];
//            $datum['curriculum_id'] = $datum['curriculum_id'];
//            $datum['batch_id'] = $datum['batch_id'];
//            $datum['tutor_id'] = $datum['tutor_id'];
//            $datum['classroom_id'] = $datum['classroom_id'];
            //dd($datum, $data);
            if(count($data['schedule']) - 1 != $key)
            {
                Calendar::create($datum);
            } else {
                $dataA = $datum;
            }
        }
        return $dataA;

    }
}
