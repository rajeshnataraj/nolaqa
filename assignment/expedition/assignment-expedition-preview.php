<?php
/*
 * Updated for Dual login
 * Updated By: Vijayalakshmi PHP Programmer
 * and Karthick PHP Programmer
 * Updated on:16/9/2014
 */
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");

$ids = isset($method['id']) ? $method['id'] : '';	
$id = explode("~",$ids);

$scheduleid=$id[0];
$expeditionid=$id[1];
$schtype=$id[2];
$passportdest=$id[3];
$passportmediaurldest=$id[4];

$qryexpdeitions = $ObjDB->QueryObject("SELECT a.fld_exp_name, a.fld_exp_desc, a.fld_expunique_id, b.fld_file_name, b.fld_version
										FROM itc_exp_master AS a 
										LEFT JOIN itc_exp_version_track AS b ON a.fld_id=b.fld_exp_id 
										WHERE a.fld_id='".$expeditionid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
$rowqryexpdeitions = $qryexpdeitions->fetch_object();

$audio = __CNTPATH__."expedition/".$rowqryexpdeitions->fld_file_name."/resources/";
$urlformedia = "expedition/".$rowqryexpdeitions->fld_file_name."/resources/";
$_SESSION['mediaurlpath']=$audio;

$qrymedias = $ObjDB->QueryObject("SELECT fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_desc, fld_id 
									FROM itc_exp_media_master
									WHERE fld_exp_dest_task_id='".$expeditionid."' AND fld_media_category='1' AND fld_flag='1' AND fld_delstatus='0'");
?>
<script type="text/javascript" charset="utf-8">	
	$.getScript('assignment/expedition/assignment-expedition.js');
	$.getScript('js/video.js',function(){
		videojs.options.flash.swf = "video-js.swf";	
	});
</script>

<section data-type='2home' id='assignment-expedition-preview'>
    <div class='span12 dialogStyle1'>
        <div class='row' style="margin-bottom:15px;">
            <div class='twelve columns'>
            	<span id="changeclass" style="font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold;">Expedition:</span>
            	<p id="paraclass" class="lightTitle"><?php echo $rowqryexpdeitions->fld_exp_name; ?></p>
            </div>
        </div>
        
        <div class='row-fluid'>
        	<?php if($qrymedias->num_rows>0 || $rowqryexpdeitions->fld_exp_desc!=''){?>
            <div class="container">
                <div class="row formBase" style="margin-bottom:15px;">
                    <div class="eleven columns centered" style="padding:20px 0px 20px 0px;"  >
                        <div class="seven columns">
                        	<?php if($rowqryexpdeitions->fld_exp_desc!=''){?>
                        	<strong>Description:</strong><br />
                        	<?php echo strip_tags($rowqryexpdeitions->fld_exp_desc); }?>
                        </div>
                        
                        <div class="five columns">
							<?php 
                            if($qrymedias->num_rows>0) {
								while($rowqrymedias = $qrymedias->fetch_object()){
									if($rowqrymedias->fld_media_file_type!=3) {
										$click = "loadiframes('library/expedition/library-expedition-view.php?type=".$rowqrymedias->fld_media_file_type."&filename=".$rowqrymedias->fld_media_file_name."','preview');";
									}
									else
									{
										$click = "fn_playaudio(".$rowqrymedias->fld_id.")";
									}
									?>
									<div class="d-list" onclick="<?php echo $click;?>" >
                                        <?php if($rowqrymedias->fld_media_file_type!=3) {?>
										<div class="d-listimg"></div>
                                        <?php } else { ?>
                                        <div id="audioControl_<?php echo $rowqrymedias->fld_id; ?>" class="d-listimg" >
                                            <audio id="yourAudio_<?php echo $rowqrymedias->fld_id; ?>" preload='none' onended="fn_end(<?php echo $rowqrymedias->fld_id; ?>);">
                                            	<?php $url=$audio.$rowqrymedias->fld_media_file_name;?>
                                                <source src='<?php echo "../../receiveaudio.php?url=".$url;?>' type='audio/wav' />
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
            <div class='row buttons' id="destlist">
                <p id="changeclass" style="font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold; margin-bottom:7px;">Destinations:</p>
                <input type="hidden" id="calldestdiv" name="calldestdiv" value="<?php echo $ids; ?>" />
                <?php
                // Exp Pre test start
                if($schtype ==='15'){
                    $tablename = "itc_class_indasexpedition_master";
                }
                if($schtype ==='19'){
                    $tablename = "itc_class_rotation_expschedule_mastertemp";
                }
                if($schtype ==='20'){
                    $tablename = "itc_class_rotation_modexpschedule_mastertemp";
                }
                
                $shlteacherid = $ObjDB->SelectSingleValueInt("select fld_createdby from ".$tablename." where fld_id='".$scheduleid."' and fld_delstatus='0'");
                 $qrystuexppre = $ObjDB->QueryObject("select a.fld_id AS expretestid, a.fld_test_name AS expretestname,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortnamee1,b.fld_status as expreteststatus,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS exbadgestatuspre  from itc_test_master as a
                                                         left join itc_exptest_toogle as b on a.fld_id = b.fld_exptestid 
                                                         where a.fld_destid ='0' and a.fld_prepostid ='1' and b.fld_tprepost ='1' and b.fld_flag=1 and b.fld_texpid='".$expeditionid."'
                                                         and b.fld_ttaskid='0' and b.fld_tresid='0' and b.fld_tdestid='0' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");
                 if($qrystuexppre->num_rows>0){
                     $rowstuexppre = $qrystuexppre->fetch_assoc();
                     extract($rowstuexppre);
                     if($expreteststatus !=3){

                             $exptestcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track WHERE fld_exp_id='".$expeditionid."' AND fld_exp_test_id='".$expretestid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                             $expprecompletestatus=0;
                             if($exptestcntpre == '1'){
                                 $newclassee1 = "skip btn mainBtn dim completed";
                                 $expprecompletestatus=1;                               
                             }
                             else{
                                 $newclassee1 = "skip btn mainBtn required";                              
                             }
                             if($exbadgestatuspre == "notrequired"){
                                 $newclassee1 = "skip btn mainBtn optional";
                                 $expprecompletestatus=1;
                             }

   
                        ?>
                
                            <a class="<?php echo $newclassee1; ?>" onclick="fn_exptest(<?php echo $expretestid;?>,<?php echo $expreteststatus;?>,<?php echo $expeditionid;?>,<?php echo $scheduleid;?>,<?php echo $schtype;?>)">
                                <div class="icon-Destination"></div>
                                <div class='onBtn tooltip' original-title='<?php echo $expretestname; ?>'><?php echo $shortnamee1; ?></div>
                            </a>
                         <?php

                     }//preteststatus ends
                 }
                 else{
                     $expprecompletestatus=1;
                 }
                
                
                
						$qrydestinations = $ObjDB->QueryObject("SELECT a.fld_id AS destid, a.fld_dest_name AS destname, fn_shortname (CONCAT(a.fld_dest_name), 1) AS shortname, 
                                                                a.fld_dest_desc AS destdesc, a.fld_order, a.fld_next_order, a.fld_dest_status AS deststatus, a.fld_destunique_id
																FROM itc_exp_destination_master AS a 
																LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id 
																LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
                                                                                                                                left join itc_class_indasexpedition_master as d on c.fld_license_id=d.fld_license_id
																WHERE a.fld_exp_id='".$expeditionid."' AND b.fld_exp_id='".$expeditionid."' AND a.fld_flag='1' AND b.fld_flag='1' 
																	AND a.fld_delstatus='0' AND c.fld_user_id='".$indid."' AND c.fld_school_id='".$schoolid."' AND b.fld_delstatus='0' 
																	AND c.fld_delstatus='0' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'and d.fld_id='".$scheduleid."'
																 group by a.fld_id ORDER BY a.fld_order ");
                if($qrydestinations->num_rows>0) {
					$i=1;
                                        $cnt1=1;
                                        $dcpstatus='';
                                        $posttestcpstatus=1;
					$statusflag = true;
                                        $dimflagd = 0;
					while($rowqrydestinations = $qrydestinations->fetch_object()){
                            $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$rowqrydestinations->destid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$shlteacherid."' AND fld_user_id='".$indid."'");
						
                            if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                            {
                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$rowqrydestinations->destid."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");

                                if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                                {
                                    $dstatus = $rowqrydestinations->deststatus;
                                }
                            }
                            if($dstatus!=3){
                                // Pre test destination start
                            
                                    $qrystudestpre = $ObjDB->QueryObject("select a.fld_id AS dpretestid, a.fld_test_name AS dpretestname,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortnamed1,b.fld_status as preteststatus,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS dbadgestatuspre  from itc_test_master as a
                                                                            left join itc_exptest_toogle as b on a.fld_id = b.fld_exptestid 
                                                                            where a.fld_destid ='".$rowqrydestinations->destid."' and a.fld_prepostid ='1' and b.fld_tprepost ='1' and b.fld_flag=1 and b.fld_texpid='".$expeditionid."'
                                                                            and b.fld_ttaskid='0' and b.fld_tresid='0' and b.fld_tdestid='".$rowqrydestinations->destid."' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");

                                    if($qrystudestpre->num_rows>0){
                                        $rowstudestpre = $qrystudestpre->fetch_assoc();
                                        extract($rowstudestpre);
                                        if($preteststatus !=3){
                                                $desttestcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_dest_test_id='".$dpretestid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");

                                                if($desttestcntpre == '1' and $dbadgestatuspre != "notrequired"){
                                                    $destteststatus=1;
                                                    $newclassd1 = "skip btn mainBtn dim completed";
                                                    $befdesttestcntpre='1';                                                  
                                                }
                                                else
                                                {
                                                    $destteststatus=0;
                                                    $newclassd1 = "skip btn mainBtn required";
                                                }
                                                // Block first test
                                                if($cnt1 !=1 and $desttestcntpre !=1){
                                                    $destcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$beforedestid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                                    if($destcntpre =='1' or $bfdestbadgestatus == "notrequired"){
                                                        if($studestpostcount == 1){
                                                            if($posttestcpstatus == 1){
                                                                $newclassd1 = "skip btn mainBtn required";
                                                            }
                                                            else{
                                                                $newclassd1 = "skip btn mainBtn dim required";
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {                                                        
                                                    }
                                                }
                                                
                                                if($expprecompletestatus != 1){
                                                    $newclassd1 = "skip btn mainBtn dim required";
                                                }
                                                if($dbadgestatuspre == "notrequired"){
                                                    $destteststatus=1;
                                                    $newclassd1 = "skip btn mainBtn optional";
                                                }
                                           ?>
                                                 <a class="<?php echo $newclassd1; ?>" onclick="fn_desttest(<?php echo $dpretestid;?>,<?php echo $preteststatus;?>,<?php echo $rowqrydestinations->destid;?>,<?php echo $expeditionid;?>,<?php echo $scheduleid;?>,<?php echo $schtype;?>);">
                                                    <div class="icon-Destination"></div>
                                                    <div class='onBtn tooltip' original-title='<?php echo $dpretestname; ?>'><?php echo $shortnamed1; ?></div>
                                                </a>
                                            <?php
                                        }//preteststatus ends
                                    }
                                    else{
                                        $destteststatus=1;
                                    }
                            }
                            
						$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_read_status='1' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
							
                            if($dstatus==1)
                            {
                                if($statusflag and $i==$rowqrydestinations->fld_next_order and $expprecompletestatus == 1 and $destteststatus ==1)
                                {
                                    if($uid1 != '' and $uid1!=0) {
                                        
                                        // Dual login update starts
                                        $firstuid= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid."'");
                                        $secnduid= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid1."'");
                                        if($firstuid != $secnduid){
                                            if($firstuid > $secnduid) {
                                                $tempuid = $uid;
                                                $tempuid1 = $uid1;
                                            }
                                            elseif($secnduid > $firstuid) {
                                                 $tempuid = $uid1;
                                                 $tempuid1 = $uid;
                                            }
                                            $seldestdetails = $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_dest_unique_id FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$tempuid."'");
                                            $rowdest = $seldestdetails->fetch_assoc();
                                            extract($rowdest);
                                            $ObjDB->NonQuery("INSERT INTO itc_exp_dest_play_track(fld_exp_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_dest_unique_id) 
                                                                   VALUES('".$fld_exp_id."', '".$fld_dest_id."', '".$tempuid1."', '".$fld_schedule_id."', '".$fld_schedule_type."', '".$fld_read_status."', '".$tempuid1."', '".$date."', '".$fld_dest_unique_id."')");
                                        }
                                        else{
                                            $firstuid1= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_student_id='".$uid."'");
                                            $secnduid1= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_student_id='".$uid1."'");
                                            if($firstuid1 > $secnduid1) {
                                                $statuasuid = $uid1;
                                                
                                            }
                                            elseif($secnduid1 > $firstuid1) {
                                                 $statuasuid = $uid;
                                            }
                                            else{
                                               $statuasuid=''; 
                                            }
                                            if($statuasuid !=''){
                                                $ObjDB->NonQuery("UPDATE itc_exp_dest_play_track SET fld_read_status = '1',fld_updated_by='".$statuasuid."' WHERE fld_exp_id='".$expeditionid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$schtype."' AND fld_dest_id='".$rowqrydestinations->destid."' AND fld_delstatus='0' AND fld_student_id='".$statuasuid."'");
                                            }
                                            
                                        }
                              
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_read_status='1' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                        $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_read_status='1' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");

                                        if($cnt>=1 && $cnt1>=1)
                                                $newclass = "skip btn mainBtn completed";
                                        else
                                                $newclass = "skip btn mainBtn required";
                                        // Dual login update Destination Ends
                                        // Dual login update Task starts
                                            $tfirstuid= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_task_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid."'");
                                            $tsecnduid= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_task_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid1."'");
                                            if($tfirstuid != $tsecnduid){
                                                if($tfirstuid > $tsecnduid) {
                                                $ttempuid = $uid;
                                                $ttempuid1 = $uid1;
                                                }
                                                elseif($tsecnduid > $tfirstuid) {
                                                     $ttempuid = $uid1;
                                                     $ttempuid1 = $uid;
                                                }
                                                $seltaskdet= $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_task_unique_id FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$ttempuid."'");
                                                while($rowseltaskdet = $seltaskdet->fetch_assoc()){
                                                    extract($rowseltaskdet);
                                                    $taskcnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_task_id='".$fld_task_id."' AND fld_delstatus='0' AND fld_student_id='".$ttempuid1."'");
                                                    if($taskcnt == 0){
                                                        $ObjDB->NonQuery("INSERT INTO itc_exp_task_play_track(fld_exp_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
                                                                        VALUES('".$fld_exp_id."', '".$fld_dest_id."', '".$fld_task_id."', '".$ttempuid1."', '".$fld_schedule_id."', '".$fld_schedule_type."', '".$fld_read_status."', '".$ttempuid1."', '".$date."', '".$fld_task_unique_id."')");
                                                        }
                                                    }
                                                    
                                                }
                                                else{
                                                    $tfirstuid1= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_task_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_student_id='".$uid."'");
                                                    $tsecnduid1= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_task_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_student_id='".$uid1."'");
                                                    if($tfirstuid1 > $tsecnduid1) {
                                                        $tstatuasuid = $uid;
                                                        $tstatuasuid1 = $uid1;
                                                        

                                                    }
                                                    elseif($tsecnduid1 > $tfirstuid1) {
                                                         $tstatuasuid = $uid1;
                                                         $tstatuasuid1 = $uid;
                                                    }
                                                    else{
                                                       $tstatuasuid=''; 
                                                    }
                                                    echo $tstatuasuid;

                                                    if($tstatuasuid !=''){
                                                        $seltaskdet1= $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_task_unique_id FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$tstatuasuid."'");
                                                        while($rowseltaskdet1 = $seltaskdet1->fetch_assoc()){
                                                            extract($rowseltaskdet1);
                                                            $taskcnt1 = $ObjDB->SelectSingleValueInt("SELECT fld_read_status FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_task_id='".$fld_task_id."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$tstatuasuid1."'");
                                                            if($taskcnt1 == 0){
                                                               $ObjDB->NonQuery("UPDATE itc_exp_task_play_track SET fld_read_status = '".$fld_read_status."',fld_updated_by='".$tstatuasuid1."' WHERE fld_exp_id='".$fld_exp_id."' AND fld_schedule_id='".$fld_schedule_id."' AND fld_schedule_type='".$fld_schedule_type."'  AND fld_dest_id='".$fld_dest_id."' AND fld_task_id='".$fld_task_id."' AND fld_delstatus='0' AND fld_student_id='".$tstatuasuid1."'"); 
                                                            }
                                                            
                                                        }
                                                        
                                                    }
                                            }
                                        // Dual login update Task Ends
                                        // Dual login update resources starts
                                         $rfirstuid= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_res_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid."'");
                                         $rsecnduid= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_res_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid1."'");
                                         if($rfirstuid != $rsecnduid){
                                            if($rfirstuid > $rsecnduid) {
                                                $rtempuid = $uid;
                                                $rtempuid1 = $uid1;
                                                }
                                                elseif($rsecnduid > $rfirstuid) {
                                                     $rtempuid = $uid1;
                                                     $rtempuid1 = $uid;
                                                } 
                                                 $selresdet= $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_res_unique_id FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$rtempuid."'");
                                                 while($rowselresdet = $selresdet->fetch_assoc()){
                                                    extract($rowselresdet);
                                                    $rescnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_task_id='".$fld_task_id."' AND fld_res_id='".$fld_res_id."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$rtempuid1."'");
                                                    if($rescnt == 0){
                                                        $ObjDB->NonQuery("INSERT INTO itc_exp_res_play_track(fld_exp_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_res_unique_id) 
                                                                        VALUES('".$fld_exp_id."', '".$fld_dest_id."', '".$fld_task_id."', '".$fld_res_id."', '".$rtempuid1."', '".$fld_schedule_id."', '".$fld_schedule_type."', '".$fld_read_status."', '".$rtempuid1."', '".$date."', '".$fld_res_unique_id."')");
                                                        }
                                                    }
                                         }   
                                        else {
                                            $rfirstuid1= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_res_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_student_id='".$uid."'");
                                            $rsecnduid1= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_res_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_student_id='".$uid1."'");
                                            if($rfirstuid1 > $rsecnduid1) {
                                                $rstatuasuid = $uid;
                                                $rstatuasuid1 = $uid1;


                                            }
                                            elseif($rsecnduid1 > $rfirstuid1) {
                                                 $rstatuasuid = $uid1;
                                                 $rstatuasuid1 = $uid;
                                            }
                                            else{
                                               $rstatuasuid=''; 
                                            }
                                            if($rstatuasuid !=''){
                                                $selresdet1= $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_res_unique_id FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$rstatuasuid."'");
                                                while($rowselresdet1 = $selresdet1->fetch_assoc()){
                                                    extract($rowselresdet1);
                                                    $rescnt1 = $ObjDB->SelectSingleValueInt("SELECT fld_read_status FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_task_id='".$fld_task_id."' AND fld_res_id='".$fld_res_id."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$tstatuasuid1."'");
                                                    if($taskcnt1 == 0){
                                                       $ObjDB->NonQuery("UPDATE itc_exp_res_play_track SET fld_read_status = '".$fld_read_status."',fld_updated_by='".$tstatuasuid1."' WHERE fld_exp_id='".$fld_exp_id."' AND fld_schedule_id='".$fld_schedule_id."' AND fld_schedule_type='".$fld_schedule_type."'  AND fld_dest_id='".$fld_dest_id."' AND fld_task_id='".$fld_task_id."' AND fld_res_id='".$fld_res_id."' AND fld_delstatus='0' AND fld_student_id='".$tstatuasuid1."'"); 
                                                    }

                                                }

                                            }

                                        }
                                        // Dual login update resources ends
                                    }  // ends of if($uid1!='' and $uid1!=0){
                                    else {
                                        if($cnt>=1){
                                            $newclass = "skip btn mainBtn completed";
                                            $dcpstatus =1;
                                        }
                                        else{
                                            $newclass = "skip btn mainBtn required";
                                            $dcpstatus =0;
                                    }
                                    }
                                }  //ends of if($statusflag and $i==$rowqrydestinations->fld_next_order)
                                else
                                {
                                  $newclass = "skip btn mainBtn dim required";
                                  $dimflagd = 1;  
                                }

                                if($dimflagd == 1){
                                    $checkcom = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
           
                                    if($uid1 != '' and $uid1!=0) {
                                        
                                        $checkcom1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                    
                                    }
                                    if($uid1 != '' and $uid1!=0) {
                                        
                                        if($checkcom ==1 && $checkcom1 ==1){
                                            $newclass = "skip btn mainBtn dim completed";
                                        }
                                        else{
                                            $newclass = "skip btn mainBtn dim required";
                                        }

                                    }
                                    else {
                                        if($checkcom ==1){
                                        $newclass = "skip btn mainBtn dim completed";
                                        }
                                        else{
                                            $newclass = "skip btn mainBtn dim required";
                                        }
                                    }

                                }  //ends of if($dimflagd == 1)
                            } // ends of if($dstatus==1)
                            else if($dstatus==2)
                            {
                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                if($cnt===0)
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_exp_dest_play_track(fld_exp_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_dest_unique_id) 
								VALUES('".$expeditionid."', '".$rowqrydestinations->destid."', '".$uid."', '".$scheduleid."', '".$schtype."', '1', '".$uid."', '".$date."', '".$rowqrydestinations->fld_destunique_id."')");
                                }                                
                                if($uid1 != '' and $uid1!=0) {
                                    // Dual login destination update starts
                                        $firstuid2= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."'  AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                        $secnduid2= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."'  AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$uid1."'");
                                         if($firstuid2 != $secnduid2){
                                            if($firstuid2 > $secnduid2) {
                                                $tempuid2 = $uid;
                                                $tempuid12 = $uid1;
                                            }
                                            elseif($secnduid2 > $firstuid2) {
                                                 $tempuid2 = $uid1;
                                                 $tempuid12 = $uid;
                                            }
                                            $seldestdetails2 = $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_dest_unique_id FROM itc_exp_dest_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$tempuid2."'");
                                            $rowdest2 = $seldestdetails2->fetch_assoc();
                                            extract($rowdest2);
                                            $ObjDB->NonQuery("INSERT INTO itc_exp_dest_play_track(fld_exp_id, fld_dest_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_dest_unique_id) 
                                                                   VALUES('".$fld_exp_id."', '".$fld_dest_id."', '".$tempuid12."', '".$fld_schedule_id."', '".$fld_schedule_type."', '1', '".$tempuid12."', '".$date."', '".$fld_dest_unique_id."')");
                                        }
                                    // Dual login Destination ends
                                    // Dual login Task starts
                                        $tfirstuid2= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_task_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid."'");
                                        $tsecnduid2= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_task_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid1."'");
                                        if($tfirstuid2 != $tsecnduid2){
                                            if($tfirstuid2 > $tsecnduid2) {
                                            $ttempuid2 = $uid;
                                            $ttempuid12 = $uid1;
                                            }
                                            elseif($tsecnduid2 > $tfirstuid2) {
                                                 $ttempuid2 = $uid1;
                                                 $ttempuid12 = $uid;
                                            }
                                            $seltaskdet2= $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_task_unique_id FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$ttempuid2."'");
                                            while($rowseltaskdet2 = $seltaskdet2->fetch_assoc()){
                                                extract($rowseltaskdet2);
                                                $taskcnt2 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_task_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_task_id='".$fld_task_id."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$ttempuid12."'");
                                                if($taskcnt2 == 0){
                                                    $ObjDB->NonQuery("INSERT INTO itc_exp_task_play_track(fld_exp_id, fld_dest_id, fld_task_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_task_unique_id) 
                                                                    VALUES('".$fld_exp_id."', '".$fld_dest_id."', '".$fld_task_id."', '".$ttempuid12."', '".$fld_schedule_id."', '".$fld_schedule_type."', '".$fld_read_status."', '".$ttempuid12."', '".$date."', '".$fld_task_unique_id."')");
                                                    }
                                                }

                                            }
                                    // Dual login Task ends
                                    // Dual login resourse starts
                                        $rfirstuid2= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_res_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid."'");
                                         $rsecnduid2= $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_res_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid1."'");
                                         if($rfirstuid2 != $rsecnduid2){
                                            if($rfirstuid2 > $rsecnduid2) {
                                                $rtempuid2 = $uid;
                                                $rtempuid12 = $uid1;
                                            }
                                            elseif($rsecnduid2 > $rfirstuid2) {
                                                 $rtempuid2 = $uid1;
                                                 $rtempuid12 = $uid;
                                            } 
                                             $selresdet2= $ObjDB->QueryObject("SELECT fld_exp_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_res_unique_id FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$rtempuid2."'");
                                             while($rowselresdet2 = $selresdet2->fetch_assoc()){
                                                extract($rowselresdet2);
                                                $rescnt2 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_task_id='".$fld_task_id."' AND fld_res_id='".$fld_res_id."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' AND fld_student_id='".$rtempuid12."'");
                                                if($rescnt2 == 0){
                                                    $ObjDB->NonQuery("INSERT INTO itc_exp_res_play_track(fld_exp_id, fld_dest_id, fld_task_id, fld_res_id, fld_student_id, fld_schedule_id, fld_schedule_type, fld_read_status, fld_created_by, fld_created_date, fld_res_unique_id) 
                                                                    VALUES('".$fld_exp_id."', '".$fld_dest_id."', '".$fld_task_id."', '".$fld_res_id."', '".$rtempuid12."', '".$fld_schedule_id."', '".$fld_schedule_type."', '".$fld_read_status."', '".$rtempuid12."', '".$date."', '".$fld_res_unique_id."')");
                                                    }
                                                }
                                         }
                                    // Dual login resourse ends
                                        
                                 }
                                
                                $newclass = "skip btn mainBtn optional";
                                $dcpstatus =1;
                                $bfdestbadgestatus = "notrequired";
                                $templastbadgestatus ="notrequired";
                            }
                            
                            if($posttestcpstatus == 0){
                                $newclass = "skip btn mainBtn dim required";
                            }
                            $templastdeststatus = $dcpstatus;
                            
                            if($dstatus!=3)
                            {
                                if($passportdest =="passport"){
                                    $i=0;
                                    $dataparamdest =  $rowqrydestinations->destid.','.$i.','.$expeditionid.','.$scheduleid.','.$schtype.",".$passportdest.",".$passportmediaurldest;
                                }
                                else{
                                    $dataparamdest =  $rowqrydestinations->destid.','.$i.','.$expeditionid.','.$scheduleid.','.$schtype;
                                }
						?>
                        <a class="<?php echo $newclass; ?>" href="#assignment-expedition-tasks" id="btnassignment-expedition-tasks" name="<?php echo $dataparamdest;?>">
                            <div class="icon-Destination"></div>
                            <div class='onBtn tooltip' original-title='<?php echo $rowqrydestinations->destname; ?>'><?php echo $rowqrydestinations->shortname; ?></div>
                        </a>
                        <?php
                            }
                            if($dstatus!=3){
                            // Post test starts
                                    $qrystudestpost = $ObjDB->QueryObject("select a.fld_id AS posttestid, a.fld_test_name AS posttestname,b.fld_status AS postteststatus,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortnamed2,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS badgestatuspost  from itc_test_master as a
                                                                                left join itc_exptest_toogle as b on a.fld_id = b.fld_exptestid 
                                                                                where a.fld_destid ='".$rowqrydestinations->destid."' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_texpid='".$expeditionid."'
                                                                                and b.fld_ttaskid='0' and b.fld_tresid='0' and b.fld_tdestid='".$rowqrydestinations->destid."' and b.fld_created_by='".$shlteacherid."' and a.fld_delstatus ='0'");
                                    $studestpostcount1 = $qrystudestpost->num_rows;
                                    if($qrystudestpost->num_rows>0){
                                        $rowstudestpost = $qrystudestpost->fetch_assoc();
                                        extract($rowstudestpost);
                                        if($postteststatus !=3){
                                            $studestpostcount = $studestpostcount1;
                                            $desttestcntpre = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_dest_testplay_track WHERE fld_dest_id='".$rowqrydestinations->destid."' AND fld_dest_test_id='".$posttestid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                            if($desttestcntpre == '1'){
                                                $newclassd2 = "skip btn mainBtn dim completed";
                                                $posttestcpstatus=1;
                                            }
                                            else{
                                               $newclassd2 = "skip btn mainBtn dim required";
                                            }
                                            if($dcpstatus ==1 and $desttestcntpre !=1){
                                                $newclassd2 = "skip btn mainBtn required";
                                                $posttestcpstatus=0;
                                            }

                                           if($badgestatuspost == "notrequired"){
                                                $newclassd2 = "skip btn mainBtn optional";
                                                $posttestcpstatus=1;
                                            }
                                            $posttestcpstatustemp = $posttestcpstatus;
                                            ?>
                                                
                                                <a class="<?php echo $newclassd2; ?>" onclick="fn_desttest(<?php echo $posttestid;?>,<?php echo $postteststatus;?>,<?php echo $rowqrydestinations->destid;?>,<?php echo $expeditionid;?>,<?php echo $scheduleid;?>,<?php echo $schtype;?>);">
                                                    <div class="icon-Destination"></div>
                                                    <div class='onBtn tooltip' original-title='<?php echo $posttestname; ?>'><?php echo $shortnamed2; ?></div>
                                                </a>
                
                                            <?php
                                        } // posttestatus ends
                                    }
                                    else{
                                        $posttestcpstatus=1;
                                    }//if post ends
                            }
                        $i++;
                                    $beforedestid=$rowqrydestinations->destid; //taken before destid
                                    $cnt1++;                           
                            if($cnt>=1)
							$statusflag = true;
                            else if($dstatus!=1)
                                    $statusflag = $statusflag;
						else
							$statusflag = false;
                	}
				} 
                                // Exp post test start
                                $qrystuexppost = $ObjDB->QueryObject("select a.fld_id AS exposttestid, a.fld_test_name AS expoattestname,fn_shortname (CONCAT(a.fld_test_name), 1) AS shortnamee2,b.fld_status as expostteststatus,(CASE b.fld_status WHEN 1 THEN 'required' WHEN 2 THEN 'notrequired' WHEN 3 THEN '' END) AS exbadgestatuspost  from itc_test_master as a
                                                                        left join itc_exptest_toogle as b on a.fld_id = b.fld_exptestid 
                                                                        where a.fld_destid ='0' and a.fld_prepostid ='2' and b.fld_tprepost ='2' and b.fld_flag=1 and b.fld_texpid='".$expeditionid."'
                                                                        and b.fld_ttaskid='0' and b.fld_tresid='0' and b.fld_tdestid='0' and b.fld_created_by='".$shlteacherid."' and b.fld_status IN(1,2) and a.fld_delstatus ='0'");
                                if($qrystuexppost->num_rows>0){
                                    $rowstuexppost = $qrystuexppost->fetch_assoc();
                                    extract($rowstuexppost);
                                    if($expostteststatus !=3){

                                            $exptestcntpost = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_testplay_track WHERE fld_exp_id='".$expeditionid."' AND fld_exp_test_id='".$exposttestid."' AND fld_schedule_id='".$scheduleid."' AND fld_read_status='1' AND fld_delstatus='0' AND fld_student_id='".$uid."'");
                                           
                                            if($exptestcntpost == '1'){
                                                $newclasse2 = "skip btn mainBtn dim completed";
                                            }
                                            else{
                                                $newclasse2 = "skip btn mainBtn required";
                                            }
                                            if($templastdeststatus !=1 or $posttestcpstatus !=1){
                                                $newclasse2 = "skip btn mainBtn dim required";
                                            }
                                            
                                            if($exbadgestatuspost == "notrequired"){
                                                $newclasse2 = "skip btn mainBtn optional";
                                            }
                                       ?>
                                            <a class="<?php echo $newclasse2; ?>" onclick="fn_exptest(<?php echo $exposttestid;?>,<?php echo $expostteststatus;?>,<?php echo $expeditionid;?>,<?php echo $scheduleid;?>,<?php echo $schtype;?>)">
                                                <div class="icon-Destination"></div>
                                                <div class='onBtn tooltip' original-title='<?php echo $expoattestname; ?>'><?php echo $shortnamee2; ?></div>
                                            </a>
                                    <?php

                                    }
                                }
                            //Exp post test ends
                            ?>
            </div>
        </div>
    </div>
    <input type="hidden" id="hidexpeditionid" value="<?php echo $expeditionid;?>" />
    <input type="hidden" id="mediaurl" value="<?php echo $urlformedia;?>" />
    <script>
		if($('#assignment-expedition-preview').prevAll('section:eq(1)').attr('class')=='blueWindow1')
		{
			setTimeout(function(){$('#changeclass').css('color','#537F98')},1000);
		}
		else
		{
			setTimeout(function(){$('#changeclass').css('color','#FFFFFF')},1000);
		}
	</script>
</section>