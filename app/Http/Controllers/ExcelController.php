<?php namespace App\Http\Controllers;


use App\Attendance;
use App\Organizer;
use App\Conference;
use App\Task;
use App\WorkingFields;
use Faker\Provider\DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

class ExcelController extends Controller
{

    public function conference(){

        /* return conferance */

        $conference = Conference::find(2);
        $conference_id =  $conference->id;

        /*return task */
        $task = Task::where('conference_id','=',$conference_id)->get()->first();
        $task_id = $task->id;

        /* check if task confirmed or not */

        if($task->confirmed == 1){
            /*return organizer related to this conference */

            $organizers = $conference->organizers;

            /* return workingfield which organizer working in it */
            $workingfields = WorkingFields::find($task->working_fields_id);

            $organizer_array = array();
            foreach($organizers as $organizer){
                $organizer_id = $organizer->id;

                $organizer_grade_in_alltasks = Organizer::organizer_grade($organizer_id);
                $organizer_grade_in_thistask = Task::grading_average($task_id,$organizer_id);
                $array = array(
                    'name' => $organizer->name,
                    'email' => $organizer->email,
                    'phone' => $organizer->cell_phone,
                    'id_number' => $organizer->id_number,
                    'organizer_grade_in_alltasks' => $organizer_grade_in_alltasks,
                    'organizer_grade_in_thistask' => $organizer_grade_in_thistask,
                    'working_field' => $workingfields->name
                );
                array_push($organizer_array,$array);
            }
            /* data send to view */
            $data = array(
                'name' => $conference->name,
                'from' => $conference->from,
                'to' => $conference->to,
                'venue' => $conference->venue,
                'organizer' => $organizer_array
            );
            Excel::create($conference->name, function($excel) use($data) {

                $excel->sheet('conferance', function($sheet) use($data) {

                    $sheet->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  18,
                            'bold'      =>  false
                        )
                    ));
                    $sheet->cells('A1:K1', function($cells) {

                        $cells->setBackground('#4CAF50');
                        $cells->setFontColor('#ffffff');
                        $cells->setFontSize(22);
                    });

                    $sheet->setWidth(array(
                        'A'=>23,
                        'B' => 25,
                        'C' =>25,
                        'D' =>25,
                        'E' =>18,
                        'F' =>25,
                        'G'=>20,
                        'H'=>20,
                        'I' =>20,
                        'J'=>20,
                        'K'=>25,


                    ));



                    $sheet->loadView('sheet2')
                        ->with('data', $data);
                });
            })->export('xlsx');

        }else{
            return "the task not confirmed";
        }

    }

    public function organizer(){
        $email = "kayli.mcglynn@okuneva.com" ;

        /* return organizer */

        $organizer = Organizer::findByEmailOrFail($email,$columns = array('*'));
        $organizer_id = $organizer->id;

        /*grade of this organizer in all tasks */

        $organizer_grade_in_alltasks = Organizer::organizer_grade($organizer_id);

        /*return all conferences related to this organizer  */

        $conferances = $organizer->conferences;

        $conference_array = array();
        foreach($conferances as $conferance){

           $conference_id =  $conferance->id;

            $task = Task::where('conference_id','=',$conference_id)->get()->first();
            $working_days = $this->check_attendance($organizer_id,$task->id);
            $organizer_grade_in_thistask = Task::grading_average($task->id,$organizer_id);
            $array = array(
                'name' => $conferance->name,
                'from' => $conferance->from,
                'to' => $conferance->to,
                'venue' => $conferance->venue,
                'organizer_grade_in_this_conference'=>$organizer_grade_in_thistask,
                'working_days' => $working_days

            );

            array_push($conference_array,$array);
        }

        /* return working field that organizer working into them */

       $workingfields = $organizer->workingfields;
       $workingfield_list="";
       foreach($workingfields as $workingfield){
           $workingfield->name;
           $workingfield_list .= $workingfield->name.',';

        }

        /* data send to view */

        $data = array(
            'name'=>$organizer->name,
            'email'=>$organizer->email,
            'address'=>$organizer->address,
            'phone'=>$organizer->cell_phone,
            'date_of_birth'=>$organizer->dob,
            'id_number'=>$organizer->id_number,
            'organizer_grade_in_alltasks'=>$organizer_grade_in_alltasks,
            'workingfields'=> $workingfield_list,
            'conferences' => $conference_array
        );

        Excel::create($organizer->name, function($excel) use($data) {

            $excel->sheet('conferance', function($sheet) use($data) {

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  18,
                        'bold'      =>  false
                    )
                ));
                $sheet->cells('A1:N1', function($cells) {

                    $cells->setBackground('#4CAF50');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontSize(22);
                });

                $sheet->setWidth(array(
                    'A'=>23,
                    'B' => 25,
                    'C' =>35,
                    'D' =>18,
                    'E' =>15,
                    'F' =>15,
                    'G'=>25,
                    'H'=>25,
                    'I' =>25,
                    'K'=>25,
                    'L'=>25,
                    'J' =>20,
                    'M' =>20,
                    'N' =>20

                ));



                $sheet->loadView('organizer_sheet')
                    ->with('data', $data);
            });
        })->export('xlsx');

    }

    public function all_organizer(){

        $all_organizers = array();
        /* return organizers */

        $organizers = Organizer::all();
        foreach($organizers as $organizer){
            $organizer_id = $organizer->id;
            /*grade of this organizer in all tasks */
            $organizer_grade_in_alltasks = Organizer::organizer_grade($organizer_id);

            /* return working field that organizer working into them */
            $workingfields = $organizer->workingfields;
            $workingfield_list='';
            foreach($workingfields as $workingfield){
                $workingfield->name;
                $workingfield_list .= $workingfield->name.',';

            }

            /*return all conferences related to this organizer  */
            $conferances = $organizer->conferences;
            $conference_array = array();
            foreach($conferances as $conferance){

                $conference_id =  $conferance->id;

                $task = Task::where('conference_id','=',$conference_id)->get()->first();
                $working_days = $this->check_attendance($organizer_id,$task->id);
                $organizer_grade_in_thistask = Task::grading_average($task->id,$organizer_id);
                $array = array(
                    'name' => $conferance->name,
                    'from' => $conferance->from,
                    'to' => $conferance->to,
                    'venue' => $conferance->venue,
                    'organizer_grade_in_this_conference'=>$organizer_grade_in_thistask,
                    'working_days' => $working_days

                );

                array_push($conference_array,$array);
            }
            /* data send to view */
            $data = array(
                'name'=>$organizer->name,
                'email'=>$organizer->email,
                'address'=>$organizer->address,
                'phone'=>$organizer->cell_phone,
                'date_of_birth'=>$organizer->dob,
                'id_number'=>$organizer->id_number,
                'organizer_grade_in_alltasks'=> $organizer_grade_in_alltasks,
                'workingfields' =>$workingfield_list,
                'conferences'=>$conference_array
            );

            array_push($all_organizers,$data);
        }
        View::make('all_organizer',array('all_organizers'=>$all_organizers));

        Excel::create('organizers', function($excel) use($all_organizers) {

            $excel->sheet('conferance', function($sheet) use($all_organizers) {

                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  18,
                        'bold'      =>  false
                    )
                ));
                $sheet->cells('A1:O1', function($cells) {

                    $cells->setBackground('#4CAF50');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontSize(22);
                });

                $sheet->setWidth(array(
                    'A'=>5,
                    'B' => 23,
                    'C' =>25,
                    'D' =>35,
                    'E' =>18,
                    'F' =>15,
                    'G'=>15,
                    'H'=>25,
                    'I' =>25,
                    'K'=>25,
                    'L'=>25,
                    'J' =>25,
                    'M' =>20,
                    'N' =>20,
                    'O' =>20


                ));

                $sheet->loadView('all_organizer')
                    ->with('all_organizers', $all_organizers);
            });
        })->export('xlsx');

    }





    /*
     * check the attendance of organizer in this conferance
     */

    private function check_attendance($organizer_id,$task_id){
        $attendances = Attendance::where('organizer_id','=',$organizer_id)
            ->where('task_id','=',$task_id)->get();
        return $working_days = count($attendances);
    }


}