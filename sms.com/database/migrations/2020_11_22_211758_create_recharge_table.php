<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at');
            $table->integer('customer_id');
            $table->string('email', 96);
            $table->string('full_name', 64);
            $table->decimal('amount_paid', 15, 0);
            $table->decimal('amount_received', 15, 0);
            $table->decimal('amount_total', 15, 0);
            $table->string('payment_method');
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharge');
    }
}
