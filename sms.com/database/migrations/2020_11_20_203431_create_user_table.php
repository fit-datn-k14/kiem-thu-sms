<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_group_id');
            $table->string('username', 64)->unique();
            $table->string('email', 96)->unique();
            $table->string('password', 64);
            $table->string('full_name', 64);
            $table->string('image')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->boolean('status');
            $table->boolean('is_sadmin')->default(false);
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
        Schema::dropIfExists('user');
    }
}
