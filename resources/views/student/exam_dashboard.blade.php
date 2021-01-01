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
        <!-- checkbox question-1 -->
        <div class="form-group question" id="1">
            <h4>1. What is the Capital of Bangladsh?</h4>
            <div class="checkbox">
                <input type="checkbox" value="a">
                <label><span> a. </span>Dhaka</label>                    
            </div>
            <div class="checkbox">
                <input type="checkbox" value="b">
                <label><span> b. </span>Chittagonj</label>                           
            </div>
            <div class="checkbox">
                    <input type="checkbox" value="c" >
                    <label><span> c. </span>Khulna</label>                    
                </div>

                <div class="checkbox">
                    <input type="checkbox" value="d">
                    <label><span> d. </span>Rajsahi</label>                           
                </div>
            
        </div><br/><br/>

        <!-- checkbox question-2 -->
        <div class="form-group question" id="2">
            <h4>2. Who was the prime minister of Bangladsh?</h4>
            <div class="checkbox">
                <input type="checkbox" value="a">
                <label><span> a. </span>Sheikh Hasina</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="b">
                <label><span> b. </span>Begum Zia</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="c">
                <label><span> c. </span>Barack Obama</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="d">
                <label><span> d. </span>Donald Trump</label>                           
            </div>
        </div><br/><br/>
        
        <!-- checkbox question-3 -->
        <div class="form-group question" id="3">
            <h4>3. Who is he national poet of Bangladsh?</h4>
            <div class="checkbox">
                <input type="checkbox" value="a">
                <label><span> a. </span>Kazi Nazrul Islam</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="b">
                <label><span> b. </span>Rabindranath Tagor</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="c">
                <label><span> c. </span>Jibanando Das</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="d">
                <label><span> d. </span>Micheal Modhusudon Dutta</label>                           
            </div>	
        </div><br/><br/>			
        
        <!-- checkbox question-4 -->
        <div class="form-group question" id="4">
            <h4>4. What is the national fruit of Bangladsh?</h4>
            <div class="checkbox">
                <input type="checkbox" value="a">
                <label><span> a. </span>Mango</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="b">
                <label><span> d. </span>Jackfruit</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="c">
                <label><span> d. </span>Apple</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="d">
                <label><span> d. </span>Orange</label>                           
            </div>                
        </div><br/><br/>
                    
        <!-- checkbox question-5 -->
        <div class="form-group question" id="5">
            <h4>5. What is the national animal of Bangladsh?</h4>
            <div class="checkbox">
                <input type="checkbox" value="a">
                <label><span> a. </span>Cow</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="b">
                <label><span> b. </span>Tiger</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="c">
                <label><span> c. </span>Lion</label>                           
            </div>
            <div class="checkbox">
                <input type="checkbox" value="d">
                <label><span> d. </span>Fox</label>                           
            </div>                
        </div><br/><br/><br/><br/><br/><br/>
            
        <button class="submit" name="submit" align="right" id="save_btn"> Save & Close </button>		
    </div>
    
@endsection()

@section('script_area')

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
                alert("last question no: "+ last_q_object.attr("id"));
                let json_object = Object();
                json_object.q_no = last_q_object.attr("id");    //store question's id
                let json_array = [];
                $.each($(last_q_object).find("div").children("input:checked"),function(index){
                    json_array[index]= $(this).attr("value");   //stores all choices
                });
                json_object.option_no = json_array;
                console.log(json_object);                    
            }                
        });      
    </script>
    
@endsection()
