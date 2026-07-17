<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FindingAction extends Model
{
    protected $fillable = [
        'finding_id', 'action_description', 'photo', 'performed_by', 'action_date'
    ];

    protected function casts(): array
    {
        return [
            'action_date' => 'datetime',
        ];
    }

    public function finding() { return $this->belongsTo(Finding::class); }
    public function performer() { return $this->belongsTo(User::class, 'performed_by'); }
}
