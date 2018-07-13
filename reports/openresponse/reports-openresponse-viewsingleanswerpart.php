<?php
/*
 * created by - Vijayalakshmi PHP programmer
 * creating for new and edit Materials
 * DB:itc_materials_master
 */
ini_set('display_errors', '0');
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s"); 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$questiontrackid = $id[0];
$studentid = $id[1];
echo "stundent".$studentid; exit;
?>
<!--Script for the Tag Well-->
<script language="javascript" type="text/javascript" charset="utf-8">
</script>

<section data-type='2home' id='reports-openresponse-showsingleanswerset'>
    <div class='container'>
    	<!--Load the Material Name / New material-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">xxxxx</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the material Form-->
        <div class='row formBase rowspacer'>
       <div id="wrapper">
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
		<div class="imageBox">Puncuation<br/>
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
?>
<div id="im<?php echo $qrycnt; ?>" class="ui-draggable <?php echo $classname;?>" data-remarkdisplayed="false" style="left: <?php echo $leftpt;?>px; top: <?php echo $toppt;?>px;"></div>

<?php

		}

	}

?>
		
	</div><!-- end of frame -->
</div><!-- end of wrapper -->
        </div>
    </div>
</section>

<?php
	@include("footer.php");
