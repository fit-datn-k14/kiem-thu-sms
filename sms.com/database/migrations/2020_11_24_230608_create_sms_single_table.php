<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSingleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_single', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('contact_id');
            $table->string('content', 510);
            $table->integer('total_sms');
            $table->boolean('is_sent');
            $table->date('date_write');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_single');
    }
}
