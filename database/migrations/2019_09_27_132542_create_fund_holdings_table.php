<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundHoldingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_holdings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('fund_id');
            $table->string('name')->nullable();
            $table->string('identifier')->nullable();
            $table->double('percentage_of_net_assets',20,2)->nullable()->default(0.00);
            $table->double('shares_held', 20, 2)->nullable()->default(0.00);
            $table->double('market_value', 20, 2)->nullable()->default(0.00);
            $table->integer('status')->default(1);
            $table->unsignedInteger('position')->default(0);
            $table->string('url_key')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('add_ip')->nullable();
            $table->string('update_ip')->nullable();           

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
        Schema::dropIfExists('fund_holdings');
    }
}
