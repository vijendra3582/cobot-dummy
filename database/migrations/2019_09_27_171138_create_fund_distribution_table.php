<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_distribution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('fund_id');
            $table->string('ex_date')->nullable();
            $table->string('record_date')->nullable();
            $table->string('payable_date')->nullable();
            $table->string('amount')->nullable();
            $table->string('income')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->integer('position')->default(0)->nullable();
            $table->string('add_ip')->nullable();
            $table->string('update_ip')->nullable(); 
            $table->string('url_key')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
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
        Schema::dropIfExists('fund_distribution');
    }
}
