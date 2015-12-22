<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer("seen");
			$table->string("message");
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
		Schema::drop('notifications');
	}

}
