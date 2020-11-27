<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fund_name')->nullable();
            $table->string('sub_title')->nullable();
            $table->date('fund_inception_date')->nullable();
            $table->string('fund_profile_id')->nullable();
            $table->string('fund_ticker')->nullable();
            $table->text('fund_short_description')->nullable();
            $table->longText('fund_description')->nullable();
            $table->longText('fund_detail_notes')->nullable();
            $table->longText('fund_data_pricing_notes')->nullable();
            $table->longText('holdings_notes')->nullable();
            $table->longText('performance_description')->nullable();
            $table->longText('fund_disclosure')->nullable();
            $table->string('banner_image')->nullable();
            $table->integer('is_premium_discount')->default(0);
            $table->string('premium_discount_file')->nullable();
            $table->integer('position')->nullable();
            $table->string('url_key')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();

            $table->integer('status')->default(0);

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
        Schema::dropIfExists('fund');
    }
}
