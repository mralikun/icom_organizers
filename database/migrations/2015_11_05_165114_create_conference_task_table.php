<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferenceTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("conference_task",function($table){
			$table->increments("id");
			$table->integer("conference_id")->unsigned();
			$table->integer("task_id")->unsigned();

			$table->foreign("conference_id")
				  ->references("id")->on("conference")
				  ->onDelete("cascade");

			$table->foreign("task_id")
				  ->references("id")->on("task")
				  ->onDelete("cascade");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('conference_task');
	}

}
