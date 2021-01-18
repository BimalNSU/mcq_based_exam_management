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
                            <td>
                                <a href="{{url('/teacher/exam/view',$value['exam_id'])}}">{{$value['exam_name']}}</a>
                            </td>
                            <td>{{$value['exam_descriptions']}}</td>
                            <td>
                                <a href= "{{url('/teacher/exam/edit_view',$value['exam_id'])}}"> 
                                    <button type="button" name="edit" id="{{$value['exam_id']}}" class="edit btn btn-success">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
                                <button type="button" name="delete" value="{{$value['exam_id']}}" class="delete btn btn-danger">
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

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-confirm-title">Confirmation</h2>
            </div>
            <div class="modal-body">
                <h4 align="center" id="delete_result"style="margin:0;">Are you sure you want to remove this data?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="confirm_btn" id="confirm_delete_btn" class="btn btn-danger">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection()

@section('script_area')
<script>
    let exam_id;
    //dynamically created 'delete button' on click event function
    $(document).on('click', '.delete', function() {
            exam_id = $(this).attr("value");     //get stored exam_id
            $('#delete_btn').text('Delete'); //rename 'ok button' as 'Delete button'
            $('#confirmModal').modal('show');
        });

     //predefine 'ok button' click event function
     $('#confirm_delete_btn').click(function() {
            
            $.ajax({
                url: "{{ route('deleteExam', ['q_track_id' => '']) }}/" + exam_id,
                method:"DELETE",
                beforeSend: function() {
                    $('#confirm_delete_btn').text('Deleting...');
                },
                success: function(data) {
                    setTimeout(function() {
                        $('#confirmModal').modal('hide');                        
                    }, 500);
                    location.reload();  //page refresh
                }
            })
    });
</script>    
    
@endsection()
