<?php

namespace Modules\Kegiatan\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Kegiatan\Models\Department;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'department_id',
        'created_by',
        'name',
        'slug',
        'speaker_name',
        'location',
        'contact_person',
        'start_datetime',
        'end_datetime',
        'quota',
        'status',
        'cancellation_reason',
        'description',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
        'quota'          => 'integer',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft'     => 'Draf',
            'published' => 'Dibuka',
            'closed'    => 'Ditutup',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->status),
        };
    }
}
