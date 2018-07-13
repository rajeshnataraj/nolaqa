<?php
@include("sessioncheck.php");

$id= isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);

if($id[1] !="mis"){
 $schid = '0';
 $schtype = '0';
 $maxcount = $ObjDB->SelectSingleValueInt("SELECT `fld_max_attempts` FROM `itc_test_student_mapping` WHERE  fld_student_id='".$uid."' and fld_test_id='".$id[0]."' and fld_id='".$id[1]."'");
         
$count = $ObjDB->SelectSingleValueInt("SELECT `fld_max_attempts` FROM `itc_test_student_mapping` WHERE  fld_student_id='".$uid."' and fld_test_id='".$id[0]."' and fld_id='".$id[1]."'")+1;

$ObjDB->NonQuery("UPDATE `itc_test_student_mapping` SET `fld_max_attempts`='".$count."' 
                 WHERE fld_student_id='".$uid."' and fld_test_id='".$id[0]."' and fld_id='".$id[1]."'");
				 
$pasusetest = $ObjDB->SelectSingleValueInt("SELECT fld_test_pause FROM `itc_test_student_mapping` WHERE  fld_student_id='".$uid."' and fld_test_id='".$id[0]."' and fld_id='".$id[1]."'");				 
if($pasusetest == 0){	 //mohan m for previos record full empty		 
}
}

$questioncount= $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM `itc_test_questionassign` AS a 
                                              LEFT JOIN itc_question_details AS b ON a.`fld_question_id`=b.`fld_id` 
											  WHERE a.fld_test_id='".$id[0]."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
if($id[1] =="mis"){
    $schid = $id[2];
    $schtype = $id[3];
    $testdet = $ObjDB->QueryObject("SELECT fld_id as testid, fld_time_limit as times, fld_test_name as testname, fld_expt AS expid , fld_mist AS misid
                               FROM `itc_test_master` WHERE fld_id='".$id[0]."'");
$row = $testdet->fetch_assoc();
extract($row);
    if($expid==0 and $misid!=0){
        $expid = $misid;
    }
    $maxcount=0;
}

$time=explode(":",$times);
$h=$time[0];
$m=$time[1];

if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
?>
<style>
iframe {
 max-height: 615px!important;
}
#divlbcontent {
 max-height: 610px!important;
overflow-y : auto;
}    
</style>
<?php
}

?>
<script language="javascript" src="/assignment/assignmentengine/assignment-assignmentengine-test.js"></script>
<script language="javascript">
	$(document).ready(function () {
		$('body').css('overflow','hidden');		
		var cssObjOuter = {
		  'display' : 'block',
		  'width' : $('body').width(),
		  'height' : $(document).height()
		};
		
		var cssObjInner = {
		  'display' : 'block',
		  'width' : $('body').width(),
		  'height' : $(window).height() - 90
		};
                $('body').append("<div id='divcustomlightbox' title='Synergy ITC'><div class='btnprevclose'><span class='dialogTitleMediumFullScr'><?php echo $testname; ?></span><span class='dialogTitleMediumFullScr' id='qustionnumber'></span><span class='dialogTitleMediumFullScr timeRemain' <?php if($times!= "00:00:00"){?> id='timecount'<?php } ?>> </span><a href='javascript:void(0);' <?php if($id[1] =="exp"){?>onclick='fn_closescreen1(<?php echo $id[2];?>,<?php echo $expid;?>,<?php echo $id[3];?>,<?php echo $id[4];?>,<?php echo $id[5];?>,<?php echo $id[6];?>,<?php echo $schtype;?>);'<?php } else {?> onclick='fn_closescreen(<?php echo $id[0];?>,1,<?php echo $schid;?>,<?php echo $schtype;?>);' <?php } ?> class='icon-synergy-close-dark'></a></div><div id='divlbcontent'></div><div class='diviplbottom'><a style='cursor:pointer;' class='btnactive dim' <?php if($id[1] =="exp"){?> onclick='fn_takequestion(<?php echo $id[0];?>,1,<?php echo $schid;?>,<?php echo $schtype;?>);fn_closescreen(<?php echo $id[0];?>,1);' <?php } else {?> onclick='fn_takequestion(<?php echo $id[0];?>,0,<?php echo $schid;?>,<?php echo $schtype;?>);' <?php }?> id='finish' >Finish</a><a class='btnactive dim' id='previous'>previous</a><a class='btnactive' id='next'>next</a><span class='dialogTitleSmallFullScr gotoText'>GO TO</span><select class='select-page' id='goto'><?php for($j=1;$j<=$questioncount;$j++) { ?><option id='<?php echo $j;?>'><?php echo $j; ?></option><?php }?></select> <?php if($id[1] !="exp"){?><span><a class='btnactive' id='pause'>pause</a></span> <?php } ?></div></div>"); //style='cursor:pointer;'
		$('#divcustomlightbox').css(cssObjOuter);
		$('#divlbcontent').css(cssObjInner);
		$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
		$('html, body').animate({scrollTop: '0px'}, 0);
		setTimeout('fn_loadquestion(<?php echo $id[0];?>,<?php echo $schid;?>,<?php echo $schtype;?>,<?php echo $maxcount; ?>);',500)
	});
</script>
<?php
	@include("footer.php");

	
