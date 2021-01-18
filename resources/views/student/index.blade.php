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
            background-color:rgb(240, 240, 240);
            margin:auto;				
            padding:10px 0px 10px 10px; 
            border-radius:15px 15px 0px 0px;
            }
            
        .submit{
            position:relative;
            left:200px;
            top:-37px;
            line-height:40px;
            width:180px;
            border-radius:6px;
            padding:0 22px;
            font-size:16px;
            color:#455;
        }
    </style>
@endsection()        



@section('content_area')
@foreach($exams as $value)
    <div class="row">
        <div class="col-md-12">
            <div class="box box-light">
                <div class="box-header">
                    <a href="{{url('/student/exam/view',$value['exam_id'])}}">
                        <h3 class="box-title">{{$value['exam_name']}}</h3>
                    </a>
                </div>
                <div class="box-body">
                    <p>{{$value['exam_descriptions']}}</p> 
                </div>
            </div>    
        </div>
    </div>  
@endforeach

@endsection()


@section('script_area')
    
@endsection()
