<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'type',
        'scheduled_at',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at < now();
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<', now());
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }
}
