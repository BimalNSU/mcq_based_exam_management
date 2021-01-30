<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

class TeacherController extends Controller
{
    public function index()
    {
        // return view('teacher.index');
      
        $sqlQuery = "SELECT exam_id,exam_name, exam_descriptions 
                    FROM exams ";
        $result = DB::select(DB::raw($sqlQuery));
        // $result = DB::select($sqlQuery);
        $data = json_encode($result);
        $data  = json_decode($data,true);   //store data in array

        return view('teacher.index', ['data' => $data]);
        
    }

    public function create_exam_page()
    {
        return view('teacher.create_exam');
    }

    public function create_exam_to_course(Request $request)
    {
        $rules = array(
            'exam_name' => 'required|string|max:20',
            'exam_descriptions' => 'required|string|max:100',
            'attempt_limit' => 'required|int',
            'session_start_date' => 'required',
            'session_start_time' => 'required',
            'session_end_date' => 'required',
            'session_end_time' => 'required',
            'time_limit' => 'required|int',
            'grading_method' => 'required|string|max:20'
        );

        // getting json data
        $data = $request->all();

        $error = Validator::make($request->all(), $rules);
        $data['session_start_date'] = Carbon::parse( $data["session_start_date"])->format('Y-m-d');
        $data['session_end_date'] = Carbon::parse( $data["session_end_date"])->format('Y-m-d');
        // 12-hour time to 24-hour time conversion
        $data['session_start_time'] = Carbon::parse( $data['session_start_time'])->format('H:i') ;
         // 12-hour time to 24-hour time conversion
         $data['session_end_time'] = Carbon::parse( $data['session_end_time'])->format('H:i') ;
        
        // dd( Carbon::createFromFormat('Y-m-d', $data['session_start_date'])->toDateTimeString() );
        // dd($request->all());
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //extracting json data
        //dd($data);
        $exam_name = $data['exam_name'];
        $descriptions = $data['exam_descriptions'];
        $attempts_limit = (int)$data['attempt_limit'];
        $session_start = $data['session_start_date'] .' '.$data['session_start_time'];
        // $session_start = new DateTime($session_start);
        $session_end = $data['session_end_date'].' '.$data['session_end_time'];
        // $session_end = new DateTime($session_end);
        $time_limit = (int)$data['time_limit'];
        $grading_method = $data['grading_method'];
        $created_by = auth()->user()->id;
        $created_on = Carbon::now('Asia/Dhaka');
       
        $sqlQuery = "INSERT INTO exams (exam_name,exam_descriptions,session_start,session_end,time_limit,attempt_limit,grading_method,created_by,created_on)
                    VALUES('$exam_name','$descriptions','$session_start', '$session_end',$time_limit, $attempts_limit, '$grading_method', $created_by, '$created_on');";
        
        DB::beginTransaction();
        try{
            DB::insert($sqlQuery);
            DB::commit();
            return response()->json(['success' => 'Data Added successfully.']);
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062)
            {
                return "duplicate data insertion error";
            }
            return $e;
        }       

    }

    public function get_course_exams(Request $request)
    {
        if(!$request->ajax())
        {
            $sqlQuery = "SELECT exam_id,exam_name, exam_descriptions 
                        FROM exams ";
            $result = DB::select(DB::raw($sqlQuery));
            // $result = DB::select($sqlQuery);
            $data = json_encode($result);
            $data  = json_decode($data,true);   //store data in array           

           return view('teacher.index', ['data' => $data]);
        }
    }

    public function get_exam_data(Request $request)
    {
        $exam_id = (int)$request->exam_id;
        // $exam_id = (int)$request->input('exam_id');
        $sqlQuery = "SELECT exam_id,exam_name,exam_descriptions,
                    DATE(session_start) as session_start_date,TIME(session_start) as session_start_time,
                    DATE(session_end) as session_end_date,TIME(session_end) as session_end_time,
                    time_limit,attempt_limit,grading_method
                    FROM exams
                    WHERE exam_id= $exam_id ;";
        //$result = DB::select(DB::raw($sqlQuery));
        $result = DB::select($sqlQuery);
        $data = json_encode($result);
        $data  = json_decode($data,true);   //store data in array
        $data = $data[0];        
        $data['session_start_date'] = Carbon::parse( $data["session_start_date"])->format('m/d/Y');
        $data['session_end_date'] = Carbon::parse( $data["session_end_date"])->format('m/d/Y');
    
        // 24-hour time to 12-hour time with am,pm
        $data['session_start_time'] = Carbon::parse( $data['session_start_time'])->format('h:i a') ;
        $data['session_end_time'] = Carbon::parse( $data['session_end_time'])->format('h:i a');
        // echo date('g:i a', strtotime('23:45'));  //it is working
        // dd($data);
        return view('teacher.edit_exam',['data' => $data ]);
    }

    public function update_exam_of_course(Request $request)
    {        
        $rules = array(
            'exam_name' => 'required|string|max:20',
            'exam_descriptions' => 'required|string|max:100',
            'attempt_limit' => 'required|int',
            'session_start_date' => 'required',
            'session_start_time' => 'required',
            'session_end_date' => 'required',
            'session_end_time' => 'required',
            'time_limit' => 'required|int',
            'grading_method' => 'required|string|max:20'
        );

        // getting json data
        $data = $request->all();
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            $errors = $error->errors()->all();
            return redirect()->back()->with('errors', $errors );
        }

        $exam_id = (int)$request->exam_id;
        $exam_name = $data['exam_name'];
        $descriptions = $data['exam_descriptions'];
        $attempt_limit = (int)$data['attempt_limit'];
        
        $data["session_start_date"] = Carbon::parse( $data["session_start_date"])->format('Y-m-d');
        $data['session_end_date'] = Carbon::parse( $data["session_end_date"])->format('Y-m-d');
        // 12-hour time to 24-hour time conversion
        $data['session_start_time'] = Carbon::parse( $data['session_start_time'])->format('H:i') ;
         // 12-hour time to 24-hour time conversion
         $data['session_end_time'] = Carbon::parse( $data['session_end_time'])->format('H:i') ;

        $session_start = $data['session_start_date'] .' '.$data['session_start_time'];        
        $session_end = $data['session_end_date'].' '.$data['session_end_time'];
        $time_limit = (int)$data['time_limit'];
        $grading_method = $data['grading_method'];        

        $sqlQuery = "UPDATE exams
                    SET exam_name = '$exam_name',
                        exam_descriptions = '$descriptions',
                        attempt_limit = $attempt_limit,
                        session_start = '$session_start',
                        session_end = '$session_end',
                        time_limit = $time_limit,
                        grading_method = '$grading_method'
                    WHERE exam_id = $exam_id;";
        DB::beginTransaction();
        try{ 
            $affected = DB::update($sqlQuery);
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];                     
            return $e;
        }        
        // return response()->json(['success' => 'Data successfully updated']);
        return redirect()->back()->with('status', 'Exam data successfully updated');
    }

    public function delete_exam_of_course($exam_id)
    {
        $sqlQuery = "DELETE 
                    FROM exams 
                    WHERE exam_id = $exam_id";
        DB::beginTransaction();
        try{ 
            $deleted = DB::delete($sqlQuery);
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];         
            return $e;
        }
        return response()->json(['response' => 'Delete successfully']);
    }



    public function exam_info(Request $request)
    {        
        $exam_id = (int)$request->exam_id;
        $sqlQuery = "SELECT exam_id, exam_name,exam_descriptions,
                        DATE(session_start) as session_start_date,TIME(session_start) as session_start_time,
                        DATE(session_end) as session_end_date,TIME(session_end) as session_end_time,
                        time_limit,attempt_limit,grading_method 
                    FROM exams
                    WHERE exam_id = $exam_id ;";
        $exam_info = DB::select(DB::raw($sqlQuery));
        $exam_info = json_decode(json_encode($exam_info),true);
        $exam_info = $exam_info[0];

        $exam_info['session_start_date'] = Carbon::parse( $exam_info["session_start_date"])->format('d F y');
        $exam_info['session_end_date'] = Carbon::parse( $exam_info["session_end_date"])->format('d F y');
        
        // 24-hour time to 12-hour time with am,pm
        $exam_info['session_start_time'] = Carbon::parse( $exam_info['session_start_time'])->format('h:i a') ;
        $exam_info['session_end_time'] = Carbon::parse( $exam_info['session_end_time'])->format('h:i a');

        $sqlQuery = "SELECT q_track_id, q_no, q_text 
                    FROM exam_questions
                    WHERE exam_id = $exam_id";
        $questions = DB::select(DB::raw($sqlQuery));
        $questions = json_decode(json_encode($questions),true);

        $data = array( 'exam_info' => $exam_info,
                        'questions' => $questions
                    );    
        // dd($data);                        
        return view('teacher.exam_info', ['data' => $data]);        
    }

    public function create_question_page(Request $request)
    {
        $exam_id = (int)$request->exam_id;
        return view('teacher.create_question',['exam_id'=>$exam_id]);
    }

    public function create_question_to_exam(Request $request)
    {
        $rules = array(
            'question_no' => 'required|int',
            'question_text' => 'required|string|max:100',
            'options' => 'required|array',
            'options.*' => 'distinct',
            'answers' => 'required|array'          
        );

        // getting json data
        $question_data = $request->all();
        $exam_id = (int)$request->exam_id;
        $question_data = json_decode($question_data['data'],true);
        $error = Validator::make($question_data, $rules);
        
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $q_no = (int)$question_data['question_no'];
        $q_text = $question_data['question_text'];

        $query1 = "INSERT INTO exam_questions (exam_id,q_no,q_text)
                    values($exam_id,$q_no,'$q_text');";
        
        DB::beginTransaction();
        try{        
            DB::insert($query1);
            $q_track_id = DB::getPdo()->lastInsertId();
            
            $option = $question_data['options'][0];
            $is_answer = $question_data['answers'][0];
            $query2 = "INSERT INTO exam_questions_details (q_track_id,q_options,is_answers)
                        values($q_track_id,'$option',$is_answer)";  
            $length = count($question_data['options']);
            $i=1;

            while($i < $length)
            {
                $option = $question_data['options'][$i];
                $is_answer = $question_data['answers'][$i];
                $query2 = $query2 . ",($q_track_id,'$option',$is_answer)";
                $i++;
            }
            DB::insert($query2);            
            DB::commit();
            return response()->json(['success' => 'New question no ' . $q_no . ' is added successfully.']);
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062)
            {
                return "duplicate data insertion error";
            }            
            return $e;
        }

    }

    public function get_exam_questions_details(Request $request)
    {
        $exam_id = (int)$request->exam_id;
        $q_track_id = (int)$request->q_track_id;
        // $question = array(
        //                 'exam_id'  =>1,
        //                 'q_track_id'  =>1,
        //                 'q_serial_no' => 1,
        //                 'q_text' => 'this is question',                        
        //                 'options' => array("a","b","c", "d"),
        //                 'answers' => array( "a","c")                                        
        //             );

        $q_track_id = (int)$request->q_track_id;
        $sqlQuery = " SELECT q_track_id,q_no,q_text,
                                GROUP_CONCAT(q_options) AS options,
                                GROUP_CONCAT(is_answers) AS is_answers	 
                        FROM  exam_questions NATURAL JOIN exam_questions_details
                        WHERE q_track_id = $q_track_id;";
        $result = DB::select(DB::raw($sqlQuery));
        $result = $result[0];
        $result->options = explode(",", $result->options); //spliting all options as array and store in that object
        $result->is_answers = explode(",", $result->is_answers); //spliting all answers as array and store in that object
        $question = json_decode(json_encode($result),true);
        
        // dd($question);
        // return $data;
        // return response()->json($question);
        return view('teacher.edit_question',['question'=> $question,'exam_id'=> $exam_id, 'q_track_id'=> $q_track_id ]);
        
        // $button = '<button type="button" name="edit" id="'.$item->item_id.'" class="edit btn btn-success"><i class="fa fa-pencil"></i></button>';
        // $button .= '<br><br>';
        // $button .= '<button type="button" name="delete" id="'.$item->item_id.'" class="delete btn btn-danger"><i class="fa fa-trash"></i></button>';
        
    }

    public function update_question_of_exam(Request $request)
    {
        $rules = array(
            'question_no' => 'required|int',
            'question_text' => 'required|string|max:100',
            'options' => 'required|array',
            'options.*' => 'distinct',
            'answers' => 'required|array'          
        );

        // getting json data
        $question_data = $request->all();
        // return $question_data;
        $exam_id = (int)$request->exam_id;
        $q_track_id = (int)$request->q_track_id;
        
         $question_data = json_decode($question_data['data'],true);
         $error = Validator::make($question_data, $rules);
         if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $q_no = (int)$question_data['question_no'];
        $q_text = $question_data['question_text'];
        
        $query1 = "UPDATE exam_questions
                    SET q_no = $q_no,
                        q_text = '$q_text'
                    WHERE q_track_id = $q_track_id ;";

        $query2 = "DELETE 
                    FROM exam_questions_details                        
                    WHERE q_track_id = $q_track_id;";

        $option = $question_data['options'][0];
        $is_answer =(int)$question_data['answers'][0];
        $query3 = "INSERT INTO exam_questions_details (q_track_id, q_options,is_answers)
                    values($q_track_id,'$option',$is_answer)";
        $length = count($question_data['options']);
        $i = 1;
        while( $i < $length )
        {
            $option = $question_data['options'][$i];
            $is_answer = (int)$question_data['answers'][$i];
            $query3 = $query3 .",($q_track_id,'$option',$is_answer)";
            $i++;
        }       

        DB::beginTransaction();
        try{

            $affected = DB::update($query1);
            $deleted1 = DB::delete($query2);
            DB::insert($query3);
            DB::commit();
            return response()->json(['success' => 'Question no ' . $q_no . ' is updated successfully.']);
        }
        catch(Exception $e)
        {
            DB::rollback();
            $errorCode = $e->errorInfo[1];
            // if($errorCode == 1062)
            // {
            //     return "duplicate data insertion error";
            // }            
            return $e;
        }

        return response()->json(['success' => 'Data successfully updated']);
    }

    public function delete_exam_question($q_track_id)
    {
        // return $q_track_id;
        $sqlQuery = "DELETE 
                    FROM exam_questions
                    WHERE q_track_id = $q_track_id;";        
        try{ 
            $deleted = DB::delete($sqlQuery);            
        }
        catch(Exception $e)
        {
            $errorCode = $e->errorInfo[1];
            // if($errorCode == 1062)
            // {
            //     return "duplicate data insertion error";
            // }            
            return $e;
        }
    }

}

