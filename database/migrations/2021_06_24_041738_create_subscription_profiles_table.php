<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_profiles', function (Blueprint $table) {
            $table->id();

			$table->unsignedInteger('location_id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('variant_id');
			$table->unsignedInteger('subscription_option_id');

			$table->integer('frequency')->nullable();
			$table->integer('price')->nullable();
			$table->integer('qty')->nullable();
			$table->date('next_order')->nullable();

			$table->integer('buffer_days')->nullable();
			$table->integer('pause_count')->nullable();
			$table->integer('pause_days')->nullable();
			$table->integer('paused_count')->nullable();
            
			$table->timestamp('cancelled_at')->nullable();
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
        Schema::dropIfExists('subscription_profiles');
    }
}
