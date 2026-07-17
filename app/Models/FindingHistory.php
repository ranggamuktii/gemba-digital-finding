<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FindingHistory extends Model
{
    protected $fillable = [
        'finding_id', 'old_status', 'new_status', 'changed_by', 'remark'
    ];

    public function finding() { return $this->belongsTo(Finding::class); }
    public function user() { return $this->belongsTo(User::class, 'changed_by'); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
