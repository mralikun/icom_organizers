<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Organizer;
use App\Task;
use App\WorkingFields;


class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UserTableSeeder');

        $this->command->info('User table seeded!');

		$this->call('OrganizerTableSeeder');

        $this->command->info('organizer table seeded!');
		
		$this->call('TaskTableSeeder');

        $this->command->info('task table seeded!');

		$this->call('WorkingFieldsTableSeeder');

		$this->command->info('WorkingFields table seeded!');
	}

}


class UserTableSeeder extends Seeder {
  
	private $departments = [
			"DMU",
			"Information Technology",
			"Media Production",
			"CMD",
			"Design and Printing",
			"Marketing",
			"Admin",
			"Finance",
			"Medical",
			"CME",
	];

    public function run()
    {
    	foreach ($this->departments as $department ) {
            $password = Hash::make("123456");
			User::create(["name" => $department , "password" => $password , "role" => "department"]);
    	}

        $password = Hash::make("123456");
        User::create(["name" =>  "operations", "password" => $password , "role" => "operations"]);
        
        $password = Hash::make("123456");
        User::create(["name" => "System Admin" , "password" => $password , "role" => "admin"]);
    }

}


class OrganizerTableSeeder extends Seeder {
	

    public function run()
    {
    		$faker = Faker\Factory::create();

            for($i = 0; $i < 10; $i++){
                Organizer::create(array(
                    'name' => $faker->name,
                    'dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'email' => $faker->email,
                    'activity' => $faker->boolean($chanceOfGettingTrue = 75),
                    'language' => $faker->languageCode,
                    'college' => $faker->word,
                    'id_number' => $faker->unique($reset = true)->randomDigitNotNull,
                    'cell_phone' => $faker->phoneNumber,

                ));
            }
    

    }

}


class TaskTableSeeder extends Seeder {
	

    public function run()
    {
    		$faker = Faker\Factory::create();

            for($i = 0; $i < 10; $i++){
                Task::create(array(
                    'title' => $faker->word,
                    'description' => $faker->text,
                    'from' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'to' => $faker->date($format = 'Y-m-d'),
                    'type' => $faker->randomElement(array("one time", "office hours")),
                    'confirmed' => $faker->boolean($chanceOfGettingTrue = 75),
                    'organizer_id' => $faker->numberBetween($min = 1, $max = 10),

                ));
            }
    

    }

}

class WorkingFieldsTableSeeder extends Seeder
{

	private $workingfields  = [
			"Accommodation",
			"Audiovisual",
			"Barcode",
			"Food and Beverage",
			"Hall",
			"Information Desk",
			"Ladies Program",
			"Meet and Assist",
			"Registration",
			"Transportation",
			"Studio"
	];

	public function run()
	{

		foreach ($this->workingfields as $workingfield) {

			WorkingFields::create(["name" => $workingfield, "teamleader" => "hend",
					"teamleader_email" => "hen@yahoo.com","teamleader_phone" => "1234"]);
		}
	}
}

            

