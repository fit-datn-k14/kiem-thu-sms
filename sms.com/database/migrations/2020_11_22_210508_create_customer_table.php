<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('customer_group_id');
            $table->string('email', 96)->unique();
            $table->string('password', 64);
            $table->string('full_name', 64);
            $table->string('image')->nullable();
            $table->string('sms_brand_name', 24)->nullable();
            $table->string('sms_prefix', 32)->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('status');
            $table->boolean('newsletter')->nullable();
            $table->decimal('money', 15, 0)->nullable();
            $table->integer('total_sms');
            $table->integer('sms_price', 8);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
