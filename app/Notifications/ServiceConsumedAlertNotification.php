<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceConsumedAlertNotification extends Notification
{
    use Queueable;

    private string $serviceName;
    private string $baseUrl;

    public function __construct($serviceName, $baseUrl)
    {
        $this->serviceName = $serviceName;
        $this->baseUrl = $baseUrl;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->error()
            ->subject('API Service Consumption Alert')
            ->greeting('Hello!')
            ->line('Sorry to inform you that, API service consumption limit reached for provider '.$this->serviceName)
            ->action('Please Update', $this->baseUrl)
            ->line('Thank you for using our application!');
    }
}
