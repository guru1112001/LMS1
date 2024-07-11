<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Resources\AnnouncementResource;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements= Announcement::where('schedule_at','<=',now())
        ->orderBy('schedule_at', 'desc')
        ->get();

        return AnnouncementResource::collection($announcements);
    }
}
