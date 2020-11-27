<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('fund_id');
            $table->string('data_type')->nullable();
            $table->text('data_head')->nullable();
            $table->text('data_value')->nullable();
            $table->integer('display_status')->default(1);
            $table->string('tags')->nullable();
            $table->string('tags_field')->nullable();
            $table->string('tags_table')->nullable();
            $table->integer('tags_cond')->default(0);
            $table->integer('do_not_update')->default(1);
            $table->unsignedInteger('position')->default(0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('fund_data');
    }
}
