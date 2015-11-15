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
			$table->date("date");
			$table->string("type");
			$table->boolean("confirmed");
			$table->integer("organizer_id")->unsigned();
			$table->integer("conference_id")->unsigned()->nullable();

			$table->foreign("organizer_id")
				  ->references("id")->on("organizer")
				  ->onDelete("cascade");

			$table->foreign("conference_id")
				  ->references("id")->on("Conference")
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
