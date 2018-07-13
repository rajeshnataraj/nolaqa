<?php
@include("sessioncheck.php");
	
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Save Read Status ---*/
if($oper=="savereadstatus" and $oper != " " )
{
	$missionid = isset($method['expid']) ? $method['expid'] : '0';
	$destinationid = isset($method['destid']) ? $method['destid'] : '0';
	$taskid = isset($method['taskid']) ? $method['taskid'] : '0';
	$resourceid = isset($method['resid']) ? $method['resid'] : '0';
	$uid = isset($method['userid']) ? $method['userid'] : '0';
	$uid1 = isset($method['userid1']) ? $method['userid1'] : '0';
	$schid = isset($method['schid']) ? $method['schid'] : '0';
	$schtype = isset($method['schtype']) ? $method['schtype'] : '0';
	
	$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$resourceid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");

	if($cnt===0)
	{
		$ObjDB->NonQuery("INSERT INTO itc_mis_res_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date) 
									VALUES('".$missionid."', '".$destinationid."', '".$taskid."', '".$resourceid."', '".$uid."', '".$schid."', '".$schtype."', '1', '".$uid."', '".$date."')");
	}
	else
	{
		$ObjDB->NonQuery("UPDATE itc_mis_res_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_res_id='".$resourceid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
	}
	
	if($uid1!='' or $uid1!=0)
	{
		$cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$resourceid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
	
		if($cnt1===0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_mis_res_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date) 
										VALUES('".$missionid."', '".$destinationid."', '".$taskid."', '".$resourceid."', '".$uid1."', '".$schid."', '".$schtype."', '1', '".$uid1."', '".$date."')");
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_mis_res_play_track SET fld_read_status='1', fld_updated_by='".$uid1."', fld_updated_date='".$date."' WHERE fld_res_id='".$resourceid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
		}
	}
}

if($oper=="inserttpt" and $oper != " " )
{
    $destid = isset($method['destid']) ? $method['destid'] : '0';
    $taskid = isset($method['taskid']) ? $method['taskid'] : '0';
    $taskguid = isset($method['taskguid']) ? $method['taskguid'] : '0';
    $schid = isset($method['schid']) ? $method['schid'] : '0';
    $misid = isset($method['misid']) ? $method['misid'] : '0';
     
    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
    if($cnt===0)
    {
        $taskguid = $ObjDB->SelectSingleValueInt("SELECT fld_task_id FROM itc_mis_task_master WHERE fld_id='".$taskid."' AND fld_flag='1' AND fld_delstatus='0'");
        $ObjDB->NonQuery("INSERT INTO itc_mis_task_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
                            VALUES('".$misid."', '".$destid."', '".$taskid."', '".$uid."', '".$schid."','18', '0', '".$uid."', '".$date."', '".$taskguid."')");
    }
}

if($oper=="expteststatus" and $oper != " " )
{
    $stuid = $uid;
    $schid = isset($method['schid']) ? $method['schid'] : '0';
    $schtype = isset($method['schtype']) ? $method['schtype'] : '0';
    $missionid = isset($method['misid']) ? $method['misid'] : '0';
    $preposttestid = isset($method['testid']) ? $method['testid'] : '0';
    
    $exptestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_testplay_track WHERE fld_student_id='".$stuid."' AND fld_delstatus='0' AND fld_exp_test_id='".$preposttestid."'");
    
    if($exptestcnt =='0'){
        $ObjDB->NonQuery("INSERT INTO itc_mis_testplay_track(fld_student_id, fld_schedule_id, fld_schedule_type, fld_mis_id, fld_exp_test_id, fld_read_status, fld_created_by, fld_created_date) 
                                                                VALUES('".$stuid."', '".$schid."', '".$schtype."', '".$missionid."', '".$preposttestid."', '1', '".$stuid."', '".$date."')");
    }
    else
    {
        $ObjDB->NonQuery("UPDATE itc_mis_testplay_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_mis_id='".$missionid."' AND fld_exp_test_id='".$preposttestid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
    }
}

if($oper=="destteststatus" and $oper != " " )
{
    $stuid = $uid;
    $schid = isset($method['schid']) ? $method['schid'] : '0';
    $schtype = isset($method['schtype']) ? $method['schtype'] : '0';
    $missionid = isset($method['misid']) ? $method['misid'] : '0';
    $tdestid = isset($method['destid']) ? $method['destid'] : '0';
    $preposttestid = isset($method['preposttestid']) ? $method['preposttestid'] : '0';
    
    $desttestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_dest_testplay_track WHERE fld_student_id='".$stuid."' AND fld_delstatus='0' AND fld_dest_test_id='".$preposttestid."'");
    
    if($desttestcnt =='0'){
        $ObjDB->NonQuery("INSERT INTO itc_mis_dest_testplay_track(fld_student_id, fld_schedule_id, fld_schedule_type, fld_mis_id, fld_dest_id, fld_dest_test_id, fld_read_status, fld_created_by, fld_created_date) 
                                                                VALUES('".$stuid."', '".$schid."', '".$schtype."', '".$missionid."', '".$tdestid."', '".$preposttestid."', '1', '".$stuid."', '".$date."')");
    }
    else
    {
        $ObjDB->NonQuery("UPDATE itc_mis_dest_testplay_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$tdestid."' AND fld_dest_test_id='".$preposttestid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
    }
}

if($oper=="taskteststatus" and $oper != " " )
{
    $stuid = $uid;
    $schid = isset($method['schid']) ? $method['schid'] : '0';
    $schtype = isset($method['schtype']) ? $method['schtype'] : '0';
    $missionid = isset($method['misid']) ? $method['misid'] : '0';
    $ttaskid = isset($method['taskid']) ? $method['taskid'] : '0';
    $tdestid = isset($method['destid']) ? $method['destid'] : '0';
    $preposttestid = isset($method['preposttestid']) ? $method['preposttestid'] : '0';
    
    $tasktestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_testplay_track WHERE fld_student_id='".$stuid."' AND fld_delstatus='0' AND fld_task_test_id='".$preposttestid."'");
    
    
    if($tasktestcnt =='0'){
        $ObjDB->NonQuery("INSERT INTO itc_mis_task_testplay_track(fld_student_id, fld_schedule_id, fld_schedule_type, fld_mis_id, fld_task_id, fld_dest_id, fld_task_test_id, fld_read_status, fld_created_by, fld_created_date) 
                                                                VALUES('".$stuid."', '".$schid."', '".$schtype."', '".$missionid."', '".$ttaskid."', '".$tdestid."', '".$preposttestid."', '1', '".$stuid."', '".$date."')");
    }
    else
    {
        $ObjDB->NonQuery("UPDATE itc_mis_task_testplay_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$ttaskid."' AND fld_task_test_id='".$preposttestid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
    }
}

if($oper=="resteststatus" and $oper != " " )
{
    $stuid = $uid;
    $schid = isset($method['schid']) ? $method['schid'] : '0';
    $schtype = isset($method['schtype']) ? $method['schtype'] : '0';
    $missionid = isset($method['misid']) ? $method['misid'] : '0';
    $tresid = isset($method['resid']) ? $method['resid'] : '0';
    $ttaskid = isset($method['taskid']) ? $method['taskid'] : '0';
    $tdestid = isset($method['destid']) ? $method['destid'] : '0';
    $preposttestid = isset($method['preposttestid']) ? $method['preposttestid'] : '0';
    
    $restestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_testplay_track WHERE fld_student_id='".$stuid."' AND fld_delstatus='0' AND fld_res_test_id='".$preposttestid."'");
    
    
    if($restestcnt =='0'){
        $ObjDB->NonQuery("INSERT INTO itc_mis_res_testplay_track(fld_student_id, fld_schedule_id, fld_schedule_type, fld_mis_id,fld_res_id, fld_task_id, fld_dest_id, fld_res_test_id, fld_read_status, fld_created_by, fld_created_date) 
                                                                VALUES('".$stuid."', '".$schid."', '".$schtype."', '".$missionid."', '".$tresid."', '".$ttaskid."', '".$tdestid."', '".$preposttestid."', '1', '".$stuid."', '".$date."')");
    }
    else
    {
        $ObjDB->NonQuery("UPDATE itc_mis_res_testplay_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_res_id='".$tresid."' AND fld_res_test_id='".$preposttestid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
    }
}


if($oper=="savereadstatusrestest" and $oper != " " )
{
	$missionid = isset($method['misid']) ? $method['misid'] : '0';
	$destid = isset($method['destid']) ? $method['destid'] : '0';
	$taskid = isset($method['taskid']) ? $method['taskid'] : '0';
	$resid = isset($method['resid']) ? $method['resid'] : '0';
	$schid = isset($method['schid']) ? $method['schid'] : '0';
	$schtype = isset($method['schtype']) ? $method['schtype'] : '0';
        
        if($schtype ==='18'){
            $tablename = "itc_class_indasmission_master";
        }
        if($schtype ==='23'){
            $tablename = "itc_class_rotation_mission_mastertemp";
        }
        
        $shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from ".$tablename." where fld_id='".$schid."' and fld_delstatus='0'");
        
        
        $reslastid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                                    FROM itc_mis_resource_master As a
                                                    LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_res_id
                                                    WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$shlteacherid."' AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) order by a.fld_id desc");
         if($resid == $reslastid){
            
            $resposttest = $ObjDB->SelectSingleValue("select a.fld_id from itc_test_master as a
                                                            left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                            where a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                            and b.fld_ttaskid='".$taskid."' and b.fld_tresid='".$reslastid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status='1' and a.fld_delstatus ='0'");
            if($resposttest !=""){
                $restestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_testplay_track WHERE fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_res_test_id='".$resposttest."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                if($restestcntpost =='1'){
                    $ObjDB->NonQuery("UPDATE itc_mis_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'"); 
                }
                
                $tasklastid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                                    FROM itc_mis_task_master as a 
                                                    LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                                    WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$shlteacherid."' AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_delstatus='0'  order by a.fld_id desc");
            
                $taskposttest = $ObjDB->SelectSingleValue("select a.fld_id AS posttestid from itc_test_master as a
                                                            left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                            where a.fld_destid ='".$destid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                            and b.fld_ttaskid='".$tasklastid."' and b.fld_tresid='0' and b.fld_tdestid='".$destid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status='1' and a.fld_delstatus ='0'");
                if($taskposttest !=""){
                    $tasktestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_testplay_track WHERE fld_dest_id='".$destid."' AND fld_task_id='".$tasklastid."' AND fld_task_test_id='".$taskposttest."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                    if($tasktestcntpost =='1'){
                        $destcount1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_status WHERE fld_mis_id='".$missionid."' AND fld_dest_id='".$destinationid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$shlteacherid."' AND fld_user_id='".$indid."'");
                        if($destcount1 == 1){
                            $fieldtask = 'CONCAT("\'",a.fld_id,"\'")';
                            $grouptaskids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask.") 
                                                                        FROM itc_mis_task_master as a 
                                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                                                        WHERE a.fld_dest_id='".$destinationid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$shlteacherid."' AND b.fld_user_id='".$indid."' AND b.fld_status='1' AND a.fld_delstatus='0'");
                        }
                        else{
                            $fieldtask = 'CONCAT("\'",a.fld_id,"\'")';
                            $grouptaskids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask.") 
                                                                        FROM itc_mis_task_master as a 
                                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                                                        WHERE a.fld_dest_id='".$destinationid."' AND b.fld_school_id='0' AND b.fld_user_id='0' AND b.fld_status='1' AND a.fld_delstatus='0'");
                        }
                        $taskreadcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id IN (".$grouptaskids.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_read_status='1'");
                        if($taskreadcnt === sizeof(explode(',',$grouptaskids))){
                        $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                    }
                }
                }
                else{
                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                }
                
            }
         }
}

if($oper=="savereadstatustasktest" and $oper != " " )
{
	$missionid = isset($method['misid']) ? $method['misid'] : '0';
	$destid = isset($method['destid']) ? $method['destid'] : '0';
	$taskid = isset($method['taskid']) ? $method['taskid'] : '0';
	$resid = isset($method['resid']) ? $method['resid'] : '0';
	$schid = isset($method['schid']) ? $method['schid'] : '0';
	$schtype = isset($method['schtype']) ? $method['schtype'] : '0';
        
        if($schtype ==='18'){
            $tablename = "itc_class_indasmission_master";
        }
        if($schtype ==='23'){
            $tablename = "itc_class_rotation_mission_mastertemp";
        }

        $shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from ".$tablename." where fld_id='".$schid."' and fld_delstatus='0'");
        
        $tasklastid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                            FROM itc_mis_task_master as a 
                                            LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                            WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$shlteacherid."' AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_delstatus='0'  order by a.fld_id desc");
        if($taskid == $tasklastid){
            $taskposttest = $ObjDB->SelectSingleValue("select a.fld_id AS posttestid from itc_test_master as a
                                                        left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                        where a.fld_destid ='".$destid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                        and b.fld_ttaskid='".$tasklastid."' and b.fld_tresid='0' and b.fld_tdestid='".$destid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status='1' and a.fld_delstatus ='0'");
            if($taskposttest !=""){
                $tasktestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_testplay_track WHERE fld_dest_id='".$destid."' AND fld_task_id='".$tasklastid."' AND fld_task_test_id='".$taskposttest."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                if($tasktestcntpost =='1'){
                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                }
            }
        }
}
@include("footer.php");