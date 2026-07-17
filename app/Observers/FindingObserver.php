<?php

namespace App\Observers;

use App\Models\Finding;

class FindingObserver
{
    public function creating(Finding $finding): void
    {
        $yearMonth = now()->format('Ym');
        
        $lock = \Illuminate\Support\Facades\Cache::lock('finding_sequence_generation', 5);
        $lock->block(5, function () use ($finding, $yearMonth) {
            $lastFinding = Finding::where('finding_no', 'like', "GDF-{$yearMonth}-%")->orderBy('id', 'desc')->first();
            $nextSequence = 1;
            if ($lastFinding) {
                $lastSequence = (int) substr($lastFinding->finding_no, -4);
                $nextSequence = $lastSequence + 1;
            }
            $finding->finding_no = "GDF-{$yearMonth}-" . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Handle the Finding "created" event.
     */
    public function created(Finding $finding): void
    {
        \App\Models\FindingHistory::create([
            'finding_id' => $finding->id,
            'old_status' => null,
            'new_status' => $finding->status,
            'changed_by' => auth()->id() ?? $finding->created_by,
            'remark' => 'Finding created.',
        ]);

        event(new \App\Events\FindingCreated($finding));
    }

    /**
     * Handle the Finding "updated" event.
     */
    public function updated(Finding $finding): void
    {
        if ($finding->isDirty('status')) {
            \App\Models\FindingHistory::create([
                'finding_id' => $finding->id,
                'old_status' => $finding->getOriginal('status'),
                'new_status' => $finding->status,
                'changed_by' => auth()->id() ?? $finding->created_by, // Fallback if no auth
                'remark' => 'Status updated.',
            ]);
        }
    }

    /**
     * Handle the Finding "deleted" event.
     */
    public function deleted(Finding $finding): void
    {
        //
    }

    /**
     * Handle the Finding "restored" event.
     */
    public function restored(Finding $finding): void
    {
        //
    }

    /**
     * Handle the Finding "force deleted" event.
     */
    public function forceDeleted(Finding $finding): void
    {
        //
    }
}
