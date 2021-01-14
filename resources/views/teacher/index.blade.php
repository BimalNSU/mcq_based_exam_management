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
                                <a href= "{{url('/teacher/exam/update',$value['exam_id'])}}"> 
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
<!-- bootstrap datepicker -->
<script src="{{ asset('FrontEnd') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('FrontEnd') }}/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script>
	$(function () {

		//Date picker
		$('#datepicker').datepicker({
		autoclose: true
		})

		//Date picker
		$('#datepicker2').datepicker({
		autoclose: true
		})
		
		//Timepicker
		$('.timepicker').timepicker({
		showInputs: false
		})
	})
</script>
<script>
    $(document).ready(function()
    {
            //static 'create-item' add button click event
            $('#create-item').click(function(){
            //show 'modal-body' 'Add Record' form by '.row' class
            $("#exam_form").find(".row").show();
             //clear input fields in 'Add Record' form
             $("#exam_form").find("input").val('');
            //show 'cancel & Add' buttons by '.model-footer' class
            $("#exam_form").find(".modal-footer").show();
             //set modal-title name as 'Add Record'
            $('.modal-title').text("Add Record");
            //set button name as 'Add'
            $('#action_button').text("Add");
            $('#action').val("Add");

            $('#form_result').html("");
            $('#action').val("Add");
           // $('#formModal').modal('show'); //issue found here
        });

        $('#exam_form').on('submit', function(event){
            event.preventDefault();

            if($('#action').val() == 'Add')
            {
                $.ajax({
                    url:"{{ route('createExam') }}",
                    method:"POST",
                    data: new FormData(this),
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success:function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#exam_form')[0].reset();
                            $('#exam_table').DataTable().ajax.reload();
                        }

                        $('#form_result').html(html);
                    }
                })
            }

            if($('#action').val() == "Edit")
            {
                $.ajax({
                    url:"{{ route('updateExam') }}",
                    method:"POST",
                    data:new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType:"json",
                    success:function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            //$('#exam_form')[0].reset();
                            // $('#exam_table').DataTable().ajax.reload();
                             //hide modal-body of edit form by '.row' class
                            $("#exam_form").find(".row").hide();
                            //hide 'cancel & edit' buttons by '.model-footer' class
                            $("#exam_form").find(".modal-footer").hide();
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

        //dynamically created 'edit button' on click event function
        $(document).on('click', '.edit', function() {
            var exam_id = $(this).attr('id');
            $.ajax({
               type:'GET',
               url:"{{ route('updateExam') }}/" + exam_id,
               data: '',
               success:function(data) {
                //   $("#msg").html(data.msg);
                    console.log(data);
               }
            });
            var item_id = $(this).attr('id');
            $('#form_result').html('');
            //get stock table editable 'row' object
            var rowObject = $(this).closest("tr");
            //getting editable row data
            var item_name = rowObject.find('td:eq(0)').text();
            var generic_name = rowObject.find('td:eq(1)').text();
            var company_name = rowObject.find('td:eq(2)').text();
            var buy_price = Number(rowObject.find('td:eq(3)').text());
            var sale_price = Number(rowObject.find('td:eq(4)').text());
            var quantity = Number(rowObject.find('td:eq(5)').text());
            var location = rowObject.find('td:eq(6)').text();
            var minimum_stock = Number(rowObject.find('td:eq(7)').text());

            //copy data into editable window
            $('#item_name').val(item_name);
            $('#generic_name').val(generic_name);
            $('#company_name').val(company_name);
            $('#buy_price').val(buy_price);
            $('#sale_price').val(sale_price);
            $('#quantity').val(quantity);
            $('#location').val(location);
            $('#minimum_stock').val(minimum_stock);

            $('#hidden_id').val(item_id);
            $('.modal-title').text("Edit Record");
             //set modal-title name as 'Add Record'
            $('.modal-title').text("Edit Record")
            $('#action_button').text("Edit");
            $('#action').val("Edit");
            //show 'Edit Record' form by '.row' class
            $("#exam_form").find(".row").show();
            //show 'cancel & Edit' buttons by '.model-footer' class
            $("#exam_form").find(".modal-footer").show();
            $('#formModal').modal('show');  //show pop-up window

            // //testing
            // //var row = $(this).parents("td")[0].text();
            // //var pos = $( '#exam_table' ).fnGetPosition(row);
            // //var id = $( '#exam_table' ).fnGetData(pos[0])["item_id"];
            // var rows = $("#exam_table").dataTable().fnGetNodes();
            // var arr=$('#exam_table').dataTable().fnGetData($(this));
            // //alert($("#tbl").dataTable().fnGetData(0).('item_id'));
            // alert(arr[0]);
        });

        var exam_id;

        //dynamically created 'delete button' on click event function
        $(document).on('click', '.delete', function() {
            exam_id = $(this).attr('id');
            $('#ok_button').text('Delete'); //rename 'ok button' as 'Delete button'
            $('#confirmModal').modal('show');
        });

        //predefine 'ok button' click event function
        $('#ok_button').click(function() {
            $.ajax({
                url: "{{ route('deleteExam', ['exam_id' => '']) }}/" + exam_id,
                method:"DELETE",
                beforeSend: function() {
                    $('#ok_button').text('Deleting...');
                },
                success: function(data) {
                    setTimeout(function() {
                        $('#confirmModal').modal('hide');
                        // $('#exam_table').DataTable().ajax.reload(); //refresh stock list after delete operation
                    }, 500);
                }
            })
        });
    })

</script>
    
@endsection()
