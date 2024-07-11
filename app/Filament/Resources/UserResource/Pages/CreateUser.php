<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Notifications\WelcomeEmail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $email = $this->form->getStateOnly(['email']);
        $password = $this->form->getStateOnly(['password']);

        //dd($email, $password);

        $notification = new WelcomeEmail(['login_email' => $email['email'], 'login_password' => $password['password']]);
        \Notification::route('mail', $email['email'])->notify($notification);
    }
}
