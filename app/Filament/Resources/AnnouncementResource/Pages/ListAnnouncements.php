<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\Batch;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Events\DatabaseNotificationsSent;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()->is_admin) {
            return [
                Actions\CreateAction::make()
                    ->after(function (Announcement $record) {
                        //$recipient = auth()->user();
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
                    })
//                    ->successNotification(
//                        Notification::make()
//                            ->success()
//                            ->title('User registered')
//                            ->body('The user has been created successfully.'),
//                    )
//                    ->mutateFormDataUsing(function (array $data) {
//
//                        // Get all users
//                        $users = User::all();
//
//                        // Send notification to all users
//                        //Notification::send($users, new NewAnnouncement($data));
//                        Notification::send($users, new NewAnnouncement($data));
//
//
//                        return $data;
//                    }),
            ];
        }
        return [];
    }
}
