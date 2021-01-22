<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use json;
use Illuminate\Support\Facades\DB;
// use Auth;
use Carbon\Carbon;
use Validator;
use Session;

class StudentController extends Controller
{
    public function index()
    {
        $query = "SELECT exam_id, exam_name, exam_descriptions
                FROM exams ;";
        $data = DB::select( DB::raw($query));
        $exams  = json_decode(json_encode($data),true);   //store data in array
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
        
        $session_start = $exam_info['session_start_date'] ." ". $exam_info['session_start_time'];
        $session_end = $exam_info['session_end_date'] ." ". $exam_info['session_end_time'];
        $current_datetime = Carbon::parse( Carbon::now('Asia/Dhaka'))->format('Y-m-d H:i'); 
        $session_start = Carbon::parse( $session_start)->format('Y-m-d H:i'); 
        $session_end = Carbon::parse( $session_end)->format('Y-m-d H:i'); 
        //  $current_datetime = Carbon::parse( Carbon::now('Asia/Dhaka'))->format('d F y h:i a'); 
        $sql2 = "SELECT count(q_track_id) as total_questions
                    FROM exam_questions
                    WHERE exam_id = $exam_id";
        $numberOfQuestions = DB::select(DB::raw($sql2));
        $numberOfQuestions = json_decode(json_encode($numberOfQuestions),true);
        $numberOfQuestions = $numberOfQuestions[0]['total_questions'];

         $attempt_btn = '';
        //  dd($current_datetime, $session_start);
        if ($current_datetime >= $session_start and $current_datetime < $session_end )
        {
            if($numberOfQuestions == 0)
            {
                $attempt_btn = "<span>No questions are added</span>";
            }
            else
            {
                $attempt_btn = "<a id ='link'>
                                    <button type='button' class='btn btn-primary'> Attempt quiz now</button>
                                </a>";
            }
        } 
        // dd($exam_id);
        // return array_push($exam_info['attempt_btn'], attempt_btn' => $attempt_now_btn);     
        // dd($data);                        
        return view('student.exam_info', ['exam_info' => $exam_info,'attempt_btn' => $attempt_btn, 'exam_id' => $exam_id ]);        
    }
    
    public function join_request_exam(Request $request)
    {   
        $exam_id = (int)$request->exam_id;
        $student_id = auth()->user()->id;        
        $sql1 ="select session_start, session_end, time_limit, attempt_limit, exam_track_id, attempt_no,student_start,student_end                    
                from exam_assign natural join exams
                where exam_id = $exam_id and student_id = $student_id
                order by attempt_no desc
                limit 1;";
        $data1 = DB::select(DB::raw($sql1));        
        $data1  = json_decode(json_encode($data1),true);        
        // dd($data1);
        $data_found = !empty($data1);        
        $session_start;
        $session_end;
        $time_limit = 0;
        $attempt_limit = 0;
        $exam_track_id = null;
        $attempt_no = 0;        
        $student_start;
        $student_end = null;
        $data2;
        if($data_found == true)
        {        
            $session_start = $data1[0]['session_start'];
            $session_end = $data1[0]['session_end'];
            $time_limit = $data1[0]['time_limit'];
            $attempt_limit =$data1[0]['attempt_limit'];
            $exam_track_id = $data1[0]['exam_track_id'];
            $attempt_no = $data1[0]['attempt_no'];
            // $next_attempt = $attempt_no + 1;
            $student_start =$data1[0]['student_start'];
            $student_end = $data1[0]['student_end'];
        }
        else
        {
            $sql2 ="select session_start, session_end, time_limit, attempt_limit
                    from exams
                    where exam_id = $exam_id";
            $data2 = DB::select(DB::raw($sql2));
            $data2  = json_decode(json_encode($data2),true);

            $session_start = $data2[0]['session_start'];
            $session_end = $data2[0]['session_end'];
            $time_limit = $data2[0]['time_limit'];
            $attempt_limit =$data2[0]['attempt_limit'];
        }                              
        $next_attempt = $attempt_no + 1;
        $current_datetime = Carbon::parse( Carbon::now('Asia/Dhaka'))->format('Y-m-d H:i'); 
    
        $t1;
        $t2;
        $remaining_time =0;
        if( $current_datetime < $session_end )
        {   
            if($data_found == true)
            {
                $start = new Carbon($current_datetime);
                // $end = new Carbon($session_end);
                $end = new Carbon($student_start);

                //$escape time = |$current_datetime - student_start|;
                $escape_time = $start->diffInMinutes($end);
                //$remaining_time = $time_limit - $escape_time
                $remaining_time = $time_limit - $escape_time;
                if($remaining_time < 0)
                {
                    $remaining_time = 0;
                } 
            }               
            
            if($attempt_limit > $attempt_no)
            {
                if($remaining_time == 0 or ($remaining_time > 0 and $student_end != null))
                {                
                    DB::beginTransaction();
                    try{
                        $sql2 ="INSERT INTO exam_assign (exam_id,attempt_no,student_id,student_start)
                                values($exam_id, $next_attempt,$student_id, '$current_datetime');";
                        $data2 = DB::select(DB::raw($sql2));                    
                        $exam_track_id = DB::getPdo()->lastInsertId();   
                        
                        // $myAry =[0,1,2,3,4,5];
                        // shuffle($myAry);
                        // dd($myAry);
                        $sql3 = "select q_track_id
                                from exam_questions
                                where exam_id = $exam_id
                                order by rand();";
                        $data3 = DB::select(DB::raw($sql3));
                        $data3  = json_decode(json_encode($data3),true);                    

                        $q_track_id = $data3[0]['q_track_id'];
                        $sql4 = "INSERT INTO exam_papers (exam_track_id, q_track_id, q_serial_no)
                                    values($exam_track_id,$q_track_id,1 )";
                        $i=1;
                        $length = count($data3);
                        while($i < $length)
                        {
                            $q_track_id = $data3[$i]['q_track_id'];
                            $sql4 = $sql4 . ",($exam_track_id, $q_track_id, $i+1 )";                         
                            $i = $i+1;
                        }
                        DB::insert(DB::raw($sql4));    
                        $sql5 =" select e.q_track_id,GROUP_CONCAT(q_options) as options
                                from exam_questions e natural join exam_questions_options
                                where exam_id = $exam_id
                                GROUP BY e.q_track_id
                                order by e.q_track_id";

                        $data5 = DB::select(DB::raw($sql5));
                        $length = count($data5);
                        $i = 0;
                        while ($i < $length)    
                        {
                            $array_options = explode(",",$data5[$i]->options); //spliting all options as array and store in that object
                            shuffle($array_options);    //all options are being shuffled
                            $data5[$i]->options = $array_options;
                            $i = $i+1;
                        }
                        $data5  = json_decode(json_encode($data5),true);
                        // dd($data5[0]['q_track_id']);
                        $option = $data5[0]['options'][0];
                        $q_track_id = $data5[0]['q_track_id'];
                        $sql6 = "INSERT INTO exam_papers_q_options (exam_track_id,q_track_id,q_option_no,q_options,is_selected)
                                values($exam_track_id, $q_track_id, 1 ,'$option', 0)";
                        $i=0;
                        while($i < $length)
                        {
                            $j = 0;
                            if($i == 0 and $j == 0)
                            {
                                $j = 1;
                            }
                            $options_numbers = $length = count($data5[$i]['options']);
                            while($j < $options_numbers)
                            {
                                $opiton = $data5[$i]['options'][$j];
                                $q_track_id = $data5[$i]['q_track_id'];
                                $q_option_no = $j +1;
                                $sql6 = $sql6 . ",($exam_track_id, $q_track_id, $q_option_no , '$opiton', 0)";
                                $j = $j +1;
                            }
                            $i =$i +1;                
                        }            
                        DB::insert(DB::raw($sql6));

                        DB::commit();
                        // return view('student.exam_dashboard',['exam_data' => $exam_data,'exam_track_id' => $exam_track_id]);  
                        // return redirect()->route('join_exam', $exam_track_id)->with( ['exam_data' => $exam_data] );
                        // return redirect()->route('join_exam', $exam_track_id)->with(['remaining_time' => $remaining_time]);
                        return redirect()->route('join_exam', $exam_track_id);
                    }
                    catch(Exception $e)
                    {
                        DB::rollback();
                        $errorCode = $e->errorInfo[1];                
                        return $e;
                    }
                }
            }
            if($remaining_time > 0 and $student_end == null)
            {    //if studnet has no going exam return that exam
                return redirect()->route('join_exam', $exam_track_id);
            }
            
        }            
        return 'you are not allowed to do exam';
    }
    public function join_exam_page(Request $request)
    {
        $exam_track_id = $request->exam_track_id;
        $sql =" select session_start,session_end,time_limit,student_id,student_start,student_end
                    from exams e natural join exam_assign
                    where exam_track_id = $exam_track_id;";

        $data = DB::select(DB::raw($sql));
        $data  = json_decode(json_encode($data),true);
        $student_id = auth()->user()->id;
        
        if($student_id == $data[0]['student_id'])   //valid student id or not
        {
            $session_start = $data[0]['session_start'];
            $session_end = $data[0]['session_end'];
            $time_limit =(int) $data[0]['time_limit'];
            $student_start =$data[0]['student_start'];
            $student_end =$data[0]['student_end'];                         
            $current_datetime = Carbon::parse( Carbon::now('Asia/Dhaka'))->format('Y-m-d H:i');         
            $t1;
            $t2;
            $remaining_time =0;
            // dd($session_end);
            if( $current_datetime < $session_end)
            {
                $start = new Carbon($current_datetime);
                // $end = new Carbon($session_end);
                $end = new Carbon($student_start);
                
                //$escape time = $current_datetime - student_start;
                $escape_time = $start->diffInMinutes($end);
                //$remaining_time = $time_limit - $escape_time
                $remaining_time = $time_limit - $escape_time;                   
                if($remaining_time > 0)
                {
                    $sql2 ="select m.*    
                        from (select t.q_track_id,x.q_serial_no, y.q_text, t.q_option_numbers, t.options, t.is_selected
                                from (SELECT exam_track_id, q_track_id,
                                        GROUP_CONCAT(q_option_no) AS q_option_numbers,
                                            GROUP_CONCAT(q_options) AS options,
                                            GROUP_CONCAT(is_selected) AS is_selected
                                    FROM exam_papers_q_options
                                    WHERE exam_track_id = $exam_track_id
                                    GROUP BY exam_track_id, q_track_id
                                    order by q_option_no) as t natural join exam_papers x  join exam_questions y
                                    ON x.q_track_id = y.q_track_id) as m                                            
                        order by m.q_serial_no";
                    $data2 = DB::select(DB::raw($sql2));            
                    // dd($data7);
                    $length = count($data2);
                    $i = 0;
                    while ($i < $length)    
                    {
                        $data2[$i]->q_option_numbers = explode(",",$data2[$i]->q_option_numbers); //spliting all options as array and store in that object                
                        $data2[$i]->options = explode(",",$data2[$i]->options); //spliting all options as array and store in that object                
                        $data2[$i]->is_selected = explode(",",$data2[$i]->is_selected); //spliting all options as array and store in that object                
                        $i = $i+1;
                    }        
                    $exam_data  = json_decode(json_encode($data2),true);
                        // dd($exam_data);
                        $i = 0;
                        $j = 0;
                    while ($i < $length)    
                    {
                        $length2 = count($exam_data[$i]['q_option_numbers']);
                        while($j < $length2)
                        {
                            $exam_data[$i]['is_selected'][$j] = (int)$exam_data[$i]['is_selected'][$j]; 
                            $j = $j +1;           
                        }
                        
                        $i = $i+1;
                    }
                    // dd($exam_data);
                    return view('student.exam_dashboard',[ 'exam_data' => $exam_data, 'exam_track_id' => $exam_track_id, 'remaining_time' => $remaining_time]);  
                }
                
            }
        }
        return redirect()->route('student');        
    }

    public function do_exam(Request $request)
    {
        $exam_track_id = (int)$request->exam_track_id;
        // getting json data
        $data = $request->all();                      
        $data = json_decode($data['data'],true);
        // return $data; 
        $stop_exam = $data['stop_exam'];        
        $q_track_id = $data['q_track_id'];
        $q_option_no = $data['option_no'];
        
        $i = 0;
        $length = count($q_option_no);        
        while($i < $length)
        {
            $q_option_no[$i] = (int)$q_option_no[$i];
            $i = $i+1;            
        }
        $sql = "UPDATE exam_papers_q_options
                SET is_selected = 0
                WHERE exam_track_id = $exam_track_id and q_track_id = $q_track_id ;";
        
        
        try{
            DB::beginTransaction();
            DB::update(DB::raw($sql));
            foreach($q_option_no as $value)
            {
                $sql2 = "UPDATE exam_papers_q_options 
                            SET is_selected = 1
                        WHERE exam_track_id = $exam_track_id and q_track_id = $q_track_id and q_option_no = $value;";
                DB::update(DB::raw($sql2));                        
            }                        
            DB::commit();
            // return response()->json(['success' => "Question's answer is stored successfully."]);
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
        if($stop_exam == 1)
        {
            $current_datetime = Carbon::parse( Carbon::now('Asia/Dhaka'))->format('Y-m-d H:i');
            $sql_query ="update exam_assign
                set student_end = $current_datetime                                    
                where exam_track_id = $exam_track_id;";
            DB::update(DB::raw($sql_query));
            // return redirect()->route('student');
            return view('student.index');
        }                

    }

}
