@extends('master')

 @section('title_area') 
	 create question
 @endsection() 
@section('style_area')
    <style type="text/css">
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
            background-color:rgb(240, 240, 240);
            margin:auto;
            text-align:center; 
            padding:10px 0px 10px 10px; 
            border-radius:15px 15px 0px 0px;
            }

    </style>
@endsection() 

@section('content_area')

<div class="details">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Create question</h3>
        </div>
        <!-- /.box-header -->    
        <div class="box-body">
            <span id="respond_result"></span>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">question no</label>
                <div class="col-sm-10">
                <textarea class="form-control" rows="2" id="q_serial_no" placeholder="write your question no" style="resize: none"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Question</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" id="q_text" placeholder="write your question" style="resize: none"></textarea>
                </div>                
            </div>     
            <div class="form-group row">
                <label class="col-sm-2 control-label">Option 1</label>
                <div class="input-group">
                    <span class="input-group-addon">                            
                        <input type="checkbox" name="answers[]">
                    </span>
                    <textarea class="form-control" name="options[]" rows="2" placeholder="write your options" style="resize: none"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Option 1</label>
                <div class="input-group">
                    <span class="input-group-addon">                            
                        <input type="checkbox" name="answers[]">
                    </span>
                    <textarea class="form-control" name="options[]" rows="2" placeholder="write your options" style="resize: none"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Option 1</label>
                <div class="input-group">
                    <span class="input-group-addon">                            
                        <input type="checkbox" name="answers[]">
                    </span>
                    <textarea class="form-control" name="options[]" rows="2" placeholder="write your options" style="resize: none"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Option 1</label>
                <div class="input-group">
                    <span class="input-group-addon">                            
                        <input type="checkbox" name="answers[]">
                    </span>
                    <textarea class="form-control" name="options[]" rows="2" placeholder="write your options" style="resize: none"></textarea>
                </div>
            </div>         
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="" class="btn btn-default">Cancel</button>
            <button onclick="create_quation()" class="btn btn-info pull-right">Create</button>
        </div>        
        <!-- /.box-footer -->	
    </div>
</div>
		
@endsection() 

@section('script_area') 
<script>
    function create_quation()
    {
        // alert("last question no: "+ last_q_object.attr("id"));
        let json_object = Object();
        // json_object.q_no = last_q_object.attr("id");    //store question's id
        let q_serial_no = $("#q_serial_no").val();
        let q_text = $("#q_text").val();
        json_object.q_serial_no = q_serial_no;
        json_object.q_text = q_text;
        
        let options = [];
        let answers = [];
        let json_array = [];
        $.each($("div textarea[name='options[]']"),function(index){                
            let option = $(this).val();
            if(option != "")
            {
                options[index] = option;   //store one by one all options in array
                // console.log(option);
                if($(this).siblings("span").find('input[type="checkbox"]').prop("checked") == true)
                {
                    let answer = option;
                    // alert(answer);
                    answers.push(answer);    //store one by one all answers in array               
                }
            }
            
        });
        json_object.options = options;
        json_object.answers = answers;
        let exam_id = {!! json_encode($exam_id) !!};    //collect exam_id which was sent during loading this page
        $.post("{{ route('createQuestion', [':exam_id']) }}".replace(':exam_id', exam_id) , {data: JSON.stringify(json_object) } , function(data){
            let html = '';
            if(data.errors)
            {
                html = '<div class="alert alert-danger">';
                console.log(data);
                for(var count = 0; count < data.errors.length; count++)
                {
                    html += '<p>' + data.errors[count] + '</p>';
                }
                html += '</div>';
            }
            if(data.success)
            {
                html = '<div class="alert alert-success">' + data.success + '</div>';
            }
            $('#respond_result').html(html);
        });           
    }  



    $(document).ready(function(){

              
    });      
        
</script>
@endsection()     