<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
			$table->id();
			
			$table->unsignedInteger('product_id')->nullable();

			$table->string('option_value')->nullable();

			$table->integer('price')->nullable();
			$table->string('sku')->nullable();
			$table->integer('available')->nullable();

			$table->float('weight')->nullable();
			$table->float('length')->nullable();
			$table->float('width')->nullable();
			$table->float('height')->nullable();

			$table->text('description')->nullable();
			$table->boolean('visible');
            
			$table->integer('sort_order');
			
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
        Schema::dropIfExists('variants');
    }
}
