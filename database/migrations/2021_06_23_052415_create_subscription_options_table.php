<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_options', function (Blueprint $table) {
			$table->id();

		    $table->unsignedInteger('variant_id')->nullable();

			$table->integer('frequency')->nullable();
			$table->integer('price')->nullable();

			$table->integer('buffer_days')->nullable();
			$table->integer('pause_count')->nullable();
			$table->integer('pause_days')->nullable();

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
        Schema::dropIfExists('subscription_options');
    }
}
