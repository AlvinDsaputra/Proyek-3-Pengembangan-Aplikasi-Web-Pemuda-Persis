<?php

namespace Modules\SharedCore\Policies;

use App\Models\User;
use Modules\SharedCore\Models\SignatureImage;

class SignatureImagePolicy
{
    public function view(User $user, SignatureImage $signature): bool
    {
        return (int) $user->id === (int) $signature->user_id;
    }

    public function delete(User $user, SignatureImage $signature): bool
    {
        return (int) $user->id === (int) $signature->user_id;
    }
}
