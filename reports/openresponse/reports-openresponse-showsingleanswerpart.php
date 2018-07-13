<?php
/*
 * created by - Vijayalakshmi PHP programmer
 * created on:19/12/2014
 * 
 */
ini_set('display_errors', '0');
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s"); 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$questiontrackid = $id[0];
$studentid = $id[1];
?>

<section data-type='2home' id='reports-openresponse-showsingleanswerpart'>
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

    $qryview_tools = $ObjDB->QueryObject("SELECT fld_left as leftpt,fld_top as toppt,fld_comment_text as comment,fld_tool_classname as classname FROM itc_test_teacher_comment WHERE fld_answer_id='".$questiontrackid."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND fld_delstatus='0'");
$qrycnt = 0;
	if($qryview_tools->num_rows > 0){
		while($rowsqry = $qryview_tools->fetch_assoc()){
		$qrycnt++;
		extract($rowsqry);
			if($classname == "dragged1") {
			$tool_head = "Spelling";
			}
			elseif($classname == "dragged2") {
			$tool_head = "Capitalization";
			}
			elseif($classname == "dragged3") {
			$tool_head = "Subject/Verb Agreement";
			}
			elseif($classname == "dragged4") {
			$tool_head = "Punctuation";
			}
			elseif($classname == "dragged5") {
			$tool_head = "Great!";
			}
			elseif($classname == "dragged6") {
			$tool_head = "Good!";
			}
			elseif($classname == "dragged7") {
			$tool_head = "Super!";
			}
?>
<div id="show<?php echo $qrycnt; ?>" class="<?php echo $classname;?> " data-remarkdisplayed="false" style="left: <?php echo $leftpt;?>px; top: <?php echo $toppt;?>px;" onclick="fn_showcommentbox('<?php echo $comment; ?>','<?php echo $tool_head; ?>');"></div>

<?php
		}
	}
?>
        
    </div><!-- end of frame -->

</div><!-- end of wrapper -->
<div class="row rowspacer">
	</div>
        </div>
    </div>
</section>
<script>
   
</script>
<?php
	@include("footer.php");
