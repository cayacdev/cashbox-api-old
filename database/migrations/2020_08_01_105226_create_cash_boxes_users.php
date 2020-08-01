<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashBoxesUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_boxes_users', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('cash_box_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cash_box_id')->references('id')->on('cash_boxes');
            $table->unique(['user_id', 'cash_box_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_boxes_users');
    }
}
