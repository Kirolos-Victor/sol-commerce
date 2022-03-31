<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
			$table->id();

			$table->unsignedInteger('location_id')->nullable();
			$table->unsignedInteger('order_id')->nullable();
			$table->unsignedInteger('user_id')->nullable();
			$table->string('session_id')->nullable();
            
            $table->tinyInteger('non_perishable')->nullable();  
            $table->tinyInteger('step')->nullable();  
            $table->string('user_status')->nullable();  

            $table->string('email')->nullable();  
			$table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();

			$table->string('deliver_with_renewal_order')->nullable();
            $table->date('delivery_date')->nullable();

			$table->string('shipping_address')->nullable();
			$table->string('shipping_apartment')->nullable();
			$table->string('shipping_city')->nullable();
			$table->string('shipping_state')->nullable();
			$table->string('shipping_postcode')->nullable();
			$table->string('shipping_country')->nullable();

            $table->tinyInteger('billing_address_same')->default(1);
            $table->string('billing_address')->nullable();
			$table->string('billing_apartment')->nullable();
			$table->string('billing_city')->nullable();
			$table->string('billing_state')->nullable();
			$table->string('billing_postcode')->nullable();
			$table->string('billing_country')->nullable();

            $table->string('shipping_method')->nullable();
			$table->integer('shipping_price')->nullable();

			$table->unsignedInteger('discount_code_id')->nullable();
            $table->string('discount_code')->nullable();
			$table->integer('discount_amount')->nullable();

            $table->string('payment_option')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_intent')->nullable();

            $table->boolean('paid')->default(0)->nullable();  
            $table->text('notes')->nullable();

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
        Schema::dropIfExists('cart');
    }
}
