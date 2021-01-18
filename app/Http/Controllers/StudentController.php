<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use json;
use Illuminate\Support\Facades\DB;
// use Auth;
use Carbon\Carbon;
use Validator;

class StudentController extends Controller
{
    public function index()
    {
        $query = "SELECT exam_id, exam_name, exam_descriptions
                FROM exams ;";
        $data = DB::select( DB::raw($query));
        $exams  = json_decode(json_encode($data),true);   //store data in array

        // $exams = array(
        //                 array(
        //                 'id'  =>1,
        //                 'name'  =>'exam 1',
        //                 'description'=>'Syllabus: chapter 1,2' 
        //                 ),
        //                 array(
        //                     'id'  =>2,
        //                     'name' => 'exam 2',
        //                     'description'=>'Syllabus: chapter 1,2' 
        //                     )
        //             );
        // echo $exams;
        return view('student.index',['exams' => $exams]);  
    }

    public function exam_info(Request $request)
    {
        $exam_id = (int)$request->exam_id;
        $sqlQuery = "SELECT exam_id, exam_name,exam_descriptions,
                    DATE(session_start) as session_start_date,TIME(session_start) as session_start_time,
                    DATE(session_end) as session_end_date,TIME(session_end) as session_end_time,
                    time_limit,attempt_limit,grading_method 
                    FROM exams
                    WHERE exam_id = $exam_id";
        $exam_info = DB::select(DB::raw($sqlQuery));
        $exam_info = json_decode(json_encode($exam_info),true);
        $exam_info = $exam_info[0];

        $exam_info['session_start_date'] = Carbon::parse( $exam_info["session_start_date"])->format('d F y');
        $exam_info['session_end_date'] = Carbon::parse( $exam_info["session_end_date"])->format('d F y');
        
        // 24-hour time to 12-hour time with am,pm
        $exam_info['session_start_time'] = Carbon::parse( $exam_info['session_start_time'])->format('h:i a') ;
        $exam_info['session_end_time'] = Carbon::parse( $exam_info['session_end_time'])->format('h:i a');
 
        // dd($data);                        
        return view('student.exam_info', ['exam_info' => $exam_info]);        
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
        $data  = json_decode($data,true);
         return view('student.test_exam',['data' => $data]);  
        // $user_id = auth()->user()->id;
        // dd($data);      
        $exam_track_id = 2;  
        // return redirect( url("/student/exam/$exam_track_id")  );     
       // $results = DB::select( DB::raw("SELECT * FROM some_table WHERE some_col = '$someVariable'") );
    }
    public function test_exam2(Request $request)
    {
        $exam_id = (int)$request->exam_id;
        dd($exam_id);
        

    }
}
