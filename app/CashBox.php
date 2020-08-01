<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashBox extends Model
{
    protected $fillable = [
        'id', 'name', 'description'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
