<?php
/*
 * Updated for Dual login
 * Updated By: Vijayalakshmi PHP Programmer
 * and Karthick PHP Programmer
 * Updated on:16/9/2014
 */
@include("sessioncheck.php");

$method=$_REQUEST;
$date = date("Y-m-d H:i:s");
	
$ids = isset($method['id']) ? $method['id'] : '0';
$id=explode(",",$ids);

$destinationid=$id[0];
$destinationorder=$id[1];
$missionid=$id[2];
$schid=$id[3];
$schtype=$id[4];
$passporttask=$id[5];
$passportmediaurltask=$id[6];

if($schtype ==='18'){
            $tablename = "itc_class_indasmission_master";
}
if($schtype ==='23'){
    $tablename = "itc_class_rotation_mission_mastertemp";
}

$shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from ".$tablename." where fld_id='".$schid."' and fld_delstatus='0'");

$destuniqueid = $ObjDB->SelectSingleValue("SELECT fld_destunique_id FROM itc_mis_destination_master WHERE fld_id='".$destinationid."' AND fld_delstatus='0' AND fld_flag='1'");

$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");

if($cnt===0)
{
	$ObjDB->NonQuery("INSERT INTO itc_mis_dest_play_track(fld_mis_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_dest_unique_id) 
								VALUES('".$missionid."', '".$destinationid."', '".$uid."', '".$schid."', '".$schtype."', '0', '".$uid."', '".$date."', '".$destuniqueid."')");
}

if($uid1!='' and $uid1!=0)
{
	$cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");

	if($cnt1===0)
	{
		$ObjDB->NonQuery("INSERT INTO itc_mis_dest_play_track(fld_mis_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_dest_unique_id) 
									VALUES('".$missionid."', '".$destinationid."', '".$uid1."', '".$schid."', '".$schtype."', '0', '".$uid1."', '".$date."', '".$destuniqueid."')");
	}
}
$qrydestination = $ObjDB->QueryObject("SELECT fld_id, fld_dest_name, fld_dest_desc, fld_destunique_id 
                                      FROM itc_mis_destination_master WHERE fld_id='".$destinationid."' AND fld_flag='1' AND fld_delstatus='0'");
$rowqrydestination = $qrydestination->fetch_object();

$qrymedias = $ObjDB->QueryObject("SELECT fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_desc, fld_id 
									FROM itc_mis_media_master
									WHERE fld_mis_dest_task_id='".$destinationid."' AND fld_media_category='2' AND fld_flag='1' AND fld_delstatus='0'");
?>
<section data-type='2home' id='assignment-mission-tasks'>
    <div class='span12 dialogStyle1'>
        <div class='row' style="margin-bottom:15px;">
            <div class='twelve columns'>
            	<span id="changeclass" style="font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold;"></span>
                <p class="lightTitle"><?php echo $rowqrydestination->fld_dest_name;?></p>
                <p class="lightSubTitle">&nbsp;</p>
            </div>
        </div>
       
        <div class='row-fluid'>
        	<?php if($qrymedias->num_rows>0 || $rowqrydestination->fld_dest_desc!=''){?>
        	<div class="container">
                <div class="row formBase" style="margin-bottom:15px;">
                    <div class="eleven columns centered" style="padding:20px 0px 20px 0px;"  >
                        <div class="seven columns">
                        	<?php if($rowqrydestination->fld_dest_desc!=''){?>
                        	<strong>Description:</strong><br />
							<?php echo $rowqrydestination->fld_dest_desc; }?>
                        </div>
                        
                        <div class="five columns">
							<?php 
							if($qrymedias->num_rows>0) {
								while($rowqrymedias = $qrymedias->fetch_object()){
									if($rowqrymedias->fld_media_file_type!=3) {
										$click = "loadiframes('path?type=".$rowqrymedias->fld_media_file_type."&filename=".$rowqrymedias->fld_media_file_name."','preview');";
									}
									else
									{
										$click = "fn_playaudio(".$rowqrymedias->fld_id.")";
									}
									?>
									<div class="d-list" onclick="<?php echo $click;?>" >
                                    	<?php if($rowqrymedias->fld_media_file_type!=3) {?>
										<div class="d-listimg"></div>
                                        <?php } else { 										
										?>
                                        <div id="audioControl_<?php echo $rowqrymedias->fld_id; ?>" class="d-listimg" >
                                            <audio id="yourAudio_<?php echo $rowqrymedias->fld_id; ?>" preload='none' onended="fn_end(<?php echo $rowqrymedias->fld_id; ?>);">
                                             <?php $url=$_SESSION['mediaurlpath'].$rowqrymedias->fld_media_file_name;?>
                                           <source src='<?php echo "../../receiveaudio.php?url=".$url;?>' type='audio/mp3' />                                               
                                            </audio>
                                        </div>
                                        <?php }?>
										<div class="d-listtitle"><?php echo $rowqrymedias->fld_media_name; ?></div>
										<div class="d-listdesc"><?php echo $rowqrymedias->fld_media_desc; ?></div>
									</div>
									<?php 
								} 
							} ?> 
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <div class='row buttons' id="tasklist">
                <p id="changeclass" style="font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold; margin-bottom:7px;"></p>
                <input type="hidden" id="calltaskdiv" name="calltaskdiv" value="<?php echo $ids; ?>" />
                <?php
                $qrytasks= $ObjDB->QueryObject("SELECT fld_id, fld_task_name, fn_shortname (CONCAT(fld_task_name), 1) AS shortname, fld_task_desc, fld_task_id AS taskuni, fld_order, fld_next_order, fld_task_status
												FROM itc_mis_task_master
                								WHERE fld_dest_id='".$destinationid."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                if($qrytasks->num_rows>0) {
					$i=1;
					$statusflag = true;
                                        $show = 0;
                                        $dimflag = 0;
                        $cntt1=1;
                        while($rowqrytasks = $qrytasks->fetch_object()){
                            // For single resources
                            $rescntone = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_resource_master WHERE fld_task_id = '".$rowqrytasks->fld_id."' AND fld_flag = '1' AND fld_delstatus = '0'");
                            if($rescntone =='1'){
                                $resdet = $ObjDB->QueryObject("SELECT fld_id AS resid,fld_res_file_name AS resfilename, fld_res_file_type AS resfiletype, fld_res_id AS taskguid FROM itc_mis_resource_master WHERE fld_task_id = '".$rowqrytasks->fld_id."' AND fld_flag = '1' AND fld_delstatus = '0'");
                                if($resdet->num_rows>0){
                                        $rowresdet = $resdet->fetch_assoc();
                                        extract($rowresdet);
                                }
                            }
                            
                            $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$missionid."' AND fld_task_id='".$rowqrytasks->fld_id."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$shlteacherid."' AND fld_user_id='".$indid."'");
                            
                            if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                            {
                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$missionid."' AND fld_task_id='".$rowqrytasks->fld_id."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");

                                if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                {
                                    $tstatus = $rowqrytasks->fld_task_status;
                                }
                            }
                            $shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from itc_class_indasmission_master where fld_id='".$schid."' and fld_delstatus='0'");
                            if($tstatus !=3){
                                // Pre test start
                                $qrystutaskpre = $ObjDB->QueryObject("select a.fld_id AS pretestid, a.fld_test_name AS pretestname,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortname1,b.fld_status as pretesttaskstatus,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS badgestatuspre  from itc_test_master as a
                                                                        left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                                        where a.fld_destid ='".$destinationid."' and a.fld_prepostid ='1' and b.fld_tprepost ='1' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                                        and b.fld_ttaskid='".$rowqrytasks->fld_id."' and b.fld_tresid='0' and b.fld_tdestid='".$destinationid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");
                                if($qrystutaskpre->num_rows>0){
                                    $rowstutaskpre = $qrystutaskpre->fetch_assoc();
                                    extract($rowstutaskpre);
                                    if($pretesttaskstatus !=3){
                            
                                        $tasktestcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_testplay_track WHERE fld_dest_id='".$destinationid."' AND fld_task_id='".$rowqrytasks->fld_id."' AND fld_task_test_id='".$pretestid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                        if($tasktestcntpre == '1'){
                                            $taskteststatus=1;
                                            $newclass1 = "skip btn mainBtn dim completed";
                                            $beftasktestcntpre='1';
                                        }
                                        else
                                        {
                                            $newclass1 = "skip btn mainBtn required";
                                            $taskteststatus=0;
                                        }

                                        // Block first test
                                        if($cntt1 !=1){
                                            $taskcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$beforetaskid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                            if($taskcntpre =='1' or $taskopstatus == "optional"){
                                                if($stutaskpostcount == '1'){
                                                    $newclass1 = "skip btn mainBtn dim required";
                                                }
                                                else{
                                                    $newclass1 = "skip btn mainBtn dim required";
                                                }
                                            }
                                            else{
                                                $newclass1 = "skip btn mainBtn dim required";
                                            }
                                        }
                                        if($tskpostcopstatus ==1){
                                            $newclass1 = "skip btn mainBtn required";
                                        }
                                        if($tasktestcntpre == '1'){
                                            $newclass1 = "skip btn mainBtn dim completed";
                                        }
                                        if($badgestatuspre == "notrequired"){
                                            $taskteststatus=1;
                                            $newclass1 = "skip btn mainBtn optional";
                                        }

                                       ?>
                                        <a class="<?php echo $newclass1; ?>" onclick="fn_tasktest(<?php echo $pretestid;?>,<?php echo $pretesttaskstatus;?>,<?php echo $rowqrytasks->fld_id;?>,<?php echo $destinationid;?>,<?php echo $missionid;?>,<?php echo $schid;?>,<?php echo $destinationorder?>);">
                                            <div class="icon-Destination"></div>
                                            <div class='onBtn tooltip' original-title='<?php echo $pretestname; ?>'><?php echo $shortname1; ?></div>
                                        </a>
                                        <?php
                                    }//preteststatus ends
                                }
                                else{
                                    $taskteststatus=1;
                                }
                            }
                            //Pre task test ends
                            
						$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$rowqrytasks->fld_id."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                            if($uid1!='' and $uid1!=0)
                            {
                                $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$rowqrytasks->fld_id."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                            }
                            /* Required tasks */					
                            if($tstatus==1)
                            {
                                if($statusflag and $i==$rowqrytasks->fld_next_order and $taskteststatus ==1)
                                {
                                    if($uid1!='' and $uid1!=0){
                                        if($cnt>=1 && $cnt1>=1)
                                            $newclass = "skip btn mainBtn completed";
                                        else
                                        {
                                            $newclass = "skip btn mainBtn required";
                                            $show = 1;
                                        }
                                    }  //ends of  if($uid1!='' and $uid1!=0){
                                    else{
                                        if($cnt>=1){
                                            $newclass = "skip btn mainBtn completed";
                                            $taskcpstatus=1;
                                        }
                                        else
                                        {
                                            $newclass = "skip btn mainBtn required";
                                            $show = 1;
                                             $taskcpstatus=0;
                                        }
                                    }
                                } // ends of if($statusflag and $i==$rowqrytasks->fld_next_order)
                                else
                                {
                                    $newclass = "skip btn mainBtn dim required";
                                    $show = 1;
                                    $dimflag = 1;
                                }
                                if($tskpostcopstatus == '0'){
                                    $newclass = "skip btn mainBtn dim required";
                            }
                            }
                            else if($tstatus==2)
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$rowqrytasks->fld_id."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");

                                if($cnt===0)
                                {
                                    $ObjDB->NonQuery("INSERT INTO itc_mis_task_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
								VALUES('".$missionid."', '".$destinationid."', '".$rowqrytasks->fld_id."', '".$uid."', '".$schid."', '".$schtype."', '1', '".$uid."', '".$date."', '".$rowqrytasks->taskuni."')");
                                }   
                                if($uid1!='' and $uid1!=0)
                                {
                                    $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$rowqrytasks->fld_id."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                    if($cnt1===0)
                                    {
                                        $ObjDB->NonQuery("INSERT INTO itc_mis_task_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
								VALUES('".$missionid."', '".$destinationid."', '".$rowqrytasks->fld_id."', '".$uid1."', '".$schid."', '".$schtype."', '1', '".$uid1."', '".$date."', '".$rowqrytasks->taskuni."')");
                                    }
                                }
                                $newclass = "skip btn mainBtn optional";
                                $taskopstatus="optional";
                            }
                            if($dimflag == 1){
                                $checkcom = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$rowqrytasks->fld_id."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                if($uid1 != '' and $uid1!=0) {
                                    $checkcom1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$rowqrytasks->fld_id."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                }
                                if($uid1 != '' and $uid1!=0) {
                                    if($checkcom ==1 && $checkcom1 ==1){
                                    $newclass = "skip btn mainBtn dim completed";
                                    }
                                    else{
                                        $newclass = "skip btn mainBtn dim required";
                                    }
                                }
                                else{
                                    if($checkcom ==1){
                                        if($passporttask =="passport"){
                                            $newclass = "skip btn mainBtn completed";
                                        }
                                        else{
                                        $newclass = "skip btn mainBtn dim completed";
                                    }
                                    }
                                    else{
                                        $newclass = "skip btn mainBtn dim required";
                                    }
                                }
                            }  //ends of  if($dimflag == 1){
                            
                            if($passporttask =="passport"){
                                $i=0;
                                $dataparam =  $destinationid.','.$rowqrytasks->fld_id.','.$i.','.$missionid.','.$schid.','.$schtype.",".$passporttask.",".$passportmediaurltask;
                            }
                            else{
                                $flag=$ObjDB->SelectSingleValueInt("SELECT fld_lock FROM itc_class_indasmission_master WHERE fld_id='".$schid."'");
                                $resclick = "loadiframes('path?destinationid=".$destinationid."&taskid=".$rowqrytasks->fld_id."&resourceid=".$resid."&type=".$resfiletype."&userid=".$uid."&userid1=".$uid1."&profileid=".$sessmasterprfid."&schid=".$schid."&schtype=".$schtype."&filename=".$resfilename."','preview',".$flag.");";             
                                $link='';
                                $target='';                            
                            }
                            
                            if($tstatus!=3)
                            {
						?>
						<a <?php echo $link."  ".$target; ?>  class="<?php echo $newclass;?>" onclick="<?php echo $resclick?>;fn_inserttpt(<?php echo $destinationid;?>,<?php echo $rowqrytasks->fld_id;?>,'<?php echo $taskguid;?>',<?php echo $schid;?>,<?php echo $missionid;?>);" name="<?php echo $destinationid.",".$rowqrytasks->fld_id ?>,1">
                            <div class="icon-synergy-tests"></div>
                            <div class='onBtn tooltip' original-title='<?php echo $rowqrytasks->fld_task_name;?>'><?php echo $rowqrytasks->shortname;?></div>
						</a>
						<?php
                            }
						$i++;
                            $cntt1++;
                            $beforetaskid=$rowqrytasks->fld_id;
                            if($cnt>=1)
                                $statusflag = true;
                            else if($tstatus!=1)
                                $statusflag = $statusflag;
                            else
                                $statusflag = false;
                            
                            if($tstatus !=3){
                            // Post test starts
                                $qrystutaskpost = $ObjDB->QueryObject("select a.fld_id AS posttestid, a.fld_test_name AS posttestname,b.fld_status AS postteststatus,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortname2,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS badgestatuspost  from itc_test_master as a
                                                                            left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                                            where a.fld_destid ='".$destinationid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                                            and b.fld_ttaskid='".$rowqrytasks->fld_id."' and b.fld_tresid='0' and b.fld_tdestid='".$destinationid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");
                               $stutaskpostcount1 = $qrystutaskpost->num_rows;
                                if($qrystutaskpost->num_rows>0){
                                    $rowstutaskpost = $qrystutaskpost->fetch_assoc();
                                    extract($rowstutaskpost);
                                    if($postteststatus !=3){
                                        $stutaskpostcount = $stutaskpostcount1;
                                        $tasktestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_testplay_track WHERE fld_dest_id='".$destinationid."' AND fld_task_id='".$rowqrytasks->fld_id."' AND fld_task_test_id='".$posttestid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                        if($tasktestcntpost == '1' and $badgestatuspost != "notrequired"){
                                            $newclass2 = "skip btn mainBtn dim completed";
                                            $tskpostcopstatus='1';
                    }
                                        else{
                                            $newclass2 = "skip btn mainBtn required";
                                            $tskpostcopstatus='0';
                                        }
                                        if($taskcpstatus!=1 and $taskopstatus !="optional"){
                                            $newclass2 = "skip btn mainBtn dim required";
                                        }
                                        if($taskopstatus =="optional"){
                                            $newclass2 = "skip btn mainBtn required";
                                        }
                                        if($taskteststatus!=1){
                                            $newclass2 = "skip btn mainBtn dim required";
                                        }
                                        if($badgestatuspost == "notrequired"){
                                           $newclass2 = "skip btn mainBtn optional";
                                           $tskpostcopstatus='1';
                                        }
                                        ?>
                                            <a class="<?php echo $newclass2; ?>" onclick="fn_tasktest(<?php echo $posttestid;?>,<?php echo $postteststatus;?>,<?php echo $rowqrytasks->fld_id;?>,<?php echo $destinationid;?>,<?php echo $missionid;?>,<?php echo $schid;?>,<?php echo $destinationorder?>);">
                                                <div class="icon-Destination"></div>
                                                <div class='onBtn tooltip' original-title='<?php echo $posttestname; ?>'><?php echo $shortname2; ?></div>
                                            </a>
                                        <?php
                                    } // posttestatus ends
                                }
                                else{
                                    $tskpostcopstatus='1';
                                }//if post ends
                            }
                    }
                    
                    if($show == 0)
                    {
                         $tasklastid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                                    FROM itc_mis_task_master as a 
                                                    LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_task_id
                                                    WHERE a.fld_dest_id='".$destinationid."' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$shlteacherid."' AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) AND a.fld_delstatus='0'  order by a.fld_id desc");
            
                            $taskposttest = $ObjDB->SelectSingleValue("select a.fld_id AS posttestid from itc_test_master as a
                                                                        left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                                        where a.fld_destid ='".$destinationid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                                        and b.fld_ttaskid='".$tasklastid."' and b.fld_tresid='0' and b.fld_tdestid='".$destinationid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status='1' and a.fld_delstatus ='0'");
                        
                            if($taskposttest !=""){
                                $tasktestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_testplay_track WHERE fld_dest_id='".$destinationid."' AND fld_task_id='".$tasklastid."' AND fld_task_test_id='".$taskposttest."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                if($tasktestcntpost =='1'){
                            $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                }
                            }
                            else{
                                $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                            }
                            if($uid1 != '' and $uid1!=0) {
                               $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid1."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'"); 
                            }
                            ?>
                            <script>
                                $("#destlist").load("assignment/mission/assignment-mission-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
                            </script>
                            <?php
                    }
                } ?>
            </div>
        </div>
    </div>
    <script>
		if($('#assignment-mission-tasks').prevAll('section:eq(1)').attr('class')=='blueWindow1')
		{
			setTimeout(function(){$('#changeclass').css('color','#537F98')},1000);
		}
		else
		{
			setTimeout(function(){$('#changeclass').css('color','#FFFFFF')},1000);
		}
	</script>
</section>