<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
			$table->id();
			
			$table->unsignedInteger('order_id')->nullable();
			$table->unsignedInteger('variant_id')->nullable();
			$table->unsignedInteger('subscription_option_id')->nullable();

			$table->integer('price')->nullable();
			$table->integer('qty')->nullable();
			$table->integer('tax')->nullable();
			$table->integer('subtotal')->nullable();
			$table->integer('total')->nullable();

			$table->json('custom_options')->nullable();
            
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
        Schema::dropIfExists('order_items');
    }
}
