<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("grading",function($table){

			$table->increments("id");
			$table->integer("task_id")->unsigned();
			$table->integer("organizer_id")->unsigned();
			$table->integer("grade");
			$table->string("criteria");


			$table->foreign("task_id")
					->references("id")->on("task")
					->onDelete("cascade");

			$table->foreign('organizer_id')
					->references('id')->on("organizer")
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
		Schema::dropIfExists('grading');
	}

}
