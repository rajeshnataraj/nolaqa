<?php
@include("sessioncheck.php");

?>


<section data-type='#users-individuals' id='users-individuals-student_delstudent'>
    <script type="text/javascript" charset="utf-8">		
	$.getScript("users/individuals/users-individuals-student_newstudent.js");
        setTimeout("fn_selradio();",500);	
</script>
     <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Delete Students</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
                <h1></h1>
            </div>
        </div>
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
               
                        <div class="row rowspacer" id="dupstu"> 
                      
                        </div>
                    
                 
              
           </div>
        </div>
    </div>
</section>
<?php
     @include("footer.php");