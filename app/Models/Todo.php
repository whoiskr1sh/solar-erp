<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_by',
        'due_date',
        'completion_date',
        'remarks',
        'not_completed_reason',
        'is_daily_task',
        'task_date',
        'is_carried_over',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completion_date' => 'date',
        'task_date' => 'date',
        'is_daily_task' => 'boolean',
        'is_carried_over' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeNotCompleted($query)
    {
        return $query->where('status', 'not_completed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForToday($query)
    {
        return $query->where('task_date', now()->toDateString());
    }

    public function scopeCarriedOver($query)
    {
        return $query->where('is_carried_over', true);
    }

    public function scopeAssignedTasks($query)
    {
        return $query->whereNotNull('assigned_by');
    }

    public function scopeDailyTasks($query)
    {
        return $query->where('is_daily_task', true);
    }
}
