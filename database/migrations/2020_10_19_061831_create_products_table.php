<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
			$table->id();
			
            $table->string('title');
            $table->string('url');
            $table->text('subtitle')->nullable();
			$table->text('description')->nullable();
			$table->text('benefits')->nullable();
			$table->string('product_type')->nullable();
			$table->string('option_name')->nullable();
            
            $table->boolean('visible')->default(1);
            $table->boolean('perishable')->nullable();
            $table->boolean('enquire_only')->nullable();

            $table->boolean('custom_options')->nullable();
            $table->integer('custom_options_choices')->nullable();
			
            $table->date('launch_date')->nullable();
            $table->date('end_date')->nullable();

			$table->string('product_recommendations_heading')->nullable();
            $table->text('content')->nullable();
            $table->text('faq')->nullable();
            
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
        Schema::dropIfExists('products');
    }
}
