<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use json;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    public function index()
    {
        // $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = '$someVariable'") );
        // return response()
        //     ->view('student.index', json([
        //         'name' => 'Abigail',
        //         'state' => 'CA',
        //     ]), 200);
        // return view('student.index')
        
        // ->json([
        //     'name' => 'Abigail',
        //     'state' => 'CA',
        // ]);
        // return Response::view('hello')->header('Content-Type', $type);
        // return view('student.index',compact(json([
        //     'name' => 'Abigail',
        //     'state' => 'CA',
        // ])));
        // $pizza = [[1,2],[2,3],[3,4]];
        // $myObj->name = "John";
        // $myObj->age = 30;
        // $myObj->city = "New York";
        // return view('student.index',['pizza' => $pizza]);

        $exams = array(
                        array(
                        'id'  =>1,
                        'name'  =>'exam 1',
                        'description'=>'Syllabus: chapter 1,2' 
                        ),
                        array(
                            'id'  =>2,
                            'name' => 'exam 2',
                            'description'=>'Syllabus: chapter 1,2' 
                            )
                    );
        // echo $exams;
        return view('student.index',['exams' => $exams]);  
  
        // $json_data = '[
        //                 { "q_id"=>1, "q_text" => "What is the capital of BD?", "Options"=>[ "Dhaka", "Comilla", "Khulna", "Borishal" ] },
        //                 { "q_id"=>2, "q_text" => "What is the capital of BD?", "Options"=>[ "Dhaka", "Comilla", "Khulna", "Borishal" ] },
        //                 { "q_id"=>3, "q_text" => "What is the capital of BD?", "Options"=>[ "Dhaka", "Comilla", "Khulna", "Borishal" ] },
        //                 { "q_id"=>4, "q_text" => "What is the capital of BD?", "Options"=>[ "Dhaka", "Comilla", "Khulna", "Borishal" ] }
        //                 ]';
        // $myObj = ' [
        //             { "name"=>"Ford", "models"=>[ "Fiesta", "Focus", "Mustang" ] },
        //             { "name"->"BMW", "models"->[ "320", "X3", "X5" ] },
        //             { "name":"Fiat", "models"->[ "500", "Panda" ] }
        //             ]';
        // echo $myObj;
        // echo $myObj = json_encode($myObj);
                    
        // return view('student.index',['myObj' => $myObj]);  
    }
    public function test_exam()
    {
        $sql =' select x.*,  GROUP_CONCAT(answers) AS answers
                from	(SELECT q_track_id,
                                GROUP_CONCAT(q_options) AS choices	 
                        FROM exam_papers_q_options
                        WHERE exam_track_id =1
                        GROUP BY q_track_id
                        order by q_option_no) as x natural join exam_questions_answers        
                group by x.q_track_id ;';
        
        $data = DB::select(DB::raw($sql));
        $i = 0;
        foreach($data as $value)
        {
            $data[$i]->choices = explode(",", $value->choices); //spliting all choices as array and store in that object
            $i++;           
        }                
        $data = json_encode($data);
        // var_dump($results);
        // echo "<br>".$results;
         $data  = json_decode($data,true);
         return view('student.test_exam',['data' => $data]);  
        // dd($results);
        // echo ($results[0]['q_track_id']);
        // print_r ($results[0]);
        // echo $results[0]['q_track_id'];
        // $resultArray = json_decode(json_encode($results), true);
        // dd($resultArray);
                  
       // $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = '$someVariable'") );
    }

}
