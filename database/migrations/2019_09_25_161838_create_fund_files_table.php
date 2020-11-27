<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fund_id');
            $table->string('label_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('status')->default(1);
            $table->integer('position')->default(0);
            $table->string('url_key')->nullable();
            $table->string('add_ip')->nullable();
            $table->string('add_by')->nullable();
            $table->string('update_ip')->nullable();
            $table->string('update_by')->nullable();
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
        Schema::dropIfExists('fund_files');
    }
}
