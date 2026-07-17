<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSLAs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finding:check-slas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check findings SLAs and update statuses if overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // Check for Overdue (Exclude WAITING_VERIFICATION to prevent unfair PIC penalty)
        $overdueFindings = \App\Models\Finding::whereIn('status', ['OPEN', 'IN_PROGRESS'])
            ->where('due_date', '<', $now)
            ->get();

        foreach ($overdueFindings as $finding) {
            $finding->update(['status' => 'OVERDUE']);
            // TODO: Notify Supervisor
            $this->info("Finding {$finding->finding_no} marked as OVERDUE.");
        }

        // Reminders for < 4 hours
        $reminderFindings = \App\Models\Finding::whereIn('status', ['OPEN', 'IN_PROGRESS'])
            ->whereBetween('due_date', [$now, $now->copy()->addHours(4)])
            ->get();

        foreach ($reminderFindings as $finding) {
            // TODO: Send Reminder Notification to PIC
            $this->info("Reminder needed for Finding {$finding->finding_no}.");
        }
    }
}
