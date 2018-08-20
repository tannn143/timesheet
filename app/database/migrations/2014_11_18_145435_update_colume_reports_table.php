<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumeReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
        Schema::table('reports', function($table) {
            $table->text('next_task')->after('content')->nullable();
            $table->text('notice')->after('next_task')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        Schema::table('reports', function($table) {
            $table->dropColumn('next_task');
            $table->dropColumn('notice');
        });
	}

}
