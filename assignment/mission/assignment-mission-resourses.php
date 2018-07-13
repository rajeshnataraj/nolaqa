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
$taskid=$id[1];
$taskorder=$id[2];
$missionid=$id[3];
$schid=$id[4];
$schtype=$id[5];
$passport=$id[6];
$passportmediaurl=$id[7];

if($schtype ==='18'){
            $tablename = "itc_class_indasmission_master";
}
if($schtype ==='23'){
    $tablename = "itc_class_rotation_mission_mastertemp";
}

$shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from ".$tablename." where fld_id='".$schid."' and fld_delstatus='0'");

if($uid1=='')
{
    $uid1=0;
}

$taskuniqueid = $ObjDB->SelectSingleValue("SELECT fld_task_id FROM itc_mis_task_master WHERE fld_id='".$taskid."' AND fld_delstatus='0' AND fld_flag='1'");

$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");

if($cnt===0)
{
	$ObjDB->NonQuery("INSERT INTO itc_mis_task_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
						VALUES('".$missionid."', '".$destinationid."', '".$taskid."', '".$uid."', '".$schid."', '".$schtype."', '0', '".$uid."', '".$date."', '".$taskuniqueid."')");
}

if($uid1!='' and $uid1!=0)
{
	$cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");

	if($cnt1===0)
	{
		$ObjDB->NonQuery("INSERT INTO itc_mis_task_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
							VALUES('".$missionid."', '".$destinationid."', '".$taskid."', '".$uid1."', '".$schid."', '".$schtype."', '0', '".$uid1."', '".$date."', '".$taskuniqueid."')");
	}
}

$qrytask= $ObjDB->QueryObject("SELECT fld_id, fld_task_name, fld_task_desc, fld_task_id FROM itc_mis_task_master WHERE fld_id='".$taskid."' AND fld_flag='1' AND fld_delstatus='0'");
$rowqrytask = $qrytask->fetch_object();

$qrymedias = $ObjDB->QueryObject("SELECT fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_desc, fld_id 
									FROM itc_mis_media_master
									WHERE fld_mis_dest_task_id='".$taskid."' AND fld_media_category='3' AND fld_flag='1' AND fld_delstatus='0'");
?>
<section data-type='2home' id='assignment-mission-resourses'>
    <div class='span12 dialogStyle1'>
        <div class='row'>
            <div class='twelve columns'>
            	<span id="changeclass" style="font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold;"></span>
                <p class="lightTitle"><?php echo $rowqrytask->fld_task_name;?> </p>
                <p class="lightSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row-fluid'>
        	<?php if($qrymedias->num_rows>0 || $rowqrytask->fld_task_desc!=''){?>
            <div class="container">
                <div class="row formBase" style="margin-bottom:15px;">
                    <div class="eleven columns centered" style="padding:20px 0px 20px 0px;"  >
                        <div class="seven columns">
                        	<?php if($rowqrytask->fld_task_desc!=''){?>
                        	<strong>Description:</strong><br />
							<?php echo $rowqrytask->fld_task_desc; }?>
                        </div>
                        
                        <div class="five columns">
							<?php 
							if($qrymedias->num_rows>0) {
								while($rowqrymedias = $qrymedias->fetch_object()){
									if($rowqrymedias->fld_media_file_type!=3) {
										$click = "loadiframes('library/mission/library-mission-view.php?type=".$rowqrymedias->fld_media_file_type."&filename=".$rowqrymedias->fld_media_file_name."','preview');";
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
                                            <audio preload="auto" crossorigin="anonymous" id="yourAudio_<?php echo $rowqrymedias->fld_id; ?>"  onended="fn_end(<?php echo $rowqrymedias->fld_id; ?>);">
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
            <div class='row buttons' id="reslist">
                <p id="changeclass" style="font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold; margin-bottom:7px;"></p>
                <input type="hidden" id="callresdiv" name="callresdiv" value="<?php echo $ids; ?>" />
                <?php 
                $qryresources= $ObjDB->QueryObject("SELECT fld_id, fld_res_id AS resuniqid, fld_res_name, fn_shortname (CONCAT(fld_res_name), 1) AS shortname, fld_res_file_name, fld_res_file_type, fld_typeof_res, fld_res_status, fld_order, fld_next_order, fld_resource_status FROM itc_mis_resource_master WHERE fld_task_id='".$taskid."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                
                if($qryresources->num_rows>0) {
                            $i=1;
                            $cntr1=1;
                            $statusflag = true;
                            $finalflag = true;
                            $show = 0;
                            $dimflagr = 0;
                            $befpostteststatus=1;
                            while($rowqqryresources = $qryresources->fetch_object()){
                            
                                $rstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$missionid."' AND fld_res_id='".$rowqqryresources->fld_id."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$shlteacherid."' AND fld_user_id='".$indid."'");

                                if($rstatus=='' or $rstatus=='0' or $rstatus==NULL)
                                {
                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                    $rstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_mis_res_status WHERE fld_mis_id='".$missionid."' AND fld_res_id='".$rowqqryresources->fld_id."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");

                                    if($rstatus=='' or $rstatus=='0' or $rstatus==NULL)
                                    {
                                        $rstatus = $rowqqryresources->fld_resource_status;
                                    }
                                }

                                if($rstatus !=3){
                                    // Pre test start
                                    $shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from itc_class_indasmission_master where fld_id='".$schid."' and fld_delstatus='0'");

                                    $qrysturespre = $ObjDB->QueryObject("select a.fld_id AS pretestid, a.fld_test_name AS pretestname,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortname1,b.fld_status as pretestresstatus,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS badgestatuspre  from itc_test_master as a
                                                                            left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                                            where a.fld_destid ='".$destinationid."' and a.fld_prepostid ='1' and b.fld_tprepost ='1' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                                            and b.fld_ttaskid='".$taskid."' and b.fld_tresid='".$rowqqryresources->fld_id."' and b.fld_tdestid='".$destinationid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");
                                    if($qrysturespre->num_rows>0){
                                        $rowsturespre = $qrysturespre->fetch_assoc();
                                        extract($rowsturespre);
                                        if($pretestresstatus !=3){

                                            $restestcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_testplay_track WHERE fld_dest_id='".$destinationid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$rowqqryresources->fld_id."' AND fld_res_test_id='".$pretestid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                            if($restestcntpre == '1'){
                                                $resteststatus=1;
                                                $newclass1 = "skip btn mainBtn dim completed";                                              
                                            }
                                            else
                                            {
                                                $resteststatus=0;
                                                $newclass1 = "skip btn mainBtn required";                                               
                                            }

                                            // Block first test
                                            if($cntr1 !=1){
                                                $rescntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$beforeresid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                                if($rescntpre =='1' or $beforeresidstatus =='3' or $resoptatstus =="optional"){
                                                   $newclass1 = "skip btn mainBtn required";
                                                }
                                                else{
                                                    $newclass1 = "skip btn mainBtn dim required";
                                                }
                                            }
                                            if($befpostteststatus !=1){
                                                $newclass1 = "skip btn mainBtn dim required";
                                            }
                                            
                                            if($befrestestcntpre == '0'){
                                                $newclass1 = "skip btn mainBtn dim required";
                                            }
                                            if($restestcntpre == '1'){
                                                $newclass1 = "skip btn mainBtn dim completed";
                                            }
                                            
                                            if($badgestatuspre == "notrequired"){
                                                $resteststatus=1;
                                                $newclass1 = "skip btn mainBtn optional";
                                            }

                                           ?>
                                                <a class="<?php echo $newclass1; ?>" onclick="fn_restest(<?php echo $pretestid;?>,<?php echo $pretestresstatus;?>,<?php echo $rowqqryresources->fld_id;?>,<?php echo $taskid;?>,<?php echo $destinationid;?>,<?php echo $missionid;?>,<?php echo $schid;?>,<?php echo $taskorder;?>);">
                                                    <div class="icon-Destination"></div>
                                                    <div class='onBtn tooltip' original-title='<?php echo $pretestname; ?>'><?php echo $shortname1; ?></div>
                                                </a>
                                            <?php
                                            if($restestcntpre=='0' and $badgestatuspre == "required"){
                                                $befrestestcntpre='0';
                                            }
                                        }//preteststatus ends
                                    }
                                    else{
                                        $resteststatus=1;
                                    }
                                    //Pre task test ends
                                }

                                $resids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_mis_resource_master WHERE fld_res_id=(SELECT fld_res_id FROM itc_mis_resource_master WHERE fld_id='".$rowqqryresources->fld_id."')");

                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$resids.")  AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                if($uid1!='' and $uid1!=0){
                                    $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id IN (".$resids.")  AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                }
							
                                if($rstatus==1)
                                {                                    
                                    if($statusflag and $finalflag and $i==$rowqqryresources->fld_next_order and $resteststatus ==1)
                                    {
                                        if($uid1!='' and $uid1!=0){
                                            if($cnt>=1 && $cnt1>=1)
                                                $newclass = "skip btn mainBtn completed";
                                            else
                                            {
                                                $newclass = "skip btn mainBtn required";
                                                $show = 1;
                                            }
                                        } //ends of if($uid1!='' and $uid1!=0)
                                        else{
                                            if($cnt>=1){
                                                    $newclass = "skip btn mainBtn completed";
                                                $rescpatstus=1;
                                            }
                                            else
                                            {
                                                    $newclass = "skip btn mainBtn required";
                                        $show = 1;
                                                $rescpatstus=0;
                                            }
                                            
                                    }

                                    } // ends of if($statusflag and $finalflag and $i==$rowqqryresources->fld_next_order)
                                    else
                                    {
                                        $newclass = "skip btn mainBtn dim required";
                                        $show = 1;
                                        $dimflagr = 1;
                                    }
                                    if($befpostteststatus !=1){
                                        $newclass = "skip btn mainBtn dim required";
                                    }
                                    if($dimflagr == 1){
                                        $checkcom = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$rowqqryresources->fld_id."'  AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");

                                        if($uid1!='' and $uid1!=0){
                                            $checkcom1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$rowqqryresources->fld_id."'  AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                        }
                                        if($uid1!='' and $uid1!=0){

                                            if($checkcom ==1 && $checkcom1 ==1){
                                                $newclass = "skip btn mainBtn dim completed";
                                            }
                                            else{
                                                $newclass = "skip btn mainBtn dim required";
                                            }
                                        }
                                        else{
                                            if($checkcom ==1){
                                                $newclass = "skip btn mainBtn dim completed";
                                            }
                                            else{
                                                $newclass = "skip btn mainBtn dim required";
                                            }
                                        }
                                    }
                                }  //ends of  if($rstatus==1)
                                else if($rstatus==2)
                                {
                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$rowqqryresources->fld_id."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
							
                                    if($cnt===0)
                                    {
                                            $ObjDB->NonQuery("INSERT INTO itc_mis_res_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_res_unique_id) 
                                                                    VALUES('".$missionid."', '".$destinationid."', '".$taskid."', '".$rowqqryresources->fld_id."', '".$uid."', '".$schid."', '".$schtype."', '1', '".$uid."', '".$date."', '".$rowqqryresources->resuniqid."')");
                                    }                                
                                    if($uid1!='' and $uid1!=0){
                                        $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_play_track WHERE fld_res_id='".$rowqqryresources->fld_id."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                        if($cnt1===0)
                                        {
                                            $ObjDB->NonQuery("INSERT INTO itc_mis_res_play_track(fld_mis_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_res_unique_id) 
                                                                    VALUES('".$missionid."', '".$destinationid."', '".$taskid."', '".$rowqqryresources->fld_id."', '".$uid1."', '".$schid."', '".$schtype."', '1', '".$uid1."', '".$date."', '".$rowqqryresources->resuniqid."')");
                                        }
                                    }
                                    $newclass = "skip btn mainBtn optional";
                                    $resoptatstus ="optional";                                   
                                }
                                
                                if($rowqqryresources->fld_res_file_type != 6 and $rowqqryresources->fld_res_file_type != 7){
                                        $flag=$ObjDB->SelectSingleValueInt("SELECT fld_lock FROM itc_class_indasmission_master WHERE fld_id='".$schid."'");
                                        if($passport == "passport"){
                                            
                                            $resclick = "loadiframespassport('path?destinationid=".$destinationid."&taskid=".$taskid."&resourceid=".$rowqqryresources->fld_id."&type=".$rowqqryresources->fld_res_file_type."&filename=".$rowqqryresources->fld_res_file_name."','preview',$missionid,'$passportmediaurl');";
                                        }
                                        else{
                                        $resclick = "loadiframes('path?destinationid=".$destinationid."&taskid=".$taskid."&resourceid=".$rowqqryresources->fld_id."&type=".$rowqqryresources->fld_res_file_type."&userid=".$uid."&userid1=".$uid1."&profileid=".$sessmasterprfid."&schid=".$schid."&schtype=".$schtype."&filename=".$rowqqryresources->fld_res_file_name."','preview',".$flag.");";             
                                        $link='';
                                        $target='';  
                                }
                                }
                                else
                                {
                                        $resclick="return false";
                                        $link='href='.$rowqqryresources->fld_res_file_name;
                                        $target='target="new"';  
                                }

                                if($rowqqryresources->fld_typeof_res==1)
                                {
                                        $classname = " icon-Information";                                        
                                }
                                else
                                        $classname = " icon-Activity";

                                if($rstatus!=3)
                                {
                                    ?>
                                    <a <?php echo $link."  ".$target; ?>  class="<?php echo $newclass;?>" onclick="<?php echo $resclick;?>" name="<?php echo $id[0].",".$id[1] ?>,1">
                                            <div class="<?php echo $classname;?>"></div>
                                            <div class='onBtn tooltip' original-title="<?php echo $rowqqryresources->fld_res_name;?>"><?php echo $rowqqryresources->shortname;?></div>
                                    </a>
                                    <?php
                                }
						$i++;
                                $cntr1++;
                                if($cnt>=1 or $rowqqryresources->fld_typeof_res===1)
							$statusflag = true;
                                else if($rstatus!=1)
                                {
                                    $statusflag = $statusflag;
                                    $finalflag = $finalflag;
                                }
                                else
                                        $statusflag = false;
						
                                if($rstatus!=3){
                                // Post test starts
                                    $qrysturespost = $ObjDB->QueryObject("select a.fld_id AS posttestid, a.fld_test_name AS posttestname,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortname2,b.fld_status AS postteststatus,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS badgestatuspost  from itc_test_master as a
                                                                                left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                                                where a.fld_destid ='".$destinationid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                                                and b.fld_ttaskid='".$taskid."' and b.fld_tresid='".$rowqqryresources->fld_id."' and b.fld_tdestid='".$destinationid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");
                                   $sturespostcount1 = $qrysturespost->num_rows;
                                    if($qrysturespost->num_rows>0){
                                        $rowsturespost = $qrysturespost->fetch_assoc();
                                        extract($rowsturespost);
                                        if($postteststatus !=3){
                                            $sturespostcount = $sturespostcount1;                                          
                                            if($rescpatstus ==1 or $resoptatstus =="optional"){
                                                $newclass2 = "skip btn mainBtn required";
                                            }
                                            else{
                                                $newclass2 = "skip btn mainBtn dim required";
                                            }
                                                

                                            $restestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_testplay_track WHERE fld_dest_id='".$destinationid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$rowqqryresources->fld_id."' AND fld_res_test_id='".$posttestid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                            if($restestcntpost == '1' and $badgestatuspost != "notrequired"){
                                                $newclass2 = "skip btn mainBtn dim completed";
                                                $befpostteststatus=1;
                                            }
                                            else{
                                                $befpostteststatus=0;
                                            }
                                            if($befrestestcntpre == '0' and $restestcntpost !=1){
                                                $newclass2 = "skip btn mainBtn dim required";
                                            }
                                            if($badgestatuspost == "notrequired"){
                                                $newclass2 = "skip btn mainBtn optional";
                                                $befpostteststatus=1;
                                            }
                                            ?>
                
                                                <a class="<?php echo $newclass2; ?>" onclick="fn_restest(<?php echo $posttestid;?>,<?php echo $postteststatus;?>,<?php echo $rowqqryresources->fld_id;?>,<?php echo $taskid;?>,<?php echo $destinationid;?>,<?php echo $missionid;?>,<?php echo $schid;?>,<?php echo $taskorder;?>);">
                                                    <div class="icon-Destination"></div>
                                                    <div class='onBtn tooltip' original-title='<?php echo $posttestname; ?>'><?php echo $shortname2; ?></div>
                                                </a>
                
                                            <?php
                                        } // posttestatus ends
                                    }
                                    else{
                                        $befpostteststatus=1;
                                    }//if post ends
                                }
                                
                                $beforeresid=$rowqqryresources->fld_id;
                                $beforeresidstatus=$rstatus;
                                
	
                        }
                        if($show == 0)
                        {
                            $latsresid = $ObjDB->SelectSingleValue("SELECT a.fld_id 
                                                                        FROM itc_mis_resource_master As a
                                                                        LEFT JOIN itc_mis_res_status as b on a.fld_id=b.fld_res_id
                                                                        WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_created_by='".$shlteacherid."' AND b.fld_user_id='".$indid."' AND b.fld_status IN(1,2) order by a.fld_id DESC");
                            
                            $latsrespostid = $ObjDB->SelectSingleValue("select a.fld_id AS posttestid from itc_test_master as a
                                                                                left join itc_mistest_toogle as b on a.fld_id = b.fld_mistestid 
                                                                                where a.fld_destid ='".$destinationid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_tmisid='".$missionid."'
                                                                                and b.fld_ttaskid='".$taskid."' and b.fld_tresid='".$latsresid."' and b.fld_tdestid='".$destinationid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status=1 and a.fld_delstatus ='0'");
                            if($latsrespostid !=""){
                                $restestcntpost1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_res_testplay_track WHERE fld_dest_id='".$destinationid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$latsresid."' AND fld_res_test_id='".$latsrespostid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                if($restestcntpost1 ==1){
                            $ObjDB->NonQuery("UPDATE itc_mis_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                }
                                
                            }
                            else{
                                $ObjDB->NonQuery("UPDATE itc_mis_task_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                            }
                            
                            if($uid1 != '' and $uid1!=0) {
                                $ObjDB->NonQuery("UPDATE itc_mis_task_play_track SET fld_read_status='1', fld_updated_by='".$uid1."', fld_updated_date='".$date."' WHERE fld_task_id='".$taskid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                            }
                            ?>
                            <script>
                                $("#tasklist").load("assignment/mission/assignment-mission-tasks.php #tasklist > *",{"id":$('#calltaskdiv').val()});
                            </script>
                            <?php
                            $fieldtask = 'CONCAT("\'",fld_task_id,"\'")';
                            $grouptaskids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(".$fieldtask.") FROM itc_mis_task_master WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_flag='1'");

                            $taskreadcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_unique_id IN (".$grouptaskids.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_read_status='1'");
                            if($uid1 != '' and $uid1!=0) {
                                $taskreadcnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_task_play_track WHERE fld_task_unique_id IN (".$grouptaskids.") AND fld_delstatus='0' AND fld_student_id='".$uid1."' AND fld_read_status='1'");
                            }
                            if($uid1 != '' and $uid1!=0){
                                 if($taskreadcnt === sizeof(explode(',',$grouptaskids)) && $taskreadcnt1 === sizeof(explode(',',$grouptaskids)))
                                {
                                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid1."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                              ?>
                                    <script>
                                        $("#destlist").load("assignment/mission/assignment-mission-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
                                    </script>
                                    <?php
                                }
	
                            }
                            else {
                                if($taskreadcnt === sizeof(explode(',',$grouptaskids)))
                                {
                                    $ObjDB->NonQuery("UPDATE itc_mis_dest_play_track SET fld_read_status='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_dest_id='".$destinationid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                    ?>
                                    <script>
                                        $("#destlist").load("assignment/mission/assignment-mission-preview.php #destlist > *",{"id":$('#calldestdiv').val()});
                                    </script>
                                    <?php
                                }
                            }
                                        
                        }
                                        
                                         /** show the view materials when assigned by teacher  **/                                   
                                        $select_viewexpmatlist=$ObjDB->QueryObject("SELECT AB.fld_material as matid,GH.fld_materials as materialname,GH.fld_thumbimg_url as thumbimg FROM itc_exp_extendmaterials_mapping AS AB
                                                                                INNER JOIN itc_materials_master AS GH ON AB.fld_material = GH.fld_id 
                                                                                INNER JOIN itc_class_indasmission_extcontent_mapping AS LK ON AB.fld_extend_id=LK.fld_ext_id
                                                                                WHERE AB.fld_task='".$taskid."' AND AB.fld_expedition='".$missionid."'AND LK.fld_schedule_id='".$schid."' AND LK.fld_mis_id = '".$missionid."' AND AB.fld_delstatus='0'");
                        
                        
                        if($select_viewexpmatlist->num_rows > 0)   {
                                        
                                        ?>
                                        <a class="skip btn mainBtn" href="#assignment-mission-viewmaterialfortask" id="btnassignment-mission-viewmaterialfortask" name="<?php echo $missionid;?>,<?php echo $destinationid;?>,<?php echo $taskid; ?>,<?php echo $schid; ?>">
                                            <div class="icon-synergy-tests"></div>
                                            <div class='onBtn tooltip' original-title='View Materials'>View Materials</div>
                                        </a>
                                                
                <?php }    }    ?>
            </div>
        </div>
    </div>
    <script>
		if($('#assignment-mission-resourses').prevAll('section:eq(1)').attr('class')=='blueWindow1')
		{
			setTimeout(function(){$('#changeclass').css('color','#537F98')},1000);
		}
		else
		{
			setTimeout(function(){$('#changeclass').css('color','#FFFFFF')},1000);
		}
	</script>
</section>