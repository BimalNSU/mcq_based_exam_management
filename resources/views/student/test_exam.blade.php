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
<?php $i =1 ?>
    @foreach($data as $a)
        <ul>
            <li>{{$a['q_track_id']}}</li>
            <span>Choices: </span>
            @foreach($a['choices'] as $choice)
                <li>{{$choice}}</li>    
            @endforeach
            <span>Answers: {{$a['answers']}}</span>
        </ul>   
    @endforeach

@endsection()

@section('script_area')
    
@endsection()
