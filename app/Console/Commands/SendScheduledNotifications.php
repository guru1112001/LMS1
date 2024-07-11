<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use App\Models\Batch;
use App\Models\User;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-scheduled-notifications';
    protected $description = 'Send scheduled Announcement';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifications = Announcement::where('schedule_at', '<=', now())
            ->where('sent', 0)
            ->get();

        foreach ($notifications as $record) {
            if ($record->audience == "all") {
                $users = User::all();
            } else {
                $users = collect(); // Using a collection to store all users
                //$batches = explode(",", $record->batch_ids);

                foreach ($record->batch_ids as $batch) {
                    //dd($batch, $record->batch_ids);
                    $batch_data = Batch::with('students')->find($batch);
                    $batch_users = $batch_data->students;
                    if ($batch_users)
                        $users = $users->merge($batch_users);
                }
            }

            //phpdd($record->schedule_at, now());
            if ($record->schedule_at <= now() && $record->sent == 0) {
                foreach ($users as $user) {
                    Notification::make()
                        ->title($record->title)
                        ->sendToDatabase($user);

                    event(new DatabaseNotificationsSent($user));
                }

                $record_update = Announcement::find($record->id);
                $record_update->sent = 1;
                $record_update->save();
            }
        }
    }
}
