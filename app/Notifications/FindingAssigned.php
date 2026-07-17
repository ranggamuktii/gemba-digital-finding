<?php

namespace App\Notifications;

use App\Models\Finding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FindingAssigned extends Notification
{
    use Queueable;

    public $finding;

    /**
     * Create a new notification instance.
     */
    public function __construct(Finding $finding)
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
        return ['database'];
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
            'description' => \Illuminate\Support\Str::limit($this->finding->description, 50),
            'message' => 'Anda ditugaskan sebagai PIC untuk temuan ' . $this->finding->finding_no,
            'url' => route('findings.show', $this->finding->id),
        ];
    }
}
