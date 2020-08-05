<?php

namespace App\Policies;

use App\CashBoxBudgetPlan;
use App\User;

class CashBoxBudgetPlanPolicy
{
    public function show(User $user, CashBoxBudgetPlan $plan)
    {
        return $plan->users()->where('id', $user->id)->exists();
    }

    public function update(User $user, CashBoxBudgetPlan $plan)
    {
        return $plan->users()->where('id', $user->id)->exists();
    }

    public function destroy(User $user, CashBoxBudgetPlan $plan)
    {
        return $plan->users()->where('id', $user->id)->exists();
    }
}
