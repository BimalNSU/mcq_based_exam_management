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
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('FrontEnd') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ asset('FrontEnd') }}/plugins/timepicker/bootstrap-timepicker.min.css">        
@endsection()        


@section('content_area')
<div class="details">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Update exam</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
        <form action="{{url('teacher/exam/edit',$data['exam_id'] )}}" method="POST">
        @csrf        
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 control-label">Exam name</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="exam_name" placeholder="Exam name" value="{{$data['exam_name'] }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Descriptions</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" name="exam_descriptions" placeholder="exam descriptions" style="resize: none">{{$data['exam_descriptions'] }}</textarea>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 control-label">Attempts limit</label>		
                <div class="col-sm-2">
                    <input class="form-control js-input-absint" type="number" step="1" min="1"  max="150" name="attempt_limit" value="{{$data['attempt_limit'] }}" placeholder="Attempts allow">
                </div>
            </div>
            
            <div class="form-group row">
                <label for="inputPassword3" class="control-label col-sm-2">exam session start</label>
                <div class='col-sm-5'>								
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="session_start_date" value="{{$data['session_start_date'] }}" class="form-control pull-right" id="datepicker">
                    </div>
                </div>
                <div class ="col-sm-3">
                    <div class="input-group">			
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" name="session_start_time" value="{{$data['session_start_time'] }}" class="form-control timepicker">	
                    </div>
                </div>						
            </div>

            <div class="form-group row">
                <label for="inputPassword3" class="control-label col-sm-2">exam end</label>
                <div class='col-sm-5'>								
                    <div class=" input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="session_end_date" value="{{$data['session_end_date'] }}" class="form-control pull-right" id="datepicker2">
                    </div>
                </div>
                <div class ="col-sm-3">
                    <div class="input-group">			
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" name="session_end_time" value="{{$data['session_end_time'] }}" class="form-control timepicker">	
                    </div>
                </div>						
            </div>					  	  

            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 control-label">Time limit</label>
                <div class="col-sm-3">
                    <input class="form-control js-input-absint" type="number" name="time_limit" step="1" min="10"  max="150" value="{{$data['time_limit'] }}" placeholder="enter time limit in minutes">
                </div>
            </div>				

            <div class="form-group row">
                <label class ="col-sm-2">Grading method</label>
                <div class="col-sm-3">
                    <select class="form-control" name="grading_method">
                        <option selected disabled hidden>{{$data['grading_method']}}</option>
                        <option>Last attempt</option>
                        <option>Best attempt</option>
                    </select>
                </div>
            </div>
        
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-default">Cancel</button>
            <button type="submit" class="btn btn-info pull-right">Update</button>
        </div>
        </form>
        <!-- /.box-footer -->	
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
    var $inputAbsint = $('.js-input-absint');

    if ($inputAbsint.length) {

        $(document).on('keypress', '.js-input-absint', function (event) {

            var allowed = /^[0-9]|Arrow(Left|Right)|Backspace|Home|End|Delete$/;
            return allowed.test(event.key);

        }).on('focusout paste', '.js-input-absint', function () {

                var $input = $(this);
                var defaultValue = this.defaultValue || $input.attr('min');
                // Important(!): Timeout for the updated value
                setTimeout(function () {
                    var current = $input.val();
                    var regexNumbers = new RegExp(/^[0-9]*$/, 'g');
                    var isNumbersOnly = regexNumbers.test(current);
                    // Clear wrong value (not numbers)
                    if ((current === '' || !isNumbersOnly) && defaultValue.length) {
                        $input.val(defaultValue);
                        current = defaultValue;
                    }
                    // Min/Max
                    var min = parseInt($input.attr('min'), 10);
                    var max = parseInt($input.attr('max'), 10);
                    var currentInt = parseInt(current, 10);
                    if (!isNaN(min) && !isNaN(currentInt) && currentInt < min) {
                        $input.val(min);
                    }
                    if (!isNaN(max) && !isNaN(currentInt) && currentInt > max) {
                        $input.val(max);
                    }
                }, 100);

            });

    }
</script>   
@endsection()
