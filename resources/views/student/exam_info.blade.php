@extends('master')

 @section('title_area') 
	 exam_info
 @endsection() 
@section('style_area')
    <style>
        .dashboard{
				width:900px;
				background-color:rgb(0,0,0,0.6);
				margin:auto;
				color:#FFFFFF;
				padding:10px 0px 10px 10px; 
				text-align:center; 
				border-radius:15px 15px 0px 0px;
				}
			.details{
				width:900px;
				background-color:rgb(0,0,0,0.8);
				margin:auto;
				color:#FFFFFF;
				text-align:center; 
				padding:10px 0px 10px 10px; 
				border-radius:15px 15px 0px 0px;
				}
				
			}

    </style>
@endsection() 


@section('content_area')
    <div class="dashboard"><h1 align="left"> Exam name: {{$exam_info['exam_name']}} </h1>
                            <h3 align="left"> {{$exam_info['exam_descriptions']}}</h3>
    </div> 	
    <div class="details">
        <h3>Attempts allowed: {{$exam_info['attempt_limit']}}</h3>
        <h4>This quiz started {{$exam_info['session_start_date']}}, {{$exam_info['session_start_time']}}</h4>
        <h4>This quiz closed {{$exam_info['session_end_date']}}, {{$exam_info['session_end_time']}}</h4>
        <h4> Grading method: {{$exam_info['grading_method']}}</h4> 
        <h4> Time limits: {{$exam_info['time_limit']}} minutes</h4> 
        <div id='attempt'></div>
    </div>
@endsection()


@section('script_area')
 <script>
	let attempt_btn = {!! json_encode($attempt_btn) !!};
	let exam_id = {!! json_encode($exam_id) !!};
	// console.log(attempt_btn);
	$('#attempt').html(attempt_btn);
	// document.getElementById("link").href = '/mcq_based_exam/public/student/exam/request/'+exam_id;
	document.getElementById("link").href = "{{ route('requestExam', [':exam_id']) }}".replace(':exam_id', exam_id);
 </script>
 
@endsection()