<?php 
@include('../includes/table.class.php');
@include('../includes/comm_func.php');	
$oper = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content='width=device-width,initial-scale=1' name='viewport' />
	<link href="css/style.css" type="text/css" rel="stylesheet" />	
	<link href='css/gumby.hybrid.css' rel='stylesheet' type="text/css" />
    <link href='css/ui.css' rel='stylesheet' type="text/css" />
    <link href='css/style-icons.css' rel='stylesheet' type="text/css" />
	<script language="javascript" type="text/javascript" src="jquery-1.8.3.min.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.ui.touch-punch.min.js"></script>
    <script language="javascript" type="text/javascript" src='ipad-assignment.js'></script>         
    <style>
		.diviplbottom, .btnprevclose
		{
			background-color:#0078b7 !important;
			/*z-index:1000000;*/
			position:relative;
		}
		
	</style>
</head>
<body>
    <div id="divcustomlightbox" title="Synergy ITC">        
        <div class="btnprevclose">
        	<p class="dialogTitleFullScr" style="width:90%"><?php echo $lessonname; ?></p>
        	<a href="javascript:void(0);" onClick="fn_closescreen()" class="icon-synergy-close-dark" ></a>
        </div>
        <div id="divlbcontent" style="z-index:10; position:relative;"></div>
        <div id="diviplbottom" class="diviplbottom"><p class="dialogTitleFullScr" id="fottitle"></p></div> 
    </div>	 
    <script language="javascript" type="text/javascript">
    	$(document).ready(function() {
			$('body').css('overflow','hidden');
			
			var cssObjOuter = {		
			'display' : 'block',		
			'width' : $('body').width(),		
			'height' : $(window).height()		
			};		
			
			var cssObjInner = {		
			'display' : 'block',		
			'width' : $('body').width(),		
			'height' : $(window).height() - 89		
			};
			
			$('#divcustomlightbox').css(cssObjOuter);		
			$('#divlbcontent').css(cssObjInner);		
			$('iframe').attr('width',$('#divlbcontent').width());
			$('iframe').attr('height',$('#divlbcontent').height());		
			$(document).scrollTop(0);
		});		
		/*$(window).resize(function() {
		if($('#divcustomlightbox').length){
			var cssObjOuter = {
			  'display' : 'block',
			  'width' : $('body').width(),
			  'height' : $(window).height()
			};
				
			var cssObjInner = {
			  'display' : 'block',
			  'width' : $('body').width(),
			  'height' : $(window).height() - 90
			};
			
			$('#divcustomlightbox').css(cssObjOuter);
			$('#divlbcontent').css(cssObjInner);
			$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
		}
	});*/
    </script>
    </body>
</html>
<?php 

if($oper == 'starttest' and $oper != ''){
	$uid= isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';	
	$sid= isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';	
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : '';	
	
	$currentqry = $ObjDB->QueryObject("SELECT fld_id AS maxid, fld_lesson_id AS lessonid, fld_type AS type, fld_status AS status FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$uid."' AND fld_status=0 ORDER BY fld_id DESC LIMIT 0,1");
	$flag=0;
	$completed=0;
	if($currentqry->num_rows>0){
		$current_res = $currentqry->fetch_assoc();
		extract($current_res);
		
		//check is the lesson is abailable or not 
		$chklesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id WHERE a.fld_sigmath_id='".$sid."' AND a.fld_lesson_id='".$lessonid."' AND a.fld_flag='1' AND b.fld_access='1' AND b.fld_delstatus='0'");		
						
		if($chklesson>0){
			$flag=1;
			if($type==1){
				$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
			}
			if($type==2){
				$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
				if($orientationid==0 || $orientationid=='')
					$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
				else
					$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.",1)";
			}
			if($type==3){
				$function = "fn_startmastery1(".$sid.",".$lessonid.",".$maxid.")";
			}
			if($type==4){
				$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
			}
			if($type==6){
				$function = "fn_diagfinish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
			if($type==5 or $type==7){
				$function = "fn_mastery1finish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
			if($type==8){
				$function = "fn_mastery2finish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
		}
	}								
	
	if($flag!=1){
		//get the lesson with out previously attend		
		$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0'");		
		$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND ".$orientationid." NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') AND fld_lesson_id='".$orientationid."'");
		
		$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";	
		if($lessonid==0 || $lessonid=='')
		{
			$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') ORDER BY fld_order LIMIT 0,1");
		
			if($lessonid==0){
				$completed=1;								
			}
			$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
		}
	}
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");
	?>
    <input type="hidden" id="uid" value="<?php echo $uid; ?>" />
    <script>		
		setTimeout('<?php echo $function; ?>',500);	
    </script>
    <?php 
}
@include("footer.php");
?>