<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("attendance",function($table){
		    $table->increments('id');
		    $table->timestamp("check_in");
		    $table->timestamp("check_out")->nullable();
		    $table->integer("organizer_id")->unsigned();
		    $table->integer("task_id")->unsigned();

			$table->foreign('organizer_id')
			      ->references('id')->on('organizer')
			      ->onDelete('cascade');

			$table->foreign('task_id')
					->references('id')->on('task')
					->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('attendance');
	}

}
