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
            <h3 class="box-title">Update question</h3>
        </div>
        <!-- /.box-header -->    
        <div class="box-body">
        <span id="respond_result"></span>
            <input type="hidden" name="_token" value="{{csrf_token() }}">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">question no</label>
                <div class="col-sm-10">
                <textarea class="form-control" rows="2" id="q_serial_no" placeholder="write your question no" style="resize: none">{{$question['q_serial_no']}}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Question</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" id="q_text" placeholder="write your question" style="resize: none">{{$question['q_text']}}</textarea>
                </div>                
            </div>
            @foreach($question['options'] as $option)  
                 <?php $i=0 ?>         
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Option 1</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            @foreach($question['answers'] as $answer)
                                @if($option == $answer)
                                <?php $i=1 ?>
                                <input type="checkbox" name="answers[]" checked>
                                @endif
                            @endforeach
                        @if($i == 0)
                            <input type="checkbox" name="answers[]">
                        @endif
                        </span>
                        <textarea class="form-control" name="options[]" rows="2" placeholder="write your options" style="resize: none">{{$option}}</textarea>
                    </div>
                </div>
            @endforeach            
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="" class="btn btn-default">Cancel</button>
            <button onclick="update_quation()" class="btn btn-info pull-right">Update</button>
        </div>        
        <!-- /.box-footer -->	
    </div>
</div>
		

<input type="checkbox" class="onoffswitch-checkbox" id="inline" checked=1 >
@endsection() 

@section('script_area') 
<script>
    function update_quation()
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
            }
            // console.log(option);
            if($(this).siblings("span").find('input[type="checkbox"]').prop("checked") == true)
            {
                let answer = $(this).val();
                if(answer != "")
                {
                    answers.push(answer);    //store one by one all answers in array               
                }
            }
        // $.each($(last_q_object).find("div").children("input:checked"),function(index){
            // json_array[index]= $(this).attr("value");   //stores all choices
        });
        json_object.options = options;
        json_object.answers = answers;
        // const queryString = window.location.search;
//         // console.log(queryString);
        let exam_id = {!! json_encode($exam_id) !!};
        let q_track_id = {!! json_encode($q_track_id) !!};
        $.post("{{ route('updateQuestion', [':exam_id',':q_track_id']) }}".replace(':exam_id', exam_id).replace(':q_track_id',q_track_id), {data: JSON.stringify(json_object) } , function(data){
                // Display the returned data in console
                console.log(data);
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




        // json_object.option_no = json_array;
        // console.log(json_object);                    
    }  



    $(document).ready(function(){

        // let q_details = {!! json_encode($q_track_id) !!};
        // console.log(q_details);
        // // console.log(q_details.options);

              
    });      
        
</script>
@endsection()     