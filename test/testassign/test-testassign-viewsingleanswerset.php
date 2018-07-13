<?php
/*
 * created by - Vijayalakshmi PHP programmer
 * to get comments for each individual answer
 * created on:6/12/2014
 * modified on :29/12/2014,19/1/2015
 * Modified by - Vijayalakshmi PHP Programmer
 */
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s"); 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$questiontrackid = $id[0];
$studentid = $id[1];

?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">

 //  Selecting Drag and Drop Tools
    $(document).ready(function(){

        //Counter
        counter = 0;
        //Make element draggable
        $(".drag").draggable({
            helper:'clone',
            containment: 'frame',
	    cursor: 'move',          // sets the cursor apperance
	appendTo:'div#frame',
	zIndex: 999,
	scroll:true,
	 opacity: 0.35,
//When first dragged
            stop:function(ev) {
	      objName = "#clonediv"+counter;
	      idname ="clonediv"+counter;
              $(objName).css({"left":leftpt,"top":toppt});
              $(objName).removeClass("drag");
	      dropItemOffsetX = $( objName ).css( "left" );
	      dropItemOffsetY = $( objName ).css( "top" );
	      var imgclassname = $(objName).attr('class').split(' ')[1];
	      $button = $(objName);
//Save when drop the comment tag on answer div without comment text in itc_test_teacher_comment table

	      savedropposition(dropItemOffsetX,dropItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',imgclassname,idname);
	 
		if($(objName).attr("data-remarkDisplayed") == "false") {
//Save the comment tag along with comment text or not in itc_test_teacher_comment table
			commentdatabox(dropItemOffsetX,dropItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',$button,imgclassname,idname);
		}

//When an existiung object is dragged
                $(objName).draggable({
                	containment: 'parent',
			scroll: false,
  		start: function(event, ui) {
		    	// Show start dragged position of image.

		     removebox = this.id;
		     $('#x'+removebox).remove();

		dragItemOffsetX = $( this ).css( "left" );
		dragItemOffsetY = $( this ).css( "top" );
		
// when move image commenttag from one place to another (check by backend with table name:itc_test_teacher_comment
			getdragposition(dragItemOffsetX,dragItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>');
		 },
			
                    stop:function(ev, ui) {
		     removebox = this.id;
		     $('#x'+removebox).remove();
		     var $button = $(this);
		     var stopimgidname = $( this ).attr( "id" );
		     $button.attr('data-remarkDisplayed', 'false');
 			var pos=$(ui.helper).position();
		     stopItemOffsetX = $( this ).css( "left" );
		     stopItemOffsetY = $( this ).css( "top" );

		     var stopimgclassname = $(objName).attr('class').split(' ')[1];
//Save when drop the comment tag on answer div without comment text in itc_test_teacher_comment table

		     savedropposition(stopItemOffsetX,stopItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',stopimgclassname,stopimgidname);
			if($button.attr("data-remarkDisplayed") == "false") {
//Save the comment tag along with comment text or not in itc_test_teacher_comment table
			           commentdatabox(stopItemOffsetX,stopItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',$button,stopimgclassname,stopimgidname);
	            }
	            }
                });
}   
        });  //ends Make element draggable
        //Make element droppable
        $("#frame").droppable({
	     drop: function(ev, ui) {
              
		if (ui.draggable.attr('id').search(/drag[0-9]/) != -1){
			objName = "#clonediv"+counter;

	if((ev.offsetX=='' || ev.offsetX==undefined) && (ev.offsetY=='' || ev.offsetY==undefined))  // this works for Firefox
	{

		var pos=$(ui.helper).position();
	        leftpt = pos.left;
		toppt =  pos.top;
	}  
	else {

		var pos=$(ui.helper).position();
                leftpt = pos.left;
		toppt = pos.top;
	}
	counter++;
	var element=$(ui.draggable).clone();

	element.addClass("tempclass");
	$(this).append(element);

	$(".tempclass").attr("id","clonediv"+counter);
	$("#clonediv"+counter).removeClass("tempclass");
	//Get the dynamically item id
	draggedNumber = ui.draggable.attr('id').search(/drag([0-9])/);
	itemDragged = "dragged" + RegExp.$1
	
	$("#clonediv"+counter).addClass(itemDragged);
	$("#clonediv"+counter).removeClass("drag");
	$("#clonediv"+counter).attr('data-remarkDisplayed', 'false');
	$("#clonediv"+counter).css({"left":leftpt,"top":toppt});
		}
      
                $('.ui-draggable').click(function(e) {  
                     var $button = $(this);
		     var imageidname = $( this ).attr( "id" );
		     $('#x'+imageidname).remove();
	             var imageclassname = $('#'+imageidname).attr('class').split(' ')[1];
		     var topposition = $( this ).css( "top" );
		     var leftposition = $( this ).css( "left" );
	 	     $button.attr("data-remarkDisplayed","false");

                     if($button.attr("data-remarkDisplayed") == "false") {
//Save the comment tag along with comment text or not in itc_test_teacher_comment table
		         commentdatabox(leftposition,topposition,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',$button,imageclassname,imageidname); 
                    }
    });
        	}
        });  //ends Make element droppable
        });
   
</script>

<section data-type='2home' id='test-testassign-viewsingleanswerset'>
    <div class='container'>
    	<!--Load the Material Name / New material-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Comments</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the material Form-->
        <div class='row formBase rowspacer'>
<div class='rowspacer'>
	</div>
       <div id="wrapper" class='row'>
	<div id="options">
		<div class="imageBox">Spelling<br/>
				<div id="drag1" class="drag" ></div>  <!-- end of drag1 -->
		</div>
		<div class="imageBox">Capitalization<br/>
				<div id="drag2" class="drag" ></div> <!-- end of drag2 -->
		</div>
		<div class="imageBox">Subject/Verb Agreement<br/>
				<div id="drag3" class="drag" ></div> <!-- end of drag3 -->
		</div>
		<div class="imageBox">Punctuation<br/>
				<div id="drag4" class="drag" ></div> <!-- end of drag4 -->
		</div>
		<div class="imageBox">Great!<br/>
				<div id="drag5" class="drag" ></div> <!-- end of drag5 -->
		</div>
		<div class="imageBox">Good!<br/>
		    <div id="drag6" class="drag"></div> <!-- end of drag6 -->
		</div>
		<div class="imageBox">Super!<br/>
		    <div id="drag7" class="drag"></div> <!-- end of drag7 -->
		</div>
		<div class="imageBox">Comment<br/>
		    <div id="drag8" class="drag"></div> <!-- end of drag8 -->
		</div>
			</div><!-- end of options -->
		<br/>
	<div id="frame">
	<div style="margin:15px; text-align:justify;">
	<?php
		$result_answer = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_test_student_answer_track 
		                                        WHERE fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND fld_delstatus='0' AND fld_id='".$questiontrackid."'"); 
		echo $result_answer;
	?>
	</div> 
<?php 

       $chk_updateddate= $ObjDB->SelectSingleValue("SELECT fld_updated_date FROM itc_test_student_answer_track WHERE fld_id='".$questiontrackid."'");

       $comment_updateddate= $ObjDB->SelectSingleValue("SELECT fld_trackupdated_date FROM itc_test_teacher_comment WHERE fld_answer_id='".$questiontrackid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'");

if($chk_updateddate == $comment_updateddate) {

	$qryview_tools = $ObjDB->QueryObject("SELECT fld_left as leftpt,fld_top as toppt,fld_comment_text as comment,fld_tool_classname as classname FROM itc_test_teacher_comment WHERE fld_answer_id='".$questiontrackid."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND fld_delstatus='0'");
$qrycnt = 0;
 	if($qryview_tools->num_rows > 0){
		while($rowsqry = $qryview_tools->fetch_assoc()){
$qrycnt++;
			extract($rowsqry);
?>
<div id="im<?php echo $qrycnt; ?>" class="ui-draggable <?php echo $classname;?>" data-remarkdisplayed="false" style="left: <?php echo $leftpt;?>px; top: <?php echo $toppt;?>px;"></div>
<script language="javascript" type="text/javascript" charset="utf-8">

  	$(document).ready(function(){
// sets draggable the elements with id="im"
  		$('#im<?php echo $qrycnt; ?>').draggable({
    			cursor: 'move',        // sets the cursor apperance
    			containment: '#frame',    // sets to can be dragged only within "#drg" element

		start: function(event, ui) {
			    	// Show start dragged position of image.
	 		dragItemOffsetX = $( this ).css( "left" );
			dragItemOffsetY = $( this ).css( "top" );
      // when image comment tag move from one place to another (check by backend with table name:itc_test_teacher_comment
			getdragposition(dragItemOffsetX,dragItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>');
		},
		 stop:function(ev, ui) {
	             removebox = this.id;
			   
		     $('#x'+removebox).remove();
		     var $button = $(this);

		      dropItemOffsetX = $( this ).css( "left" );
		      dropItemOffsetY = $( this ).css( "top" );
		      var imgidname = $( this ).attr( "id" );
		      var imgclassname = $('#'+imgidname).attr('class').split(' ')[1];
	
                      savedropposition(dropItemOffsetX,dropItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',imgclassname,imgidname);
if($button.attr("data-remarkDisplayed") == "false") {
//Save the comment tag along with comment text or not in itc_test_teacher_comment table
			           commentdatabox(dropItemOffsetX,dropItemOffsetY,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',$button,imgclassname,imgidname);
		}
		}
  	});

	$('#im<?php echo $qrycnt; ?>').click(function(e) {  
		$(".remarkContainer").show();
	        var $button = $(this);
		var imageidname = $( this ).attr( "id" );
		var imageclassname = $('#'+imageidname).attr('class').split(' ')[1];
		var topposition = $( this ).css( "top" );
		var leftposition = $( this ).css( "left" );
//Save when drop the comment tag on answer div without comment text in itc_test_teacher_comment table
		    savedropposition(leftposition,topposition,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',imageclassname,imageidname);	
                if($button.attr("data-remarkDisplayed") == "false") {
//Save the comment tag along with comment text or not in itc_test_teacher_comment table
		    commentdatabox(leftposition,topposition,'<?php echo $questiontrackid; ?>','<?php echo $studentid; ?>',$button,imageclassname,imageidname);
                    }
	});
  });
</script>
<?php
		}  // ends of while($rowsqry = $qryview_tools->fetch_assoc())
	}  //ends of if($qryview_tools->num_rows > 0)
}  //ends of if($chk_updateddate == $comment_updateddate)
else
{
///
		}
?>
		</div><!-- end of frame -->
	</div><!-- end of wrapper -->
<div class="row rowspacer">
	</div>
<div class="row rowspacer">
        </div>
<div class="row rowspacer">
    </div>
<div class="row rowspacer">
	</div>
<div class="row rowspacer">
        </div>
<div class="row rowspacer">
    </div>
<div class="row rowspacer">
	</div>
        </div>
    </div>
</section>
<script>
   
</script>
<?php
	@include("footer.php");
