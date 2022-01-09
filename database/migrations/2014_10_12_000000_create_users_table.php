<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();//unsignedBigInteger('user_id')->unique();
            $table->string('owner_name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->boolean('has_pro_account');
            $table->boolean('had_pro_account');
            $table->string('business_name');
            $table->string('number_of_employees');
            $table->string('business_description');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->rememberToken();
            $table->string('general_layman_location');
            $table->string('password');
            $table->string('image');
            $table->boolean('can_create_store');
            $table->boolean('is_blocked');
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
        Schema::dropIfExists('users');
    }
}
