<?php

namespace App\Listeners;

use App\Events\FindingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFindingNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FindingCreated $event): void
    {
        $finding = $event->finding;

        if ($finding->assigned_to) {
            $assignee = $finding->assignee;
            if ($assignee) {
                $assignee->notify(new \App\Notifications\FindingAssignedNotification($finding));
            }
        }
    }
}
