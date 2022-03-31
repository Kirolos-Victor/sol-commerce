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
            $table->id();
            
            $table->unsignedInteger('location_id')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
			
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();

            $table->string('shipping_address')->nullable();
			$table->string('shipping_apartment')->nullable();
			$table->string('shipping_city')->nullable();
			$table->string('shipping_state')->nullable();
			$table->string('shipping_postcode')->nullable();
			$table->string('shipping_country')->nullable();

			$table->string('billing_address')->nullable();
			$table->string('billing_apartment')->nullable();
			$table->string('billing_city')->nullable();
			$table->string('billing_state')->nullable();
			$table->string('billing_postcode')->nullable();
			$table->string('billing_country')->nullable();

			$table->text('notes')->nullable();

            $table->string('password')->nullable();

            $table->tinyInteger('local_pickup')->default(0);
            $table->tinyInteger('admin')->default(0);

            $table->rememberToken();
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
