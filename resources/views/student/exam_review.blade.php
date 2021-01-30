@extends('master')

 @section('title_area') 
	 Dasboard
 @endsection() 
@section('style_area')
    <style type="text/css">
        .qpaper{
            width:900px;
            background-color:rgb(0,0,0,0.6);
            margin:auto;
            color:#FFFFFF;
            padding:10px 0px 10px 10px; 
            text-align:center; 
            border-radius:15px 15px 0px 0px;
            }
            
        .qbody{
            width:900px;				
            background-color:white;
            margin:auto;   
            margin-top: 10px;         				
            padding:10px 0px 10px 10px; 
            border-radius:15px 15px 0px 0px;
            }
        .question{
            margin-left: 20px;            
            }
           
            
    </style>
@endsection()        

@section('content_area')
    <div class="qpaper">
        <h1> Exam id: {{$exam_info['exam_id']}} </h1>
        <h3> attempt no: {{$exam_info['attempt_no']}} </h3>
        <h3>Attempt start: {{$exam_info['student_start']}}</h3>
        <h3>Total marks: {{$exam_info['total_marks']}}</h3>
    </div> 
    
    <div class="qbody">
    @foreach($exam_data as $value)
        <!-- checkbox question-1 -->
        <div class="form-group question" id="{{$value['q_track_id']}}">
            <h4>{{$value['q_serial_no']}}. {{$value['q_text']}}</h4>
            <?php $i = 0; ?>
            @foreach($value['q_options'] as $option)
            <div class="checkbox">
                @if($value['is_selected'][$i] == "1")
                <input type="checkbox" value="{{$value['q_option_no'][$i]}}" checked disabled>
                @else
                <input type="checkbox" value="{{$value['q_option_no'][$i]}}" disabled>
                @endif                
                <label><span> {{$value['q_option_no'][$i]}}. </span>{{$option}}</label>                    
            </div>
                <?php $i = $i +1; ?>
            @endforeach
            @if($value['marks'] == "1")
            <p>Your answer is correct</p>
            @elseif($value['marks'] == "0")
            <p>Your answer is empty</p>
            <p>Correct Answers: {{$value['answers']}} </p>
            @else
            <p>Your answer is incorrect</p>
            <p>Correct Answers: {{$value['answers']}} </p>
            @endif           
        </div><br/>
    @endforeach
    </div>
@endsection()

@section('script_area')
    
@endsection()
