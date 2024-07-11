<?php
namespace App\Models;

class AuthenticationLog extends \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog
{
    public function user()
    {
        return $this->belongsTo(User::class, 'authenticatable_id');
    }
}
