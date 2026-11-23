<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\SharedCore\Models\OrganizationalPeriod;
use Modules\SharedCore\Models\Role;
use Modules\SharedCore\Models\SignatureImage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    private const ROLE_PRIORITY = [
        'superadmin',
        'ketua',
        'sekretaris',
        'bidang_administrasi',
        'anggota',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'npa',
        'asal_pc_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot(['period_id', 'assigned_by', 'assigned_at']);
    }

    // Hanya peran permanen (period_id NULL) atau yang terikat periode aktif yang berlaku.
    public function activeRoles(): BelongsToMany
    {
        $activePeriodId = OrganizationalPeriod::active()?->id;

        return $this->roles()->where(function (Builder $query) use ($activePeriodId): void {
            $query->whereNull('user_roles.period_id');

            if ($activePeriodId !== null) {
                $query->orWhere('user_roles.period_id', $activePeriodId);
            }
        });
    }

    public function hasRole(string ...$slugs): bool
    {
        if ($slugs === []) {
            return false;
        }

        return $this->activeRoles()
            ->whereIn('roles.slug', $slugs)
            ->exists();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->activeRoles()
            ->whereHas('permissions', fn (Builder $query) => $query->where('slug', $permissionSlug))
            ->exists();
    }

    public function primaryRole(): ?Role
    {
        $roles = $this->activeRoles()->get();

        if ($roles->isEmpty()) {
            return null;
        }

        foreach (self::ROLE_PRIORITY as $slug) {
            $matched = $roles->firstWhere('slug', $slug);
            if ($matched !== null) {
                return $matched;
            }
        }

        return $roles->first();
    }

    public function dashboardRouteName(): string
    {
        return match ($this->primaryRole()?->slug) {
            'superadmin' => 'admin.dashboard',
            'ketua' => 'ketua.dashboard',
            'sekretaris' => 'sekretaris.dashboard',
            'bidang_administrasi' => 'bidang-administrasi.dashboard',
            'anggota' => 'anggota.dashboard',
            default => 'dashboard',
        };
    }

    public function signatureImages(): HasMany
    {
        return $this->hasMany(SignatureImage::class);
    }

    public function activeSignature(): HasOne
    {
        return $this->hasOne(SignatureImage::class)
            ->where('is_active', true)
            ->latest('id');
    }

    public function hasActiveSignature(): bool
    {
        return $this->activeSignature()->exists();
    }
}
