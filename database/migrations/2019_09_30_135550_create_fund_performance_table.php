<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_performance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fund_name')->nullable();
            $table->string('fund_ticker')->nullable();
            $table->string('one_month')->nullable();
            $table->string('three_month')->nullable();
            $table->string('six_month')->nullable();
            $table->string('ytd')->nullable();
            $table->string('since_inception_cumulative')->nullable();
            $table->string('one_year')->nullable();
            $table->string('three_year')->nullable();
            $table->string('five_year')->nullable();
            $table->string('since_inception_annualized')->nullable();
            $table->date('date')->nullable();
            $table->time('insert_time')->nullable();
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
        Schema::dropIfExists('fund_performance');
    }
}
