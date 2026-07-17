<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FindingAssignedNotification extends Notification
{
    use Queueable;

    public $finding;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Finding $finding)
    {
        $this->finding = $finding;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // We will add 'whatsapp' channel later
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Gemba Finding Assigned: ' . $this->finding->finding_no)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('You have been assigned a new finding.')
                    ->line('Category: ' . $this->finding->category->name)
                    ->line('Location: ' . $this->finding->location)
                    ->line('Deadline: ' . $this->finding->due_date->format('d M Y, H:i'))
                    ->action('View Finding', url('/findings/' . $this->finding->id))
                    ->line('Please take action before the deadline.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'finding_id' => $this->finding->id,
            'finding_no' => $this->finding->finding_no,
            'message' => 'New Finding Assigned: ' . $this->finding->category->name . ' at ' . $this->finding->location,
            'due_date' => $this->finding->due_date->toIso8601String(),
        ];
    }
}
