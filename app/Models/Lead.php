<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_name',
        'company',
        'email',
        'phone',
        'source',
        'user_id',
        'pipeline_stage_id',
        'needs_summary',
        'first_contact_date',
    ];

    protected function casts(): array
    {
        return [
            'first_contact_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pipelineStage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }

    public function getTotalOpportunityValueAttribute(): float
    {
        return $this->opportunities->sum('estimated_value');
    }

    public function hasOverdueActivities(): bool
    {
        return $this->activities()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '<', now())
            ->exists();
    }
}
