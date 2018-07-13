<?php

@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");

$ids = isset($method['id']) ? $method['id'] : '';	
$id = explode("~",$ids);

$scheduleid=$id[0];
$missionid=$id[1];
$schtype=$id[2];
$mistypename=$id[3];

$mis=explode("-",$missionid);

$misid=$mis[0];
$rotation=$mis[1]-1;


$missionname = $ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$misid."' AND fld_delstatus='0'");

$rowid=$ObjDB->SelectSingleValueInt("SELECT fld_row_id FROM itc_class_rotation_mission_schedulegriddet where fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$misid."' AND fld_rotation='".$mis[1]."' AND fld_flag='1' AND fld_student_id='".$uid."'");

?>
<section data-type='2home' id='assignment-missionassignment'>
    <script type="text/javascript" charset="utf-8">	
	$.getScript('assignment/mission/assignment-mission.js');
   </script>
   <div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
      			<p class="dialogTitle"><?php echo $missionname." / Rotation".$rotation; ?> </p>
                </div>
    	</div> 
       
        <div class='row rowspacer'>        	
        	<div class='twelve columns formBase'>
                    <div class='eleven columns centered insideForm'>
                        <ul>
                        <?php
                           
                          $qrystudents=$ObjDB->QueryObject("SELECT b.fld_student_id,CONCAT(a.fld_fname,' ',a.fld_lname) as name FROM itc_user_master as a LEFT JOIN itc_class_rotation_mission_schedulegriddet as b on a.fld_id=b.fld_student_id where b.fld_schedule_id='".$scheduleid."' AND b.fld_row_id='".$rowid."' AND b.fld_mission_id='".$misid."' AND b.fld_rotation='".$mis[1]."' AND a.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_student_id<>'".$uid."'");
                           $i=0;      
                          if($qrystudents->num_rows>0)
                          {
                              while($row=$qrystudents->fetch_assoc())
                              {
                                  extract($row);
                                  $i++;
                              ?>
                            <li>
                                <input type="checkbox" checked="checked" name="students" id="checkbox-<?php echo $i;?>" value="<?php echo $fld_student_id; ?>" class="custom"/>
                                <label for="checkbox-<?php echo $i;?>"><?php echo $name; ?></label>
                            </li>
                            <?php
                              }
                              
                          }
                        ?>
                        </ul>
                        
                        <div class="row rowspacer">
                            <input type="button" name="btn" value="START" style="cursor:pointer;" onclick="fn_showmission(<?php echo $scheduleid.",".$schtype.",".$misid.",".$uid ;?>,'<?php echo $mistypename; ?>')">
                        </div>
                        
                        
                    </div>
                </div>
        </div>
</section>