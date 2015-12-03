<?php namespace App\Http\Controllers;


use App\Organizer;
use App\Conference;
use App\WorkingFields;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{

    public function organize_sheet()
    {
//        $datas = Conference::select('id','name','from','to','venue')->get()->toArray();
        $conferences = Conference::all();

        $arr=array();
        foreach($conferences as $conference){
            $conference->organizers->toarray();

            array_push($arr,$conference);
        }



      Excel::create('Sheets', function($excel) use($arr) {

            $excel->setTitle('Our new awesome title');

           $excel->setDescription('A demonstration to change the file properties');

            $excel->sheet('organizer', function($sheet) use($arr) {

                //$sheet->with($arr);
                foreach($arr as $value){
                    $i=1;
                    $array=array($value['id'],$value['name']);
                    $sheet->row(1, $value['id']);
                    $sheet->row(1, );
                    $sheet->row($i,$value['from']);
                    $sheet->row($i,$value['to']);
                    $sheet->row($i, $value['venue']);
                    $i++;
                }


            });

        })->export('xls');
   }


}