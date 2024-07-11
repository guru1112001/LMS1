<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $deviceToken = $request->input('device_token');
        $title = $request->input('title');
        $body = $request->input('body');

        $this->firebaseService->sendNotification($deviceToken, $title, $body);

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
