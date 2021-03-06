<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAssignDay extends Migration {

	/**
    	 * Run the migrations.
    	 *
    	 * @return void
    	 */
    	public function up()
    	{
            Schema::table('users', function($table) {
                $table->string('assign_days')->after('role')->nullable();
            });
    	}

    	/**
    	 * Reverse the migrations.
    	 *
    	 * @return void
    	 */
    	public function down()
    	{
            Schema::table('users', function($table) {
                $table->dropColumn('assign_days');
            });
    	}

}
