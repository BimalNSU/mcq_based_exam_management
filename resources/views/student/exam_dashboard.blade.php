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
    <div class="qpaper">
        <h1> Question Paper 1 </h1>
        <h3> Total marks: 5 </h3>
        <h3 align="right">Time left: 10:00 minutes</h3>
    </div>  
    
    <div class="qbody">
    @foreach($exam_data as $value)
        <!-- checkbox question-1 -->
        <div class="form-group question" id="{{$value['q_track_id']}}">
            <h4>{{$value['q_serial_no']}}. {{$value['q_text']}}</h4>
            <?php $i = 0; ?>
            @foreach($value['options'] as $option)
            <div class="checkbox">
                <input type="checkbox" value="{{$value['q_option_numbers'][$i]}}">
                <label><span> {{$value['q_option_numbers'][$i]}}. </span>{{$option}}</label>                    
            </div>
                <?php $i = $i +1; ?>
            @endforeach                        
        </div><br/>
    @endforeach
                             
                  
    </div><br/><br/><br/><br/><br/><br/>
       <div>     
        <button class="submit" name="submit" align="right" id="save_btn"> Save & Close </button>		
    </div>
    
@endsection()

@section('script_area')
    <script>
         $(document).ready(function(){
             var last_q_object = null;
            $(".question div input:checkbox").click(function()
            {
                // alert($(this).parents());
                // console.log($(this).parents("div .question").attr("id"));
                let current_q_object = $(this).parents("div .question")
                if(last_q_object != null && last_q_object.attr("id") != current_q_object.attr("id")) 
                {
                    getLastChoices();
                    last_q_object = current_q_object; 
                }
                else
                {
                    last_q_object = $(this).parents("div .question");   //store current question's object
                }
            });
            $("#save_btn").click(function(){
                getLastChoices();
                last_q_object = null;
            });

            function getLastChoices()
            {
                // alert("last question no: "+ last_q_object.attr("id"));
                let json_object = Object();
                json_object.q_no = last_q_object.attr("id");    //store question's id
                let json_array = [];
                $.each($(last_q_object).find("div").children("input:checked"),function(index){
                    json_array[index]= $(this).attr("value");   //stores all choices
                });
                json_object.option_no = json_array;
                // console.log(json_object); 
                let exam_track_id = {!! json_encode($exam_track_id) !!};
                $.post("{{ route('do_exam', [':exam_track_id']) }}".replace(':exam_track_id', exam_track_id), {data: JSON.stringify(json_object) } , function(data){
                        // Display the returned data in console
                        //console.log(data);                    
                    if(data.success)
                    {
                        console.log(data); 
                    }                    
                });                           
            }                
        });      
    </script>
    
@endsection()
