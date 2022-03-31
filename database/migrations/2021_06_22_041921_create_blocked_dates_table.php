<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockedDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();

			$table->unsignedInteger('location_id')->nullable();
			$table->unsignedInteger('category_id')->nullable();
			$table->unsignedInteger('product_id')->nullable();

			$table->text('dates')->nullable();
			$table->date('alternate_date')->nullable();
			$table->boolean('sold_out')->nullable();

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
        Schema::dropIfExists('blocked_dates');
    }
}
