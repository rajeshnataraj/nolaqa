<?php
@include("sessioncheck.php");

$oper = (isset($method['oper'])) ? $method['oper'] : 0;


if($oper == "teacheralert" and $oper != '')
{
    
error_reporting(E_ALL);
ini_set('display_errors', '1');
$qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS id
                                FROM itc_class_master 
                                WHERE fld_delstatus='0' AND fld_archive_class ='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
                                        AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
                                        AND fld_flag='1')) order by classname asc");
    if($qryclass->num_rows>0){
        $dot=0;
        $j=0;
        $k=0;
        
        while($rowclass = $qryclass->fetch_assoc())
        {
            extract($rowclass);
            //$id=266;
            $temflag=0;
            //$expstuflaf=0;
            $qrystudent= $ObjDB->QueryObject("SELECT fld_id AS studentid,CONCAT(fld_lname,' ',fld_fname)AS studentname,fld_username as username 
                                            FROM itc_user_master 
                                            WHERE fld_id IN (SELECT fld_student_id FROM itc_class_student_mapping WHERE fld_class_id='".$id."' 
                                                   AND fld_flag='1') AND (fld_school_id = '".$schoolid."' AND fld_user_id='".$indid."') 
                                                   AND fld_profile_id=10 AND fld_activestatus='1' AND fld_delstatus='0' 
                                            ORDER BY studentname");


            if($qrystudent->num_rows>0){
                while($rowstudent = $qrystudent->fetch_assoc())
                {
                    extract($rowstudent);
                    $expstuflaf=0;
                    $qryschedule = $ObjDB->QueryObject ("SELECT a.fld_id AS schid, a.fld_schedule_name AS sname,19 AS schtype, 'Expedition Schedule' AS typename,'' as wcalock, a.fld_class_id AS classid,
                            a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                            FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b 
                                    ON b.fld_schedule_id=a.fld_id
                            WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                            GROUP BY schid

                            UNION ALL
                            SELECT a.fld_id AS schid, a.fld_schedule_name AS sname,20 AS schtype, 'Module/Expedition Schedule' AS typename,'' as wcalock, a.fld_class_id AS classid,
                                    a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                            FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b 
                                    ON b.fld_schedule_id=a.fld_id
                            WHERE a.fld_class_id='".$id."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                            GROUP BY schid		

                            UNION ALL
                            SELECT a.fld_id AS schid, a.fld_schedule_name AS sname,15 AS schtype,'Whole Class Assignment - Expedition' AS typename, a.fld_lock as wcalock,  a.fld_class_id AS classid, 
                                    a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                            FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON b.fld_schedule_id=a.fld_id
                            WHERE a.fld_class_id='".$id."'  AND a.fld_delstatus='0' AND b.fld_flag='1' 
                            GROUP BY schid");
                    if($qryschedule->num_rows>0){
                        while($rowschedule = $qryschedule->fetch_assoc())
                        {
                            extract($rowschedule);

                                if($schtype ==='15'){
                                    $tablename = "itc_class_indasexpedition_master"; // Expedition
                                    $qryexpdet = $ObjDB->QueryObject("select a.fld_exp_id as expid,b.fld_student_id as stuid from itc_class_indasexpedition_master as a
                                                                    left join itc_class_exp_student_mapping as b on a.fld_id=b.fld_schedule_id
                                                                    where b.fld_schedule_id='".$schid."'  and fld_scheduletype='".$schtype."' and b.fld_student_id='".$studentid."' and b.fld_flag=1 and a.fld_flag=1");
                                }
                                if($schtype ==='19'){
                                    $tablename = "itc_class_rotation_expschedule_mastertemp"; // Expedition sch
                                    $qryexpdet = $ObjDB->QueryObject("select b.fld_expedition_id as expid,b.fld_student_id as stuid from itc_class_rotation_expschedule_mastertemp as a
                                                                    left join itc_class_rotation_expschedulegriddet as b on a.fld_id=b.fld_schedule_id
                                                                    where b.fld_schedule_id='".$schid."' and b.fld_student_id='".$studentid."' and b.fld_flag=1 and a.fld_flag=1");
                                }
                                if($schtype ==='20'){
                                    $tablename = "itc_class_rotation_modexpschedule_mastertemp"; // Expedition and module Sch
                                    $qryexpdet = $ObjDB->QueryObject("select b.fld_module_id as expid,b.fld_student_id as stuid from itc_class_rotation_modexpschedule_mastertemp as a
                                                                    left join itc_class_rotation_modexpschedulegriddet as b on a.fld_id=b.fld_schedule_id
                                                                    where b.fld_schedule_id='".$schid."'  and fld_type='2' and b.fld_student_id='".$studentid."' and b.fld_flag=1 and a.fld_flag=1");
                                }
                                if($qryexpdet->num_rows>0){
                                    
                                    while($rowexpdet = $qryexpdet->fetch_assoc())
                                    {
                                        extract($rowexpdet);
                                        //echo "hi".$expid."/".$stuid."<br>";

                                        if($stuid!="" and $stuid!=0){
                                            //Test Alert for sudent starts
                                            $totalwholetest="";
                                            $breakflag=0;
                                            $wholetest="";
                                            $exptest="";
                                            $tasktest="";
                                            $restest="";
                                            $destposttest="";
                                            $taskposttest="";
                                            $resposttest="";
                                            $expposttest="";
                                            
                                            
                                            $schooltstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expid."'  
                                                                                                    AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                                            
                                            if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                
                                                $destvar = 'CONCAT("\'",a.fld_id,"\'")';
                                                $groupdestids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$destvar.")
                                                                                        FROM itc_exp_destination_master AS a
                                                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                        WHERE a.fld_exp_id='".$expid."' AND b.fld_school_id='0' and fld_profile_id='2'
                                                                                        AND b.fld_user_id='0' AND b.fld_status IN(1,2) AND a.fld_delstatus='0'");
                                            }
                                            else{
                                                $destvar = 'CONCAT("\'",a.fld_id,"\'")';
                                                $groupdestids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$destvar.")
                                                                                        FROM itc_exp_destination_master AS a
                                                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                        WHERE a.fld_exp_id='".$expid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                        AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_delstatus='0'");
                                            }
                                            if($groupdestids==""){
                                                $groupdestids=0;
                                            }
                                            //echo "hi".$groupdestids."~";
                                            $firstcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id IN(".$groupdestids.") AND fld_schedule_id='".$schid."' 
                                                                AND fld_delstatus='0' AND fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."'");
                                            
                                            
                                            $dateexprsch = $ObjDB->SelectSingleValueInt("select count(fld_id) from ".$tablename." where fld_id='".$schid."' and fld_scheduletype='".$schtype."' 
                                                                                        and fld_enddate < CURDATE()and fld_class_id='".$id."' and fld_delstatus='0' and fld_flag=1");
                                            
                                            
                                            if($firstcnt>0 or $dateexprsch==1){
                                                $qrystudesttest = $ObjDB->QueryObject("SELECT a.fld_id AS destid 
                                                                                        FROM itc_exp_destination_master AS a
                                                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                        WHERE a.fld_exp_id='".$expid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                        AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_delstatus='0' ORDER BY a.fld_order");



                                                 if($qrystudesttest->num_rows>0) {
                                                    $b=1;
                                                    while($rowqrytest = $qrystudesttest->fetch_assoc()){
                                                    extract($rowqrytest);

                                                        $desttestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$destid."' AND fld_schedule_id='".$schid."' 
                                                                                        AND fld_delstatus='0' AND fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."'");

                                                        if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                            $destidopl = $ObjDB->SelectSingleValue("SELECT count(a.fld_id) FROM itc_exp_destination_master As a
                                                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                            WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND a.fld_id='".$destid."' AND b.fld_school_id='0' and fld_profile_id='2' 
                                                                                AND b.fld_user_id='0' AND b.fld_status=2");
                                                        }
                                                        else{
                                                            $destidopl = $ObjDB->SelectSingleValue("SELECT count(a.fld_id) FROM itc_exp_destination_master As a
                                                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                            WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND a.fld_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                AND b.fld_user_id='".$indid."' AND b.fld_status=2");
                                                        }

                                                        if($desttestcnt==1 or $destidopl==1 or $dateexprsch==1){
                                                            // Exppre test starts
                                                            if($b==1){
                                                                $expretestid = $ObjDB->SelectSingleValueInt("select a.fld_pretest 
                                                                                                    from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where a.fld_tdestid='0' and a.fld_ttaskid='0' and a.fld_tresid=0 
                                                                                                    and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                if($expretestid!=''){
                                                                    $expprecomsts = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track 
                                                                                                               WHERE fld_exp_id='".$expid."' AND fld_exp_test_id='".$expretestid."' AND fld_schedule_id='".$schid."' 
                                                                                                                   AND fld_schedule_type='".$schtype."' AND fld_read_status='1' and fld_retake='0' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                    if($expprecomsts==0){
                                                                        $exppretechcheck = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                             AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$expretestid."' AND fld_delstatus = '0'");
                                                                        if(trim($exppretechcheck)==''){
                                                                            $exptest=$expretestid;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            //echo "et".$exptest;

                                                            // Exppre test ends

                                                            // Dest post test staerts

                                                            $destposttestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$destid."' AND fld_schedule_id='".$schid."' 
                                                                                        AND fld_delstatus='0' AND fld_read_status='1' AND fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."'");

                                                            if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                $destidopl1 = $ObjDB->SelectSingleValue("SELECT count(a.fld_id) FROM itc_exp_destination_master As a
                                                                                LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND a.fld_id='".$destid."' AND b.fld_school_id='0' and fld_profile_id='2' 
                                                                                    AND b.fld_user_id='0' AND b.fld_status=2");
                                                            }
                                                            else{
                                                                $destidopl1 = $ObjDB->SelectSingleValue("SELECT count(a.fld_id) FROM itc_exp_destination_master As a
                                                                                LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND a.fld_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                    AND b.fld_user_id='".$indid."' AND b.fld_status=2");
                                                            }

                                                            $destposttest="";
                                                            if($destposttestcnt==1 or $destidopl1==1 or $dateexprsch==1){

                                                                $destptestid = $ObjDB->SelectSingleValueInt("select a.fld_posttest 
                                                                                                            from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where a.fld_texpid ='".$expid."' and a.fld_tdestid ='".$destid."' 
                                                                                                            and a.fld_ttaskid ='0' and a.fld_tresid ='0' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                if($destptestid!='' and $destptestid!=0){
                                                                   $destpostretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track 
                                                                                                                WHERE fld_dest_id='".$destid."' AND fld_dest_test_id='".$destptestid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."'  
                                                                                                                    AND fld_read_status='1' AND fld_retake='0' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                   if($destpostretake==0){
                                                                        $destposttechcheck = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$destptestid."' AND fld_delstatus = '0'");
                                                                        if(trim($destposttechcheck)==''){
                                                                            $destposttest=$destptestid;
                                                                        }
                                                                   }

                                                               }
                                                               //echo "dpt".$destposttest;
                                                            }
                                                            // Dest post test ends

                                                            //Exp post test starts
                                                            $destlastid = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_exp_destination_master As a
                                                                                                LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                                WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                                    AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) order by a.fld_id desc limit 0,1");

                                                            if($destlastid=='' or $destlastid=='0'){
                                                                $destlastid = $ObjDB->SelectSingleValue("SELECT a.fld_id FROM itc_exp_destination_master As a
                                                                                                LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                                WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND b.fld_school_id='0' and fld_profile_id='2'
                                                                                                    AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) order by a.fld_id desc limit 0,1");



                                                            }
                                                            if($destlastid==$destid){
                                                                $lastdestposttestcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$destlastid."' AND fld_schedule_id='".$schid."' 
                                                                                            AND fld_delstatus='0' AND fld_read_status='1' AND fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."'");
                                                                
                                                                

                                                                if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                    $lastdestidopl = $ObjDB->SelectSingleValue("SELECT count(a.fld_id) FROM itc_exp_destination_master As a
                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                    WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND a.fld_id='".$destlastid."' AND b.fld_school_id='0' and fld_profile_id='2' 
                                                                                        AND b.fld_user_id='0' AND b.fld_status=2");
                                                                }
                                                                else{
                                                                    $lastdestidopl = $ObjDB->SelectSingleValue("SELECT count(a.fld_id) FROM itc_exp_destination_master As a
                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_dest_id
                                                                                    WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0' AND a.fld_id='".$destlastid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                        AND b.fld_user_id='".$indid."' AND b.fld_status=2");
                                                                }


                                                                $expposttest="";
                                                                if($lastdestposttestcnt==1 or $lastdestidopl==1 or $dateexprsch==1){
                                                                    $expposttestid = $ObjDB->SelectSingleValueInt("select a.fld_posttest 
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where a.fld_tdestid='0' and a.fld_ttaskid='0' and a.fld_tresid=0 
                                                                                                        and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                    if($expposttestid!="" and $expposttestid!=0){
                                                                        $exppostretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track 
                                                                                                                   WHERE fld_exp_id='".$expid."' AND fld_exp_test_id='".$expposttestid."' AND fld_schedule_id='".$schid."' 
                                                                                                                       AND fld_schedule_type='".$schtype."' AND fld_read_status='1' and fld_retake='0' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                       if($exppostretake==0){
                                                                            $expposttechcheck = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$expposttestid."' AND fld_delstatus = '0'");
                                                                            if(trim($expposttechcheck)==''){
                                                                                $expposttest=$expposttestid;
                                                                            }
                                                                       }

                                                                    }
                                                                    //echo "expt".$expposttest;
                                                                }
                                                            }
                                                            //Exp post test Ends

                                                            if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                $selecttasks = $ObjDB->QueryObject("SELECT a.fld_id AS taskid, b.fld_status as taskstaus 
                                                                                        FROM itc_exp_task_master AS a
                                                                                        LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                                                                        WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='0' and fld_profile_id='2' 
                                                                                        AND b.fld_user_id='0' AND b.fld_status IN(1,2) AND a.fld_delstatus='0' ORDER BY a.fld_order");
                                                            }
                                                            else{
                                                                $selecttasks = $ObjDB->QueryObject("SELECT a.fld_id AS taskid, b.fld_status as taskstaus 
                                                                                            FROM itc_exp_task_master AS a
                                                                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                                                                            WHERE a.fld_dest_id='".$destid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                            AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_delstatus='0' ORDER BY a.fld_order");
                                                            }
                                                            $t=1;
                                                            if($selecttasks->num_rows>0) {
                                                                $desttest="";
                                                                while ($rowselecttasks = $selecttasks->fetch_assoc()) {
                                                                extract($rowselecttasks);
                                                                    if($t==1){
                                                                        $destpretestid = $ObjDB->SelectSingleValueInt("select a.fld_pretest 
                                                                                                            from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where a.fld_texpid ='".$expid."' and a.fld_tdestid ='".$destid."' 
                                                                                                                and a.fld_ttaskid ='0' and a.fld_tresid ='0' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                        if($destpretestid!='' and $destpretestid!=0){
                                                                           $destpreretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track 
                                                                                                                        WHERE fld_dest_id='".$destid."' AND fld_dest_test_id='".$destpretestid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."'  
                                                                                                                            AND fld_read_status='1' AND fld_retake='0' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                           if($destpreretake==0){
                                                                                $destpretechcheck1 = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$uid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$destpretestid."' AND fld_delstatus = '0'");
                                                                                if(trim($destpretechcheck1)==''){
                                                                                   $desttest=$destpretestid;
                                                                                }
                                                                           }

                                                                       }
                                                                    }
                                                                    //echo "dt".$desttest;

                                                                    //Task post test starts
                                                                    $taskpostcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_task_id='".$taskid."' 
                                                                                                        AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                    $taskidopl=0;
                                                                    //if($taskstaus==2){
                                                                        if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                        $taskidopl = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) 
                                                                                                FROM itc_exp_task_master AS a
                                                                                                LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                                                                                WHERE a.fld_dest_id='".$destid."' and a.fld_id='".$taskid."' AND b.fld_school_id='0' and fld_profile_id='2' 
                                                                                                AND b.fld_user_id='0' AND b.fld_status IN(2) AND a.fld_delstatus='0' ORDER BY a.fld_order");
                                                                        }
                                                                        else{
                                                                        $taskidopl = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) 
                                                                                                    FROM itc_exp_task_master AS a
                                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_task_id
                                                                                                    WHERE a.fld_dest_id='".$destid."' and a.fld_id='".$taskid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                                        AND b.fld_user_id='".$indid."' AND b.fld_status IN(2) AND a.fld_delstatus='0' ORDER BY a.fld_order");
                                                                        }
                                                                    //}

                                                                    $taskposttest="";
                                                                    $lastresposttest="";
                                                                    if($taskpostcnt==1 or $taskidopl==1 or $dateexprsch==1){
                                                                        $taskposttestid = $ObjDB->SelectSingleValueInt("select a.fld_posttest 
                                                                                                                from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where a.fld_ttaskid='".$taskid."' and a.fld_tresid='0' and a.fld_tdestid='".$destid."' 
                                                                                                                and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                        if($taskposttestid!="" and $taskposttestid!=0){

                                                                            $taskpostretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_testplay_track 
                                                                                                                        WHERE fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_task_test_id='".$taskposttestid."' 
                                                                                                                            AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' 
                                                                                                                                AND fld_read_status='1' AND fld_retake='0' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                            if($taskpostretake==0){
                                                                                $taskposttechcheck1 = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$taskposttestid."' AND fld_delstatus = '0'");
                                                                                if(trim($taskposttechcheck1)==''){
                                                                                   $taskposttest=$taskposttestid;
                                                                                }
                                                                            }
                                                                        }
                                                                        //echo "tpt".$taskposttest;

                                                                        // Last resources check each task starts
                                                                        if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                            $lastresid = $ObjDB->SelectSingleValueInt("SELECT a.fld_id AS resid 
                                                                                                                    FROM itc_exp_resource_master AS a
                                                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                                                                    WHERE a.fld_dest_id='".$destid."' AND a.fld_task_id='".$taskid."' AND b.fld_school_id='0' and fld_profile_id='2' 
                                                                                                                        AND b.fld_user_id='0' AND b.fld_status IN(1,2) AND a.fld_typeof_res='2' AND a.fld_delstatus='0' ORDER BY a.fld_order desc limit 0,1");
                                                                        }
                                                                        else{
                                                                            $lastresid = $ObjDB->SelectSingleValueInt("SELECT a.fld_id AS resid 
                                                                                                                    FROM itc_exp_resource_master AS a
                                                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                                                                    WHERE a.fld_dest_id='".$destid."' AND a.fld_task_id='".$taskid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."' 
                                                                                                                        AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_typeof_res='2' AND a.fld_delstatus='0' ORDER BY a.fld_order desc limit 0,1");
                                                                        }


                                                                        $lastresposttestid = $ObjDB->SelectSingleValueInt("select a.fld_posttest
                                                                                                                                from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where a.fld_tdestid='".$destid."' and a.fld_ttaskid='".$taskid."' and a.fld_tresid='".$lastresid."' 
                                                                                                                                and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                        if($lastresposttestid!="" and $lastresposttestid!=0 ){
                                                                            $lastrespostretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track 
                                                                                                                                WHERE fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$lastresid."' AND fld_res_test_id='".$lastresposttestid."' 
                                                                                                                                    AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND fld_read_status='1' AND fld_retake='0'
                                                                                                                                    AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                            if($lastrespostretake==0){
                                                                                $lresposttechcheck1 = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$lastresposttestid."' AND fld_delstatus = '0'");
                                                                                if(trim($lresposttechcheck1)==''){
                                                                                  $lastresposttest=$lastresposttestid;
                                                                                }
                                                                            }

                                                                        }
                                                                        //echo "lrpt".$lastresposttest;
                                                                        // Last resources check each task starts

                                                                    }
                                                                    //Task post test ends
                                                                    $beforeresid="";

                                                                    if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                        $selectres = $ObjDB->QueryObject("SELECT a.fld_id AS resid 
                                                                                    FROM itc_exp_resource_master AS a
                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                                    WHERE a.fld_dest_id='".$destid."' AND a.fld_task_id='".$taskid."' AND b.fld_school_id='0' and fld_profile_id='2'  
                                                                                        AND b.fld_user_id='0' AND b.fld_status IN(1,2) AND a.fld_typeof_res='2' AND a.fld_delstatus='0' ORDER BY a.fld_order");
                                                                    }
                                                                    else{
                                                                        $selectres = $ObjDB->QueryObject("SELECT a.fld_id AS resid 
                                                                                    FROM itc_exp_resource_master AS a
                                                                                    LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                                    WHERE a.fld_dest_id='".$destid."' AND a.fld_task_id='".$taskid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."'
                                                                                        AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_typeof_res='2' AND a.fld_delstatus='0' ORDER BY a.fld_order");
                                                                    }

                                                                    if($selectres->num_rows>0) {
                                                                        $r=1;
                                                                        $tasktest="";
                                                                        while ($rowselectres = $selectres->fetch_assoc()) {
                                                                        extract($rowselectres);
                                                                            $restest="";
                                                                            $resposttest="";

                                                                            if($r==1){
                                                                            $r=$r+1; 
                                                                                $taskpretestid = $ObjDB->SelectSingleValueInt("select a.fld_pretest 
                                                                                                                from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where a.fld_ttaskid='".$taskid."' and a.fld_tresid='0' and a.fld_tdestid='".$destid."' 
                                                                                                                and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                                if($taskpretestid!="" and $taskpretestid!=0){

                                                                                    $taskpreretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_testplay_track 
                                                                                                                                WHERE fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_task_test_id='".$taskpretestid."' 
                                                                                                                                    AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' 
                                                                                                                                        AND fld_read_status='1' AND fld_retake='0' AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                                    if($taskpreretake==0){
                                                                                        $taskpretechcheck1 = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$taskpretestid."' AND fld_delstatus = '0'");
                                                                                        if(trim($taskpretechcheck1)==''){
                                                                                          $tasktest=$taskpretestid;
                                                                                        }
                                                                                    }
                                                                                }
                                                                                //echo "tt".$tasktest;
                                                                            }
                                                                            else{
                                                                                $exptest="";
                                                                                $desttest="";
                                                                                $tasktest="";
                                                                                $destposttest="";
                                                                                $taskposttest="";
                                                                                $resposttest="";
                                                                                $lastresposttest="";
                                                                                $expposttest="";


                                                                                //Res post test starts
                                                                                $chkpostrescount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid."'  AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' 
                                                                                                                                AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                                if($chkpostrescount==1){
                                                                                    $resposttestid = $ObjDB->SelectSingleValueInt("select a.fld_posttest
                                                                                                                                from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where a.fld_tdestid='".$destid."' and a.fld_ttaskid='".$taskid."' and a.fld_tresid='".$beforeresid."' 
                                                                                                                                and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                                    if($resposttestid!="" and $resposttestid!=0){
                                                                                        $respostretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track 
                                                                                                                                            WHERE fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$beforeresid."' AND fld_res_test_id='".$resposttestid."' 
                                                                                                                                                AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND fld_read_status='1' AND fld_retake='0'
                                                                                                                                                AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                                        if($respostretake==0){
                                                                                            $resposttechcheck = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$resposttestid."' AND fld_delstatus = '0'");
                                                                                            if(trim($resposttechcheck)==''){
                                                                                                $resposttest=$resposttestid;
                                                                                            }
                                                                                        }

                                                                                    }
                                                                                   //echo  "rpt".$resposttest;
                                                                                }
                                                                                //Res post test ends
                                                                            }
                                                                            $chkprescount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id='".$resid."'  AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' 
                                                                                                                                AND fld_delstatus='0' AND fld_student_id='".$stuid."'");

                                                                            if($schooltstatus=='' or $schooltstatus=='0' or $schooltstatus==NULL){
                                                                                $checkresopl = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
                                                                                            FROM itc_exp_resource_master AS a
                                                                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                                            WHERE a.fld_dest_id='".$destid."' AND a.fld_task_id='".$taskid."' AND b.fld_res_id='".$resid."' AND b.fld_school_id='0' and fld_profile_id='2'  
                                                                                                AND b.fld_user_id='0' AND b.fld_status='2' AND a.fld_typeof_res='2' AND a.fld_delstatus='0'");
                                                                            }
                                                                            else{
                                                                                $checkresopl = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) 
                                                                                            FROM itc_exp_resource_master AS a
                                                                                            LEFT JOIN itc_exp_res_status as b on a.fld_id=b.fld_res_id
                                                                                            WHERE a.fld_dest_id='".$destid."' AND a.fld_task_id='".$taskid."' AND b.fld_res_id='".$resid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$uid."'
                                                                                                AND b.fld_user_id='".$indid."' AND b.fld_status='2' AND a.fld_typeof_res='2' AND a.fld_delstatus='0'");
                                                                            }

                                                                            if($chkprescount==1 or $checkresopl==1 or $dateexprsch==1){
                                                                                $respretestid = $ObjDB->SelectSingleValueInt("select a.fld_pretest
                                                                                                                                from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where a.fld_tdestid='".$destid."' and a.fld_ttaskid='".$taskid."' and a.fld_tresid='".$resid."' 
                                                                                                                                and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$schid."' and a.fld_schtype_id='".$schtype."' and b.fld_delstatus='0'");
                                                                                if($respretestid!="" and $respretestid!=0){
                                                                                    $respreretake = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_testplay_track 
                                                                                                                                        WHERE fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_res_test_id='".$respretestid."' 
                                                                                                                                            AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND fld_read_status='1' AND fld_retake='0'
                                                                                                                                            AND fld_delstatus='0' AND fld_student_id='".$stuid."'");
                                                                                    if($respreretake==0){
                                                                                        $respretechcheck = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_exp_points_master WHERE fld_schedule_type='".$schtype."' AND fld_student_id='".$stuid."' 
                                                                                                                                            AND fld_exp_id='".$expid."' AND fld_schedule_id='".$schid."' AND fld_res_id='".$respretestid."' AND fld_delstatus = '0'");
                                                                                        if(trim($respretechcheck)==''){
                                                                                            $restest=$respretestid;
                                                                                        }
                                                                                    }

                                                                                }
                                                                                if($wholetest=='')
                                                                                {
                                                                                   $wholetest= $exptest."/".$desttest."/".$destposttest."/".$tasktest."/".$taskposttest."/".$restest."/".$resposttest."/".$lastresposttest."/".$expposttest;
                                                                                }
                                                                                else
                                                                                {
                                                                                    $wholetest=$wholetest."/".$desttest."/".$destposttest."/".$tasktest."/".$taskposttest."/".$restest."/".$resposttest."/".$lastresposttest."/".$expposttest;
                                                                                }

                                                                            }
                                                                            else{
                                                                               $breakflag=1;
                                                                               break;

                                                                            }
                                                                           //echo "rt".$restest;
                                                                           $beforeresid=$resid;
                                                                        }
                                                                    }

                                                                     if($breakflag==1)
                                                                     {
                                                                         break;
                                                                     }
                                                                     $t++;
                                                                }// task while loop end


                                                            } 

                                                            if($breakflag==1)
                                                            {
                                                                break;
                                                            }

                                                            $b++;

                                                        } // dest while loop end


                                                    }
                                                }

                                                //echo "test".$wholetest;
                                            }
                                            $fstudentflag=0;
                                            $fstudent = explode("/",$wholetest);
                                            for($i=0;$i<sizeof($fstudent);$i++){
                                                if($fstudent[$i]>0){
                                                    $fstudentflag=1;
                                                    break;
                                                }
                                            }
                                        } // Student check ends

                                        // Display starts
                                        $test1 = explode("/",$wholetest);

                                        if($test1!="" and $fstudentflag==1){

                                            $s1name = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$stuid."' AND fld_delstatus='0'");
                                            if($temflag==0){
                                                $temflag=1;
                                                if($dot!=0){
                                                    echo "<hr>";
                                                }
                                                $dot++;
                                                echo "<span style='font-size:20px;font-weight:bold;margin-left:5px;'>".$classname."</span><br>";
                                            }
                                            echo "&nbsp;&nbsp;&nbsp;"."<strong>".$s1name."</strong><br>";
                                            $expstuflaf=1;
                                            $temptestname="";
                                            for($i=0;$i<sizeof($test1);$i++){

                                                if($test1[$i]>0){
                                                    $j++;
                                                    $testnames = $ObjDB->SelectSingleValue("select fld_test_name from itc_test_master where fld_id='".$test1[$i]."'");
                                                    //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$testnames."<br>";
                                                    echo '<div style="float:left;text-indent:20px;width:15%;"><img style="text-align:center;" src="../img/dot.png"/></div><div style="float:left;font-size:15px;line-height:25px;text-indent:5px;width:85%;">'.$testnames.'</div><div style="clear:both;"></div>';
                                                }
                                            }
                                            //print_r($temptestname);
                                        }

                                        // Display ends

                                        //echo "hi".$wholetest."~";

                                    }
                                }
                        }
                    }// Schedule if Ends
                    // Ass test starts
                    $qryasstestdest = $ObjDB->QueryObject("select fld_test_id as asstestid,fld_id as mapid from itc_test_student_mapping
                                                                where fld_student_id = '".$studentid."' and fld_class_id = '".$id."' and fld_flag=1");
                    
                    if($qryasstestdest->num_rows>0){
                        $stuflag=0;
                        while($rowasstestdest = $qryasstestdest->fetch_assoc())
                        {
                            extract($rowasstestdest);
                            if($asstestid!=""){

                                $assteststatus = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_test_student_mapping
                                                                        where fld_end_date < CURDATE() and fld_max_attempts='0' and fld_student_id='".$studentid."' and fld_class_id='".$id."' and fld_test_id='".$asstestid."' and fld_flag=1");

//                                $retakeassteststatus = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_test_student_answer_track
//                                                                                        where fld_student_id='".$studentid."' and fld_stumap_id='".$mapid."' and fld_test_id='".$asstestid."' and fld_schedule_id='0' and fld_schedule_type='0' 
//                                                                                        and fld_retake=0 and fld_delstatus='0'");
                                //echo $assteststatus."/".$retakeassteststatus."~";
                                if($assteststatus==1){
                                    $testtechcheck = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned FROM itc_test_student_mapping WHERE  
                                                                                fld_test_id='".$asstestid."' AND fld_student_id = '".$studentid."' and fld_class_id = '".$id."' and fld_flag=1");
                                    if(trim($testtechcheck)==''){
                                        $k++;
                                        $asstestnme = $ObjDB->SelectSingleValue("select fld_test_name from itc_test_master where fld_id='".$asstestid."'");
                                        if($temflag==0){
                                            $temflag=1;

                                            if($dot!=0){
                                               echo "<hr>";
                                             }
                                             $dot++;
                                            echo "<span style='font-size:20px;font-weight:bold;margin-left:5px;'>".$classname."</span><br>";

                                        }
                                        //echo $stuflag."/".$expstuflaf."~";
                                        if($stuflag==0 and $expstuflaf==0){
                                            $s1name = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id='".$studentid."' AND fld_delstatus='0'");
                                            echo "&nbsp;&nbsp;&nbsp;"."<strong>".$s1name."</strong><br>";
                                            $stuflag=1;
                                        }

                                        
                                        echo '<div style="float:left;text-indent:20px;width:15%;"><img style="text-align:center;" src="../img/dot.png"/></div><div style="float:left;font-size:15px;line-height:25px;text-indent:5px;width:15%;">'.$asstestnme.'</div><div style="clear:both;"></div>';
                                        //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$asstestnme."<br>";
                                    }
                                }
                            }

                        }
                    }
                    //Ass test starts
                }
            }

        }
    }
}
                                        
                   
                    