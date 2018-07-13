<?php 
@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(',',$id);
$classid = $id[2];
$sid=$id[0];
$scheduletype = $id[1];
$cond='0';
$studenttype = 1;
$licenseid = 0;
$sname = '';
$startdate = '';
$enddate = '';
$lname = '';
$licenseqrycount=0;
$licensename = '';
$schflag=0;
$qryclassdet=$ObjDB->QueryObject("SELECT COUNT(a.fld_id) AS countid,b.fld_class_name as classname  FROM itc_class_student_mapping AS a LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id WHERE a.fld_class_id='".$classid."'  AND a.fld_flag=1");

$row=$qryclassdet->fetch_assoc();
extract($row);

$classmapstu=$countid;

if($sid==0)
{
	$maintitle = "Add a new schedule";
	$subtitle = "Provide details about your new schedule below.";
	$sname=$classname;
}
else
{
	$maintitle = "Edit this schedule";
	$subtitle = "Change the desired properties of your schedule below.";
}
//get schedule details
if($scheduletype==1){
	$cond = "SELECT fld_student_id 
			FROM itc_class_sigmath_student_mapping 
			WHERE fld_sigmath_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id AS studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_sigmath_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id)  
				 FROM itc_user_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_sigmath_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Signature Math Lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_start_date AS startdate, fld_end_date AS enddate, fld_student_type AS studenttype 
								FROM itc_class_sigmath_master 
								WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}	
	$tbl = " itc_class_sigmath_master ";	
}
if($scheduletype==2 or $scheduletype==6){
	
	$date=date("Y-m-d");
	$sdate=$ObjDB->SelectSingleValue("SELECT fld_startdate 
										FROM itc_class_rotation_schedulegriddet 
										WHERE fld_schedule_id='".$sid."' AND fld_rotation IN (SELECT MIN(fld_rotation) FROM itc_class_rotation_schedulegriddet 
											WHERE fld_schedule_id='".$sid."' AND fld_flag='1') AND fld_flag='1' ORDER BY fld_id ASC LIMIT 0,1");	
	if($sdate>$date)
	{
		$sflag=0;
	}
	else
	{
		$sflag=1;
	}	
	$cond = "SELECT fld_student_id 
				FROM itc_class_rotation_schedule_student_mappingtemp 
				WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT  COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Algebra Acadamy";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype,fld_flag As schflag 
											FROM itc_class_rotation_schedule_mastertemp 
											WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_rotation_schedule_mastertemp ";	
}

if($scheduletype==17){
	
	$date=date("Y-m-d");
	$sdate=$ObjDB->SelectSingleValue("SELECT fld_startdate 
										FROM itc_class_rotation_expschedulegriddet 
										WHERE fld_schedule_id='".$sid."' AND fld_rotation IN (SELECT MIN(fld_rotation) FROM itc_class_rotation_expschedulegriddet 
											WHERE fld_schedule_id='".$sid."' AND fld_flag='1') AND fld_flag='1' ORDER BY fld_id ASC LIMIT 0,1");	
	if($sdate>$date)
	{
		$sflag=0;
	}
	else
	{
		$sflag=1;
	}	
	$cond = "SELECT fld_student_id 
				FROM itc_class_rotation_expschedule_student_mappingtemp 
				WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT  COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Algebra Acadamy";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype,fld_flag As schflag 
											FROM itc_class_rotation_expschedule_mastertemp 
											WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_rotation_expschedule_mastertemp ";	
}

if($scheduletype==19){
	
	$date=date("Y-m-d");
	$sdate=$ObjDB->SelectSingleValue("SELECT fld_startdate 
										FROM itc_class_rotation_modexpschedulegriddet 
										WHERE fld_schedule_id='".$sid."' AND fld_rotation IN (SELECT MIN(fld_rotation) FROM itc_class_rotation_modexpschedulegriddet 
											WHERE fld_schedule_id='".$sid."' AND fld_flag='1') AND fld_flag='1' ORDER BY fld_id ASC LIMIT 0,1");	
	if($sdate>$date)
	{
		$sflag=0;
	}
	else
	{
		$sflag=1;
	}	
	$cond = "SELECT fld_student_id 
				FROM itc_class_rotation_modexpschedule_student_mappingtemp 
				WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT  COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Algebra Acadamy";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype,fld_flag As schflag 
											FROM itc_class_rotation_modexpschedule_mastertemp 
											WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_rotation_modexpschedule_mastertemp ";	
}

if($scheduletype==20){
	
	$date=date("Y-m-d");
	$sdate=$ObjDB->SelectSingleValue("SELECT fld_startdate 
										FROM itc_class_rotation_mission_schedulegriddet 
										WHERE fld_schedule_id='".$sid."' AND fld_rotation IN (SELECT MIN(fld_rotation) FROM itc_class_rotation_mission_schedulegriddet 
											WHERE fld_schedule_id='".$sid."' AND fld_flag='1') AND fld_flag='1' ORDER BY fld_id ASC LIMIT 0,1");	
	if($sdate>$date)
	{
		$sflag=0;
	}
	else
	{
		$sflag=1;
	}	
	$cond = "SELECT fld_student_id 
				FROM itc_class_rotation_mission_student_mappingtemp 
				WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT  COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Algebra Acadamy";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype,fld_flag As schflag 
											FROM itc_class_rotation_mission_mastertemp 
											WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_rotation_mission_mastertemp ";	
}

if($scheduletype==3){
	$cond = "SELECT fld_student_id 
			 FROM itc_class_dyad_schedule_studentmapping 
			 WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT  COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Traditional lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype 
										 FROM itc_class_dyad_schedulemaster 
										 WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_dyad_schedulemaster ";	
}
if($scheduletype==4){
	$cond = "SELECT fld_student_id 
			 FROM itc_class_triad_schedule_studentmapping 
			 WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username
				 FROM itc_user_master AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Traditional lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype 
										 FROM itc_class_triad_schedulemaster 
										 WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_triad_schedulemaster ";	
}
if($scheduletype==5){
	$cond = "SELECT fld_student_id 
			 FROM itc_class_indassesment_student_mapping 
			 WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Traditional lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype 
										 FROM itc_class_indassesment_master 
										 WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_indassesment_master ";	
}
if($scheduletype==15){
	$cond = "SELECT fld_student_id 
			 FROM itc_class_exp_student_mapping 
			 WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
				 
	$type = "Traditional lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype 
										 FROM itc_class_indasexpedition_master 
										 WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_indasexpedition_master ";	
}
/*mission*/
if($scheduletype==18){
	$cond = "SELECT fld_student_id 
			 FROM itc_class_mission_student_mapping 
			 WHERE fld_schedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
				 FROM itc_user_master AS a LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname";
	$studentcount =$ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id)
				 FROM itc_user_master AS a LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
				 
	$type = "Traditional lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate, fld_student_type AS studenttype 
										 FROM itc_class_indasmission_master 
										 WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}
	$tbl = " itc_class_indasmission_master ";	
}

//pd
if($scheduletype==16){
	$cond = "SELECT fld_student_id 
			FROM itc_class_pdschedule_student_mapping 
			WHERE fld_pdschedule_id='".$sid."' AND fld_flag='1'";
	$selected = "SELECT a.fld_id AS studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username 
                                FROM itc_user_master AS a 
                               LEFT JOIN itc_class_pdschedule_student_mapping AS b ON a.fld_id=b.fld_student_id 
                               WHERE b.fld_pdschedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY sname" ;
	$studentcount =$ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id) FROM itc_user_master AS a 
                                                    LEFT JOIN itc_class_pdschedule_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                                    WHERE b.fld_pdschedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0'");
	$type = "Signature Math Lab";
	$schdetailsqry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_start_date AS startdate, fld_end_date AS enddate, fld_student_type AS studenttype 
                                                FROM itc_class_pdschedule_master WHERE fld_id='".$sid."'");
	if($schdetailsqry->num_rows>0){
		$res = $schdetailsqry->fetch_assoc();
		extract($res);
	}	
	$tbl = " itc_class_pdschedule_master ";	
}

//pd
//get license details
if($sid!=0){
	$qry = $ObjDB->QueryObject("SELECT a.fld_license_id AS licenseid, b.fld_license_name AS licensename,fn_shortname(b.fld_license_name,2) AS shortname  
								FROM ".$tbl." AS a LEFT JOIN itc_license_master AS b ON a.fld_license_id=b.fld_id 
								WHERE a.fld_id='".$sid."'");
	if($qry->num_rows>0)
		extract($qry->fetch_assoc());
}
$trackid = $ObjDB->SelectSingleValueInt("SELECT fld_id 
										FROM itc_license_track 
										WHERE fld_license_id='".$licenseid."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'  
											AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");
?>
<section data-type='#class-newclass' id='class-newclass-newschedulestep'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle"><?php echo $maintitle;?></p>
                <p class="darkSubTitle"><?php echo $subtitle;?></p>
            </div>
        </div>	
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<form id="scheduleform" name="sform">
                        	<div class="formSubHeading">Basic schedule information</div>
                            <div class='row rowspacer'>
                                 <div class='four columns'>
                                 	Schedule name<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Schedule name' required='' type='text' id="sname" name="sname" value="<?php echo $sname ;?>">
                                        </dt>                                        
                                    </dl>
                                </div>                                
                                <div class='four columns'>
                                	Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input  id="startdate" readonly name="startdate" placeholder='Start date' type='text' value="<?php if($startdate!=''){ echo date("m/d/Y",strtotime($startdate));}?>" <?php if(($scheduletype==2 or $scheduletype==6) and $schflag==1){?> readonly class="tooltip" title="To change the schedule start date, You must go to View Class Calendar and change the rotations start date."<?php } ?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns' id="schenddate" <?php if($scheduletype!=5){?> style="display:none;" <?php } ?>>
                                	End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                           <input  id="enddate" readonly name="enddate" placeholder='End date' type='text' value="<?php if($enddate!=''){ echo date("m/d/Y",strtotime($enddate));}?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>                             
                            <div class='row rowspacer'>
                                <div class='six columns'>
                                    <input type="hidden" id="scrollhid" value="0" />
                                	Select license<span class="fldreq">*</span><span id="remainusers"></span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="licenseid" id="licenseid" value="<?php echo $licenseid; ?>"  onchange="fn_loadscheduletemplate()" />
                                                <?php if($licenseid!=0){?><input type="hidden" id="lic_<?php echo $licenseid; ?>" name="<?php echo $trackid; ?>" /><?php }?>
                                                <a class="selectbox-toggle tooltip" role="button" data-toggle="selectbox" href="#" title="<?php echo $licensename;?>">
                                                    <span class="selectbox-option input-medium" data-option="" id="clearlicense"><?php if($licenseid!=0){ echo $shortname;} else{?>Select license <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <?php if($licenseid==0){?>
                                                    <div class="selectbox-options" id="license">
                                                        <input type="text" class="selectbox-filter" placeholder="Search license">
                                                        <ul role="options">
                                                        <?php 
                                                        $qrylicense = $ObjDB->QueryObject("SELECT b.fld_id as trackid, a.fld_id as licenseid, a.fld_license_name as lname, 
																								fn_shortname(a.fld_license_name,2) AS slname 
																							FROM itc_license_master AS a LEFT JOIN itc_license_track AS b ON a.fld_id=b.fld_license_id 
																							WHERE a.fld_delstatus='0' AND b.fld_school_id='".$schoolid."' AND b.fld_user_id='".$indid."' 
																								AND '".date("Y-m-d")."' BETWEEN b.fld_start_date AND b.fld_end_date AND b.fld_delstatus='0'
																							GROUP BY licenseid ORDER BY lname");   
														$licenseqrycount =  $qrylicense->num_rows;                                               
                                                        if($licenseqrycount>0){ 
                                                            while($reslicense=$qrylicense->fetch_assoc()){
                                                                extract($reslicense);
																$chklicense = $ObjDB->QueryObject("SELECT fld_id 
																								  FROM itc_license_cul_mapping 
																								  WHERE fld_license_id='".$licenseid."' AND fld_active='1'
																								  UNION ALL 
																								  
																								  SELECT fld_id 
																								  FROM itc_license_mod_mapping 
																								  WHERE fld_license_id='".$licenseid."' AND fld_active='1' 
																								  	AND (fld_type='1' OR fld_type='2' OR fld_type='7')
																								  UNION ALL 
																								  SELECT fld_id 
																								  FROM itc_license_exp_mapping 
																								  WHERE fld_license_id='".$licenseid."' AND fld_flag='1'
                                                                                                                                                                                                  UNION ALL 
                                                                                                                                                                                                  SELECT fld_id 
                                                                                                                    FROM itc_license_mission_mapping 
                                                                                                                    WHERE fld_license_id='".$licenseid."' AND fld_flag='1'             
                                                                                                            UNION ALL 
                                                                                                                    SELECT fld_id 
																								  FROM itc_license_pd_mapping 
																								  WHERE fld_license_id='".$licenseid."' AND fld_active='1'");
                                                                                                                                        
                                                                                      
																if($chklicense->num_rows>0){?>
                                                                	<li><a tabindex="-1" href="#" data-option="<?php echo $licenseid;?>"  id="lic_<?php echo $licenseid; ?>" name="<?php echo $trackid; ?>" title="<?php echo $lname;?>" class="tooltip"><?php echo $slname;?> </a></li>
                                                                    
                                                            <?php }
                                                            }
                                                        }?>      
                                                        </ul>
                                                    </div>
                                               <?php }?>
                                            </div>
                                        </dt>                                         
                                    </dl>
                                </div>
                                
                                    <?php if($sid!=0 or $licenseqrycount==1){
                                        if($licenseqrycount==1){
                                            ?>
                                           <input type="hidden" id="lic_<?php echo $licenseid; ?>" name="<?php echo $trackid; ?>" />
                                <script>
                                            $('#clearlicense').html("<?php echo $lname; ?>");
                                            $('#licenseid').val('<?php echo $licenseid; ?>');
                                            $('#license').hide();
                                            fn_loadscheduletemplate('<?php echo $licenseid; ?>');
                                            </script>    
                                      								
                                        <?php 
                                        } ?> <script>
											fn_loadscheduletemplate('<?php echo $licenseid; ?>');
                                                                                         </script>
										<?php 										                                        
                                     }
                                     ?>
                                                               
                                <div class='six columns'>
                                	Select student type<span class="fldreq">*</span><span id="studentcountdiv"><?php echo "(".$countid.")";?></span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="studenttype" id="studenttype" value="<?php echo $studenttype; ?>" onchange="fn_blockcheck(this.value);" />                                                
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php if($studenttype==1){ echo "Include all students";} else{ echo "Include selected students";?> <script> $('#studentcountdiv').hide();</script> <?php }?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <ul role="options">
                                                        <li><a tabindex="-1" href="#" data-option="1" onclick="$('#studentlist').hide();$('#studentcountdiv').show()">Include all students</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="2" onclick="$('#studentlist').show();$('#studentcountdiv').hide();">Include selected students</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>                                     
                                    </dl>
                                </div>                         
                            </div>
                            <div class="row rowspacer" id="loadtemplate">
                            	                                                                  
                            </div>                            
                            <script type="text/javascript" language="javascript">
								<?php if($sid!=0){ ?>								
								<?php }?>
								$(function() {
									$('#testrailvisible0').slimscroll({
										width: '410px',
										height:'366px',
										size: '7px',
										railVisible: true,
                                                                                alwaysVisible: true, 
										allowPageScroll: false,
										railColor: '#F4F4F4',
										opacity: 1,
										color: '#d9d9d9',
										 wheelStep: 1
									});
									$('#testrailvisible1').slimscroll({
										width: '410px',
										height:'366px',
										size: '7px',
										railVisible: true,
                                                                                alwaysVisible: true,
										allowPageScroll: false,
										railColor: '#F4F4F4',
										opacity: 1,
										color: '#d9d9d9',
                                                                                 wheelStep: 1
									});
									$("#list9").sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										items: "div[class='draglinkleft']",
										receive: function(event, ui) { 
											$("div[class=draglinkright]").each(function(){ 
												if($(this).parent().attr('id')=='list9'){
													fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
												}
											});											
										}
									});
								
									$( "#list10" ).sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										receive: function(event, ui) { 
											$("div[class=draglinkleft]").each(function(){ 
												if($(this).parent().attr('id')=='list10'){
													fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
												}
											});								
										}
									});								
									
								});																	
							</script>  
                            <div class="row rowspacer" id="studentlist" <?php if($studenttype==1){?>style="display:none;"<?php }?>>
                            	<div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
										$qrystudent= $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname,a.fld_username as username
												 								  FROM itc_class_student_mapping AS b LEFT JOIN itc_user_master AS a  ON a.fld_id=b.fld_student_id  
																				  WHERE b.fld_class_id='".$classid."' AND b.fld_flag='1' AND b.fld_student_id NOT IN(".$cond.") 
																				  ORDER BY sname");
									?>
                                        <div class="dragtitle">Students available(<span id="nostudentleftdiv1"> <?php echo $qrystudent->num_rows;?></span>)</div>
                                        <div class="draglinkleftSearch" id="s_list9" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible0" >
                                            <div id="list9" class="dragleftinner droptrue1">
                                             <?php 											 	
                                                if($qrystudent->num_rows > 0){													
                                                    while($rowsstudent = $qrystudent->fetch_assoc()){
                                                        extract($rowsstudent);
                                                        ?>
                                                    <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                                        <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>);"></div>
                                                    </div> 
                                            <?php 
                                                    }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0);">add all students</div>
                                    </div>
                                </div>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Students in your schedule (<span id="nostudentrightdiv1"><?php if($sid!=0){ echo $studentcount;}else { echo "0"; } ?></span>)</div>
                                        <div class="draglinkleftSearch" id="s_list10" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible1">
                                            <div id="list10" class="dragleftinner droptrue1">
                                             <?php 
											 if($sid!=0){
                                                $qryclassstudentmap=$ObjDB->QueryObject($selected);
                                                if($qryclassstudentmap->num_rows > 0){                                                    
                                                    while($rowstudent = $qryclassstudentmap->fetch_assoc()){
                                                        extract($rowstudent);
                                                    ?>
                                                            <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $sname; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>);"></div>
                                                            </div>
                                            <?php 	}
                                                }
											 }
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0);">remove all students</div>
                                    </div>
                                </div>
                            </div>                     
                                                     
                            <div class="row">
                            	<div class='row rowspacer' id="rotcontent"></div> 
                                <div class='row rowspacer' id="units"></div>                                    
                        		<div class='row rowspacer' id="ipls"></div>   
                            </div>
                            </form>
                            <div class="row rowspacer">
                            	<div class='span10 offset1'>
                                	<div class='row rowspacer' id="instructionstages"></div>  
                            	</div>               
                            </div>  
                            
                            <script type="text/javascript" language="javascript">
							<?php if($sid!=0){?>
								$("#enddate").datepicker( {
									onSelect: function(dateText,inst){
										$(this).parents().parents().removeClass('error');
									},
									minDate : '<?php echo date("m/d/Y",strtotime($startdate)); ?>'
								});
							<?php }
								if($schflag==0)
							   { 
							?>
								$("#startdate").datepicker( {
									onSelect: function(dateText,inst){										
										$(this).parents().parents().removeClass('error');
										
										$("#enddate").val('');
										$('#enddate').datepicker('destroy');
										$("#enddate").datepicker( {
											onSelect: function(dateText,inst){
												$(this).parents().parents().removeClass('error');
											},
											minDate : dateText
										});
									}
								});
							<?php
							 	}
							 ?>
								
								$(function(){
									$("#scheduleform").validate({
										ignore: "",
											errorElement: "dd",
											errorPlacement: function(error, element) {
												$(element).parents('dl').addClass('error');
												error.appendTo($(element).parents('dl'));
												error.addClass('msg'); 	
										},
										rules: { 
											sname: { required: true, lettersonlyschedule: true,placeholder:true },
											startdate: { required: true },
											enddate: {
												required: {
													depends: function(element){
														return ($("#scheduletype").val()==5 || $("#scheduletype").val()==15 || $("#scheduletype").val()==18) 
													}
												}
											},													
											scheduletype: { required: true }	
										}, 
										messages: { 
											sname:{  required:  "please enter schedule name", placeholder:"please enter schedule name"  },                
											startdate:{  required: "Select the start date" },
											enddate:{ required: "Select the end date", greaterThan: "Enddate must be greater"},											
											scheduletype: {  required: "please select template" }								
										},
										highlight: function(element, errorClass, validClass) {
											$(element).parent('dl').addClass(errorClass);
											$(element).addClass(errorClass).removeClass(validClass);
										},
										unhighlight: function(element, errorClass, validClass) {
											if($(element).attr('class') == 'error'){
													$(element).parents('dl').removeClass(errorClass);
													$(element).removeClass(errorClass).addClass(validClass);
											}
										},
										invalidHandler: function(form, validator) {
											var errors = validator.numberOfInvalids();
											if (errors!=0) {                    
												tophei=$(validator.errorList[0].element).offset().top;
												tophei=tophei-120;
												$("html, body").animate({ scrollTop: tophei }, 500);
											}
										} ,
										onkeyup: false,
										onblur: true
									});
								});	
							</script>                                                                                                               
                    	
                    </div>
                </div>
                <input type="hidden" id="hidscheduleid" value="<?php echo $id[0];?>" />
                <input type="hidden" id="hidscheduletype" value="<?php echo $scheduletype;?>" /> 
                <input type="hidden" id="hidpagetitle" value="" />  
                <input type="hidden" id="hidpoints" value="" />  
                <input type="hidden" id="hidgrades" value="" /> 
                <input type="hidden" name="assgnstudents" id="assignstudents" value="<?php echo $classmapstu;?>" />                 
            </div>
        </div>  
 	</div>   
</section>
<?php
	@include("footer.php");