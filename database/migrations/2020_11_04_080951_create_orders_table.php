<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
			$table->id();
			
			$table->unsignedInteger('location_id')->nullable();
			$table->unsignedInteger('user_id')->nullable();
			$table->unsignedInteger('subscription_id')->nullable();

			$table->string('status')->nullable();

            $table->string('email')->nullable();  
			$table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();

            $table->date('delivery_date')->nullable();

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

            $table->string('shipping_method')->nullable();
			$table->integer('shipping_price')->nullable();

			$table->unsignedInteger('discount_code_id')->nullable();
			$table->integer('discount_amount')->nullable();

            $table->string('payment_option')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_intent')->nullable();

            $table->integer('refund_amount')->nullable();
            $table->text('refund_reason')->nullable();
            
            $table->text('tracking_number')->nullable();
            $table->text('notes')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
