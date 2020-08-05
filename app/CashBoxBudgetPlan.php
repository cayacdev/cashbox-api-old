<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CashBoxBudgetPlan extends Model
{

    protected $fillable = [
        'id', 'name', 'budget', 'start_date', 'end_date'
    ];

    public function cashBox()
    {
        return $this->belongsTo('App\CashBox');
    }

    public function users()
    {
        return $this->manyThroughMany('App\User', 'cash_box_user', 'user_id', 'cash_box_id');
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
