<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sms_id');
            $table->integer('customer_id');
            $table->integer('contact_id');
            $table->integer('total_sms');
            $table->string('api_sms_id');
            $table->boolean('is_success');
            $table->string('phone', 24);
            $table->string('msg');
            $table->string('content', 510);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_log');
    }
}
