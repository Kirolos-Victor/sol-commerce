<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
			$table->id();

			$table->unsignedInteger('parent_id')->nullable();

			$table->string('title');
			$table->string('url');
			$table->text('short_description');
			$table->text('description');
			$table->text('image');
			$table->text('faq');
			$table->text('footer_quote');

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
        Schema::dropIfExists('categories');
    }
}
