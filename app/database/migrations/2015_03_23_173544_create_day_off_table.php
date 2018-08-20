<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayOffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        Schema::create('reg_date_off', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->dateTime('date_off')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        Schema::drop('reg_date_off');
	}

}
