<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("task", function($table){
			$table->increments("id");
			$table->string("title");
			$table->text("description");
			$table->timestamps();
			$table->date("from");
			$table->date("to");
			$table->string("type");
			$table->boolean("confirmed");
			$table->string("teamleader_email");
			$table->integer("organizer_id")->unsigned();
			$table->integer("conference_id")->unsigned()->nullable();
			$table->integer("working_fields_id")->unsigned()->nullable();

			$table->foreign("organizer_id")
				  ->references("id")->on("organizer")
				  ->onDelete("cascade");

			$table->foreign("conference_id")
				  ->references("id")->on("conference")
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
		Schema::dropIfExists('task');
	}

}
