<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class CashBoxBudgetPlan
 *
 * @package App
 * @property int $id
 * @property int $cash_box_id
 * @property string $name
 * @property float $budget
 * @property string $start_date
 * @property string $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CashBox $cashBox
 * @method static Builder|CashBoxBudgetPlan newModelQuery()
 * @method static Builder|CashBoxBudgetPlan newQuery()
 * @method static Builder|CashBoxBudgetPlan query()
 * @method static Builder|CashBoxBudgetPlan whereBudget($value)
 * @method static Builder|CashBoxBudgetPlan whereCashBoxId($value)
 * @method static Builder|CashBoxBudgetPlan whereCreatedAt($value)
 * @method static Builder|CashBoxBudgetPlan whereEndDate($value)
 * @method static Builder|CashBoxBudgetPlan whereId($value)
 * @method static Builder|CashBoxBudgetPlan whereName($value)
 * @method static Builder|CashBoxBudgetPlan whereStartDate($value)
 * @method static Builder|CashBoxBudgetPlan whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $active
 * @method static Builder|CashBoxBudgetPlan whereActive($value)
 */
class CashBoxBudgetPlan extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'name', 'budget', 'start_date', 'end_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function cashBox()
    {
        return $this->belongsTo('App\CashBox');
    }

    /**
     * @return Builder
     */
    public function users()
    {
        return $this->manyThroughMany('App\User', 'cash_box_user', 'user_id', 'cash_box_id');
    }

    /**
     * @return HasMany
     */
    public function entries()
    {
        return $this->hasMany('App\CashBoxBudgetPlanEntry');
    }

    /**
     * @param null $expectId
     * @return HasMany
     */
    public function getConflictedPlans($expectId = null): HasMany
    {
        /**
         * @var $plans HasMany
         */
        $plans = $this->cashBox()->first()->budgetPlans();

        $conflictedPlans = $plans->where(function ($query) {
            /**
             * @var $query Builder
             */
            $query
                ->whereBetween('start_date', [$this->start_date, $this->end_date])
                ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                ->orWhere(function ($query) {
                    /**
                     * @var $query Builder
                     */
                    $query
                        ->where('start_date', '<', $this->start_date)
                        ->where('end_date', '>', $this->end_date);
                });
        });

        if ($expectId) {
            $conflictedPlans->where('id', '<>', $expectId);
        }

        return $conflictedPlans;
    }

    /**
     * @param $related
     * @param $pivot
     * @param $firstKey
     * @param $secondKey
     * @return Builder
     */
    public function manyThroughMany($related, $pivot, $firstKey, $secondKey)
    {
        $model = new $related;
        $table = $model->getTable();

        return $model
            ->join($pivot, $pivot . '.' . $firstKey, '=', $table . '.' . 'id')
            ->select($table . '.*')
            ->where($pivot . '.' . $secondKey, '=', $this->$secondKey);
    }
}
