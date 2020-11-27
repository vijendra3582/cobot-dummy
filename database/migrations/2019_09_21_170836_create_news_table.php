<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('publication')->nullable();
            $table->date('date')->nullable();
            $table->string('news_type')->nullable();

            $table->string('news_file')->nullable();
            $table->text('news_url')->nullable();
            $table->string('vedio_file')->nullable();
            $table->string('vedio_image')->nullable();

            $table->integer('status')->default(0);
            $table->integer('position')->nullable();
            $table->integer('set_at_homepage')->default(0);
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
        Schema::dropIfExists('news');
    }
}
