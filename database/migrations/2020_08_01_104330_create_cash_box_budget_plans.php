<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashboxBudgetPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_box_budget_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('budget', 8, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashbox_budget_plans');
    }
}
