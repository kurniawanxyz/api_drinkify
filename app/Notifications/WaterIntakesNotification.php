<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use YieldStudio\LaravelExpoNotifier\Dto\ExpoMessage;
use YieldStudio\LaravelExpoNotifier\ExpoNotificationsChannel;

class WaterIntakesNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private string $reminder
    )
    {
        //
    }

    public function via($notifiable): array
    {
        return [ExpoNotificationsChannel::class];
    }

    public function toExpoNotification($notifiable): ExpoMessage
    {
        return (new ExpoMessage())
            ->to([$notifiable->token])
            ->title('Reminder to drink water')
            ->body($this->reminder." ml to achieve goals today")
            ->channelId('default');
    }
}
