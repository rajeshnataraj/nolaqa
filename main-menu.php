<?php
@include("sessioncheck.php");

if($sessmasterprfid == 10){
	$photo = $ObjDB->SelectSingleValue("SELECT fld_profile_pic FROM itc_user_master WHERE fld_id= '".$uid."' AND fld_delstatus='0'");
	$photo1 = $ObjDB->SelectSingleValue("SELECT fld_profile_pic FROM itc_user_master WHERE fld_id= '".$uid1."' AND fld_delstatus='0'");
?>
<section class='blueWindow1' data-type='2home' id='stulog'>
	<script language="javascript" type="text/javascript">
		$.getScript("assignment/sigmath/assignment-sigmath-test.js");
    </script>
    <style>
		.thumbimg1 {
			left: 0;
			margin: 10px 0 0 15px;
			max-width: 100%;
			position: absolute;
			top: 0;
		}
	</style>
	<div class='container'>
    	<div class='row level1Bump'>
        	<div class='twelve columns'>
            	<div class="row">
                	<div class="six columns">
                    	<div class="divstuloginboxleft">
                        	<a class='skip btn main' href='javascript:void(0);' id='student1' name='student1' style="float:left;">
                            	<?php if($photo != "no-image.png" && $photo != ''){ ?>
                                	<div class="icon-synergy-user">
                                        <img class="thumbimg1" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo; ?>&w=70&h=70&q=100" />
                                	</div>
                                <?php } else {?>
                                <div class='icon-synergy-user companionUserButtonIcon'></div>
                                <?php } ?>
                            </a>
                            <div class="userName"><?php echo $sessusrfullname; ?></div>
                            <div class="userSummaryLight"></div>
                            <div class="userSummaryBold"></div>
                        </div>
                    </div>
                    <div class="six columns alpha">
                    	<?php if($uid1 == ''){ ?>
                    	<div class="divstuloginboxright">
                        	<a class='skip btn main dim' href='javascript:void(0);' id='student1' name='student1' style="float:left;">
                                <?php if($photo1 != "no-image.png" && $photo1 != ''){ ?>
                                	<div class="icon-synergy-user">
                                        <img class="thumbimg1" src="thumb.php?src=<?php echo __CNTPPPATH__.$photo1; ?>&w=70&h=70&q=100" />
                                	</div>
                                <?php } else {?>
                                <div class='icon-synergy-user companionUserButtonIcon'></div>
                                <?php } ?>
                            </a>
                            <div class="companionUserTitle">Companion User</div>
                            <div class="companionUserSub"><a href="javascript:void(0);" onClick="showduallogin();">click here to login</a></div>
                        </div>
                        <?php }else { ?>
                        <div class="divstuloginboxright">
                        	<a class='skip btn main' href='javascript:void(0);' id='student2' name='student2' style="float:left;">
                            	<div class='icon-synergy-user companionUserButtonIcon'></div>
                            </a>
                            <div class="userName"><?php echo $sessusrfullname1; ?></div>
                            <div class="userSummaryLight"></div>
                            <div class="userSummaryBold"></div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script language="javascript" type="text/javascript">
		var swfJSPreLoaderConfig = {
			'assets':['assignment/science/Presentor.swf'],
			
			'assetLoaded': function( asset, bytes, status ){
				console.log(asset + ' loaded');
			},
			
			'loadComplete': function(){ 
				console.log('all assets loaded'); 
			},
			
			'loadError': function(){ 
				console.log('load error'); 
			}
		}			
		$.getScript("js/swfobject.js");	
		$.getScript("js/swfJSPreLoader.js");
    </script>   
</section>
<?php 
	} 
?>
<section class='blueWindow2' data-type='2home' id='home'>
    <div class='container'>
    	<div class='row formBase <?php if($sessmasterprfid != 10){ ?> level1Bump <?php } ?>'>
            <div class='eleven columns centered '>
                <div class="dash-welcome">
                    <div class="dashMessage"></div>
                    <div class="dashDual" <?php if($uid1!='') { ?>style="font-size:35px;"<?php }?>><?php echo $sessusrfullname; if($uid1!='') { echo " / ".$sessusrfullname1;}?></div>
                </div>
                <?php 
				if($sessmasterprfid == 10)
				{
					if($uid1=='') 
					{
						$qrysigmath = $ObjDB->QueryObject("SELECT a.fld_id AS sid, a.fld_schedule_name AS sname, fn_shortname(a.fld_schedule_name,1) AS shortname, a.fld_end_date AS edate 
														FROM itc_class_sigmath_master AS a 
														LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id 
														LEFT JOIN itc_class_master AS c ON c.fld_id=a.fld_class_id 
														LEFT JOIN itc_license_track AS d ON a.fld_license_id=d.fld_license_id
														WHERE a.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_student_id='".$uid."' AND DATE(a.fld_start_date) <= DATE(NOW()) 
															AND DATE(a.fld_end_date) >= DATE(NOW()) AND c.fld_delstatus='0' AND c.fld_lock='0' AND d.fld_school_id='".$schoolid."' 
															AND d.fld_user_id='".$indid."' AND d.fld_start_date<='".date("Y-m-d")."' AND d.fld_end_date >='".date("Y-m-d")."'");

						$qrydyadtriad = $ObjDB->QueryObject("SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module') AS schedulename, a.fld_id AS scheduleid, f.fld_enddate, 
																CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation-1) AS modulename, 1 AS schtype 
															FROM `itc_class_rotation_schedule_mastertemp` AS a 
															LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_scheduledate as f on b.fld_schedule_id=f.fld_schedule_id and b.fld_rotation=f.fld_rotation
															LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' 
																AND a.fld_startdate<='".date("Y-m-d")."' AND f.fld_startdate<='".date("Y-m-d")."' AND f.fld_enddate>='".date("Y-m-d")."' 
																AND a.fld_moduletype='1' AND b.fld_type='1' AND e.fld_school_id='".$schoolid."' AND b.fld_flag='1' AND f.fld_flag='1'
																AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' AND e.fld_end_date >='".date("Y-m-d")."'	  
																	UNION ALL
															SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Math Module') AS schedulename, a.fld_id AS scheduleid, f.fld_enddate, 
																CONCAT(c.fld_mathmodule_name,' / Rotation ',b.fld_rotation-1) AS modulename, 4 AS schtype 
															FROM `itc_class_rotation_schedule_mastertemp` AS a 
															LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_scheduledate as f on b.fld_schedule_id=f.fld_schedule_id and b.fld_rotation=f.fld_rotation
															LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
																AND a.fld_startdate<='".date("Y-m-d")."' AND f.fld_startdate<='".date("Y-m-d")."' AND f.fld_enddate>='".date("Y-m-d")."' 
																AND a.fld_moduletype='2' AND b.fld_type='2' AND e.fld_school_id='".$schoolid."' AND b.fld_flag='1' AND f.fld_flag='1'
																AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' AND e.fld_end_date >='".date("Y-m-d")."'	
																	UNION ALL
															SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Dyad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, 
																CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 2 AS schtype 
															FROM `itc_class_dyad_schedulemaster` AS a 
															LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
															LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															LEFT JOIN itc_class_dyad_schedule_studentmapping AS f ON (f.fld_schedule_id=b.fld_schedule_id AND f.fld_student_id='".$uid."') 
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') 
																AND DATE(a.fld_startdate)<=DATE(NOW()) AND b.fld_startdate<='".date("Y-m-d")."' AND b.fld_enddate>='".date("Y-m-d")."' 
																AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' AND f.fld_flag='1'
																	UNION ALL
															SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Triad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, 
																CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 3 AS schtype 
															FROM `itc_class_triad_schedulemaster` AS a 
															LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
															LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															LEFT JOIN itc_class_triad_schedule_studentmapping AS f ON (f.fld_schedule_id=b.fld_schedule_id AND f.fld_student_id='".$uid."') 
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') 
																AND DATE(a.fld_startdate)<=DATE(NOW()) AND b.fld_startdate<='".date("Y-m-d")."' AND b.fld_enddate>='".date("Y-m-d")."' 
																AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' AND f.fld_flag='1' 		 		  
																	UNION ALL
															SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Ind Module') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																CONCAT(c.fld_module_name,' / Individual Module ') AS modulename, 5 AS schtype 
															FROM `itc_class_indassesment_master` AS a 
															LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id 
															LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
																AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND a.fld_startdate<='".date("Y-m-d")."' 
																AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 								
																	UNION ALL
															SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Ind Math Module') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																CONCAT(c.fld_mathmodule_name,' / Individual Math Module ') AS modulename, 6 AS schtype 
															FROM `itc_class_indassesment_master` AS a 
															LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id 
															LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
																AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND a.fld_startdate<='".date("Y-m-d")."' 
																AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 	
																	UNION ALL
															SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Ind Quest') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																CONCAT(c.fld_module_name,' / Individual Quest ') AS modulename, 7 AS schtype 
															FROM `itc_class_indassesment_master` AS a 
															LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id 
															LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
																AND a.fld_moduletype='7' AND b.fld_student_id='".$uid."' AND a.fld_startdate<='".date("Y-m-d")."' 
																AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 				
																	UNION ALL
															SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Custom Module') AS schedulename, a.fld_id AS scheduleid, f.fld_enddate, 
																CONCAT(c.fld_contentname,' / Rotation ',b.fld_rotation-1) AS modulename, 8 AS schtype 
															FROM `itc_class_rotation_schedule_mastertemp` AS a 
															LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_scheduledate as f on b.fld_schedule_id=f.fld_schedule_id and b.fld_rotation=f.fld_rotation
															LEFT JOIN itc_customcontent_master AS c ON b.fld_module_id=c.fld_id 
															LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
																AND DATE(a.fld_startdate)<=DATE(NOW()) AND f.fld_startdate<='".date("Y-m-d")."' AND f.fld_enddate>='".date("Y-m-d")."' 
																AND b.fld_type='8' AND e.fld_school_id='".$schoolid."' AND b.fld_flag='1' AND f.fld_flag='1'
																AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' AND e.fld_end_date >='".date("Y-m-d")."' 
						
                                                                                                                        UNION ALL 

															SELECT a.fld_module_id,CONCAT(a.fld_schedule_name, ' / Ind Custom') AS schedulename,a.fld_id AS scheduleid,a.fld_enddate,
															CONCAT(c.fld_contentname,' / Individual Custom ') AS modulename,17 AS schtype
															FROM itc_class_indassesment_master AS a
															LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id = b.fld_schedule_id
															LEFT JOIN itc_customcontent_master as c ON a.fld_module_id = c.fld_id
															LEFT JOIN itc_class_master AS d ON d.fld_id = a.fld_class_id
															LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
															WHERE a.fld_delstatus = '0' AND d.fld_delstatus = '0' AND d.fld_lock = '0' AND b.fld_student_id ='".$uid."'
															AND a.fld_moduletype = '17' AND a.fld_startdate<='".date("Y-m-d")."' 
															AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' AND e.fld_school_id='".$schoolid."' 
															AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
															AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag = '1' AND a.fld_flag = '1'
UNION ALL   
						
                                                                                                                        SELECT b.fld_expedition_id, CONCAT(a.fld_schedule_name,' / Expedition') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                                                                                                        CONCAT(c.fld_exp_name,' / Rotation ',b.fld_rotation-1) AS modulename, 19 AS schtype
                                                                                                                        FROM `itc_class_rotation_expschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_expschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_expscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                                                                                        LEFT JOIN itc_exp_master AS c ON b.fld_expedition_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                                                                                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                                                                                                        AND DATE(a.fld_startdate) <= DATE(NOW()) AND g.fld_startdate<='".date("Y-m-d")."' AND g.fld_enddate>='".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                                                                                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                                                                                                        AND b.fld_flag='1'  
						
                                                                                                                         UNION ALL   SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module ') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                                                                                                        CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation-1) AS modulename, 21 AS schtype 
                                                                                                                        FROM `itc_class_rotation_modexpschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_modexpschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                                                                                        LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                                                                                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                                                                                                        AND DATE(a.fld_startdate) <= DATE(NOW()) AND g.fld_startdate<='".date("Y-m-d")."' AND g.fld_enddate>='".date("Y-m-d")."'  AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                                                                                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                                                                                                        AND b.fld_flag='1' AND b.fld_type='1'

                                                                                                                        UNION ALL   
                                                                                                                        
                                                                                                                        SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Custom') AS schedulename, 
                                                                                                                        a.fld_id AS scheduleid, g.fld_enddate, CONCAT(c.fld_contentname,' / Rotation ',b.fld_rotation-1) AS modulename, 
                                                                                                                        22 AS schtype
                                                                                                                        FROM itc_class_rotation_modexpschedule_mastertemp AS a
                                                                                                                        LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_modexpscheduledate as g 
                                                                                                                        on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation
                                                                                                                        LEFT JOIN itc_customcontent_master as c ON b.fld_module_id=c.fld_id 
                                                                                                                        LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id
                                                                                                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_type='8'
                                                                                                                        AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate) <= DATE(NOW()) AND g.fld_startdate<='".date("Y-m-d")."' AND g.fld_enddate>='".date("Y-m-d")."' 
                                                                                                                        AND b.fld_flag='1'
                                                                                                                        
                                                                                                                        UNION ALL   SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Expedition') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                                                                                                        CONCAT(c.fld_exp_name,' / Rotation ',b.fld_rotation-1) AS modulename, 20 AS schtype 
                                                                                                                        FROM `itc_class_rotation_modexpschedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_modexpschedulegriddet` AS b ON a.fld_id=b.fld_schedule_id 
                                                                                                                        LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                                                                                        LEFT JOIN itc_exp_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                                                                                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                                                                                                        AND DATE(a.fld_startdate) <= DATE(NOW()) AND g.fld_startdate<='".date("Y-m-d")."' AND g.fld_enddate>='".date("Y-m-d")."'  AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                                                                                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                                                                                                        AND b.fld_flag='1' AND b.fld_type='2' ");




						$qrytest = $ObjDB->QueryObject("SELECT a.fld_max_attempts AS maxattempts, b.fld_max_attempts AS timeattempted, a.fld_test_name AS testname, b.fld_start_date 
															AS fld_enddate, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_id AS testid, a.fld_ass_type, b.fld_id AS testmapid 
														FROM itc_test_master AS a
														LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.`fld_test_id`
														LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id 
														WHERE c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_student_id ='".$uid."' AND b.fld_flag='1'
															AND a.fld_delstatus='0' AND DATE(b.fld_start_date) <= DATE(NOW()) AND DATE(b.fld_end_date) >= DATE(NOW())");

						$qryactivities = $ObjDB->QueryObject("SELECT a.fld_activity_name AS activityname, a.fld_id AS activityid, b.fld_end_date AS fld_enddate, 
																fn_shortname(a.fld_activity_name,1) AS shortname 
															FROM itc_activity_master AS a 
															LEFT JOIN itc_activity_student_mapping AS b ON a.fld_id=b.`fld_activity_id`
															LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id 
															WHERE c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_student_id ='".$uid."' AND b.fld_flag='1' AND a.fld_delstatus='0' 
																AND DATE(b.fld_start_date) <= DATE(NOW()) AND DATE(b.fld_end_date) >= DATE(NOW())");

						$qryexpedition = $ObjDB->QueryObject("SELECT a.fld_exp_id, CONCAT(a.fld_schedule_name,' / Ind Expedition') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																	CONCAT(c.fld_exp_name,' / Individual Expedition ') AS modulename, 15 AS schtype 
																FROM itc_class_indasexpedition_master AS a 
																LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
																LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
																LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
																LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
																WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' 
																	AND b.fld_student_id='".$uid."' AND a.fld_startdate<='".date("Y-m-d")."' 
																	AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																	AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																	AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 
																GROUP BY scheduleid");

                                                $qrymission = $ObjDB->QueryObject("SELECT a.fld_mis_id, CONCAT(a.fld_schedule_name,' / Ind Mission') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																	CONCAT(c.fld_mis_name,' / Individual Mission ') AS modulename, 18 AS schtype 
																FROM itc_class_indasmission_master AS a 
																LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
																LEFT JOIN itc_mission_master AS c ON a.fld_mis_id=c.fld_id 
																LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
																LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
																WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' 
																	AND b.fld_student_id='".$uid."' AND a.fld_startdate<='".date("Y-m-d")."' 
																	AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																	AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																	AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 
																GROUP BY scheduleid");

                                                $qrymissionschedule=$ObjDB->QueryObject("SELECT CONCAT(b.fld_mission_id,'-',b.fld_rotation) AS fld_module_id, CONCAT(a.fld_schedule_name,' / Mission') AS schedulename, a.fld_id AS scheduleid, g.fld_enddate, 
                                CONCAT(c.fld_mis_name,' / Rotation ',b.fld_rotation-1) AS modulename, 23 AS schtype, b.fld_id as mapid 
                                                                                            FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_rotation_mission_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
                                LEFT JOIN itc_class_rotation_missionscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                LEFT JOIN itc_mission_master AS c ON b.fld_mission_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' 
                                 AND DATE(a.fld_startdate) <= DATE(NOW()) AND g.fld_startdate<='".date("Y-m-d")."' AND g.fld_enddate>='".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
                                AND b.fld_flag='1' GROUP BY scheduleid");

                                                $qrypd = $ObjDB->QueryObject("SELECT a.fld_id AS sid, a.fld_schedule_name AS sname, fn_shortname(a.fld_schedule_name,1) AS shortname, a.fld_end_date AS edate 
                                                                                    FROM itc_class_pdschedule_master AS a 
                                                                                    LEFT JOIN itc_class_pdschedule_student_mapping AS b ON a.fld_id=b.fld_pdschedule_id 
                                                                                    LEFT JOIN itc_class_master AS c ON c.fld_id=a.fld_class_id 
                                                                                    LEFT JOIN itc_license_track AS d ON a.fld_license_id=d.fld_license_id
                                                                                    WHERE a.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_student_id='".$uid."' AND DATE(a.fld_start_date) <= DATE(NOW()) 
                                                                                            AND DATE(a.fld_end_date) >= DATE(NOW()) AND c.fld_delstatus='0' AND c.fld_lock='0' AND d.fld_school_id='".$schoolid."' 
                                                                                            AND d.fld_user_id='".$indid."' AND d.fld_start_date<='".date("Y-m-d")."' AND d.fld_end_date >='".date("Y-m-d")."'");

						$testcount = 0;
						if($qrytest->num_rows>0){
							while($rowtest=$qrytest->fetch_assoc()){
								extract($rowtest);
								if($maxattempts>$timeattempted)
								{
									$testcount++;
									$schedulenames = '';
									$assnames = $testname;
									$duedates = '';
									$callfunction = "removesections('#assignment'); showpages('assignment-assignmentengine-gototest','assignment/assignmentengine/assignment-assignmentengine-gototest.php?id=".$testid."~".$testmapid."');";
								}
								else
								{
									$testcount;
								}
							}
						}

						$sigmathcount = $qrysigmath->num_rows;
						$sciencecount = $qrydyadtriad->num_rows;
						$activitiescount = $qryactivities->num_rows;
						$expeditioncount = $qryexpedition->num_rows;
                                                $missioncount=$qrymission->num_rows;
                                                $pdcount = $qrypd->num_rows;
                                                $missionschedulecount=$qrymissionschedule->num_rows;
					}
					else
					{
						$qrydyadtriad = $ObjDB->QueryObject("SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Module') AS schedulename, 
																g.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation-1) AS modulename, 1 AS schtype 
															FROM itc_class_rotation_schedulegriddet a
															JOIN itc_class_rotation_schedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
																AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
																AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                                                                                        LEFT JOIN itc_class_rotation_scheduledate AS g ON b.fld_schedule_id=g.fld_schedule_id AND b.fld_rotation=g.fld_rotation
															LEFT JOIN itc_class_rotation_schedule_mastertemp c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND a.fld_type='1' 
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW()) 
																AND c.fld_moduletype='1' AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."' AND g.fld_flag='1'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL 
															SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Math Module') AS schedulename, 
																g.fld_enddate, CONCAT(d.fld_mathmodule_name,' / Rotation ', a.fld_rotation-1) AS modulename, 4 AS schtype 
															FROM itc_class_rotation_schedulegriddet a
															JOIN itc_class_rotation_schedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
																AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
																AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                                                                                        LEFT JOIN itc_class_rotation_scheduledate AS g ON b.fld_schedule_id=g.fld_schedule_id AND b.fld_rotation=g.fld_rotation
															LEFT JOIN itc_class_rotation_schedule_mastertemp c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_mathmodule_master d ON a.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND a.fld_type='2'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW()) 
																AND c.fld_moduletype='2' AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."' AND g.fld_flag='1'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL
															SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Dyad') AS schedulename, 
																a.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation) AS modulename, 2 AS schtype 
															FROM itc_class_dyad_schedulegriddet a
															JOIN itc_class_dyad_schedulegriddet b ON (b.`fld_student_id`='".$uid."' OR b.fld_rotation='0') 
																AND b.`fld_schedule_id`=a.`fld_schedule_id` AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
																AND (a.`fld_student_id`='".$uid1."' OR a.fld_rotation='0') AND b.fld_flag='1' AND a.fld_flag='1'
															LEFT JOIN itc_class_dyad_schedulemaster c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															LEFT JOIN itc_class_dyad_schedule_studentmapping AS g ON (g.fld_schedule_id=a.fld_schedule_id AND g.fld_student_id='".$uid."')
															JOIN itc_class_dyad_schedule_studentmapping AS h ON (h.fld_schedule_id=a.fld_schedule_id AND h.fld_student_id='".$uid1."')
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
																AND g.fld_flag='1' AND h.fld_flag='1'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW()) 
																AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL
															SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Triad') AS schedulename,
																a.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation) AS modulename, 3 AS schtype 
															FROM itc_class_triad_schedulegriddet a
															JOIN itc_class_triad_schedulegriddet b ON (b.`fld_student_id`='".$uid."' OR b.fld_rotation='0') 
																AND b.`fld_schedule_id`=a.`fld_schedule_id` AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
																AND (a.`fld_student_id`='".$uid1."' OR a.fld_rotation='0') AND b.fld_flag='1' AND a.fld_flag='1'
															LEFT JOIN itc_class_triad_schedulemaster c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															LEFT JOIN itc_class_triad_schedule_studentmapping AS g ON (g.fld_schedule_id=a.fld_schedule_id AND g.fld_student_id='".$uid."')
															JOIN itc_class_triad_schedule_studentmapping AS h ON (h.fld_schedule_id=a.fld_schedule_id AND h.fld_student_id='".$uid1."')
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
																AND g.fld_flag='1' AND h.fld_flag='1'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW()) 
																AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL
															SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind Module') AS schedulename,
																c.fld_enddate, CONCAT(d.fld_module_name,' / Individual Module') AS modulename, 5 AS schtype 
															FROM itc_class_indassesment_student_mapping a
															JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
																AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
															LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_module_master d ON c.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(c.fld_enddate) >= DATE(NOW()) AND c.fld_moduletype='1' 
																AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL
															SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind MathModule') AS schedulename, 
																c.fld_enddate, CONCAT(d.fld_mathmodule_name,' / Individual MathModule') AS modulename, 6 AS schtype 
															FROM itc_class_indassesment_student_mapping a
															JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
																AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
															LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_mathmodule_master d ON c.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(c.fld_enddate) >= DATE(NOW()) AND c.fld_moduletype='2' 
																AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL
															SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind Quest') AS schedulename,
																c.fld_enddate, CONCAT(d.fld_module_name,' / Individual Quest') AS modulename, 7 AS schtype 
															FROM itc_class_indassesment_student_mapping a
															JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
																AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
															LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_module_master d ON c.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(c.fld_enddate) >= DATE(NOW()) AND c.fld_moduletype='7' 
																AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
																AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
															GROUP BY a.`fld_schedule_id`
																	UNION ALL
															SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id, CONCAT(c.fld_schedule_name,' / Custom Module') AS schedulename, 
																g.fld_enddate, CONCAT(d.fld_contentname,' / Rotation ', a.fld_rotation-1) AS modulename, 8 AS schtype 
															FROM itc_class_rotation_schedulegriddet a
															JOIN itc_class_rotation_schedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
																AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
																AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                                                                                        LEFT JOIN itc_class_rotation_scheduledate AS g ON b.fld_schedule_id=g.fld_schedule_id AND b.fld_rotation=g.fld_rotation
															LEFT JOIN itc_class_rotation_schedule_mastertemp c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_customcontent_master d ON a.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND a.fld_type='8'
																AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW()) 
																AND c.fld_moduletype='1' AND f.fld_school_id='".$schoolid."' AND f.fld_end_date >='".date("Y-m-d")."'
																AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND g.fld_flag='1'
															GROUP BY a.fld_schedule_id
                                                                                                                        UNION ALL 
															SELECT a.`fld_schedule_id` AS scheduleid, c.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Ind Custom') AS schedulename,
															c.fld_enddate, CONCAT(d.fld_contentname,' / Individual Custom ') AS modulename, 17 AS schtype 
															FROM itc_class_indassesment_student_mapping a
															JOIN itc_class_indassesment_student_mapping b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id`
															AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
															LEFT JOIN itc_class_indassesment_master c ON c.fld_id=b.fld_schedule_id
															LEFT JOIN itc_customcontent_master d ON c.fld_module_id=d.fld_id
															LEFT JOIN itc_class_master e ON e.fld_id=c.fld_class_id
															LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
															WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0'
															AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(c.fld_enddate) >= DATE(NOW()) AND c.fld_moduletype='7' 
															AND f.fld_school_id='".$schoolid."' AND f.fld_user_id='".$indid."' 
															AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
																GROUP BY a.`fld_schedule_id`
                                                                                                          UNION ALL
                                                        SELECT 
                                                a.`fld_schedule_id` AS scheduleid,
                                                a.`fld_expedition_id`,
                                                CONCAT(c.fld_schedule_name, ' / Expedition') AS schedulename,
                                                g.fld_enddate,
                                                CONCAT(d.fld_exp_name,
                                                        ' / Rotation ',
                                                        a.fld_rotation - 1) AS modulename,
                                                19 AS schtype
                                            FROM
                                                itc_class_rotation_expschedulegriddet a
                                                    JOIN
                                                itc_class_rotation_expschedulegriddet b ON b.`fld_student_id` = '".$uid."'
                                                    AND b.`fld_schedule_id` = a.`fld_schedule_id`
                                                    AND b.`fld_expedition_id` = a.`fld_expedition_id`
                                                    AND b.`fld_rotation` = a.`fld_rotation`
                                                    AND a.`fld_student_id` = '".$uid1."'
                                                    AND b.fld_flag = '1'
                                                    AND a.fld_flag = '1'
                                                    LEFT JOIN
                                                itc_class_rotation_expscheduledate AS g ON b.fld_schedule_id = g.fld_schedule_id
                                                    AND b.fld_rotation = g.fld_rotation
                                                    LEFT JOIN
                                                itc_class_rotation_expschedule_mastertemp c ON c.fld_id = b.fld_schedule_id
                                                    LEFT JOIN
                                                itc_exp_master d ON a.fld_expedition_id = d.fld_id
                                                    LEFT JOIN
                                                itc_class_master e ON e.fld_id = a.fld_class_id
                                                    LEFT JOIN
                                                itc_license_track f ON f.`fld_license_id` = c.`fld_license_id`
                                            WHERE
                                                c.fld_delstatus = '0'
                                                    AND d.fld_delstatus = '0'
                                                    AND e.fld_delstatus = '0'
                                                    AND e.fld_lock = '0'
                                                    AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW())
                                                    AND f.fld_school_id = '".$schoolid."'
                                                    AND f.fld_user_id = '".$indid."'
                                                    AND f.fld_start_date <= '".date("Y-m-d")."'
                                                    AND f.fld_end_date >= '".date("Y-m-d")."'
                                            GROUP BY a.fld_rotation , a.fld_expedition_id , a.fld_schedule_id                       



                                                                                                                        UNION ALL 
                                            SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_module_name,' / Rotation ', a.fld_rotation-1) AS modulename, 21 AS schtype 
                                                    FROM itc_class_rotation_modexpschedulegriddet a
                                                    JOIN itc_class_rotation_modexpschedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_module_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='1'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW()) AND f.fld_school_id='".$schoolid."' 
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`

                                                    UNION ALL
                                                    SELECT a.`fld_schedule_id` AS scheduleid, a.`fld_module_id`, CONCAT(c.fld_schedule_name,' / Custom Module') AS schedulename, 
                                                        g.fld_enddate, CONCAT(d.fld_contentname,' / Rotation ', a.fld_rotation-1) AS modulename, 22 AS schtype 
                                                    FROM itc_class_rotation_modexpschedulegriddet a
                                                    JOIN itc_class_rotation_modexpschedulegriddet b ON  b.`fld_student_id`='".$uid."' AND b.`fld_schedule_id`=a.`fld_schedule_id` 
                                                        AND b.`fld_module_id`=a.`fld_module_id` AND b.`fld_rotation`=a.`fld_rotation`
                                                        AND a.`fld_student_id`='".$uid1."' AND b.fld_flag='1' AND a.fld_flag='1'
                                                    LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation 
                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp c ON c.fld_id=b.fld_schedule_id
                                                    LEFT JOIN itc_customcontent_master d ON a.fld_module_id=d.fld_id
                                                    LEFT JOIN itc_class_master e ON e.fld_id=a.fld_class_id
                                                    LEFT JOIN itc_license_track f ON f.`fld_license_id`=c.`fld_license_id`
                                                    WHERE c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND e.fld_lock='0' AND b.fld_type='8'
                                                        AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW()) AND f.fld_school_id='".$schoolid."' 
                                                        AND f.fld_user_id='".$indid."' AND f.fld_start_date<='".date("Y-m-d")."' AND f.fld_end_date >='".date("Y-m-d")."'
                                                    GROUP BY a.`fld_rotation`, a.`fld_module_id`,  a.`fld_schedule_id`

                                                    UNION ALL
                                                        SELECT 
                                                a.`fld_schedule_id` AS scheduleid,
                                                a.`fld_module_id`,
                                                CONCAT(c.fld_schedule_name, ' / Expedition') AS schedulename,
                                                g.fld_enddate,
                                                CONCAT(d.fld_exp_name,
                                                        ' / Rotation ',
                                                        a.fld_rotation - 1) AS modulename,
                                                20 AS schtype
                                            FROM
                                                itc_class_rotation_modexpschedulegriddet a
                                                    JOIN
                                                itc_class_rotation_modexpschedulegriddet b ON b.`fld_student_id` = '".$uid."'
                                                    AND b.`fld_schedule_id` = a.`fld_schedule_id`
                                                    AND b.`fld_module_id` = a.`fld_module_id`
                                                    AND b.`fld_rotation` = a.`fld_rotation`
                                                    AND a.`fld_student_id` = '".$uid1."'
                                                    AND b.fld_flag = '1'
                                                    AND a.fld_flag = '1'
                                                    LEFT JOIN
                                                itc_class_rotation_modexpscheduledate AS g ON b.fld_schedule_id = g.fld_schedule_id
                                                    AND b.fld_rotation = g.fld_rotation
                                                    LEFT JOIN
                                                itc_class_rotation_modexpschedule_mastertemp c ON c.fld_id = b.fld_schedule_id
                                                    LEFT JOIN
                                                itc_exp_master d ON a.fld_module_id = d.fld_id
                                                    LEFT JOIN
                                                itc_class_master e ON e.fld_id = a.fld_class_id
                                                    LEFT JOIN
                                                itc_license_track f ON f.`fld_license_id` = c.`fld_license_id`
                                            WHERE
                                                c.fld_delstatus = '0'
                                                    AND d.fld_delstatus = '0'
                                                    AND e.fld_delstatus = '0'
                                                    AND e.fld_lock = '0'
                                                    AND b.fld_type='2'
                                                    AND DATE(c.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_startdate) <= DATE(NOW()) AND DATE(g.fld_enddate) >= DATE(NOW())
                                                    AND f.fld_school_id = '".$schoolid."'
                                                    AND f.fld_user_id = '".$indid."'
                                                    AND f.fld_start_date <= '".date("Y-m-d")."'
                                                    AND f.fld_end_date >= '".date("Y-m-d")."'
                                            GROUP BY a.fld_rotation , a.fld_module_id , a.fld_schedule_id");


						$qrycountstudents = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM(SELECT a.fld_id FROM itc_class_indasexpedition_master AS a 
																LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
																LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
																LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
																LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
																WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
																	AND a.fld_startdate<='".date("Y-m-d")."' 
																	AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																	AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																	AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 
																	AND b.fld_student_id IN('".$uid."','".$uid1."') GROUP BY a.fld_id having count(a.fld_id) > 1) AS t");
                                                if($qrycountstudents > 0)
						{


							$qryexpedition = $ObjDB->QueryObject("SELECT a.fld_exp_id, CONCAT(a.fld_schedule_name,' / Ind Expedition') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																	CONCAT(c.fld_exp_name,' / Individual Expedition ') AS modulename, 15 AS schtype FROM itc_class_indasexpedition_master AS a 
																LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
																LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
																LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
																LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
																WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' 
																	AND a.fld_startdate<='".date("Y-m-d")."' 
																	AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																	AND e.fld_school_id='".$schoolid."' AND e.fld_user_id='".$indid."' AND e.fld_start_date<='".date("Y-m-d")."' 
																	AND e.fld_end_date >='".date("Y-m-d")."' AND b.fld_flag='1' 
																	AND b.fld_student_id IN('".$uid."','".$uid1."') GROUP BY scheduleid having count(scheduleid) > 1");



						}


                        $qrymission = $ObjDB->QueryObject("SELECT a.fld_mis_id, CONCAT(a.fld_schedule_name,' / Ind Mission') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, 
																	CONCAT(c.fld_mis_name,' / Individual Mission ') AS modulename, 18 AS schtype 
																FROM itc_class_indasmission_master AS a 
																LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
																LEFT JOIN itc_mission_master AS c ON a.fld_mis_id=c.fld_id 
																LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
																LEFT JOIN itc_license_track AS e ON a.fld_license_id=e.fld_license_id
																WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_lock='0' 
																	AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' 
																	AND e.fld_school_id='".$schoolid."' 
																	AND e.fld_start_date<='".date("Y-m-d")."' AND e.fld_end_date >='".date("Y-m-d")."' 
																	AND b.fld_flag='1' AND a.fld_startdate<='".date("Y-m-d")."'  
                                                                    AND (b.fld_student_id = '".$uid."' OR b.fld_student_id = '".$uid1."') GROUP BY scheduleid");
						$sigmathcount = 0;
						$sciencecount = $qrydyadtriad->num_rows;
						$testcount = 0;
						$activitiescount = 0;
                        if($qrycountstudents > 0)
                        {
                            $expeditioncount = $qryexpedition->num_rows;
                        } else {
                            $expeditioncount = 0;
                        }
                        if($qrymission->num_rows > 0)
                        {
                            $missioncount = $qrymission->num_rows;
                        }

					}

					$totalcount = $sigmathcount+$sciencecount+$testcount+$activitiescount+$expeditioncount+$pdcount+$missioncount+$missionschedulecount;

					if($totalcount==1)
					{
						if($sigmathcount!=0)
                                                {
							while($rowsigmath=$qrysigmath->fetch_assoc()){
								extract($rowsigmath);

								$currentqry = $ObjDB->QueryObject("SELECT fld_id AS maxid, fld_lesson_id AS lessonid, fld_type AS type, fld_status AS status 
																	FROM itc_assignment_sigmath_master 
																	WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$uid."' AND fld_status=0 AND fld_delstatus='0' 
																	ORDER BY fld_id DESC 
																	LIMIT 0,1");
								$flag=0;
								$completed=0;
								if($currentqry->num_rows>0){
									$current_res = $currentqry->fetch_assoc();
									extract($current_res);

									//check is the lesson is abailable or not
									$chklesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
																				FROM itc_class_sigmath_lesson_mapping AS a 
																				LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
																				WHERE a.fld_sigmath_id='".$sid."' AND a.fld_lesson_id='".$lessonid."' AND a.fld_flag='1' 
																					AND b.fld_access='1' AND b.fld_delstatus='0'");

									if($chklesson>0){
										$flag=1;
										if($type==1){
											$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
										}
										else if($type==2){
											$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																							FROM itc_ipl_master 
																							WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
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
									$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id 
																					FROM itc_ipl_master 
																					WHERE fld_lesson_type='2' AND fld_delstatus='0'");
									if($orientationid=='') $orientationid=0;
									$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping 
												 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND ".$orientationid." NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
												 WHERE (fld_status=1 or fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
												 AND fld_delstatus='0' ) AND fld_lesson_id='".$orientationid."'");

									$function = "fn_lessonplay(".$sid.",".$lessonid.",1,1)";
									if($lessonid==0 || $lessonid=='')
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
										$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
									}
								}
								$schedulenames = $sname;
								$assnames = $ObjDB->SelectSingleValue("SELECT fld_ipl_name 
																		FROM itc_ipl_master 
																		WHERE fld_id='".$lessonid."'");
								$duedates = $edate;
								$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-sigmath-test','assignment/sigmath/assignment-sigmath-test.php','id=".$sid."~".$lessonid."~1');";
							}
						}

                                                else if($pdcount!=0)
                                                {
							while($rowpd=$qrypd->fetch_assoc()){
								extract($rowpd);

                                                                $schedulenames = $sname;

                                                                $lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_pdschedule_lesson_mapping AS a
                                                                                                                LEFT JOIN    itc_class_pdschedule_student_mapping AS b ON a.fld_license_id = b.fld_license_id
                                                                                                                WHERE  a.fld_flag = '1' AND b.fld_flag = '1' AND b.fld_student_id = '".$uid."' AND b.fld_pdschedule_id='".$sid."' 
                                                                                                                AND a.fld_pdschedule_id='".$sid."' ORDER BY fld_order LIMIT 0,1");
								$assnames = $ObjDB->SelectSingleValue("SELECT fld_pd_name 
                                                                                                                FROM itc_pd_master 
                                                                                                                WHERE fld_id='".$lessonid."'");
								$duedates = $edate;
                                                                //echo ("SELECT fld_zip_name FROM itc_pd_version_track WHERE fld_pd_id='".$lessonid."'");
                                                                $pdfoldername = $ObjDB->SelectSingleValue("SELECT fld_zip_name FROM itc_pd_version_track WHERE fld_pd_id='".$lessonid."' AND fld_delstatus='0'");
                                                                $pdfoldername1=basename($pdfoldername,".zip");//get file name without file extension
                                                                $filename=  explode('_', $pdfoldername1);
                                                                $x=1;
                                                                $final=$pdfoldername1.",".$x.",".$filename[0];
								$callfunction = "showfullscreenlessonpd('".$pdfoldername1."',".$lessonid.",'".$filename[0]."')";
                                                        }
                                                }

						else if($sciencecount!=0)
                                                {
							while($rowdyadtriad=$qrydyadtriad->fetch_assoc()){
								extract($rowdyadtriad);
								$schedulenames = $schedulename;
								$assnames = $modulename;
								$duedates = $fld_enddate;
								$values = $scheduleid."~".$fld_module_id."~".$schtype;
								if($schtype==19)
								{
									$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-science-sessions','assignment/expedition/assignment-expedition-show.php','id=".$values."')";
								}
								else
								{
								$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-science-sessions','assignment/science/assignment-science-sessions.php','id=".$values."')";
							}
						}
						}

						else if($activitiescount!=0)
                                                {
							while($rowactivities=$qryactivities->fetch_assoc()){
								extract($rowactivities);
								$schedulenames = '';
								$assnames = $activityname;
								$duedates = $fld_enddate;
								$callfunction = "removesections('#assignment'); showpageswithpostmethod('library-activities-viewactivity','library/activities/library-activities-viewactivity.php','id=".$activityid."')";
							}
						}

						else if($expeditioncount!=0)
                                                {
                                                    while($rowexpedition=$qryexpedition->fetch_assoc())
                                                    {
								extract($rowexpedition);
								$schedulenames = $schedulename;
								$assnames = $modulename;
								$duedates = $fld_enddate;
								$values = $scheduleid."~".$fld_exp_id."~".$schtype;
                                                                $expuiid = $ObjDB->SelectSingleValueInt("SELECT fld_ui_id FROM itc_exp_master WHERE fld_id='".$fld_exp_id."'");
								if($expuiid==1)
									$expuiname = "preview";
								else
									$expuiname = "show";
								$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-expedition-".$expuiname."','assignment/expedition/assignment-expedition-".$expuiname.".php','id=".$values."')";
							}
						}

                                                else if($missioncount!=0)
                                                {
							while($rowmission=$qrymission->fetch_assoc()){
//                                print_r($rowmission);
								extract($rowmission);
								$schedulenames = $schedulename;
								$assnames = $modulename;
								$duedates = $fld_enddate;
								$values = $scheduleid."~".$fld_mis_id."~".$schtype;
//                                echo "Values: $values <br>";
                                                                $expuiid = $ObjDB->SelectSingleValueInt("SELECT fld_ui_id FROM itc_mission_master WHERE fld_id='".$fld_mis_id."'");
								if($expuiid==1)
									$expuiname = "preview";
								else
									$expuiname = "show";
								$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-mission-".$expuiname."','assignment/mission/assignment-mission-".$expuiname.".php','id=".$values."')";
							}
						}

                                                else if ($missionschedulecount != 0)
                                                {
                                                    while ($rowmissionschedule = $qrymissionschedule->fetch_assoc()) {
                                                        extract($rowmissionschedule);
                                                        $schedulenames = $schedulename;
                                                        $assnames = $modulename;
                                                        $duedates = $fld_enddate;

                                                        $expuiid = $ObjDB->SelectSingleValueInt("SELECT fld_ui_id FROM itc_mission_master WHERE fld_id='" . $fld_mis_id . "'");
                                                        if ($expuiid == 1)
                                                            $expuiname = "preview";
                                                        else
                                                            $expuiname = "show";

                                                        $values = $scheduleid . "~" . $fld_module_id . "~" . $schtype . "~" . $expuiname;
                                                        $callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-missionassignment','assignment/mission/assignment-missionassignment.php','id=" . $values . "')";
                                                    }
                                                }


                                                if($completed!=1)
                                                {
						?>
						<img class="dash-nexticon" src="img/upnext.svg" />
						<div class="dashlesson-cont" id="loaddetails">
							<div class="dashSchedule"><?php echo $schedulenames; ?></div>
							<div class="dashModule"><?php echo $assnames; ?></div>
							<div class="dashDuedate"><?php if($duedates!='') {?>Due Date: <?php echo date("m/d/Y",strtotime($duedates)); }?> </div>
							<div class="dashStart"><input type="button" class="btnstart" value="Start" onclick="removesections('#home');<?php echo $callfunction; ?>"/></div>
						</div>
						<?php
						}
						else
                                                {   ?>
                        	<div class="dashlesson-cont">
							<div class="dashSchedule">Today you have no assignments.</div>
							<div class="dashStart"></div>
						</div>
                        <?php
						}
					}
					else if($totalcount!=0)
					{
						?>
						<img class="dash-nexticon" src="img/review.svg" />
						<div class="dashlesson-cont">
							<div class="dashSchedule">Today you have <?php echo $totalcount;?> assignments.</div>
							<div class="dashStart"><input type="button" class="btnstart mainBtn" id="btnassignment" value="Review" /></div>
						</div>
						<?php
					}
					else if($totalcount==0)
					{
						?>
						<div class="dashlesson-cont">
							<div class="dashSchedule">Today you have no assignments.</div>
							<div class="dashStart"></div>
						</div>
						<?php
					}
				}?>


    <?php


/*******Dash Board Class Progress********/
    if($sessmasterprfid == '9' AND $itcteacher=='1') //AND $uid!='59'
    {     ?>

    <style>

        .dashlesson-cont {
    float: left;
    margin: 40px 0 0 42px;
    min-height: 100px;
    padding-bottom: 0px;
    width: 40%;
}
        .contents{
                margin: 5px;
                padding: 5px;
                list-style: none;
                border-radius: 5px;
                width: 22%;
        }
        .contents td{
            margin-bottom: 10px;
        }
        .loading-div{
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 999;
                display:none;
        }
        .loading-div img {
            margin-left: 31%;
            margin-top: 14px;
            width: 60px;
        }

        /* Pagination style */
        .pagination{margin:0;padding:0;}
        .pagination tr td{
                display: inline;
                padding: 6px 10px 6px 10px;
                border: 1px solid #ddd;
                margin-right: -1px;
                font: 15px/20px Arial, Helvetica, sans-serif;
                background: #FFFFFF;
        }
        .pagination tr td a{
            text-decoration:none;
            color: #337ab7;
        }
        .pagination tr td.first {
            border-radius: 5px 0px 0px 5px;
        }
        .pagination tr td.last {
            border-radius: 0px 5px 5px 0px;
        }
        .pagination tr td:hover{
            background: #F0F0F5;
        }
        .pagination tr td.active{
            background: #337ab7;
            color: #fff;
            border-bottom: 1px solid #337ab7;
border-top: 1px solid #337ab7;
        }
        tr td:first-child {
            padding-left: 10px;
}

    </style>

        <script>
            </script>

    <div class="dashlesson-cont">
         <div class="loading-div"><img src="img/ajaxloader.gif" ></div>
         <div id="results" style="width: 147%;">   <!-- content will be loaded here --></div>
            </div>

    <script type="text/javascript">
        function fn_classpro1()
        {
        }
    </script>
             <?php
} //sessmasterprfid 9 if end here
/*******Dash Board Class Progress********/
?>
        </div>
        </div>

        <div class='row buttons'>
        <?php
        $qrymenuname=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_menu_name, a.fld_class, a.fld_href, a.fld_hrefid, a.fld_divclass 
										FROM itc_main_menu AS a 
										RIGHT JOIN itc_menu_privileges AS b ON a.fld_id=b.fld_menu_id 
										WHERE b.fld_profile_id='".$sessprofileid."' AND b.fld_access='1' AND a.fld_main_menu_id=0 AND a.fld_delstatus=0 AND b.fld_delstatus=0 
										ORDER BY a.fld_position ASC");
        while($rowmenuname=$qrymenuname->fetch_object())
        {
			$menuname=$rowmenuname->fld_menu_name;
			$menuid=$rowmenuname->fld_id;
			$class = $rowmenuname->fld_class;
			$href=$rowmenuname->fld_href;
			$id=$rowmenuname->fld_hrefid;
			if($sessmasterprfid == 10){	//Student level users
				if($trialuser==1){
					// for trial user student
					$iplqry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, fn_shortname(b.fld_ipl_name,1) AS shortname, b.fld_ipl_icon AS lessonicon 
													FROM itc_license_cul_mapping AS a 
													LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id 
													RIGHT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
													WHERE c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
														AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND a.fld_active='1' AND b.fld_delstatus='0' 
													GROUP BY b.fld_id");
				}
				else {
					// Lesson listed based on the class assigned for the student and availability of the license time period
					$iplqry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, fn_shortname(b.fld_ipl_name,1) AS shortname, b.fld_ipl_icon AS lessonicon 
												FROM itc_class_sigmath_student_mapping AS a 
												LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_sigmath_id=c.fld_sigmath_id 
												LEFT JOIN itc_ipl_master AS b ON b.fld_id=c.fld_lesson_id 
												LEFT JOIN itc_class_sigmath_master AS d ON d.fld_id=c.fld_sigmath_id 
												LEFT JOIN itc_class_master AS e ON e.fld_id=d.fld_class_id 
												WHERE a.fld_student_id='".$uid."' AND a.fld_flag='1' AND c.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_access='1' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
												GROUP BY b.fld_id");
				}

				$modqry = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) as modulename, fn_shortname(CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')),1) AS shortname FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<='".date("Y-m-d")."' AND c.fld_end_date>='".date("Y-m-d")."' GROUP BY b.fld_module_id ORDER BY a.fld_module_name ASC");
				$mathmodqry = $ObjDB->QueryObject("SELECT b.fld_module_id as mathmoduleid, CONCAT(a.fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_module_id AND fld_delstatus='0')) as mathmodulename, fn_shortname(CONCAT(a.fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_module_id AND fld_delstatus='0')),1) AS shortname FROM itc_mathmodule_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' AND b.fld_type='2' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() GROUP BY b.fld_module_id ORDER BY a.fld_mathmodule_name ASC");
				if($iplqry->num_rows>0 && $modqry->num_rows>0 && $mathmodqry->num_rows>0)
					$count = 0;
				else if($iplqry->num_rows>0 && $modqry->num_rows>0)
					$count = 0;
				else if($modqry->num_rows>0 && $mathmodqry->num_rows>0)
					$count = 0;
				else if($iplqry->num_rows>0 && $menuid==1){
					$count = 1;
					$id = "btnlibrary-lessons";
				}

				else if($modqry->num_rows>0 && $menuid==1){
					$count = 2;
				}
				else if($mathmodqry->num_rows>0 && $menuid==1){
					$count = 3;
				}
			}
			$divclass=$rowmenuname->fld_divclass;

                        if($sessmasterprfid == '10'){
                            $moduleqry = $ObjDB->QueryObject("SELECT w.* FROM (SELECT b.fld_module_id FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_type='1' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND a.fld_moduletype='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."') AND b.fld_flag='1' 
                                                                UNION ALL   SELECT b.fld_module_id FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_scheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND b.fld_type='2' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND a.fld_moduletype='2' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1' 
                                                                UNION ALL   SELECT a.fld_module_id FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1'
                                                                UNION ALL   SELECT a.fld_module_id FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1'
                                                                UNION ALL   SELECT b.fld_module_id FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_class_rotation_modexpscheduledate as g on b.fld_schedule_id=g.fld_schedule_id and b.fld_rotation=g.fld_rotation LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1' AND b.fld_type='1'
                                                                UNION ALL   SELECT b.fld_module_id FROM `itc_class_dyad_schedulemaster` AS a LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id LEFT JOIN itc_class_dyad_schedule_studentmapping AS e ON (e.fld_schedule_id=b.fld_schedule_id AND e.fld_student_id='".$uid."') WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') AND e.fld_flag='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND b.fld_flag='1'		
                                                                UNION ALL   SELECT a.fld_module_id FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='7' AND b.fld_student_id='".$uid."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."')
                                                                UNION ALL   SELECT b.fld_module_id FROM `itc_class_triad_schedulemaster` AS a LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id LEFT JOIN itc_class_triad_schedule_studentmapping AS e ON (e.fld_schedule_id=b.fld_schedule_id AND e.fld_student_id='".$uid."') WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (b.fld_student_id='".$uid."' OR b.fld_rotation='0') AND e.fld_flag='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' ) AND b.fld_flag='1') AS w
                                                                UNION ALL   SELECT a.fld_id FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_student_mapping as b on b.fld_class_id=a.fld_class_id WHERE b.fld_student_id='".$uid."' AND b.fld_flag = 1 AND a.fld_lock = 0 AND a.fld_delstatus = 0 AND a.fld_flag = 1");
                            $iplcnt = $iplqry->num_rows;
                            $modcnt = $moduleqry->num_rows;        
                            if(($menuid!=64 OR $menuid==64) AND ($iplcnt > 0 OR $modcnt > 0) OR $menuid != 1)
                            {
                        
                                ?>
                                    <a class='<?php echo $class; if(($uid1!='' && $menuid!=46 && $menuid!=47 && $menuid!=4) or ($trialuser =='1' && $menuid=='46') or ($trialuser =='1' && $menuid=='47')) { echo $class." dim"; }?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                                    <div class='<?php echo $divclass;?>'></div>
                                    <div class='onBtn'><?php echo ucfirst($menuname);?></div>
                                    </a>
                                <?php
                            }
                        }
                        else{
                        if(($menuid!=64 AND $itcteacher==1) OR ($menuid==64 AND $sosteacher==1) OR ($sessmasterprfid=='6') OR ($sessmasterprfid=='7'))
                        {

                        ?>
            <a class='<?php echo $class; if(($uid1!='' && $menuid!=46 && $menuid!=47 && $menuid!=4) or ($trialuser =='1' && $menuid=='46') or ($trialuser =='1' && $menuid=='47')) { echo $class." dim"; }?>' href='<?php echo $href;?>' id='<?php echo $id;?>' name='<?php echo $menuid;?>'>
                <div class='<?php echo $divclass;?>'></div>
                <div class='onBtn'><?php echo ucfirst($menuname);?></div>
			</a>
			<?php
        }
        }
                        
        }
               if($sessmasterprfid==2){
		?>
        	<a class='skip btn main' href='<?=ITC_URL ?>/research/size/file.php' onClick="window.open('<?=ITC_URL?>/research/size/file.php','_blank');" target="_blank" >
                <div class='icon-synergy-repository'></div>
                <div class='onBtn'>Space Usage</div>
			</a>
        	<?php		
		}

		if($sessmasterprfid != 10 AND $itcteacher==1){
		?>
        	<a class='skip btn main' href='http://synergyitchelp.pitsco.com/' onClick="window.open('http://synergyitchelp.pitsco.com/','_blank');" target="_blank" >
                <div class='icon-synergy-help-a'></div>
                <div class='onBtn'>Help</div>
			</a>
        	<?php		
		}
        ?>
        </div>
    </div>
</section>
<script>
	var d = new Date();
	  	var hours = d.getHours();
		
		if(hours > 0 && hours < 12)
		{
			//$('.dashMessage').html('Good Morning');
		}
		if(hours >= 12 && hours < 15)
		{
			//$('.dashMessage').html('Good Afternoon');
		}
		if(hours >= 15 && hours < 24)
		{
			//$('.dashMessage').html('Good Evening');
		}
</script>
<?php
@include("footer.php");