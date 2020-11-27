<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner_heading')->nullable();
            $table->longText('banner_text')->nullable();
            $table->string('banner_img')->nullable();
            $table->string('banner_logo')->nullable();
            $table->string('catalog_file')->nullable();
            $table->string('video_type')->nullable();
            $table->text('youtube_video')->nullable();
            $table->string('home_video')->nullable();
            $table->string('home_video_poster')->nullable();
            $table->string('slug')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
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
        Schema::dropIfExists('home_content');
    }
}
