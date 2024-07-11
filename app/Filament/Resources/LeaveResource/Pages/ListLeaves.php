<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Actions\Action;


class ListLeaves extends ListRecords
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    //dd($data);
                    $data['user_id'] = auth()->id();
                    $data['status'] = "Pending";
                    $data['updated_by'] = 0;
                    return $data;
                })->after(function (Leave $record) {

                    $users = User::where('role_id', 5)->get();

                    foreach ($users as $user) {
                        Notification::make()
                            ->title($record->user->name . " applied for leave(s).")
                            ->actions([
                                Action::make('view')->button()->url(
                                    route('filament.administrator.resources.leaves.index', Filament::getTenant()) . '?tableSearch=' . $record->id),
                            ])
                            ->sendToDatabase($user);

                        event(new DatabaseNotificationsSent($user));
                    }
                }),

//            Actions\EditAction::make()
//                ->mutateFormDataUsing(function (array $data) {
//                    //dd($data);
//                    //$data['user_id']  = auth()->id();
//                    //$data['status']  = "Pending";
//                    $data['updated_by']  = auth()->id();
//                    return $data;
//                }),
        ];
    }
}
