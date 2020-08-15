<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * Class CashBox
 * @package App
 */
class CashBox extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'name', 'description'
    ];

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * @return HasMany
     */
    public function budgetPlans()
    {
        return $this->hasMany('App\CashBoxBudgetPlan');
    }


    /**
     * @return HasMany
     */
    public function getActivePlan()
    {
        $plans = $this->budgetPlans();
        return $plans->where(function ($query) {
            $now = DB::raw('NOW()');
            $query
                ->where('start_date', '<', $now)
                ->where('end_date', '>', $now);

        });
    }
}
