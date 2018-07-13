<?php

@include("sessioncheck.php");

$fldrname = $_GET['fldrname'];
$fldpdlessonid = $_GET['fldpdlessonid'];
$pdfname = $_GET['pdfname'];
$schid=$_GET['schid'];

error_reporting(E_ALL);
ini_set('display_errors', '1');


$courseid=$ObjDB->SelectSingleValueInt("select fld_course_id from itc_pd_master where fld_id='".$fldpdlessonid."' AND fld_delstatus='0'");



$classid=$ObjDB->SelectSingleValueInt("select fld_class_id from itc_class_pdschedule_master where fld_id='".$schid."' AND fld_delstatus='0'");

$stucount=$ObjDB->SelectSingleValueInt("SELECT fld_id from itc_assignment_pd_master where fld_pdschedule_id='".$schid."' AND fld_lesson_id='".$fldpdlessonid."' AND fld_course_id='".$courseid."' AND fld_student_id='".$uid."' AND fld_delstatus='0'");

if($stucount>0)
{
    $ObjDB->NonQuery("update itc_assignment_pd_master set fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' where fld_pdschedule_id='".$schid."' AND fld_lesson_id='".$fldpdlessonid."' AND fld_course_id='".$courseid."' AND fld_student_id='".$uid."'");
}
else
{
    $ObjDB->NonQuery("INSERT INTO itc_assignment_pd_master(fld_class_id,fld_pdschedule_id,fld_lesson_id,fld_course_id,fld_student_id,fld_created_date,fld_created_by)values('".$classid."','".$schid."','".$fldpdlessonid."','".$courseid."','".$uid."','".date("Y-m-d H:i:s")."','".$uid."')");
}


?>

<style>
	body{
		margin:0px;
		padding:0px;
	}
	iframe{
		border:0px;
	}
	.btnprevclose{
		 position: absolute;
    	right: 1%;
	}
	.btnprevclose img {
	width:25px;	
		
	}
		
</style>

<script>
function closefullscreenlesson()
{	
	window.close();
}

</script>
<div id="divcustomlightbox" title="Synergy ITC">
	<div class="btnprevclose">
		<img src="<?= CONTENT_URL ?>/img/closebutton.png" title="Synergy ITC" style="cursor:pointer; "onclick="closefullscreenlesson()">
	</div>
	<div>
		<iframe id="test" src="<?= CLOUDFRONT_URL ?>/webpdlesson/<?php echo $fldrname; ?>/<?php echo $pdfname;?>/<?php echo $pdfname;?>.html" width="100%" height="100%"></iframe>
	</div>
</div>
<?php
@include("footer.php");
?>