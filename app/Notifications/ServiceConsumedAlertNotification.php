<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceConsumedAlertNotification extends Notification
{
    use Queueable;

    private $serviceName;
    private $baseUrl;

    /**
     * Create a new message instance.
     *
     * @param $serviceName
     * @param $baseUrl
     */
    public function __construct($serviceName, $baseUrl)
    {
        $this->serviceName = $serviceName;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->error()
                    ->subject('API Service Consumption Alert')
                    ->greeting('Hello!')
                    ->line('Sorry to inform you that, API service consumption limit reached for provider '.$this->serviceName)
                    ->action('Please Update', $this->baseUrl)
                    ->line('Thank you for using our application!');
    }
}
