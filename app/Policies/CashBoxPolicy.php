<?php

namespace App\Policies;

use App\CashBox;
use App\User;

class CashBoxPolicy
{

    public function show(User $user, CashBox $cashBox)
    {
        return $cashBox->users()->where('id', $user->id)->exists();
    }

    public function update(User $user, CashBox $cashBox)
    {
        return $cashBox->users()->where('id', $user->id)->exists();
    }

    public function destroy(User $user, CashBox $cashBox)
    {
        return $cashBox->users()->where('id', $user->id)->exists();
    }
}
