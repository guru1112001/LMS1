<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeachingMaterial;
use App\Models\BatchTeachingMaterial;
use App\Models\BatchUser;
use App\Models\TeachingMaterialStatus;
use App\Models\User;
use Carbon\Carbon;
use App\Services\FirebaseService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SendAssignmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming assignment due dates';

    /**
     * Execute the console command.
     */
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        parent::__construct();
        $this->firebaseService = $firebaseService;
    }

    public function handle()
    {
        Log::info('Starting to send reminders.');
        $this->sendReminders(7, 'week');
        $this->sendReminders(1, 'day');
        Log::info('Finished sending reminders.');
    }

    private function sendReminders($daysBeforeDue, $reminderType)
    {
        $dueDate = Carbon::now()->addDays($daysBeforeDue)->toDateString();
        Log::info("Fetching assignments due on {$dueDate}.");

        $assignments = TeachingMaterial::where('doc_type', 2)
            ->whereDate('start_submission', $dueDate)
            ->get();

        foreach ($assignments as $assignment) {
            $batchTeachingMaterials = BatchTeachingMaterial::where('teaching_material_id', $assignment->id)->get();

            foreach ($batchTeachingMaterials as $batchTeachingMaterial) {
                $students = BatchUser::where('batch_id', $batchTeachingMaterial->batch_id)->get();

                foreach ($students as $student) {
                    $hasSubmitted = TeachingMaterialStatus::where('teaching_material_id', $assignment->id)
                        ->where('user_id', $student->user_id)
                        ->where('batch_id', $batchTeachingMaterial->batch_id)
                        ->exists();

                    if (!$hasSubmitted) {
                        $user = User::find($student->user_id);
                        $this->sendPushNotification($user, $assignment, $reminderType);
                    }
                }
            }
        }

        $this->info("Sent {$reminderType} reminders for assignments due in {$daysBeforeDue} days.");
    }

    private function sendPushNotification($user, $assignment, $reminderType)
    {
        if ($user->device_token) {
            $title = "Assignment Due Reminder - {$reminderType} notice";
            $body = "Your assignment '{$assignment->name}' is due in one {$reminderType}. Due date: {$assignment->start_submission}";

            // Log the notification details
            Log::info("Sending notification to user {$user->id} for assignment {$assignment->id}.");

            // Send Filament notification to database
            Notification::make()
                ->title($title)
                ->body($body)
                ->danger()
                ->sendToDatabase($user);

            // Send FCM notification
            try {
                $responseCode = $this->firebaseService->sendNotification($user->device_token, $title, $body);
                Log::info("Notification sent to user {$user->id} with response code {$responseCode}.");
            } catch (\Exception $e) {
                Log::error("Failed to send notification to user {$user->id}: " . $e->getMessage());
            }
        }
    }
}

