<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("working_fields",function($table){
			$table->increments('id');

			$table->string('name');

			$table->string('teamleader');

			$table->string('teamleader_email');

			$table->string('teamleader_phone');


		});

		Schema::table('task',function($table){
			$table->foreign("working_fields_id")
					->references("id")->on("working_fields")
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


		Schema::table('task',function($table){
			$table->dropForeign('task_working_fields_id_foreign');
		});

		Schema::dropIfExists('working_fields');
	}

}
