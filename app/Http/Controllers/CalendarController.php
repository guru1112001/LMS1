<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Http\Resources\HolidayResource;
use App\Http\Resources\CalendarResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class CalendarController extends Controller
{
    public function fetchData(Request $request)
    {
        // Validate input parameters
        $request->validate([
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d|after_or_equal:start',
            
        ]);
    
        $startDate = $request->input('start');
        $endDate = $request->input('end');
    
        
        $calendar = Calendar::with('curriculum')// as  sir told retrive the data with start and end date 
            ->whereBetween('start_time', [$startDate. ' 00:00:00', $endDate. ' 23:59:59'])
            ->get();
    
    
        $calendar = CalendarResource::collection($calendar);
        
        
        $holidays = Holiday::wherebetween('date', [$startDate,$endDate])
        ->get();
    
      
        $holidays = HolidayResource::collection($holidays);
    
    
        $mergedData = $calendar->merge($holidays);
    
        // $mergedData = $calendar->merge($holidays);

    
        $perPage = $request->input('page_size', 3); // Default page size is 3
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $mergedData->slice(($page - 1) * $perPage, $perPage)->values();
        $total = $mergedData->count();
        $start = ($page - 1) * $perPage + 1;
        // $end = min($start + $perPage - 1, $total);
        $lastPage = ceil($total / $perPage);
    
        // Construct the pagination response
        $pagination = [
            'total' => $total,
            'page' => $page,
            'start' => $start,
            'end' => $lastPage,
            'page_size' => $perPage,
        ];
    
        // Return the paginated data along with pagination information
        return [
            'data' => $items,
            // 'pagination' => $pagination,
        ];
    }
}
