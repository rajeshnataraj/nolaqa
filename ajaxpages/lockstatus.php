<?php
@include("sessioncheck.php");	
$oper = isset($method['oper']) ? $method['oper'] : '';
if($oper=="lockstatus" and $oper!=''){	
    
       
	$today=date('Y-m-d');
	$messagealertdet=$ObjDB->QueryObject("SELECT fld_message as messagealert, fld_from as frommsg1 FROM itc_message_master WHERE fld_readstatus='0' AND fld_to='".$uid."' AND fld_alert='1' AND fld_delstatus='0'");
	$noofmsg = $messagealertdet->num_rows;
	if($messagealertdet->num_rows>0){
		$res =$messagealertdet->fetch_assoc();
		extract($res);
			
		$frommsg=$ObjDB->SelectSingleValueInt("SELECT fld_profile_id FROM itc_user_master WHERE fld_id='".$frommsg1."' AND fld_delstatus='0'");
	}
	$message=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_message_master WHERE fld_readstatus='0' AND fld_to='".$uid."' AND fld_delstatus='0'");
	
	$calendar=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_calendar_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' AND fld_startdate='".$today."'");
	
	$classlock=$ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
											FROM itc_class_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON b.fld_class_id=a.fld_id 
											WHERE (b.fld_teacher_id='".$uid."' OR a.fld_created_by='".$uid."') AND a.fld_lock=1 AND a.fld_delstatus='0' AND b.fld_flag='1'");
	
	$lockstatus = $ObjDB->QueryObject("SELECT a.fld_id 
									  FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_class_id=b.fld_class_id 
									  LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=a.fld_schedule_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
									  LEFT JOIN itc_user_master AS e ON e.fld_id=a.fld_student_id  LEFT JOIN itc_ipl_master AS f ON f.fld_id=a.fld_lesson_id 
									  WHERE a.fld_type=5 AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1'  AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' 
									  AND f.fld_delstatus='0' AND a.fld_lockstatus='0' AND a.fld_delstatus='0' AND a.fld_status='0'");
	$lock=0;
	if($lockstatus->num_rows>0){
		while($res =$lockstatus->fetch_assoc()){
			extract($res);
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_lockstatus='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$fld_id."'");
		}
		$lock=1;							
	}
	echo $lock."~".$message."~".$calendar."~".$classlock."~".$messagealert."~".$frommsg."~".$noofmsg;
        
      
}

	@include("footer.php");
