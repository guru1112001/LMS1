<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;
    public $login_email;
    public $login_password;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->login_email = $data['login_email'];
        $this->login_password = $data['login_password'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Dear user')
                    ->line('Please find below login details')
                    ->line('Email:'.$this->login_email)
                    ->line('Password:'.$this->login_password)
                    ->action('Login', url('/administrator'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
