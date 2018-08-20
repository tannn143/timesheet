<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
            $table->string('username', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('password', 60)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->integer('department_id')->nullable();
            $table->tinyInteger('role')->default(0)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('users');
	}

}
