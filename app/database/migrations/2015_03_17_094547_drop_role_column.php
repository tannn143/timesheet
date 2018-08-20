<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRoleColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        Schema::table('users', function($table) {
            $table->dropColumn('role');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        Schema::table('users', function($table) {
            $table->tinyInteger('role')->after('department_id')->nullable()->default(0);
        });
	}

}
