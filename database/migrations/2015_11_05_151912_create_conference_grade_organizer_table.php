<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferenceGradeOrganizerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("conference_grade_organizer", function($table){

			$table->increments("id");
			$table->integer("grade");
			$table->integer("criteria")->unsigned();
			$table->integer("conference_id")->unsigned();
			$table->integer("organizer_id")->unsigned();
			      
			$table->foreign('organizer_id')
			      ->references('id')->on('organizer')
			      ->onDelete('cascade');

			$table->foreign('conference_id')
			      ->references('id')->on('conference')
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
		Schema::dropIfExists('conference_grade_organizer');
	}

}
