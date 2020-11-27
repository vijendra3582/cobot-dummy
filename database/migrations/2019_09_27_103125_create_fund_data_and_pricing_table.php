<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundDataAndPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_data_and_pricing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('fund_id');
            $table->string('data_type')->nullable();
            $table->text('data_head')->nullable();
            $table->text('data_value')->nullable();
            $table->integer('display_status')->default(1)->nullable();
            $table->string('tags')->nullable();
            $table->string('tags_field')->nullable();
            $table->integer('tags_cond')->nullable()->default(0);
            $table->integer('do_not_update')->default(1);
            $table->integer('position')->default(0);
            $table->integer('status')->default(1); 
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
        Schema::dropIfExists('fund_data_and_pricing');
    }
}
