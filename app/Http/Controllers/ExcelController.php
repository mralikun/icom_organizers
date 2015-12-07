<?php namespace App\Http\Controllers;


use App\Organizer;
use App\Conference;
use App\WorkingFields;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

class ExcelController extends Controller
{

    public function organize_sheet()
    {
////        $datas = Conference::select('id','name','from','to','venue')->get()->toArray();
//        $conferences = Conference::all();
//
////        $conferenceArray=array();
////        foreach($conferences as $conference){
//////            return $conference->organizers()->first()->name;
////
////            array_push($conferenceArray,$conference);
////        }
////        return $conferences;
//
//        $conferenceArray = [];
//        $i = 0;
//
//        foreach($conferences as $conference){
//            $conferenceArray[$i][0] = $conference->id;
//            $conferenceArray[$i][1] = $conference->name;
//            $conferenceArray[$i][2] = $conference->from;
//            $conferenceArray[$i][3] = $conference->to;
//            $conferenceArray[$i][4] = $conference->venue;
//            $conferenceArray[$i][5] = ;
//            $i++;
//        }
//
//
//         $conferences = $conferenceArray;
//
//      Excel::create('Sheets', function($excel) use($conferences) {
//
//            $excel->setTitle('Our new awesome title');
//
//           $excel->setDescription('A demonstration to change the file properties');
//
//            $excel->sheet('organizer', function($sheet) use($conferences) {
//                $sheet->prependRow(array(
//                    'id', 'name','from','to','venue','organizer'
//                ));
////                $sheet->fromArray($conferences, null, 'A2', false, false);
//                $sheet->rows($conferences);
//                $sheet->fromArray([
//                    'dsd','dsds','dsd'
//                ], null, 'F2', false, false);
//
//                //$sheet->with($arr);
////                foreach($conferenceArray as $conference){
////                    $i=2;
//                   $conferance = array($conference['id'],$value['name'],$value['from'],
//////                        $value['to'],$value['venue']);
////
////                    $organizers=array();
////                    if(count($conference['organizers'])) {
////                        foreach ($conference['organizers'] as $organizer) {
////
////                            array_push($organizers, $organizer->name);
////                        }
////                    }
//////                        $sheet->row($i, $conferance);
////                    $sheet->fromArray($organizers, null, 'A2', false, false);
////
//////                    $sheet->rows($organizers);
////                          $i++;
////                }
//            });
//
//        })->export('xls');
   }
    public function sheet(){

      $conferences = Conference::all();
        $conferance_array=array();
       foreach($conferences as $conference){
           $id = $conference->id;
           $name = $conference->name;
           $from = $conference->from;
           $to = $conference->to;
           $venue = $conference->venue;
           $organizers = Conference::find($id)->organizers;


           $array = array(
               'id'=>$id,
               'name'=>$name,
               'from'=>$from,
               'to'=>$to,
               'venue'=>$venue,
               'organizers'=>$organizers

           );

           array_push($conferance_array,$array);
       }
        //return View::make('sheet',array('conferance_array' => $conferance_array) );

        Excel::create('Sheets', function($excel) use($conferance_array) {

            $excel->sheet('organizer', function($sheet) use($conferance_array) {

               // $sheet->loadView('sheet', array('conferance_array' => $conferance_array));
                $sheet->loadView('sheet')
                    ->with('conferance_array', $conferance_array);

            });

        })->export('xlsx');

    }


}