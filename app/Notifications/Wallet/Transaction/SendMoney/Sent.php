<?php

declare(strict_types=1);

namespace App\Notifications\Wallet\Transaction\SendMoney;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Sent extends Notification
{
    use Queueable;

    private array $channels;
    private string $descriptionMessage;

    public function __construct(array $channels, string $descriptionMessage)
    {
        $this->channels = $channels;
        $this->descriptionMessage = $descriptionMessage;
    }

    public function via()
    {
        return $this->channels;
    }

    public function toMail()
    {
        return (new MailMessage())
            ->subject('Transaction Alert')
            ->greeting('Dear Client')
            ->line($this->descriptionMessage)
            ->line('Thank you for using our application!');
    }
}
