<?php

namespace Modules\SharedCore\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationalPeriod extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active', 'activated_by', 'activated_at'];

    protected function casts(): array
    {
        return [
            'start_date'   => 'date',
            'end_date'     => 'date',
            'is_active'    => 'boolean',
            'activated_at' => 'datetime',
        ];
    }

    public function activatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    public function activate(User $activatedBy): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);

        $this->update([
            'is_active'    => true,
            'activated_by' => $activatedBy->id,
            'activated_at' => now(),
        ]);
    }

    public static function active(): ?static
    {
        return static::where('is_active', true)->first();
    }
}
