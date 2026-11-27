<?php

namespace Modules\SuratKeluar\Policies;

use App\Models\User;

class LetterMasterPolicy
{
    private function isBidangAdministrasi(User $user): bool
    {
        return $user->roles()
            ->whereIn('slug', [
                'bidang-administrasi',
                'bidang_administrasi',
                'superadmin',
                'super_admin',
            ])
            ->exists();
    }

    public function viewAny(User $user): bool
    {
        return $this->isBidangAdministrasi($user);
    }

    public function view(User $user): bool
    {
        return $this->isBidangAdministrasi($user);
    }

    public function create(User $user): bool
    {
        return $this->isBidangAdministrasi($user);
    }

    public function update(User $user): bool
    {
        return $this->isBidangAdministrasi($user);
    }

    public function delete(User $user): bool
    {
        return $this->isBidangAdministrasi($user);
    }
}