<?php
@include("sessioncheck.php");

$id= isset($method['id']) ? $method['id'] : '';
$id = explode('~',$id);
$close ='';
$mathtype = $id[2];
if($id[2]==2 or $id[2]==5)
{
    $modid = $id[3];
}
else {
    $modid = '0';
}

if($id[2] != 2 and $id[2] != 5){ //id[0]=scheduleid, id[1]=lessonid, id[2]=functionname for sigmath

	$close = "fn_signmathclear()";
	
	$sid = $id[0];
	$currentqry = $ObjDB->QueryObject("SELECT fld_id AS maxid, fld_lesson_id AS lessonid, fld_type AS type, fld_status AS status 
									  FROM itc_assignment_sigmath_master 
									  WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$uid."' AND fld_status=0 AND fld_delstatus='0' 
									  ORDER BY fld_id DESC LIMIT 0,1");
	$flag=0;
	$completed=0;
	
	if($currentqry->num_rows>0){
		$current_res = $currentqry->fetch_assoc();
		extract($current_res);
	
		//check is the lesson is abailable or not
		$chklesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
												  FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
												  WHERE a.fld_sigmath_id='".$sid."' AND a.fld_lesson_id='".$lessonid."' AND a.fld_flag='1' AND b.fld_access='1' AND b.fld_delstatus='0'");
	
		if($chklesson>0){
			$flag=1;
			if($type==1){
				$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1,0)";
			}
			else if($type==2){
				$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
				if($orientationid==0 || $orientationid=='')
					$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
				else
					$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.",1)";
			}
			else if($type==3){
				$function = "fn_startmastery1(".$sid.",".$lessonid.",".$maxid.")";
			}
			else if($type==4){
				$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
			}
			else if($type==6){
				$function = "fn_diagfinish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
			else if($type==5 or $type==7){
				$function = "fn_mastery1finish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
			else if($type==8){
				$function = "fn_mastery2finish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
		}
	}
	
	if($flag!=1){
		//get the lesson with out previously attend
		$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0'");
		if($orientationid=='') $orientationid=0;
		$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping 
												 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND ".$orientationid." NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
												 WHERE (fld_status=1 or fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
												 AND fld_delstatus='0' ) AND fld_lesson_id='".$orientationid."'");
		
		                
                $lessondiagflag=$ObjDB->SelectSingleValueInt("SELECT fld_diagnostic_flag FROM itc_class_sigmath_lessondiagnostictest_mapping where fld_sigmath_id='".$sid."' AND fld_lesson_id='".$lessonid."'");
                
                if($lessondiagflag==1)
                {
		$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1,0)";	
                }
                else if($lessondiagflag==0)
                {
                    $orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
				if($orientationid==0 || $orientationid=='')
					$function = "fn_lessonplay(".$sid.",".$lessonid.",0)";
				else
					$function = "fn_lessonplay(".$sid.",".$lessonid.",0,1)";
                    
                }
                
		if($lessonid==0 or $lessonid=='')
		{
			$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
													 FROM itc_class_sigmath_lesson_mapping 
													 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
													 WHERE (fld_status=1 or fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
													 AND fld_delstatus='0')
													 ORDER BY fld_order LIMIT 0,1");
		
			if($lessonid==0){
				$completed=1;								
			}
			if($id[3]==10)
				$function = $id[2];
			else
				$lessondiagflag=$ObjDB->SelectSingleValueInt("SELECT fld_diagnostic_flag FROM itc_class_sigmath_lessondiagnostictest_mapping where fld_sigmath_id='".$sid."' AND fld_lesson_id='".$lessonid."'");
                
                                if($lessondiagflag==1)
                                {
				$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1,0)";
		}		
                                else if($lessondiagflag==0)
                                {
                                    $orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
                                                if($orientationid==0 || $orientationid=='')
                                                        $function = "fn_lessonplay(".$sid.",".$lessonid.",0)";
                                                else
                                                        $function = "fn_lessonplay(".$sid.",".$lessonid.",0,1)";

	}	
}
	}	
}
else{ //id[0]=scheduleid, id[1]=lessonids, id[2]=testtype for math module
	$sid = $id[0];
	$lessonids = explode(',',$id[1]);
	
	//get current status
	$currentqry = $ObjDB->QueryObject("SELECT fld_id AS maxid, fld_lesson_id AS lessonid, fld_type AS type, fld_status AS status 
									  FROM itc_assignment_sigmath_master 
									  WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$uid."' AND fld_status=0 AND fld_test_type='".$id[2]."' AND fld_lesson_id IN (".$id[1].") 
									  AND fld_status<>1 AND fld_status<>2 AND fld_delstatus='0' ");
	$flag=0;
	if($currentqry->num_rows>0){
		$current_res = $currentqry->fetch_assoc();
		extract($current_res);
		if(in_array($lessonid,$lessonids))
		{
			$flag=1;
			if($type==1){
				$function = "fn_diagnosticstart(".$sid.",".$lessonid.",".$id[2].",".$id[3].")";
			}
			else if($type==2){
				$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
			}
			else if($type==3){
				$function = "fn_startmastery1(".$sid.",".$lessonid.",".$maxid.")";
			}
			else if($type==4){
				$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
			}
			else if($type==6){
				$function = "fn_diagfinish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
			else if($type==5 or $type==7){
				$function = "fn_mastery1finish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
			else if($type==8){
				$function = "fn_mastery2finish(".$sid.",".$lessonid.",0,".$maxid.")";
			}
		}
	}
	
	if($flag!=1){
		$lessonarray=array();
		$qryexistlesson = $ObjDB->QueryObject("SELECT fld_lesson_id 
											  FROM itc_assignment_sigmath_master 
											  WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='".$id[2]."' 
											  AND fld_delstatus='0'");
		if($qryexistlesson->num_rows>0){
			while($res = $qryexistlesson->fetch_assoc()){
				extract($res);
				$lessonarray[]=$fld_lesson_id;
			}
		}
		$remaininglesson = array_diff($lessonids,$lessonarray);
		$lessonid = current($remaininglesson);
		if($lessonid!=''){
			$function = "fn_diagnosticstart(".$sid.",".$lessonid.",".$id[2].",".$id[3].")";
		}		
	}
}
$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
?>
<script language="javascript" type="text/javascript">	
	$(document).ready(function () {	
	$.getScript("assignment/sigmath/assignment-sigmath-test.js");			
		$('body').css('overflow','hidden');	
		
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
				
		$('body').append('<div id="divcustomlightbox" title="Synergy ITC"><input type="hidden" value="<?php echo $modid; ?>" id="modid"><input type="hidden" id="lessonids" value="<?php echo $id[1];?>" /><input type="hidden" id="mathtype" value="<?php echo $mathtype; ?>" /><div class="btnprevclose"><p class="dialogTitleFullScr" style="width:90%"><?php echo $lessonname; ?></p><a href="javascript:void(0);" onclick="<?php if($id[3]==10) {?>closefullscreenlesson();<?php } else { ?>fn_closescreen();<?php echo $close; }?>" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"></div><div class="diviplbotto"><p class="dialogTitleFullScr" id="fottitle"></p></div></div>');
		
		$('#divcustomlightbox').css(cssObjOuter);		
		$('#divlbcontent').css(cssObjInner);		
		$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });		
		
		$('html, body').animate({scrollTop: '0px'}, 0);
		setTimeout('eval(<?php echo $function;?>)',500);
	});	
	
	$(window).resize(function() {
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
	});
</script>

<?php
	@include("footer.php");