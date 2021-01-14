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
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
                <a href="{{ url('teacher/exam/create') }}">
                    <button type="button" class="btn btn-success mb-1" ><i class="fa fa-plus"></i></button>
                </a>              
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">exam list</h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover" id="exam_table">
                    <tr>
                        <th>ID</th>
                        <th>exam name</th>
                        <th>description</th>
                        <th>action</th>
                    </tr>
                    @foreach($data as $value)
                        <tr>
                            <td>{{$value['exam_id']}}</td>
                            <td>{{$value['exam_name']}}</td>
                            <td>{{$value['exam_descriptions']}}</td>
                            <td>
                                <a href= "{{url('/teacher/exam/info',$value['exam_id'])}}"> 
                                    <button type="button" name="edit" id="{{$value['exam_id']}}" class="edit btn btn-success">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
                                <button type="button" name="delete" id="{{$value['exam_id']}}" class="delete btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>                           
                            </td>
                        </tr>
                    @endforeach                   
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>


@endsection()

@section('script_area')
    
@endsection()
