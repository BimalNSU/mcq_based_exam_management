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
			.qbody{
				width:900px;				
				background-color:white;
				margin:auto;   
				margin-top: 10px;         				
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

	<br>
	@if($exam_result)
	<div class="qbody">
		<div class="box-header">
			<h3 class="box-title">Attempt list</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover">
				<tr>
					<th>Attempt no</th>
					<th>Total marks</th>
					<th>Total marks</th>
					<th></th>
				</tr>
				@foreach($exam_result as $value)
					<tr>
						<td>{{$value['attempt_no']}}</td>
						<td>{{$value['total_marks']}}</td>
						<td>
							<a href= "{{url('/student/exam/review',$value['exam_track_id'])}}"> 
								<button type="button" class="btn btn-success">
									Review
								</button>
							</a>                          
						</td>
					</tr>
				@endforeach                   
			</table>
		</div>
		<!-- /.box-body -->
	</div>
	@endif
@endsection()


@section('script_area')
 <script>
	let attempt_btn = {!! json_encode($attempt_btn) !!};
	let exam_id = {!! json_encode($exam_id) !!};
	let data3 = {!! json_encode($exam_result) !!};
	
	console.log(data3);
	$('#attempt').html(attempt_btn);
	// document.getElementById("link").href = '/mcq_based_exam/public/student/exam/request/'+exam_id;
	document.getElementById("link").href = "{{ route('requestExam', [':exam_id']) }}".replace(':exam_id', exam_id);
 </script>
 
@endsection()