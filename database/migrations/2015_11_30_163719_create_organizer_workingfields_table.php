p<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizerWorkingfieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("organizer_workingfields",function($table){
			$table->increments("id");
			$table->integer("organizer_id")->unsigned();
			$table->integer("workingfields_id")->unsigned();

			$table->foreign("organizer_id")
					->references("id")->on("organizer")
					->onDelete("cascade");

			$table->foreign('workingfields_id')
					->references('id')->on("working_fields")
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
		Schema::dropIfExists('organizer_workingfields');
	}

}
