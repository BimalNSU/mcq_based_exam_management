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
            'exam_name' => 'required',
            'attempt_limit' => 'required',
            'session_start_date' => 'required',
            'session_start_time' => 'required',
            'session_end_date' => 'required',
            'session_end_time' => 'required',
            'time_limit' => 'required',
            'grading_method' => 'required'
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
        $attempts_limit = $data['attempt_limit'];
        $session_start = $data['session_start_date'] .' '.$data['session_start_time'];
        // $session_start = new DateTime($session_start);
        $session_end = $data['session_end_date'].' '.$data['session_end_time'];
        // $session_end = new DateTime($session_end);
        $time_limit = $data['time_limit'];
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
                    WHERE exam_id= '$exam_id' ;";
        //$result = DB::select(DB::raw($sqlQuery));
        $result = DB::select($sqlQuery);
        $data = json_encode($result);
        $data  = json_decode($data,true);   //store data in array
        $data = $data[0];        
        $data['session_start_date'] = Carbon::parse( $data["session_start_date"])->format('d/m/Y');
        $data['session_end_date'] = Carbon::parse( $data["session_end_date"])->format('d/m/Y');
        
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
            'exam_name' => 'required',
            'attempt_limit' => 'required',
            'session_start_date' => 'required',
            'session_start_time' => 'required',
            'session_end_date' => 'required',
            'session_end_time' => 'required',
            'time_limit' => 'required',
            'grading_method' => 'required'
        );

        // getting json data
        $data = $request->all();

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $exam_id = (int)$request->exam_id;
        $exam_name = $data['exam_name'];
        $descriptions = $data['exam_descriptions'];
        $attempt_limit = $data['attempt_limit'];
        
        $data["session_start_date"] = Carbon::parse( $data["session_start_date"])->format('Y-m-d');
        $data['session_end_date'] = Carbon::parse( $data["session_end_date"])->format('Y-m-d');
        // 12-hour time to 24-hour time conversion
        $data['session_start_time'] = Carbon::parse( $data['session_start_time'])->format('H:i') ;
         // 12-hour time to 24-hour time conversion
         $data['session_end_time'] = Carbon::parse( $data['session_end_time'])->format('H:i') ;

        $session_start = $data['session_start_date'] .' '.$data['session_start_time'];        
        $session_end = $data['session_end_date'].' '.$data['session_end_time'];
        $time_limit = $data['time_limit'];
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
        return response()->json(['success' => 'Data successfully updated']);
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
        $sqlQuery = "SELECT exam_name,exam_descriptions,session_start,session_end,time_limit,attempt_limit,grading_method 
                    FROM exams
                    WHERE exam_id = '$exam_id'";
        $exam_info = DB::select(DB::raw($sqlQuery));
        
        $sqlQuery = "SELECT q_track_id, q_serial_no, q_text 
                    FROM exam_questions
                    WHERE exam_id = '$exam_id'";
        $questions = DB::select(DB::raw($sqlQuery));
        $questions = json_decode(json_encode($questions),true);

        $data = array( 'exam_info' => $exam_info,
                        'questions' => $questions
                    );        
        return view('teacher.exam_info', ['data' => $data]);        
    }

    public function create_question_to_exam(Request $request)
    {
        $rules = array(
            'question_no' => 'required',
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'answers' => 'required'
        );

        // getting json data
        $data = $request->all();

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //extracting json data
        //dd($data);
        $exam_id = (int)$data['exam_id'];
        $question_no = $data['question_no'];
        $question_text = $data['question'];

        $i = 0;
        $options = array();
        foreach($data['options'] as $value)
        {
            $options[i] = $value;
        }
        $i = 0;
        $answers = array();
        foreach($data['answers'] as $value)
        {
            $answers[i] = $value;
        }

        DB::beginTransaction();
        try{
            $sqlQuery = "INSERT INTO exam_questions (exam_id, q_serial_no, q_text)
                        VALUES($exam_id, $question_no,'$question_text')";
            DB::insert($sqlQuery);

            $q_track_id = DB::getPdo()->lastInsertId();
            $sqlQuery = "INSERT INTO exam_questions_options (q_track_id, q_options)
                        VALUES($q_track_id, '$options[0]')";
            $i = 1;   
            $array_size = count($opitons);                     
            while($i < $array_size)                        
            {
                $sqlQuery = $sqlQuery .",($last_insert_id, '$options[$i]' )";
            }
            DB::insert($sqlQuery);

            $sqlQuery = "INSERT INTO exam_questions_answers (q_track_id, answers)
                        VALUES($q_track_id, '$answers[0]')";
            $i = 1;   
            $array_size = count($answers);                     
            while($i < $array_size)                        
            {
                $sqlQuery = $sqlQuery .",($last_insert_id, '$answers[$i]' )";
            }
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

    public function get_exam_questions_details(Request $request)
    {
        if($request->ajax())
        {
            $q_track_id = (int)$request->q_track_id;
            $sqlQuery = " select x.*,  GROUP_CONCAT(answers) AS answers
                            from	(SELECT q_track_id,q_serial_no,q_text
                                            GROUP_CONCAT(q_options) AS options	 
                                    FROM  exam_questions NATURAL JOIN exam_questions_options
                                    WHERE q_track_id = $q_track_id
                                    GROUP BY q_track_id
                                    order by q_option_no) as x natural join exam_questions_answers        
                            group by x.q_track_id ;";
            $result = DB::select(DB::raw($sqlQuery));
            $data = json_decode(json_encode($result),true);
            // return $data;
            return response()->json($data);
            // $result = DB::select($sqlQuery);
 
            // $button = '<button type="button" name="edit" id="'.$item->item_id.'" class="edit btn btn-success"><i class="fa fa-pencil"></i></button>';
            // $button .= '<br><br>';
            // $button .= '<button type="button" name="delete" id="'.$item->item_id.'" class="delete btn btn-danger"><i class="fa fa-trash"></i></button>';
        }
    }

    public function update_question_of_exam(Request $request)
    {
        $rules = array(
            'question_no' => 'required',
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'answers' => 'required'
        );

        // getting json data
        $data = $request->all();

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //extracting json data
        //dd($data);
        $q_track_id = (int)$data['q_track_id'];
        $q_serial_no = $data['q_serial_no'];
        $q_text = $data['question_text'];

       
        $sqlQuery = "UPDATE exam_questions
                    SET q_serial_no = $q_serial_no,
                        q_text = '$q_text'                        
                    WHERE q_track_id = $q_track_id;";

         foreach($data['options'] as $option)
         {
             $sqlQuery = $sqlQuery. "UPDATE exam_questions_options
                                    SET q_options = '$option'
                                    WHERE q_track_id = $q_track_id;";
         }
         foreach($data['answers'] as $answer)
         {
            $sqlQuery = $sqlQuery. "UPDATE exam_questions_answers
                                    SET answers = '$answer'
                                    WHERE q_track_id = $q_track_id;";
         }
        DB::beginTransaction();
        try{ 
            $affected = DB::update($sqlQuery);
            DB::commit();
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
        $sqlQuery = "DELETE 
                    FROM exam_questions
                    WHERE q_track_id = $q_track_id;

                    DELETE
                    FROM exam_questions_options
                    WHERE q_track_id = $q_track_id;

                    DELETE
                    FROM exam_questions_answers
                    WHERE q_track_id = $q_track_id;";   
        DB::beginTransaction();
        try{ 
            $deleted = DB::delete($sqlQuery);
            DB::commit();
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
    }

}

