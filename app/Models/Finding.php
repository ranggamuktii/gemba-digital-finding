<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    protected $fillable = [
        'finding_no', 'category_id', 'area_id', 'location', 'description', 
        'risk_level_id', 'photo', 'created_by', 'assigned_to', 'due_date', 'status'
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function area() { return $this->belongsTo(Area::class); }
    public function riskLevel() { return $this->belongsTo(RiskLevel::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function actions() { return $this->hasMany(FindingAction::class); }
    public function histories() { return $this->hasMany(FindingHistory::class); }
}
