<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\CashBox;
use Faker\Generator as Faker;

$factory->define(CashBox::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => ''
    ];
});
