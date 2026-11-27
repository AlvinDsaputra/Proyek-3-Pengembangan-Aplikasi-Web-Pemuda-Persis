<?php

namespace Modules\SharedCore\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SignatureImage extends Model
{
    protected $table = 'signature_images';

    protected $fillable = [
        'user_id',
        'file_path',
        'is_active',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'uploaded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function existsInStorage(): bool
    {
        if (empty($this->file_path)) {
            return false;
        }

        return Storage::disk('local')->exists($this->file_path);
    }

    public function absolutePath(): string
    {
        return Storage::disk('local')->path($this->file_path);
    }
}
