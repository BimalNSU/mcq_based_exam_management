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
    <div class="dashboard"><h1 align="left"> Exam name: {{$data['exam_info']['exam_name']}} </h1>
                            <h3 align="left"> {{$data['exam_info']['exam_descriptions']}}</h3>
    </div> 	
    <div class="details">
        <h3>Attempts allowed: {{$data['exam_info']['attempt_limit']}}</h3>
        <h4>This quiz started {{$data['exam_info']['session_start_date']}}, {{$data['exam_info']['session_start_time']}}</h4>
        <h4>This quiz closed {{$data['exam_info']['session_end_date']}}, {{$data['exam_info']['session_end_time']}}</h4>
        <h4> Grading method: {{$data['exam_info']['grading_method']}}</h4> 
        <h4> Time limits: {{$data['exam_info']['time_limit']}} minutes</h4> 
        <button type="button" class="btn btn-primary"> Test attempt quiz now</button>			
    </div>

    

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">exam question list</h3>
                <div class="box-tools">
                    <a href="{{ route('createQuestionPage',$data['exam_info']['exam_id']) }}">
                        <button type="button" class="btn btn-success mb-1" ><i class="fa fa-plus"></i></button>
                    </a> 
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
                        <th>question no</th>
                        <th>question text</th>
                        <th>action</th>
                    </tr>
                    @foreach($data['questions'] as $value)
                        <tr>
                            <td>{{$value['q_no']}}</td>
                            <td>{{$value['q_text']}}</td>
                            <td>
                                <a href= "{{route('questionEditView', [$data['exam_info']['exam_id'],$value['q_track_id'] ] )}}"> 
                                    <button type="button" name="edit" class="edit btn btn-success">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
                                <button type="button" name="delete" value="{{$value['q_track_id']}}" class="delete btn btn-danger">
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
    let q_track_id;
    //dynamically created 'delete button' on click event function
    $(document).on('click', '.delete', function() {
            q_track_id = $(this).attr("value");     //get stored q_track_id
            $('#delete_btn').text('Delete'); //rename 'ok button' as 'Delete button'
            $('#confirmModal').modal('show');
        });

     //predefine 'ok button' click event function
     $('#confirm_delete_btn').click(function() {
            
            $.ajax({
                url: "{{ route('deleteExamQuestion', ['q_track_id' => '']) }}/" + q_track_id,
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