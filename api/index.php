<?php
	@include('../includes/table.class.php');
	@include('../includes/comm_func.php');	
	$oper = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';	
	$url = "http://".$_SERVER['SERVER_NAME']."/api/index.php";
	$contenturl = CONTENT_URL . "";
	// Login Check
	if($oper == 'login' and $oper != ''){
		$username = (isset($_REQUEST['uname'])) ? $_REQUEST['uname'] : '';
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
		$deviceid = (isset($_REQUEST['deviceid'])) ? $_REQUEST['deviceid'] : '2dd3ba3c58c8050608055636e42233b792dd35cd7d1c339f7d25f3a6910f3001';
		
		$trialcheck = 0;
		
		$encryptpass = fnEncrypt($password,$encryptkey);
		
		$check = $ObjDB->Count("SELECT fld_username FROM itc_user_master WHERE MD5(fld_username)='".md5($username)."' AND fld_password='".$encryptpass."' AND fld_delstatus='0' AND fld_activestatus='1'");
		
		if($check > 0) {
			$userdetqry = $ObjDB->QueryObject("SELECT a.fld_id AS userid, b.fld_prf_main_id AS sessmasterprfid, b.fld_profile_name AS sessprofilename, a.fld_fname AS fname, a.fld_lname AS lname, a.fld_school_id AS schoolid, a.fld_district_id AS distid, a.fld_user_id AS indid, a.fld_created_by AS createdby FROM itc_user_master AS a, itc_profile_master AS b WHERE a.fld_profile_id=b.fld_id AND MD5(a.fld_username)='".md5($username)."' AND a.fld_password='".$encryptpass."' AND (b.fld_delstatus = 0 OR b.fld_delstatus=2) AND a.fld_activestatus='1' AND a.fld_delstatus='0' AND b.fld_prf_main_id>=5 AND b.fld_prf_main_id<=10");
			if($userdetqry->num_rows > 0){
				$rowuserdet = $userdetqry->fetch_assoc();
				extract($rowuserdet);
				
				$devicecheck = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_ipad_user_active WHERE fld_user_id='".$userid."' AND fld_device_id='".$deviceid."'");
				
				if($devicecheck > 0){
					$ObjDB->NonQuery("UPDATE itc_ipad_user_active SET fld_activestatus='1' WHERE fld_user_id='".$userid."' AND fld_device_id='".$deviceid."'");
					$logid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipad_user_active WHERE fld_user_id='".$userid."' AND fld_device_id='".$deviceid."'");
				}
				else {
					$ObjDB->NonQuery("INSERT INTO itc_ipad_user_active(fld_user_id, fld_device_id, fld_activestatus) VALUES('".$userid."','".$deviceid."','1')");
					$logid = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_id) FROM itc_ipad_user_active");
				}
				
				$trialcheck = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_trial_users WHERE fld_user_id='".$createdby."' AND fld_delstatus='0'");
				
				$response = array("message" => "success", "id" => $userid, "fname" => $fname, "lname" => $lname, "distid" => $distid, "schoolid" => $schoolid, "indid" => $indid, "trial" => $trialcheck, "logid" => $logid, "profileid" => $sessmasterprfid, "url" => $url, "contenturl" => $contenturl); 
			}
			else {
				$response = array("message" => "Invalid Username / Password");
			}
		}
		else {
			$response = array("message" => "Invalid Username / Password");
		}
		
		echo json_encode($response);
	}
	
	// Lessons Master
	if($oper== 'lesson-master' and $oper!=''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : 0;
		$distid = (isset($_REQUEST['distid'])) ? $_REQUEST['distid'] : 0;
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : 0;
		$indid = (isset($_REQUEST['indid'])) ? $_REQUEST['indid'] : 0;
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		//$profid = (isset($_REQUEST['profileid'])) ? $_REQUEST['profileid'] : '';
		$profid = $ObjDB->SelectSingleValueInt("select fld_profile_id from itc_user_master where fld_id='".$uid."'");
		$synqry = "";
		
		/*if($profid==10){
			$proqry = " b.fld_id IN (SELECT DISTINCT(lm.fld_lesson_id) FROM itc_class_sigmath_master AS si LEFT JOIN itc_class_sigmath_student_mapping AS sm ON sm.fld_student_id='".$uid."' AND si.fld_id=sm.fld_sigmath_id LEFT JOIN itc_class_sigmath_lesson_mapping AS lm ON lm.fld_sigmath_id=si.fld_id
WHERE si.fld_delstatus='0' AND sm.fld_flag='1' AND lm.fld_flag='1')";
		}
		else{*/
			$proqry = ' a.fld_lesson_id=b.fld_id ';			
		//}
			
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (b.`fld_created_date` > '".$syncdate."' OR b.`fld_updated_date` > '".$syncdate."')";
		}
		if($profid==10){
			$lessqry = $ObjDB->QueryObject("SELECT b.fld_id AS iplid, b.fld_ipl_name AS iplname, (SELECT fld_zip_name FROM itc_ipl_version_track 
WHERE fld_ipl_id=b.fld_id AND fld_zip_type='1' AND fld_delstatus='0') AS zipnameweb,(SELECT fld_zip_name FROM itc_ipl_version_track 
WHERE fld_ipl_id=b.fld_id AND fld_zip_type='2' AND fld_delstatus='0') AS zipname, (SELECT fld_version FROM itc_ipl_version_track 
WHERE fld_ipl_id=b.fld_id AND fld_zip_type='1' AND fld_delstatus='0') AS versionno, b.`fld_created_date` AS createddate, b.`fld_updated_date` AS updateddate,
f.`fld_unit_name` AS unitname  
											FROM itc_class_sigmath_student_mapping AS a 
											LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_sigmath_id=c.fld_sigmath_id 
											LEFT JOIN itc_ipl_master AS b ON b.fld_id=c.fld_lesson_id 
											LEFT JOIN itc_class_sigmath_master AS d ON d.fld_id=c.fld_sigmath_id 
											LEFT JOIN itc_class_master AS e ON e.fld_id=d.fld_class_id
											LEFT JOIN itc_unit_master AS f ON f.fld_id=b.fld_unit_id 
											WHERE a.fld_student_id='".$uid."' AND a.fld_flag='1' AND c.fld_flag='1' AND b.fld_delstatus='0' 
												AND b.fld_access='1' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND c.fld_license_id 
												IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schid."' AND fld_user_id='0' 
												AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date) ".$synqry." GROUP BY b.fld_id ORDER BY b.fld_ipl_name");
		}
		else{
		$lessqry = $ObjDB->QueryObject("SELECT b.fld_id AS iplid, b.fld_ipl_name AS iplname,(SELECT fld_zip_name FROM itc_ipl_version_track 
WHERE fld_ipl_id=b.fld_id AND fld_zip_type='1' AND fld_delstatus='0') AS zipnameweb,(SELECT fld_zip_name FROM itc_ipl_version_track 
WHERE fld_ipl_id=b.fld_id AND fld_zip_type='2' AND fld_delstatus='0') AS zipname, (SELECT fld_version FROM itc_ipl_version_track 
WHERE fld_ipl_id=b.fld_id AND fld_zip_type='1' AND fld_delstatus='0') AS versionno, b.`fld_created_date` AS createddate, b.`fld_updated_date` AS updateddate,
d.`fld_unit_name` AS unitname  
											FROM itc_license_cul_mapping AS a 
											LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id 
											RIGHT JOIN itc_ipl_master AS b ON ".$proqry." 
											LEFT JOIN `itc_unit_master` AS d ON b.`fld_unit_id`=d.`fld_id`
											WHERE c.fld_district_id='".$distid."' AND c.fld_school_id='".$schid."' 
												AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
												AND '".date("Y-m-d")."' BETWEEN c.fld_start_date 
												AND c.fld_end_date AND a.fld_active='1' AND b.fld_delstatus='0' AND b.fld_lesson_type='1' AND d.`fld_delstatus`='0' ".$synqry." GROUP BY b.fld_id ORDER BY b.fld_ipl_name");
		}
											
			if($lessqry->num_rows > 0) {
								
				while($rowless = $lessqry->fetch_assoc()){
					extract($rowless);
					
					$lessinsupdt = '';
					
					if($createddate > $syncdate) {
						$lessinsupdt = "ins";
					}
					
					if($createddate < $syncdate and $updateddate > $syncdate) {
						$lessinsupdt = "updt";
					}
					
					$ipltype = 1;
					$zipath = "NA";
					
					
					//if($iplid>192){
						if($versionno=='2.0.0'){
							$zipath = $contenturl."/webipl/".$zipnameweb;
							$ipltype = 2;
						}
					//}

					if($lessinsupdt != '') {
						$lessdet[] = array("id" => $iplid, "lesson_name" => $iplname, "version_no" => $versionno, "ipltype" => $ipltype ,"zippath" => $zipath,"unit-name" => $unitname, "iudflag" => $lessinsupdt, "lessonpath" => $finallessonpath1); 	
					}
				}
								
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject("SELECT b.fld_id AS iplid 
											FROM itc_license_cul_mapping AS a 
											LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id 
											RIGHT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
											WHERE c.fld_district_id='".$distid."' AND c.fld_school_id='".$schid."' 
												AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
												AND '".date("Y-m-d")."' BETWEEN c.fld_start_date 
												AND c.fld_end_date AND a.fld_active='0' AND b.fld_delstatus='0' AND b.fld_lesson_type='1'
											GROUP BY b.fld_id ORDER BY b.fld_ipl_name");
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $iplid;
						}
					}
					
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($lessdet)){
					$lessdet = "no";
				}
						
				$response = array("lessons" => $lessdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("lessons" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Student Master
	if($oper == 'student-master' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$distid = (isset($_REQUEST['distid'])) ? $_REQUEST['distid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';
		$indid = (isset($_REQUEST['indid'])) ? $_REQUEST['indid'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$synqry = "";
		
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (`fld_created_date` > '".$syncdate."' OR `fld_updated_date` > '".$syncdate."')";
		}
		
		$mstprfid = $ObjDB->SelectSingleValue("SELECT fld_profile_id FROM itc_user_master WHERE fld_id='".$uid."'");
		
		if($mstprfid == 5 or ($mstprfid == 9 and $indid !=0)){
			if($mstprfid == 9 and $indid !=0){
				$uid1 = $ObjDB->SelectSingleValue("SELECT fld_user_id FROM itc_user_master WHERE fld_id='".$uid."'");
			}
			else{
				$uid1 = $uid; 
			}
			  
			$qry = "SELECT fld_id AS studid, fld_fname AS fname, fld_lname AS lname, fld_username AS uname, fld_created_date AS createddate, fld_updated_date AS updateddate 
						FROM itc_user_master 
						WHERE fld_profile_id= '10' AND fld_user_id='".$uid1."' AND fld_delstatus='0' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";
			  
			$delqry = "SELECT fld_id AS studid
						FROM itc_user_master 
						WHERE fld_profile_id= '10' AND fld_user_id='".$uid1."' AND fld_delstatus='1' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";
		}
		else if($mstprfid == 7 or $mstprfid == 9 or $mstprfid == 8){
			$qry = "SELECT fld_id AS studid, fld_fname AS fname, fld_lname AS lname, fld_username AS uname, fld_created_date AS createddate, fld_updated_date AS updateddate 
						FROM itc_user_master 
						WHERE fld_profile_id= '10' AND fld_school_id='".$schid."' 
							AND fld_district_id='".$distid."' AND  fld_delstatus='0' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";
			$delqry = "SELECT fld_id AS studid
						FROM itc_user_master 
						WHERE fld_profile_id= '10' AND fld_school_id='".$schid."' 
							AND fld_district_id='".$distid."' AND  fld_delstatus='1' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";				
		}
		else{
			$qry = "SELECT fld_id AS studid, fld_fname AS fname, fld_lname AS lname, fld_username AS uname,fld_created_date AS createddate, fld_updated_date AS updateddate 
						FROM itc_user_master 
						WHERE fld_profile_id= '10' AND fld_delstatus='0' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";
			$delqry = "SELECT fld_id AS studid
						FROM itc_user_master 
						WHERE fld_profile_id= '10' AND fld_delstatus='1' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";			
		}
		
		$studqry = $ObjDB->QueryObject($qry);
											
			if($studqry->num_rows > 0) {
								
				while($rowstud = $studqry->fetch_assoc()){
					extract($rowstud);
					
					$studinsupdt = '';
					
					if($createddate > $syncdate) {
						$studinsupdt = "ins";
					}
					
					if($createddate < $syncdate and $updateddate > $syncdate) {
						$studinsupdt = "updt";
					}
					
					if($studinsupdt != '') {
						$studdet[] = array("id" => $studid, "fname" => $fname, "lname" => $lname,"uname" => $uname, "iudflag" => $studinsupdt); 	
					}
				}
				
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject($delqry);
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $studid;
						}
					}
					
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($studdet)){
					$studdet = "no";
				}
						
				$response = array("students" => $studdet, "delids" => $delids , "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("students" => "no" , "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}	
	
	// Teacher Master
	if($oper == 'teacher-master' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$distid = (isset($_REQUEST['distid'])) ? $_REQUEST['distid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';   
		$indid = (isset($_REQUEST['indid'])) ? $_REQUEST['indid'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$synqry = "";
		
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (`fld_created_date` > '".$syncdate."' OR `fld_updated_date` > '".$syncdate."')";
		}	
		
		$qry = "SELECT fld_id AS teachid, fld_fname AS fname, fld_lname AS lname, fld_username AS uname,  fld_created_date AS createddate, fld_updated_date AS updateddate 
					FROM itc_user_master 
					WHERE fld_profile_id IN (5,8,9) AND fld_school_id='".$schid."' 
						AND fld_district_id='".$distid."' AND  fld_delstatus='0' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";
		$delqry = "SELECT fld_id AS teachid
					FROM itc_user_master 
					WHERE fld_profile_id IN (5,8,9) AND fld_school_id='".$schid."' 
						AND fld_district_id='".$distid."' AND  fld_delstatus='1' ".$synqry." ORDER BY fld_fname ASC, fld_lname ASC";	
		
		$teachqry = $ObjDB->QueryObject($qry);
											
			if($teachqry->num_rows > 0) {							
				while($rowstud = $teachqry->fetch_assoc()){
					extract($rowstud);					
					$teachinsupdt = '';
					
					if($createddate > $syncdate) {
						$teachinsupdt = "ins";						
					}
					
					if($createddate < $syncdate and $updateddate > $syncdate) {
						$teachinsupdt = "updt";							
					}
					
					if($teachinsupdt != '') {						
						$teachdet[] = array("id" => $teachid, "fname" => $fname, "lname" => $lname,"uname" => $uname, "iudflag"  => $teachinsupdt); 	
					}
				}
				
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject($delqry);
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $teachid;
						}
					}
					
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($teachdet)){
					$teachdet = "no";
				}
						
				$response = array("teachers" => $teachdet, "delids" => $delids , "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("teachers" => "no" , "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Class Master
	if($oper == 'class-master' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$distid = (isset($_REQUEST['distid'])) ? $_REQUEST['distid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';
		$indid = (isset($_REQUEST['indid'])) ? $_REQUEST['indid'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$profid = (isset($_REQUEST['profileid'])) ? $_REQUEST['profileid'] : '';
		$synqry = "";
			
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (a.`fld_created_date` > '".$syncdate."' OR a.`fld_updated_date` > '".$syncdate."')";
		}
		if($profid==10){
			$classqry = $ObjDB->QueryObject("SELECT a.`fld_id` AS clsid, a.`fld_class_name` AS classname, a.`fld_lock` AS lockcls, a.`fld_created_date` AS createddate, a.`fld_updated_date` AS updateddate, a.fld_start_date AS sdate, a.fld_end_date AS edate 
											FROM itc_class_master AS a 
											LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_class_id 
											LEFT JOIN itc_class_sigmath_master AS c ON a.fld_id=c.fld_class_id
											LEFT JOIN itc_class_sigmath_student_mapping AS d ON d.fld_sigmath_id=c.fld_id
											WHERE a.fld_delstatus='0' AND b.fld_student_id='".$uid."' AND b.fld_flag='1' AND b.fld_flag='1' 
												AND c.fld_delstatus='0' AND d.fld_student_id='".$uid."' GROUP BY a.fld_id ORDER BY a.`fld_class_name` ASC");
		}
		else{
		
			$classqry = $ObjDB->QueryObject("SELECT a.`fld_id` AS clsid, a.`fld_class_name` AS classname, a.`fld_lock` AS lockcls, a.`fld_created_date` AS createddate, a.`fld_updated_date` AS updateddate, a.fld_start_date as sdate, a.fld_end_date as edate
											FROM itc_class_master a 
											LEFT JOIN `itc_class_sigmath_master` b ON a.`fld_id`=b.`fld_class_id`
											WHERE a.`fld_delstatus`='0' AND (a.`fld_school_id`='".$schid."' AND a.`fld_user_id`='".$indid."') 
												AND (a.`fld_created_by`='".$uid."' 
												OR a.`fld_id` IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."'
												AND fld_flag='1')) AND b.`fld_delstatus`='0' ".$synqry." GROUP BY a.`fld_id` ORDER BY a.`fld_class_name` ASC");
		}
											
			if($classqry->num_rows > 0) {
				while($rowclass = $classqry->fetch_assoc()){
					extract($rowclass);
					
					$gradedet = array();
					$qrygrade = $ObjDB->QueryObject("SELECT b.`fld_grade` AS grade, b.`fld_lower_bound` AS lbound, b.`fld_upper_bound` AS ubound FROM `itc_class_grading_scale_mapping` b WHERE b.`fld_class_id`='".$clsid."' AND b.`fld_flag`='1'");
					while($rowgrade = $qrygrade->fetch_assoc()){
						extract($rowgrade);
						
						$gradedet[] =  array("grade" => $grade, "lower_bound" => $lbound, "upper_bound" => $ubound);
					}
						
					$classinsupdt = '';
					
					if($createddate > $syncdate) {
						$classinsupdt = "ins";
					}
					
					if($createddate < $syncdate and $updateddate > $syncdate) {
						$classinsupdt = "updt";
					}
					
					if($classinsupdt != '') {
						$classstudentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM `itc_class_student_mapping` WHERE `fld_class_id`='".$clsid."' AND `fld_flag`='1'
");
						$classdet[] = array("id" => $clsid, "class_name" => $classname, "lock" => $lockcls, "studentcount" => $classstudentcount, "iudflag" => $classinsupdt, "grade_det" => $gradedet, "startdate" => date("m/d/Y",strtotime($sdate)), "enddate" => date("m/d/Y",strtotime($edate))); 	
					}
					unset($gradedet);
				}				
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject("SELECT fld_id AS clsid
													FROM itc_class_master 
													WHERE fld_delstatus='1' AND (fld_school_id='".$schid."' AND fld_user_id='".$indid."') 
													AND (fld_created_by='".$uid."' 
													OR fld_id IN(select fld_class_id from itc_class_teacher_mapping where fld_teacher_id='".$uid."'
													AND fld_flag='1')) ORDER BY `fld_class_name` ASC");
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $clsid;
						}
					}
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($classdet)){
					$classdet = "no";
				}
				
				$response = array("classes" => $classdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else if($syncdate != '0000-00-00 00:00:00'){
				$delids = array();
				$qrydel = $ObjDB->QueryObject("SELECT fld_id AS clsid
													FROM itc_class_master 
													WHERE fld_delstatus='1' AND (fld_school_id='".$schid."' AND fld_user_id='".$indid."') 
													AND (fld_created_by='".$uid."' 
													OR fld_id IN(select fld_class_id from itc_class_teacher_mapping where fld_teacher_id='".$uid."'
													AND fld_flag='1')) ORDER BY `fld_class_name` ASC");
				if($qrydel->num_rows > 0){
					while($rowdel = $qrydel->fetch_assoc()){
						extract($rowdel);
						
						$delids[] = $clsid;
					}
				}
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($classdet)){
					$classdet = "no";
				}
						
				$response = array("classes" => $classdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("classes" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Schedule Master
	if($oper == 'sch-master' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$clsid = (isset($_REQUEST['clsid'])) ? $_REQUEST['clsid'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$profid = (isset($_REQUEST['profileid'])) ? $_REQUEST['profileid'] : '';
		$synqry = "";
			
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (a.`fld_created_date` > '".$syncdate."' OR a.`fld_updated_date` > '".$syncdate."')";
		}
		$tbl = "";
		$con = "";
		if($profid==10){
			$tbl = " LEFT JOIN itc_class_sigmath_student_mapping AS b ON b.fld_sigmath_id=a.fld_id ";
			$con = "  AND b.fld_student_id=".$uid." AND b.fld_flag='1' ";
		}
		
		$schqry = $ObjDB->QueryObject("SELECT a.`fld_id` AS schid, a.`fld_schedule_name` AS schname, a.`fld_start_date` AS stdate, a.`fld_end_date` AS edate, 
										a.fld_created_date AS createddate, a.fld_updated_date AS updateddate, (SELECT COUNT(*) FROM `itc_class_sigmath_student_mapping` b 
										WHERE b.fld_sigmath_id = a.`fld_id` AND b.fld_license_id=a.`fld_license_id` AND b.fld_flag=1) AS stucount
										FROM `itc_class_sigmath_master` a ".$tbl."
										WHERE a.`fld_class_id`='".$clsid."' AND a.fld_delstatus='0' ".$synqry." ".$con." ORDER BY a.`fld_schedule_name` ASC");
											
			if($schqry->num_rows > 0) {
								
				while($rowsch = $schqry->fetch_assoc()){
					extract($rowsch);
					
					$schinsupdt = '';
					
					if($createddate > $syncdate) {
						$schinsupdt = "ins";
					}
					
					if($createddate < $syncdate and $updateddate > $syncdate) {
						$schinsupdt = "updt";
					}
					$chk = 1;
					$lessonid=0;
					if($profid==10){
						$chk = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_class_sigmath_student_mapping where fld_sigmath_id='".$schid."' and fld_student_id='".$uid."' and fld_flag='1'");
						$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$schid."' AND fld_student_id='".$uid."' AND fld_status=0 ORDER BY fld_id DESC LIMIT 0,1");		
						if($lessonid==0){
							$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$schid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$schid."' AND fld_test_type='1') ORDER BY fld_order LIMIT 0,1");
						}
					}
					if($schinsupdt != '' or $chk!=0) {
						$schdet[] = array("id" => $schid, "sch_name" => $schname, "sdate" => date("m/d/Y",strtotime($stdate)), "edate" => date("m/d/Y",strtotime($edate)), "student_count" => $stucount, "iudflag" => $schinsupdt, "lessonid" => $lessonid); 	
					}
				}
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject("SELECT a.`fld_id` AS schid
													FROM `itc_class_sigmath_master` a 
													WHERE a.`fld_class_id`='".$clsid."' AND a.fld_delstatus='1'");
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $schid;
						}
					}
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($schdet)){
					$schdet = "no";
				}
										
				$response = array("schedules" => $schdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			elseif($syncdate != '0000-00-00 00:00:00') {
				$delids = array();
				$qrydel = $ObjDB->QueryObject("SELECT a.`fld_id` AS schid
													FROM `itc_class_sigmath_master` a 
													WHERE a.`fld_class_id`='".$clsid."' AND a.fld_delstatus='1'");
				if($qrydel->num_rows > 0){
					while($rowdel = $qrydel->fetch_assoc()){
						extract($rowdel);
						
						$delids[] = $schid;
					}
				}
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($schdet)){
					$schdet = "no";
				}
						
				$response = array("schedules" => $schdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("schedules" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Current Assignments
	if($oper == 'current-assignments' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';
		$indid = (isset($_REQUEST['indid'])) ? $_REQUEST['indid'] : '';
		
		$schqry = $ObjDB->QueryObject("SELECT a.fld_class_id AS classid, a.fld_schedule_name AS schname, a.fld_id AS scheduleid, a.fld_start_date AS sdate, a.fld_end_date 
AS edate FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id LEFT JOIN itc_class_master AS c ON c.fld_id=a.fld_class_id 
WHERE a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_flag='1' AND b.fld_student_id='".$uid."' AND c.fld_delstatus='0' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schid."' AND fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date)");
											
			if($schqry->num_rows > 0) {
								
				while($rowsch = $schqry->fetch_assoc()){
					extract($rowsch);					
					
					$chk = 1;
					$lessonid=0;					
					
					$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$scheduleid."' AND fld_student_id='".$uid."' AND fld_status=0 ORDER BY fld_id DESC LIMIT 0,1");		
					if($lessonid==0){
						$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$scheduleid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$scheduleid."' AND fld_test_type='1') ORDER BY fld_order LIMIT 0,1");
					}
					if($lessonid=='')
						$lessonid=0;
					$schdet[] = array("classid" => $classid, "id" => $scheduleid, "sch_name" => $schname, "sdate" => date("m/d/Y",strtotime($sdate)), "edate" => date("m/d/Y",strtotime($edate)), "lessonid" => $lessonid); 	
					
				}				
				
				if(empty($schdet)){
					$schdet = "no";
				}
										
				$response = array("schedules" => $schdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}			
			else {
				$response = array("schedules" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Schedule Student Mapping Master
	if($oper== 'sch-stud-master' and $oper!=''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$synqry = "";
			
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (a.`fld_created_date` > '".$syncdate."' OR a.`fld_updated_date` > '".$syncdate."')";
		}
		
		$schstudqry = $ObjDB->QueryObject("SELECT a.`fld_id` AS schstudid, a.`fld_student_id` AS studid,
											(SELECT COUNT(*) FROM `itc_assignment_sigmath_master` WHERE `fld_student_id` = a.`fld_student_id` AND (`fld_status`='1' OR `fld_status`='2') AND `fld_schedule_id`='".$schid."') AS completed,
(SELECT COUNT(*) FROM `itc_class_sigmath_lesson_mapping` WHERE `fld_sigmath_id`='".$schid."' AND `fld_flag`='1') AS overall
										FROM `itc_class_sigmath_student_mapping` a
										WHERE a.fld_sigmath_id = '".$schid."' AND a.`fld_flag`=1");
											
			if($schstudqry->num_rows > 0) {
								
				while($rowschstud = $schstudqry->fetch_assoc()){
					extract($rowschstud);
					
					$gradedet = array();
					$qrygrade = $ObjDB->QueryObject("SELECT `fld_lesson_id` AS lessonid, `fld_teacher_points_earned` AS tptsearned, `fld_points_earned` AS ptsearned, `fld_points_possible` AS ptspossible, `fld_type` AS lockstage, `fld_status` AS completestatus, `fld_updated_date` AS completeddate, fld_id as maxid FROM `itc_assignment_sigmath_master` WHERE `fld_schedule_id`='".$schid."' AND `fld_student_id`='".$studid."' AND `fld_delstatus`='0' AND `fld_lesson_id`<>'' AND `fld_rubrics_id`='0'");
					if($qrygrade->num_rows > 0) {
						while($rowgrade = $qrygrade->fetch_assoc()){
							extract($rowgrade);
							
							$lockflag = 0;
							$status = "not started";
							$tptsflag = 0;
							$compldate = 'no';
							
							if($lockstage == 5) {
								$lockflag = 1;
							}
							
							if($completestatus == 1) {
								$status = "completed";	
								$compldate = $completeddate;
							}
							else if($completestatus == 2){
								$status = "failed";
								$compldate = $completeddate;
							}
							else if($completestatus == 0 and $lockstage != 1 and $lockstage != 5 ) {
								$status = "in progress";
								$compldate = 'no';
							}
							
							if($tptsearned != ''){
								$tptsflag = 1;
							}
							else {
								$tptsearned = 'no';	
							}
							
							if($ptsearned == '') {
								$ptsearned = 0;	
							}
							
							$gradedet[] =  array("lesson_id" => $lessonid, "tptsearned" => $tptsearned, "ptsearned" => $ptsearned, "ptspossible" => $ptspossible, "lockflag" => $lockflag, "lesson_status" => $status, "completed_date" => $compldate, "tptsflag" => $tptsflag, "maxid" => $maxid);
						}
					}
					
					if(empty($gradedet)) {
						$gradedet = "no";	
					}
					
					$schdet[] = array("id" => $schstudid, "sch_id" => $schid, "student_id" => $studid, "lesson_completed" => $completed, "lesson_overall" => $overall, "sch_grade_det" => $gradedet);
					unset($gradedet);
				}
				
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject("SELECT a.`fld_id` AS schstudid, a.`fld_student_id` AS studid
										FROM `itc_class_sigmath_student_mapping` a
										WHERE a.fld_sigmath_id = '".$schid."' AND a.`fld_flag`=0");
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $schid;
						}
					}
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($schdet)){
					$schdet = "no";
				}
						
				$response = array("schedule-students" => $schdet, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("schedule-students" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Schedule Lesson Mapping Master
	if($oper== 'sch-lesson-master' and $oper!=''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$distid = (isset($_REQUEST['distid'])) ? $_REQUEST['distid'] : '';
		$schlid = (isset($_REQUEST['schlid'])) ? $_REQUEST['schlid'] : '';
		$indid = (isset($_REQUEST['indid'])) ? $_REQUEST['indid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$profid = (isset($_REQUEST['profileid'])) ? $_REQUEST['profileid'] : '';
		$synqry = "";
			
		if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (a.`fld_created_date` > '".$syncdate."' OR a.`fld_updated_date` > '".$syncdate."')";
		}
		$userqry = '';
		if($profid==10){
			$userqry = ' AND fld_student_id='.$uid;
		}
		$schlessqry = $ObjDB->QueryObject("SELECT a.`fld_id` AS schlessid, a.`fld_lesson_id` AS lessid,
										(SELECT COUNT(*) FROM `itc_assignment_sigmath_master` WHERE fld_lesson_id=a.`fld_lesson_id` AND fld_schedule_id='".$schid."' AND  fld_status='2' ".$userqry.") AS failed,
										(SELECT COUNT(*) FROM `itc_assignment_sigmath_master` WHERE fld_lesson_id=a.`fld_lesson_id` AND fld_schedule_id='".$schid."' AND fld_status='1' ".$userqry.") AS passed,
										(SELECT COUNT(*) FROM `itc_assignment_sigmath_master` WHERE fld_lesson_id=a.`fld_lesson_id` AND fld_schedule_id='".$schid."' AND (fld_status='1' or fld_status='2') ".$userqry.") AS completed,
										(SELECT COUNT(*) FROM `itc_class_sigmath_student_mapping` WHERE fld_sigmath_id='".$schid."') AS overall
										FROM `itc_class_sigmath_lesson_mapping` a
										LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id
										WHERE a.fld_sigmath_id = '".$schid."' AND a.`fld_flag`='1'
										AND b.fld_district_id='".$distid."' AND b.fld_school_id='".$schlid."' AND b.fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN b.fld_start_date AND b.fld_end_date ORDER BY a.fld_order ASC");
											
			if($schlessqry->num_rows > 0) {
								
				while($rowschless = $schlessqry->fetch_assoc()){
					extract($rowschless);
					
					$schless[] = array("id" => $schlessid, "sch_id" => $schid, "lesson_id" => $lessid, "passed" => $passed, "failed" => $failed, "lesscompleted" => $completed, "lessoverall" => $overall); 	
				}
				
				$delids = array();
				
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject("SELECT a.`fld_id` AS schlessid, a.`fld_lesson_id` AS lessid
										FROM `itc_class_sigmath_lesson_mapping` a
										WHERE a.fld_sigmath_id = '".$schid."' AND a.`fld_flag`=0");
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $schid;
						}
					}
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($schless)){
					$schless = "no";
				}
						
				$response = array("schedule-lessons" => $schless, "delids" => $delids, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("schedule-lessons" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	// Message Master
	if($oper== 'message-master' and $oper!=''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$lmsgid = (isset($_REQUEST['lmsgid'])) ? $_REQUEST['lmsgid'] : 0;
		$msgtype = (isset($_REQUEST['msgtype'])) ? $_REQUEST['msgtype'] : 0;
		$loadtype = (isset($_REQUEST['loadtype'])) ? $_REQUEST['loadtype'] : 0; 
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		$synqry = "";
		$unreadcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_message_master WHERE fld_to='".$uid."' AND fld_readstatus='0' AND fld_delstatus='0'");	
		if($syncdate != '0000-00-00 00:00:00') {
			if($loadtype == 0) { // pull to refresh
				$synqry = "AND (fld_created_date > '".$syncdate."' OR fld_updated_date > '".$syncdate."')";
			}
			else { // load more
				$synqry = "AND fld_id<".$lmsgid."  AND (fld_created_date < '".$syncdate."')";
			}
		}
		else {
			if($loadtype == 1) {  // load more
				$synqry = "AND fld_id<".$lmsgid." ";
			}
		}
		
		$userstype = "fld_from";
		if($msgtype==0)/******inbox*********/
		{
			$extraqry = "AND fld_to='".$uid."' AND fld_archive_status='0' AND fld_todelstatus='0'";
			
		}
		else if($msgtype==1)/*****sent box*********/
		{
			$extraqry = "AND fld_from='".$uid."' AND fld_fromdelstatus='0' ";
		    $userstype = "fld_to";  
		}
		else if($msgtype==2)/*****archive box*********/
		{
			$extraqry="AND fld_to='".$uid."' AND fld_archive_status='1' AND fld_archdelstatus='0'";
		}		
		
		$readmsgeqry = $ObjDB->QueryObject("SELECT fld_id AS msgid,(SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id = ".$userstype.") AS unames, fld_from AS msgfrm, fld_subject AS msgsubject, fld_message AS message, fld_readstatus as readstatus, fld_created_date AS recdate, fld_archive_status as archstatus FROM itc_message_master WHERE fld_delstatus='0' ".$extraqry." ".$synqry." ORDER BY fld_created_date DESC LIMIT 10");
											
			if($readmsgeqry->num_rows > 0) {
								
				while($rowrdmsg = $readmsgeqry->fetch_assoc()){
					extract($rowrdmsg);
					$recdate = date('Y-m-d H:i:s', strtotime($recdate . ' +6 hours'));
					$rdmsg[] = array("id" => $msgid, "unames" => $unames, "msgfrm" => $msgfrm, "msgsubject" => $msgsubject, "message" => $message, "readstatus" => $readstatus,"created-date" => $recdate,"arch-status" => $archstatus); 	
				}
				
				$delids = array();
				if($syncdate != '0000-00-00 00:00:00') {
					
					$qrydel = $ObjDB->QueryObject("SELECT fld_id AS msgid FROM itc_message_master WHERE fld_delstatus='0' AND fld_to='".$uid."' AND fld_archive_status='0' AND fld_todelstatus='1' ".$synqry." ORDER BY fld_created_date DESC");
					if($qrydel->num_rows > 0){
						while($rowdel = $qrydel->fetch_assoc()){
							extract($rowdel);
							
							$delids[] = $msgid;
						}
					}
				}
				
				if(empty($delids)){
					$delids = "no";
				}
				
				if(empty($rdmsg)){
					$rdmsg = "no";
				}
						
				$response = array("messages" => $rdmsg, "delids" => $delids, "unreadcount" => $unreadcount, "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			else {
				$response = array("messages" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
			}
			
		echo json_encode($response);
	}
	
	if($oper== 'message-send' and $oper!=''){
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		$msgto = isset($_REQUEST['msgto']) ? $_REQUEST['msgto'] : '';
		$msgsubject = isset($_REQUEST['msgsubject']) ? $ObjDB->EscapeStrAll($_REQUEST['msgsubject']) : '';
		$message = isset($_REQUEST['message']) ? $ObjDB->EscapeStr($_REQUEST['message']) : '';
		$dropdowntype = isset($_REQUEST['dropdowntype']) ? $_REQUEST['dropdowntype'] : '';
		if($dropdowntype!=1)
		{
			//$sendqry=$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,fld_readstatus,fld_created_by,fld_created_date) VALUES('".$uid."','".$msgto."','".$msgsubject."','".$message."','0','".$uid."','".date("Y-m-d H:i:s")."' )");
			try{
				$sendqry=$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,fld_readstatus,fld_created_by,fld_created_date) VALUES('".$uid."','".$msgto."','".$msgsubject."','".$message."','0','".$uid."','".date("Y-m-d H:i:s")."' )");
				$response = array("status" => "success");
				}
			catch(Exception $e)
				{
					$response = array("status" => "fail");
				}
		}
		else
		{
			try{
				$classstudcount=$ObjDB->QueryObject("SELECT  fld_student_id as studid  FROM itc_class_student_mapping WHERE fld_flag=1 AND fld_class_id='".$msgto."'");
					if($classstudcount->num_rows>0){
						while($row = $classstudcount->fetch_assoc())
						{
							extract($row);
						$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,fld_readstatus,fld_created_by,fld_created_date) VALUES('".$uid."','".$studid."','".$msgsubject."','".$message."','0','".$uid."','".date("Y-m-d")."') )");
						}
						
				}
			}
			catch(Exception $e)
				{
					$response = array("status" => "fail");
				}
				
		}
		//$response = array("success");
		$response = array("status" => "success");
		echo json_encode($response);
	}
	if($oper=="message-unread" and $oper !="")
	{
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		
		$msgunread=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_message_master WHERE fld_readstatus='0' AND fld_to='".$uid."' AND fld_delstatus='0'");
			
			if($msgunread!='0'||$msgunread!='')
			{
				$response = array("unreadmsg" => $msgunread );
			}
			else
			{
				$response = array("unreadmsg" => "0" );
			}
		
		
		echo json_encode($response);
		
	}
	if($oper=="message-read" and $oper !="")
	{
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		$msgid = isset($_REQUEST['msgid']) ? $_REQUEST['msgid'] : '';
		
		$qry=$ObjDB->NonQuery("update itc_message_master set fld_readstatus='1' ,fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$msgid."'");
		$msgread=$ObjDB->SelectSingleValueInt("SELECT fld_readstatus FROM itc_message_master WHERE fld_readstatus='1' AND fld_id='".$msgid."' ");
		
		if( $msgread==1)
		{ 
			$response = array("status" => "success");
		}
		else
		{
			
			$response = array("status" => "fail");
		}
		
		echo json_encode($response);
		
	}
	if($oper=="message-archive" and $oper !="")
	{
		$msgid = isset($_REQUEST['msgid']) ? $_REQUEST['msgid'] : '';
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		
		$archiveqry=$ObjDB->NonQuery("update itc_message_master set fld_readstatus='1', fld_archive_status='1' , fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$msgid."' AND fld_delstatus='0'");
		$msgarchive=$ObjDB->SelectSingleValueInt("SELECT fld_archive_status FROM itc_message_master WHERE fld_archive_status='1' AND fld_id='".$msgid."' ");
		if($msgarchive == 1)
		{ 
			$response = array("status" => "success");
		}
		else
		{
			
			$response = array("status" => "fail");
		}
		//$response = array("success"); 
		echo json_encode($response);
		
	}
	if($oper=="message-backtoinbox" and $oper !="")
	{
		$msgid = isset($_REQUEST['msgid']) ? $_REQUEST['msgid'] : '';
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		
		$backtoinbox=$ObjDB->NonQuery("update itc_message_master set fld_readstatus='1', fld_archive_status='0' , fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$msgid."' AND fld_delstatus='0' AND fld_to='".$uid."'");
		
		$msgarchive=$ObjDB->SelectSingleValueInt("SELECT fld_archive_status FROM itc_message_master WHERE fld_archive_status='0' AND fld_id='".$msgid."' ");
		
		if($msgarchive == 0 || $msgarchive == '')
		{ 
			$response = array("status" => "success");
		}
		else
		{
			
			$response = array("status" => "fail");
		}
		//$response = array("success"); 
		echo json_encode($response);
		
	}
	if($oper=="message-reply" and $oper!="")
	{
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		$messagereply = isset($_REQUEST['messagereply']) ? $ObjDB->EscapeStr($_REQUEST['messagereply']) : '';
		$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '';
		$sender = isset($_REQUEST['sender']) ? $_REQUEST['sender'] : '';
		$msgid = isset($_REQUEST['msgid']) ? $_REQUEST['msgid'] : '';
		
		try{
			$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,fld_readstatus,fld_created_by,fld_created_date) VALUES('".$uid."','".$sender."','".$subject."','".$messagereply."','0','".$uid."','".date("Y-m-d H:i:s")."' )");
			
			$response = array("status" => "success");
		}
		catch(Exception $e)
		{
			$response = array("status" => "fail");
		}
		echo json_encode($response);
		
	}
	
	if($oper=="message-forward" and $oper != " " )
	{
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		$msgto = isset($_REQUEST['msgto']) ? $_REQUEST['msgto'] : '';
		$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '';
		$fwdmessage = isset($_REQUEST['fwdmessage']) ? $ObjDB->EscapeStr($_REQUEST['fwdmessage']) : '';
		$dropdowntype = isset($_REQUEST['dropdowntype']) ? $_REQUEST['dropdowntype'] : '';
		//echo $msgto;
		if($dropdowntype!=1)
		{
			try{
			$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,fld_readstatus,fld_created_by,fld_created_date) VALUES('".$uid."','".$msgto."','".$subject."','".$fwdmessage."','0','".$uid."','".date("Y-m-d H:i:s")."' )");
			$response = array("status" => "success");
			}
			catch(Exception $e)
			{
				$response = array("status" => "fail");
			}
		}
		else
		{
			try{
				$classstudcount=$ObjDB->QueryObject("SELECT  fld_student_id as studid  FROM itc_class_student_mapping WHERE fld_flag=1 AND fld_class_id='".$msgto."'");
				
						if($classstudcount->num_rows>0){
							while($row = $classstudcount->fetch_assoc())
							{
								extract($row);
							$ObjDB->NonQuery("INSERT INTO itc_message_master(fld_from,fld_to,fld_subject,fld_message,fld_readstatus,fld_created_by,fld_created_date) VALUES('".$uid."','".$studid."','".$msgsubject."','".$message."','0','".$uid."','".date("Y-m-d H:i:s")."' )");
							}
							
					}
					$response = array("status" => "success");
			}
			catch(Exception $e)
			{
				$response = array("status" => "fail");
			}
		}
		//$response = array("success");
		echo json_encode($response);
	}
	if($oper=="message-delete" and $oper!="")
	{
		$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
		$msgid = isset($_REQUEST['msgid']) ? $_REQUEST['msgid'] : '';
		$type = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($type!=1)
		{
			$extraqry="fld_todelstatus='1'";
		}
		if($type==1)
		{
			$extraqry="fld_fromdelstatus='1'";
		}
		if($type==2)
		{
			$extraqry="fld_archdelstatus='1'";
		}
		try{
			$ObjDB->NonQuery("update itc_message_master set ".$extraqry." ,fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' where fld_id='".$msgid."' AND fld_delstatus='0'");
			
			$response = array("status" => "success");
		}
		catch(Exception $e)
		{
			$response = array("status" => "fail");
		}
		echo json_encode($response);
	}
	
/*-------- Receving End of API  --------*/	

	// Logout
	if($oper == 'logout' and $oper != ''){
		$logid = (isset($_REQUEST['logid'])) ? $_REQUEST['logid'] : '';
		
		$ObjDB->NonQuery("UPDATE itc_ipad_user_active SET fld_activestatus='0' WHERE fld_id='".$logid."'");
	}
	
	// Update user's password
	if($oper == 'update-password' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		
		if($syncdate != '') {
			
			$encpassword = fnEncrypt($password,$encryptkey);
			$ObjDB->NonQuery("UPDATE itc_user_master SET fld_password='".$encpassword."' WHERE fld_id='".$uid."'");
			
			$response = array("messages" => "success", "lastsyncdate" => date("Y-m-d H:i:s"));
		}
		else {
			$response = array("messages" => "error");
		}
		
		echo json_encode($response);
	}
	
	// Update student's mark for a lesson
	if($oper == 'update-stu-mark' and $oper != ''){
		$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
		$schid = (isset($_REQUEST['schid'])) ? $_REQUEST['schid'] : '';
		$lessid = (isset($_REQUEST['lessid'])) ? $_REQUEST['lessid'] : '';
		$mark = (isset($_REQUEST['mark'])) ? $_REQUEST['mark'] : '';
		$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
		
	}
	
/**********/
if($oper=="unlock" and $oper!='')
{
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';
	try{		
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='4', fld_status=0, fld_unlocked_by='".$uid."', fld_unlocked_date='".date("Y-m-d H:i:s")."' where fld_id='".$maxid."'");
		$response = array("messages" => "success");
	}
	catch(Exception $e)
	{
		$response = array("messages" => "fail");
	}
	echo json_encode($response);
	
}

if($oper=="unlockall" and $oper!='')
{
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
	//$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';
	
	try{		
		$qry = $ObjDB->QueryObject("SELECT a.fld_id AS maxid, a.fld_student_id AS studentid, a.fld_lesson_id AS lessonid, c.fld_schedule_name AS sname, CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, f.fld_ipl_name AS lessonname FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_class_id=b.fld_class_id LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=a.fld_schedule_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id LEFT JOIN itc_user_master AS e ON e.fld_id=a.fld_student_id LEFT JOIN itc_ipl_master AS f ON f.fld_id=a.fld_lesson_id WHERE a.fld_type=5 AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND f.fld_delstatus='0'");
		if($qry->num_rows>0){
		   while($res = $qry->fetch_assoc()){
				extract($res);
				$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='4', fld_status=0, fld_unlocked_by='".$uid."', fld_unlocked_date='".date("Y-m-d H:i:s")."' where fld_id='".$maxid."'");
			} 
		} 
		$response = array("messages" => "success");
	}
	catch(Exception $e)
	{
		$response = array("messages" => "fail");
	}
	echo json_encode($response);
	
}

if($oper=="calendar" and $oper!='')
{
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
	$syncdate = (isset($_REQUEST['syncdate'])) ? $_REQUEST['syncdate'] : '';
	
	$synqry="";
	
	 if($syncdate != '0000-00-00 00:00:00') {
			$synqry = "AND (a.fld_created_date > '".$syncdate."' OR a.fld_updated_date > '".$syncdate."')";
		}
	try{
		$calqry = $ObjDB->QueryObject("SELECT 0 AS eventid,a.fld_schedule_name AS sname,b.fld_rotation AS rotation,b.fld_startdate AS startdate,b.fld_enddate AS enddate,0 AS starttime,0 AS endtime FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id WHERE b.fld_student_id='".$uid."' AND a.fld_delstatus=0 AND b.fld_flag=1 ".$synqry."  GROUP BY b.fld_rotation,b.fld_schedule_id UNION SELECT 0 AS eventid, a.fld_schedule_name AS sname,b.fld_rotation AS rotation,b.fld_startdate AS startdate,b.fld_enddate AS enddate,0 AS starttime,0 AS endtime FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id WHERE b.fld_student_id='".$uid."' AND a.fld_delstatus=0 AND b.fld_flag=1 ".$synqry." GROUP BY b.fld_rotation,b.fld_schedule_id UNION SELECT 0 AS eventid,a.fld_schedule_name AS sname,b.fld_rotation AS rotation,b.fld_startdate AS startdate,b.fld_enddate AS enddate,0 AS starttime,0 AS endtime FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedulegriddet AS b ON b.fld_schedule_id=a.fld_id WHERE b.fld_student_id='".$uid."' AND a.fld_delstatus=0 AND b.fld_flag=1 ".$synqry." GROUP BY b.fld_rotation,b.fld_schedule_id  UNION SELECT a.fld_id AS eventid,a.fld_app_name AS sname,0 AS rotation, a.fld_startdate AS startdate,a.fld_enddate AS enddate, a.fld_starttime AS starttime, a.fld_endtime AS endtime FROM itc_calendar_master AS a WHERE a.fld_created_by='".$uid."' AND a.fld_delstatus='0' ".$synqry."");
	
		$output=array();
		if($calqry->num_rows > 0) {
			while($cal = $calqry->fetch_assoc()){
			extract($cal);
				
				$output[] = array("eventid"=>$eventid,"schedulename" => $sname, "rotation" => $rotation, "startdate" => $startdate, "enddate" => $enddate,"starttime" => $starttime,"endtime" => $endtime );
				//array_push($output,$response);
			}
		}
        else
			$output = "no";
		
	}
	catch(Exception $e)
	{
		$response = array("status" => "fail","lastsyncdate" => date("Y-m-d H:i:s") );
	}
	
	$response=array("event"=>$output,"lastsyncdate"=> date("Y-m-d H:i:s"));
	echo json_encode($response);
	
}	
if($oper=="calendar-eventsave" and $oper!='')
{
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
	$eventid = isset($_REQUEST['eventid']) ? $_REQUEST['eventid'] : '';
	$eventtitle = isset($_REQUEST['eventtitle']) ? $ObjDB->EscapeStrAll($_REQUEST['eventtitle']) : '';
	$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '';
	$starttime = isset($_REQUEST['starttime']) ? $_REQUEST['starttime'] : '';
	$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';
	$endtime = isset($_REQUEST['endtime']) ? $_REQUEST['endtime'] : '';
	try{
		if($eventid!=0 && $eventid!="undefined" )
		{
			$ObjDB->NonQuery("update itc_calendar_master set fld_app_name='".$eventtitle."',fld_startdate='".date('Y-m-d',strtotime($startdate))."',fld_starttime='".date('H:i:s',strtotime($starttime))."', fld_enddate='".date('Y-m-d',strtotime($enddate))."',fld_endtime='".date('H:i:s',strtotime($endtime))."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$eventid."' and fld_delstatus='0'");
			$response = array("status" => "update-success","eventid"=>$eventid);
		}
		else if($eventid==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_calendar_master (fld_app_name,fld_startdate,fld_starttime,fld_enddate,fld_endtime,fld_created_by) VALUES ('".$eventtitle."','".date('Y-m-d',strtotime($startdate))."','".date('H:i:s',strtotime($starttime))."','".date('Y-m-d',strtotime($enddate))."','".date('H:i:s',strtotime($endtime))."','".$uid."')");
			$lasteventid=$ObjDB->SelectSingleValue("SELECT MAX(fld_id) FROM itc_calendar_master WHERE fld_delstatus='0'");
 			$response = array("status" => "add-success","eventid"=>$lasteventid);
		}
			
	}
	catch(Exception $e)
	{
		$response = array("status" => "fail");
	}
	echo json_encode($response);
}


// Student-Grades
if($oper== 'student-grades' and $oper!=''){	
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
	$classid  = (isset($_REQUEST['classid'])) ? $_REQUEST['classid'] : '';
	$lessonid  = (isset($_REQUEST['lessonid'])) ? $_REQUEST['lessonid'] : '';
	$mark  = (isset($_REQUEST['mark'])) ? $_REQUEST['mark'] : '';
	$scheduleid  = (isset($_REQUEST['scheduleid'])) ? $_REQUEST['scheduleid'] : '';
	$studentid  = (isset($_REQUEST['studentid'])) ? $_REQUEST['studentid'] : '';	
	$maxid  = (isset($_REQUEST['maxid'])) ? $_REQUEST['maxid'] : '0';		
	try{
		$unitid = $ObjDB->SelectSingleValueInt("select fld_unit_id from itc_ipl_master where fld_id='".$lessonid."'");
		if($maxid!=0 && $maxid!=''){
			$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_teacher_points_earned='".$mark."', fld_lock='1',fld_status='1' where fld_id='".$maxid."'");
		}
		else{
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master(fld_class_id,fld_schedule_id,fld_unit_id,fld_lesson_id,fld_student_id,fld_teacher_points_earned,fld_points_possible,fld_status,fld_created_date,fld_lock) VALUES('".$classid."','".$scheduleid."','".$unitid."','".$lessonid."','".$studentid."','".$mark."','100','1','".date("Y-m-d H:i:s")."','1' )");
			$maxid = $ObjDB->SelectSingleValueInt("select max(fld_id) from itc_assignment_sigmath_master");	
		}			
		$response = array("status" => "success","maxid" => $maxid,"createddate" => date("Y-m-d"));
	
	}
	catch(Exception $e)
	{
		$response = array("status" => "fail");
	}			
	echo json_encode($response);
}

if($oper== 'changepassword' and $oper!=''){	
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';
	$password  = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
	$encryptpass = fnEncrypt($password,$encryptkey);			
	try{
		if($password!=''){		
			$ObjDB->NonQuery("update itc_user_master set fld_password='".$encryptpass."' where fld_id='".$uid."'");			
			$response = array("status" => "success");
		}
		else{
			$response = array("status" => "fail");	
		}
	}
	catch(Exception $e)
	{
		$response = array("status" => "fail");
	}			
	echo json_encode($response);
}

if($oper == 'lockedstudents' and $oper != ''){
	$uid = (isset($_REQUEST['uid'])) ? $_REQUEST['uid'] : '';	
		
	$qry = $ObjDB->QueryObject("SELECT a.fld_id AS maxid, a.fld_student_id AS studentid, a.fld_lesson_id AS lessonid, c.fld_schedule_name AS sname, CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, f.fld_ipl_name AS lessonname, a.fld_class_id AS classid, a.fld_schedule_id AS sid, a.fld_student_id AS stuid, a.fld_points_earned AS earned, a.fld_teacher_points_earned AS tpoints, a.fld_points_possible AS possible, a.fld_lock AS tflag FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_class_id=b.fld_class_id LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=a.fld_schedule_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id LEFT JOIN itc_user_master AS e ON e.fld_id=a.fld_student_id LEFT JOIN itc_ipl_master AS f ON f.fld_id=a.fld_lesson_id WHERE a.fld_type=5 AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND f.fld_delstatus='0'");
	if($qry->num_rows>0){
	   while($res = $qry->fetch_assoc()){
			extract($res);
			if($tpoints=='')
			$tpoints=0;
			if($earned=='')
			$earned=0;
			if($possible=='')
			$possible=0;
			$studet[] =  array("maxid" => $maxid,"classid" => $classid,"scheduleid" => $sid,"studentid" => $stuid,"lessonid" => $lessonid,"pointsearned" => $earned,"teacherpoint" => $tpoints,"pointspossible" => $possible,"lockstatus" => '1',"lessonstatus" => "in progress","completeddate" => 'no',"teacherflag" => $tflag );
		} 
		$response = array("lockedstudents" => $studet, "lastsyncdate" => date("Y-m-d H:i:s"));
	} 
	else {
		$response = array("lockedstudents" => "no", "lastsyncdate" => date("Y-m-d H:i:s"));
	}
		
	echo json_encode($response);
}
@include("footer.php");