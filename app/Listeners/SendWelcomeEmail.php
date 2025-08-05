<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    public function handle(UserRegistered $event): void
    {
        Mail::raw("Welcome, {$event->user->name}!", function ($msg) use ($event) {
            $msg->to($event->user->email)->subject('Welcome!');
        });
    }
}
