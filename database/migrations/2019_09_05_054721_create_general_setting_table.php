<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('info_email')->nullable();
            $table->text('address')->nullable();
            $table->string('location_url')->nullable();
            $table->text('facebook_url')->nullable();
            $table->text('instagram_url')->nullable(); 
            $table->text('copyright')->nullable();
            $table->text('contact_us_header')->nullable();
            $table->text('contact_us_footer')->nullable();
            $table->string('contact_us_mail_to')->nullable();
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
        Schema::dropIfExists('general_setting');
    }
}
