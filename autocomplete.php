<?php
	@include("sessioncheck.php");
	
	$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '0';
	$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '0';
	$course = isset($_REQUEST['course']) ? $_REQUEST['course'] : '0';
	$unit = isset($_REQUEST['unit']) ? $_REQUEST['unit'] : '0';
	$lesson = isset($_REQUEST['lesson']) ? $_REQUEST['lesson'] : '0';
        $pdlessons = isset($_REQUEST['pdlessons']) ? $_REQUEST['pdlessons'] : '0';
        $customcontent = isset($_REQUEST['customcontent']) ? $_REQUEST['customcontent'] : '0';
	$diagtag = isset($_REQUEST['diagtag']) ? $_REQUEST['diagtag'] : '0';
	$testquestion = isset($_REQUEST['testquestion']) ? $_REQUEST['testquestion'] : '0';
	$test = isset($_REQUEST['test']) ? $_REQUEST['test'] : '0';
	$class = isset($_REQUEST['class']) ? $_REQUEST['class'] : '0';
	$module = isset($_REQUEST['module']) ? $_REQUEST['module'] : '0';
	$quest = isset($_REQUEST['quest']) ? $_REQUEST['quest'] : '0';
	$mathmodule = isset($_REQUEST['mathmodule']) ? $_REQUEST['mathmodule'] : '0';
        $material = isset($_REQUEST['material']) ? $_REQUEST['material'] : '0';
        $missionmaterial = isset($_REQUEST['missionmaterial']) ? $_REQUEST['missionmaterial'] : '0';
	$activity = isset($_REQUEST['activity']) ? $_REQUEST['activity'] : '0';
	$report = isset($_REQUEST['report']) ? $_REQUEST['report'] : '0';
	$assessment = isset($_REQUEST['assessment']) ? $_REQUEST['assessment'] : '0';
	$tag_type = isset($_REQUEST['tag_type']) ? $_REQUEST['tag_type'] : '0';		
        $expedition = isset($_REQUEST['expedition']) ? $_REQUEST['expedition'] : '0';
        
        
        if($sessmasterprfid == 2)
        {
            if($tag_type==7 OR $tag_type==8 OR $tag_type==9)
            {
               $sqry="AND fld_district_id=".$_SESSION['inddistid']." AND fld_school_id=".$_SESSION['indschoolid'].""; 
            }
            else
            {
                $sqry='';
            }
        }
         
	$response = array();
			
	if($oper=='search'){		
		if($sessmasterprfid == 2)
			$qry = $ObjDB->QueryObject("SELECT '2' as type,a.fld_id AS id, a.fld_tag_name AS name,(CASE 
WHEN b.fld_tag_type = 1 THEN (SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 2 THEN (SELECT fld_activity_name FROM itc_activity_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 3 THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 4 THEN (SELECT fld_unit_name FROM itc_unit_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type BETWEEN 7 AND 13 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=b.fld_item_id $sqry AND fld_delstatus='0') 
WHEN (b.fld_tag_type = 14 OR b.fld_tag_type=17) THEN (SELECT fld_school_name FROM itc_school_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 15 THEN (SELECT fld_district_name FROM itc_district_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 16 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 18 THEN (SELECT fld_license_name FROM itc_license_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 19 THEN (SELECT fld_question FROM itc_question_details WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 20 THEN (SELECT fld_test_name FROM itc_test_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 21 THEN (SELECT fld_class_name FROM itc_class_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 22 THEN (SELECT (SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id=f.fld_lesson_id) FROM itc_diag_question_mapping AS f WHERE f.fld_id=b.fld_item_id AND f.fld_delstatus='0')
WHEN b.fld_tag_type = 23 THEN (SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 24 THEN (SELECT fld_project_name FROM itc_reports_contentmanagement_sns WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 26 THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 27 THEN (SELECT fld_materials FROM itc_materials_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 28 THEN (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 29 THEN (SELECT fld_course_name FROM itc_course_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 30 THEN (SELECT fld_pd_name FROM itc_pd_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 31 THEN (SELECT fld_dest_name FROM itc_exp_destination_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 32 THEN (SELECT fld_task_name FROM itc_exp_task_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 33 THEN (SELECT fld_res_name FROM	itc_exp_resource_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 35 THEN (SELECT fld_unit_name FROM itc_sosunit_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 34 THEN (SELECT fld_materials FROM itc_mis_materials_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 36 THEN (SELECT fld_phase_name FROM itc_sosphase_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 37 THEN (SELECT fld_video_name FROM itc_sosvideo_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 38 THEN (SELECT fld_mis_name FROM itc_mission_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 40 THEN (SELECT fld_document_name FROM itc_sosdocument_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
END) AS itemname FROM itc_main_tag_master AS a LEFT JOIN itc_main_tag_mapping AS b ON a.fld_id=b.fld_tag_id LEFT JOIN itc_user_master AS w ON a.fld_created_by=w.fld_id  WHERE b.fld_tag_type='".$tag_type."' AND b.fld_access='1' AND a.fld_delstatus='0' AND w.fld_profile_id='2' GROUP BY a.fld_id");	

		else
			$qry = $ObjDB->QueryObject("SELECT '3' as type,a.fld_profile_id as profileid,a.fld_tag_type as tagtype,a.fld_created_by as createdid,a.fld_id AS id, a.fld_tag_name AS name,(CASE 
WHEN b.fld_tag_type = 1 THEN (SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 2 THEN (SELECT fld_activity_name FROM itc_activity_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 3 THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 4 THEN (SELECT fld_unit_name FROM itc_unit_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type BETWEEN 7 AND 13 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN (b.fld_tag_type = 14 OR b.fld_tag_type=17) THEN (SELECT fld_school_name FROM itc_school_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 15 THEN (SELECT fld_district_name FROM itc_district_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 16 THEN (SELECT CONCAT(fld_fname,' ',fld_lname) FROM itc_user_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 18 THEN (SELECT fld_license_name FROM itc_license_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 19 THEN (SELECT fld_question FROM itc_question_details WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 20 THEN (SELECT fld_test_name FROM itc_test_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 21 THEN (SELECT fld_class_name FROM itc_class_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 22 THEN (SELECT (SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id=f.fld_lesson_id) FROM itc_diag_question_mapping AS f WHERE f.fld_id=b.fld_item_id AND f.fld_delstatus='0')
WHEN b.fld_tag_type = 23 THEN (SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 24 THEN (SELECT fld_project_name FROM itc_reports_contentmanagement_sns WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 26 THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0') 
WHEN b.fld_tag_type = 27 THEN (SELECT fld_materials FROM itc_materials_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 28 THEN (SELECT fld_exp_name FROM itc_exp_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 29 THEN (SELECT fld_course_name FROM itc_course_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 30 THEN (SELECT fld_pd_name FROM itc_pd_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 34 THEN (SELECT fld_materials FROM itc_mis_materials_master WHERE fld_id=b.fld_item_id AND fld_delstatus='0')
WHEN b.fld_tag_type = 35 THEN (SELECT fld_unit_name FROM itc_sosunit_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 36 THEN (SELECT fld_phase_name FROM itc_sosphase_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 37 THEN (SELECT fld_video_name FROM itc_sosvideo_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
WHEN b.fld_tag_type = 38 THEN (SELECT fld_mis_name FROM itc_mission_master WHERE fld_id = b.fld_item_id AND fld_delstatus = '0')
END) AS itemname FROM itc_main_tag_master AS a LEFT JOIN itc_main_tag_mapping AS b ON a.fld_id=b.fld_tag_id WHERE b.fld_tag_type='".$tag_type."' AND b.fld_access='1' AND a.fld_delstatus='0' AND a.fld_created_by='".$uid."' GROUP BY a.fld_id");	
		while($res = $qry->fetch_assoc())
		{
			extract($res);
			if($itemname!='' && $itemname!='null' && $type==3){
                            
                                if($profileid==2 AND $tagtype==1)
                                {
				$id = $id;		
				$htmlcode = "".$name."<span>Tag</span>";
				$response[] = array($id, $name, null, $htmlcode);
			}
                                else if($createdid==$uid AND $profileid!=2)
                                {
                                    $id = $id;		
                                    $htmlcode = "".$name."<span>Tag</span>";
                                    $response[] = array($id, $name, null, $htmlcode);
		}				
			}
		
                        if($type==2)
                        {
                                    $id = $id;		
                                    $htmlcode = "".$name."<span>Tag</span>";
                                    $response[] = array($id, $name, null, $htmlcode);
                            
                        }
		}				
		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3)  //Admin level users
		{	
			$qry = $ObjDB->QueryObject("SELECT 'null' AS lessonid, fld_id AS unitid, 'null' AS lessonname, fld_unit_name AS unitname 
										FROM itc_unit_master WHERE fld_delstatus='0' 
										UNION 
										SELECT fld_id AS lessonid, 'null' AS unitid, fld_ipl_name AS lessonname,'null' AS unitname 
										FROM itc_ipl_master WHERE fld_delstatus='0' AND fld_access='1'");	
		}
		else if($sessmasterprfid == 10){	//Student level users
			if($trialuser==1){
				// for trial user student
				$qry = $ObjDB->QueryObject(" SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, fn_shortname(b.fld_ipl_name,1) AS shortname, b.fld_ipl_icon AS lessonicon, 
												d.fld_id AS unitid, d.fld_unit_name AS unitname  
											FROM itc_license_cul_mapping AS a LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id RIGHT JOIN itc_ipl_master AS b 
												ON a.fld_lesson_id=b.fld_id LEFT JOIN itc_unit_master AS d ON d.fld_id=b.fld_unit_id 
											WHERE c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
												AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND a.fld_active='1' AND b.fld_delstatus='0' AND d.fld_delstatus='0' 
											GROUP BY b.fld_id");
			}
			else{
				// Lesson listed based on the class assigned for the student and availability of the license time period
				$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, fn_shortname(b.fld_ipl_name,1) AS shortname, b.fld_ipl_icon AS lessonicon , 
												f.fld_id AS unitid, f.fld_unit_name AS unitname 
											FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_lesson_mapping AS c ON a.fld_sigmath_id=c.fld_sigmath_id 
												LEFT JOIN itc_ipl_master AS b ON b.fld_id=c.fld_lesson_id LEFT JOIN itc_class_sigmath_master AS d ON d.fld_id=c.fld_sigmath_id 
												LEFT JOIN itc_class_master AS e ON e.fld_id=d.fld_class_id LEFT JOIN itc_unit_master AS f ON f.fld_id=b.fld_unit_id 
											WHERE a.fld_student_id='".$uid."' AND a.fld_flag='1' AND c.fld_flag='1' AND b.fld_delstatus='0' AND b.fld_access='1' AND d.fld_delstatus='0' 
												AND e.fld_delstatus='0' AND f.fld_delstatus='0' AND c.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
												WHERE '".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') 
											GROUP BY b.fld_id");
			}
		}
		else     //other than student and admin level users
		{				
			$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, fn_shortname(b.fld_ipl_name,1) AS shortname, b.fld_ipl_icon AS lessonicon, 
											d.fld_id AS unitid, d.fld_unit_name AS unitname  FROM itc_license_cul_mapping AS a LEFT JOIN itc_license_track AS c 
											ON a.fld_license_id = c.fld_license_id RIGHT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id LEFT JOIN itc_unit_master AS d 
											ON d.fld_id=b.fld_unit_id 
										WHERE c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
											AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND a.fld_active='1' AND b.fld_delstatus='0' AND d.fld_delstatus='0' 
										GROUP BY b.fld_id");
			if($tag_type==19){
				$publictag = $ObjDB->QueryObject("SELECT fld_id, fld_tag_name FROM itc_main_tag_master WHERE fld_tag_type='1' AND fld_delstatus='0' AND fld_id<>61");
				if($publictag->num_rows>0){
					$ObjDB->NonQuery("SET SESSION group_concat_max_len = 1000000");
					$getquestions = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(h.fld_question_id) FROM itc_license_assessment_mapping AS e 
																LEFT JOIN itc_license_track AS g ON e.fld_license_id = g.fld_license_id 
																RIGHT JOIN itc_test_master AS f ON e.fld_assessment_id=f.fld_id 
																RIGHT JOIN itc_test_questionassign AS h ON h.fld_test_id=f.fld_id 
																WHERE g.fld_district_id='".$districtid."' AND g.fld_school_id='".$schoolid."' 
																AND g.fld_user_id='".$indid."' AND g.fld_delstatus='0' AND '".date("Y-m-d")."' 
																BETWEEN g.fld_start_date AND g.fld_end_date AND e.fld_access='1' AND f.fld_delstatus='0' 
																AND h.fld_delstatus='0'");
					$allquestions = explode(',',$getquestions);
					while($respublictag = $publictag->fetch_assoc()){
						extract($respublictag);
						$ObjDB->NonQuery("SET SESSION group_concat_max_len = 1000000");
						$pitstcoquestions = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(DISTINCT(a.fld_item_id)) FROM itc_main_tag_mapping AS a 
														   LEFT JOIN itc_question_details AS b ON a.fld_item_id=b.fld_id 
														   LEFT JOIN itc_user_master AS c ON b.fld_created_by = c.fld_profile_id 
														   WHERE a.fld_tag_id='".$fld_id."' AND a.fld_access='1' AND a.fld_tag_type='19' AND c.fld_profile_id ='2'");
						$pitstcoquestions = explode(',',$pitstcoquestions);	
						$result = array_intersect($allquestions, $pitstcoquestions);
						$empty = array_filter($result);
						if (!empty($empty)){
							$htmlcode = "".$fld_tag_name."<span>Public tag</span>";
							$response[] = array($fld_id, $fld_tag_name, null, $htmlcode);			
						}
					}
				}
			}					
		}	
		//push unit,lesson into array		
		$unitarray = array();
		$lessonarray = array();	
		if($qry->num_rows>0){
			while($res=$qry->fetch_assoc()){
				extract($res);				
				if($unitid!='' && $unitname!='null')
				{
					if($unit==1){
						if(in_array($unitid,$unitarray)){
						}
						else{
							$id = $unitid."~unit";
							$htmlcode = "".$unitname."<span>Unit</span>";
							$response[] = array($id, $htmlcode, $unitname, $htmlcode);
							$unitarray[]=$unitid;
						}
					}
				}
				
				if($lessonid!='' && $lessonname!='null')
				{
					if($lesson==1){
						if(in_array($lessonid,$lessonarray)){
						}
						else{
							$id = $lessonid."~lesson";
							$htmlcode = "".$lessonname."<span>Lesson</span>";
							$response[] = array($id, $htmlcode, $lessonname, $htmlcode);
							$lessonarray[]=$lessonid;
						}
					}
				}			
			}
		}
	}
        
        elseif($oper=='searchsosunits')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_unit_name AS shlname,fld_id AS id
									FROM itc_sosunit_master 
									WHERE fld_delstatus='0'");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id."~unit";	
				$htmlcode = "".$shlname."<span>SOS Unit</span>";
				$response[] = array($id, $shlname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchsosphase')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_phase_name AS shlname,fld_id AS id
									FROM itc_sosphase_master 
									WHERE fld_delstatus='0'");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id."_phase";	
				$htmlcode = "".$shlname."<span>SOS Phase</span>";
				$response[] = array($id, $shlname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchsosvideos')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_video_name AS shlname,fld_id AS id
									FROM itc_sosvideo_master 
									WHERE fld_delstatus='0'");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id."_video";		
				$htmlcode = "".$shlname."<span>SOS Videos</span>";
				$response[] = array($id, $shlname, null, $htmlcode);
			
		}	
		
	}
        
        
        
        
       //sort/filter the student list other than the â€œtagâ€? feature
	elseif($oper=='searchstudent')
	{	
            if($sessmasterprfid == 2 || $sessmasterprfid == 3)  //Admin level users
            {	
		$qry = $ObjDB->QueryObject("SELECT CONCAT(fld_fname,' ',fld_lname) As name,fld_id AS id
                                            FROM itc_user_master WHERE fld_profile_id= '10' AND fld_delstatus='0' $sqry GROUP BY id order by name");
            }
            else{
                $qry = $ObjDB->QueryObject("SELECT CONCAT(fld_fname,' ',fld_lname) As name,fld_id AS id
                                            FROM itc_user_master WHERE fld_profile_id= '10' AND fld_school_id='".$schoolid."' AND fld_district_id='".$sendistid."' AND fld_delstatus='0' GROUP BY id order by name");
            }
		
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$name."<span>Student</span>";
				$response[] = array($id, $name, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchschoolpurchasename')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_school_name AS shlname,fld_id AS id
									FROM itc_school_master 
									WHERE fld_delstatus='0' and fld_district_id=0");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);

				$id = $id;		
				$htmlcode = "".$shlname."<span>School Purchase</span>";
				$response[] = array($id, $shlname, null, $htmlcode);
    
		}	
		
	}
        elseif($oper=='searchhomename')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS indvname
												FROM itc_user_master 
												WHERE fld_profile_id='5' AND fld_delstatus='0'");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$indvname."<span>Home Purchase</span>";
				$response[] = array($id, $indvname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchdistname')
	{	
		$qry = $ObjDB->QueryObject("SELECT a.fld_district_name AS districtname, a.fld_id AS distid
										FROM itc_district_master AS a, `itc_user_master` AS b 
										WHERE a.fld_district_admin_id=b.fld_id AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $distid;		
				$htmlcode = "".$districtname."<span>District</span>";
				$response[] = array($id, $districtname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchschoolname')
	{	
		
                        $qry = $ObjDB->QueryObject("SELECT fld_school_name AS shlname,fld_id AS shlid  
										FROM itc_school_master 
										WHERE fld_delstatus='0' AND fld_district_id !=0");
		
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $shlid;		
				$htmlcode = "".$shlname."<span>School</span>";
				$response[] = array($id, $shlname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchlicensename')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS licenseid, fld_license_name AS licensename
											FROM itc_license_master 
											WHERE fld_delstatus='0'
											GROUP BY fld_id 
											ORDER BY fld_license_name");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $licenseid;		
				$htmlcode = "".$licensename."<span>License</span>";
				$response[] = array($id, $licensename, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchtestname')
	{	
		$qry = $ObjDB->QueryObject("SELECT a.fld_test_name AS testname, 
					               a.fld_id AS testid FROM itc_test_master AS a 
								   LEFT JOIN itc_user_master AS b ON a.fld_created_by = b.fld_id WHERE b.fld_profile_id ='2' 
								   AND a.fld_delstatus='0' GROUP BY a.fld_id  
								   ORDER BY a.fld_created_by, testname");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $testid;		
				$htmlcode = "".$testname."<span>Test</span>";
				$response[] = array($id, $testname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchteachername')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname
											FROM itc_user_master WHERE fld_profile_id= '9' $sqry AND fld_delstatus='0'
											ORDER BY fullname ASC");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$fullname."<span>Teacher</span>";
				$response[] = array($id, $fullname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchteacheradminname')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname FROM itc_user_master WHERE fld_profile_id= '8' $sqry AND fld_delstatus='0'
												ORDER BY fullname ASC");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$fullname."<span>Teacher Admin</span>";
				$response[] = array($id, $fullname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchschooladminname')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname
										FROM itc_user_master WHERE fld_profile_id= '7' AND fld_delstatus='0'
										ORDER BY fullname ASC");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$fullname."<span>School Admin</span>";
				$response[] = array($id, $fullname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchdistadminname')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname 
											FROM itc_user_master 
											WHERE fld_profile_id='6' AND fld_delstatus='0' 
											ORDER BY fullname ASC");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$fullname."<span>District Admin</span>";
				$response[] = array($id, $fullname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchcontentadmin')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname 
											FROM itc_user_master 
											WHERE fld_profile_id='3' AND fld_delstatus='0' 
											ORDER BY fullname ASC");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
			
				$id = $id;		
				$htmlcode = "".$fullname."<span>Content Admin</span>";
				$response[] = array($id, $fullname, null, $htmlcode);
			
		}	
		
	}
        elseif($oper=='searchpitscoadmin')
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_id AS id, CONCAT(fld_fname,' ',fld_lname) AS fullname 
											FROM itc_user_master 
											WHERE fld_profile_id='2' AND fld_delstatus='0' 
											ORDER BY fullname ASC");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);

				$id = $id;		
				$htmlcode = "".$fullname."<span>Content Admin</span>";
				$response[] = array($id, $fullname, null, $htmlcode);
			
		}	
		
	}

//sort/filter the student list other than the â€œtagâ€? feature

        
    elseif ($oper=='searchproduct') {
            /* auto search by tag for all type of products.
             * Created by : vijayalakshmi PHP Programmer             *
             * Here $response[1] is used to give the tag name in textbox list()
             * AND $response[3] is used to list the tag names reg. tag type.
             */
            $qry = $ObjDB->QueryObject("SELECT a.fld_id AS id, a.fld_tag_name AS name FROM itc_main_tag_master  as a
                                            LEFT JOIN itc_main_tag_mapping as b on a.fld_id = b.fld_tag_id
                                            WHERE  a.fld_delstatus='0' AND (b.fld_tag_type='1' OR b.fld_tag_type='23' OR b.fld_tag_type='3' OR b.fld_tag_type='4')
                                            AND b.fld_access='1' group by a.fld_id");
            while($res = $qry->fetch_assoc())
            {
                extract($res);
                $id = $id;
                $name=$name;
                $htmlcode = "".$name."<span>Tag</span>";
                $response[] = array($id, $name, null, $htmlcode);
            }
    }
	else{
		
		$qry = $ObjDB->QueryObject("SELECT fld_profile_id as profileid,fld_created_by as createdid,fld_tag_type as tagtype,fld_id AS id, fld_tag_name AS name FROM itc_main_tag_master WHERE fld_delstatus='0' AND (fld_created_by='".$uid."' or fld_profile_id='2')");	
                
		while($res = $qry->fetch_assoc())
		{
			extract($res);		
                        if($sessmasterprfid!=2)
                        {
                            if($profileid==2 AND $tagtype==1)
                            {
			$htmlcode = "".$name."<span>Tag</span>";
			$response[] = array($id, $name, null, $htmlcode);
		}
                            else if($createdid==$uid AND $profileid!=2)
                            {
                                $htmlcode = "".$name."<span>Tag</span>";
                                $response[] = array($id, $name, null, $htmlcode);
                            }
                        }
                        else 
                        {
                            $htmlcode = "".$name."<span>Tag</span>";
                            $response[] = array($id, $name, null, $htmlcode);
                        }
		}
		if($assessment==1){
			$htmlcode = "MAEP<span>MAEP Tag</span>";
			$response[] = array('61', 'MAEP', null, $htmlcode);
		}
		
		//for lesson name and unit name in test question creation
		if($assessment==1){
			$unit=1;
			$lesson=1;
			if($sessmasterprfid == 2 || $sessmasterprfid == 3)  //Admin level users
			{	
				$qry = $ObjDB->QueryObject("SELECT 'null' AS lessonid, fld_id AS unitid, 'null' AS lessonname, fld_unit_name AS unitname 
											FROM itc_unit_master WHERE fld_delstatus='0' 
											UNION 
											SELECT fld_id AS lessonid, 'null' AS unitid, fld_ipl_name AS lessonname,'null' AS unitname 
											FROM itc_ipl_master WHERE fld_delstatus='0' AND fld_access='1'");	
			}
			else     //other than student and admin level users
			{				
				$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, fn_shortname(b.fld_ipl_name,1) AS shortname, b.fld_ipl_icon AS lessonicon, 
												d.fld_id AS unitid, d.fld_unit_name AS unitname  
											FROM itc_license_cul_mapping AS a LEFT JOIN itc_license_track AS c ON a.fld_license_id = c.fld_license_id RIGHT JOIN itc_ipl_master AS b 
												ON a.fld_lesson_id=b.fld_id LEFT JOIN itc_unit_master AS d ON d.fld_id=b.fld_unit_id 
											WHERE c.fld_district_id='".$districtid."' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' 
												AND '".date("Y-m-d")."' BETWEEN c.fld_start_date AND c.fld_end_date AND a.fld_active='1' AND b.fld_delstatus='0' AND d.fld_delstatus='0' 
											GROUP BY b.fld_id");					
			}	
			//push unit,lesson into array		
			$unitarray = array();
			$lessonarray = array();	
			if($qry->num_rows>0){				
				while($res=$qry->fetch_assoc()){
					extract($res);
					if($unitid!='' && $unitname!='null')
					{
						if($unit==1){
							if(in_array($unitid,$unitarray)){
							}
							else{
								$id = $unitid."_unit";
								$htmlcode = "".$unitname."<span>Unit</span>";
								$response[] = array($id, $htmlcode, $unitname, $htmlcode);
								$unitarray[]=$unitid;
							}
						}
					}
					
					if($lessonid!='' && $lessonname!='null')
					{
						if($lesson==1){
							if(in_array($lessonid,$lessonarray)){
							}
							else{
								$id = $lessonid."~lesson";
								$htmlcode = "".$lessonname."<span>Lesson</span>";
								$response[] = array($id, $htmlcode, $lessonname, $htmlcode);
								$lessonarray[]=$lessonid;
							}
						}
					}			
				}
			}
		}
	}
	
	if($diagtag==1){
		$response[] = array("1_diagnostic", "Diagnostic Test");
		$response[] = array("2_mastery1", "Mastery Test1");
		$response[] = array("3_mastery2", "Mastery Test2");
	}
	if($testquestion==1){		
		$response[] = array("4_testengine", "Assessment questions");
		$htmlcode = "MAEP<span>MAEP Tag</span>";
		$response[] = array('61', 'MAEP', null, $htmlcode);
	}
	
	//if Assessment that is tag_type=20
        if($tag_type==40)
	{	
		$qry = $ObjDB->QueryObject("SELECT fld_document_name AS shlname,fld_id AS id
									FROM itc_sosdocument_master 
									WHERE fld_delstatus='0'");
		while($resstu = $qry->fetch_assoc())
		{
			extract($resstu);
	
				$id = $id."_document";		
				$htmlcode = "".$shlname."<span>SOS </span>";
				$response[] = array($id, $shlname, null, $htmlcode);
			
		}	
		
	}
	
	if($tag_type==20 && $test==1){
		if($sessmasterprfid == 2 or $sessmasterprfid == 3){ 
                	$qry = "SELECT a.fld_test_name AS testname, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_created_by AS createbyid, a.fld_id AS testid, a.fld_step_id AS stepid, 
								a.fld_flag AS flag 
							FROM itc_test_master AS a 
							WHERE a.fld_created_by ='".$uid."' and a.fld_delstatus='0'";
				}
				else if($sessmasterprfid == 5){
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id LEFT JOIN `itc_license_track` AS c 
								ON b.fld_license_id=c.fld_license_id 
							WHERE c.fld_user_id='".$indid."' and b.fld_access='1' 
							UNION 
							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0'";
				}
				else if($sessmasterprfid == 6){
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id LEFT JOIN `itc_license_track` AS c 
								ON b.fld_license_id=c.fld_license_id 
							WHERE c.fld_district_id='".$sendistid."' and b.fld_access='1' 
							UNION 
							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0'";
				}
				else if($sessmasterprfid == 7 and $sendistid !='0'){
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a LEFT JOIN `itc_test_school_mapping` AS b ON a.fld_id=b.fld_test_id 
							WHERE  b.fld_school_id='".$senshlid."' AND b.fld_flag='1' 
							UNION 
							SELECT DISTINCT(a.fld_id) AS testid,a.fld_test_name AS testname,fn_shortname(a.fld_test_name,1) AS shortname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM itc_test_master AS a,itc_license_assessment_mapping AS b, itc_license_track AS c 
							WHERE b.fld_license_id=c.fld_license_id AND a.fld_id=b.`fld_assessment_id` AND c.fld_school_id='".$senshlid."' AND b.`fld_access`='1' 
							UNION 
							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0'";
				}
				else if($sessmasterprfid == 7 and $sendistid =='0'){
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
								LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id 
							WHERE c.fld_school_id='".$senshlid."' and b.fld_access='1' 
							UNION 
							SELECT DISTINCT(a.fld_id) AS testid,a.fld_test_name AS testname,fn_shortname(a.fld_test_name,1) AS shortname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM itc_test_master AS a,itc_license_assessment_mapping AS b, itc_license_track AS c 
							WHERE b.fld_license_id=c.fld_license_id AND a.fld_id=b.`fld_assessment_id` AND c.fld_school_id='".$senshlid."' AND b.`fld_access`='1' 
							UNION 
							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0'";
				}
				else if($sessmasterprfid == 9 and $sendistid =='0' and $senshlid =='0'){
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a LEFT JOIN `itc_license_assessment_mapping`AS b ON a.fld_id=b.fld_assessment_id 
								LEFT JOIN `itc_license_track` AS c ON b.fld_license_id=c.fld_license_id 
							WHERE c.fld_user_id='".$indid."' and b.fld_access='1' 
							UNION 
							SELECT a.fld_id AS testid,fn_shortname(a.fld_test_name,1) AS shortname,a.fld_test_name AS testname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid,a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							WHERE a.fld_created_by='".$uid."' and a.fld_delstatus='0'";
				}
				else {
					$qry = "SELECT DISTINCT(a.fld_id) AS testid, a.fld_test_name AS testname, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM `itc_test_master` AS a LEFT JOIN `itc_test_school_mapping` AS b ON a.fld_id=b.fld_test_id 
							WHERE  b.fld_school_id='".$senshlid."' AND b.fld_flag='1' 
							UNION 
							SELECT DISTINCT(a.fld_id) AS testid,a.fld_test_name AS testname,fn_shortname(a.fld_test_name,1) AS shortname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid ,a.fld_flag AS flag 
							FROM itc_test_master AS a,itc_license_assessment_mapping AS b, itc_license_track AS c 
							WHERE b.fld_license_id=c.fld_license_id AND a.fld_id=b.`fld_assessment_id` AND c.fld_school_id='".$senshlid."' AND b.`fld_access`='1' 
							UNION 
							SELECT a.fld_id AS testid, a.fld_test_name AS testname, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_created_by AS createbyid,
								a.fld_step_id AS stepid, a.fld_flag AS flag 
							FROM `itc_test_master` AS a 
							WHERE (a.fld_created_by='".$uid."' or a.fld_school_id='".$senshlid."') AND a.fld_delstatus='0'";
				}
		
		$qrytest = $ObjDB->QueryObject($qry);
		if($qrytest->num_rows>0){
			while($rowtest = $qrytest->fetch_assoc())
			{
				extract($rowtest);		
				$response[] = array($testid."_test", $testname);
			}
		}
	}	
	
	//for class name
	if($tag_type==21 && $class==1){		
		$qryclass = "SELECT fld_class_name AS classname, fn_shortname(fld_class_name,1) AS shortname, fld_id AS classid, fld_lab AS classtypeid, fld_step_id AS stepid, fld_flag AS flag 
					 FROM itc_class_master 
					 WHERE fld_delstatus='0' AND (fld_school_id='".$schoolid."' AND fld_user_id='".$indid."') AND (fld_created_by='".$uid."' 
					 	OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' AND fld_flag='1'))";
		
		$qrytest = $ObjDB->QueryObject($qryclass);
		if($qrytest->num_rows>0){
			while($rowtest = $qrytest->fetch_assoc())
			{
				extract($rowtest);		
				$response[] = array($classid."_class", $classname);
			}
		}
	}
	
	//for module name
	if($tag_type==3 && $module==1){		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
			$qrymod = $ObjDB->QueryObject("SELECT a.fld_module_name AS modulename, fn_shortname(a.fld_module_name,1) AS shortname, a.fld_id AS moduleid 
										  FROM itc_module_master AS a 
										  WHERE a.fld_delstatus='0' AND fld_module_type<>7");
		}
		else{
			if($sessmasterprfid==6){ //For District Admin
				$qrymod = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, a.fld_module_name as modulename, fn_shortname(a.fld_module_name,1) AS shortname 
											  FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND b.fld_active='1' 
											  	AND b.fld_type='1' AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_module_id");
			}
			else{ //For Remaining users
				$qrymod = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, a.fld_module_name as modulename, fn_shortname(a.fld_module_name,1) AS shortname 
											  FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' 
											  	AND b.fld_type='1' AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_module_id");
			}
		}		
		if($qrymod->num_rows>0){
			while($rowtest = $qrymod->fetch_assoc())
			{
				extract($rowtest);		
				$response[] = array($moduleid."_module", $modulename);
			}
		}
	}	
	
	//for quest name
	if($tag_type==26 && $quest==1){		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
			$qrymod = $ObjDB->QueryObject("SELECT a.fld_module_name AS modulename, fn_shortname(a.fld_module_name,1) AS shortname, a.fld_id AS moduleid 
										  FROM itc_module_master AS a 
										  WHERE a.fld_delstatus='0' AND fld_module_type=7");
		}
		else{
			if($sessmasterprfid==6){ //For District Admin
				$qrymod = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, a.fld_module_name as modulename, fn_shortname(a.fld_module_name,1) AS shortname 
											  FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND b.fld_active='1' 
											  	AND b.fld_type='7' AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_module_id");
			}
			else{ //For Remaining users
				$qrymod = $ObjDB->QueryObject("SELECT b.fld_module_id as moduleid, a.fld_module_name as modulename, fn_shortname(a.fld_module_name,1) AS shortname 
											  FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' 
											  	AND b.fld_type='7' AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_module_id");
			}
		}		
		if($qrymod->num_rows>0){
			while($rowtest = $qrymod->fetch_assoc())
			{
				extract($rowtest);		
				$response[] = array($moduleid."_quest", $modulename);
			}
		}
	}	
        //for material name within expedition
	if($tag_type==27 && $material==1){
	
            $qrymaterial = "SELECT fld_id AS materialid, fld_materials AS materialname, fn_shortname(fld_materials,1) AS shortname  FROM itc_materials_master WHERE fld_sessprofile_id='".$sessmasterprfid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'";
            $qrymat = $ObjDB->QueryObject($qrymaterial);
		if($qrymat->num_rows>0){
			while($rowmat = $qrymat->fetch_assoc())
			{
				extract($rowmat);		
				$response[] = array($materialid."_material", $materialname);
                               
			}
		}
	}
        
        //for missionmaterial name within expedition
	if($tag_type==34 && $missionmaterial==1){
	
            $qrymaterial = "SELECT fld_id AS materialid, fld_materials AS materialname, fn_shortname(fld_materials,1) AS shortname  FROM itc_mis_materials_master WHERE fld_sessprofile_id='".$sessmasterprfid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'";
            $qrymat = $ObjDB->QueryObject($qrymaterial);
		if($qrymat->num_rows>0){
			while($rowmat = $qrymat->fetch_assoc())
			{
				extract($rowmat);		
				$response[] = array($materialid."_missionmaterial", $materialname);
                               
			}
		}
	}
        
	
	//for math module name
	if($tag_type==23 && $mathmodule==1){		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
			$qrymod = $ObjDB->QueryObject("SELECT a.fld_mathmodule_name AS mathmodulename, fn_shortname(a.fld_mathmodule_name,1) AS shortname, a.fld_id AS mathmoduleid 
											FROM itc_mathmodule_master AS a 
											WHERE a.fld_delstatus='0' ");
		}
		else{
			if($sessmasterprfid==6){ //For District Admin
				$qrymod = $ObjDB->QueryObject("SELECT b.fld_module_id as mathmoduleid, a.fld_mathmodule_name as mathmodulename, fn_shortname(a.fld_mathmodule_name,1) AS shortname 
											  FROM itc_mathmodule_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND b.fld_active='1' 
											  	AND b.fld_type='2' AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' ".$sqry." 
											  GROUP BY b.fld_module_id");

			}
			else{ //For Remaining users
				$qrymod = $ObjDB->QueryObject("SELECT b.fld_module_id as mathmoduleid, a.fld_mathmodule_name as mathmodulename, fn_shortname(a.fld_mathmodule_name,1) AS shortname 
											  FROM itc_mathmodule_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0' AND b.fld_active='1' 
											  	AND b.fld_type='2' AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' ".$sqry." 
											  GROUP BY b.fld_module_id");
			}
		}		
		if($qrymod->num_rows>0){
			while($rowtest = $qrymod->fetch_assoc())
			{
				extract($rowtest);		
				$response[] = array($mathmoduleid."_mathmodule", $mathmodulename);
			}
		}
	}	
	
        
        //For Expedition name
	if($tag_type==28 && $expedition==1){		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
			$qryexp = $ObjDB->QueryObject("SELECT a.fld_exp_name AS expname, fn_shortname(a.fld_exp_name,1) AS shortname, a.fld_id AS expid 
											FROM itc_exp_master AS a 
											WHERE a.fld_delstatus='0' ");
		}
		else{
			if($sessmasterprfid==6){ //For District Admin
				$qryexp = $ObjDB->QueryObject("SELECT b.fld_exp_id as expid, a.fld_exp_name as expname, fn_shortname(a.fld_exp_name,1) AS shortname 
											  FROM itc_exp_master AS a LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id=b.fld_exp_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
											  	AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_exp_id");

			}
			else{ //For Remaining users
				$qryexp = $ObjDB->QueryObject("SELECT b.fld_exp_id as expid, a.fld_exp_name as expname, fn_shortname(a.fld_exp_name,1) AS shortname 
											  FROM itc_exp_master AS a LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id=b.fld_exp_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0'  
											  	AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_exp_id");
			}
		}		
		if($qryexp->num_rows>0){
			while($rowexp = $qryexp->fetch_assoc())
			{
				extract($rowexp);		
				$response[] = array($expid."_expedition", $expname);
			}
		}
	}
        
        
///for Mission Created By Mohan M 8-2-2016        
if($tag_type==38 && $mission==1)
{		
    if($sessmasterprfid == 2 || $sessmasterprfid == 3)
    { //For Pitsco & Content Admin
            $qryexp = $ObjDB->QueryObject("SELECT a.fld_mis_name AS misname, fn_shortname(a.fld_mis_name,1) AS shortname, a.fld_id AS misid 
                                                                            FROM itc_mission_master AS a 
                                                                            WHERE a.fld_delstatus='0' ");
    }
    else
    {
            if($sessmasterprfid==6)
            { //For District Admin
                    $qryexp = $ObjDB->QueryObject("SELECT b.fld_mis_id as misid, a.fld_mis_name as misname, fn_shortname(a.fld_mis_name,1) AS shortname 
                                                                              FROM itc_mission_master AS a LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id=b.fld_mis_id 
                                                                                    LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
                                                                              WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
                                                                                    AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
                                                                              GROUP BY b.fld_mis_id");

            }
            else
            { //For Remaining users
                    $qryexp = $ObjDB->QueryObject("SELECT b.fld_mis_id as misid, a.fld_mis_name as misname, fn_shortname(a.fld_mis_name,1) AS shortname 
                                                                              FROM itc_mission_master AS a LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id=b.fld_mis_id 
                                                                                    LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
                                                                              WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0'  
                                                                                    AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
                                                                              GROUP BY b.fld_mis_id");
            }
    }		
    if($qryexp->num_rows>0)
    {
        while($rowexp = $qryexp->fetch_assoc())
        {
            extract($rowexp);		
            $response[] = array($misid."_mission", $misname);
        }
    }
}

///for Mission Created By Mohan M 8-2-2016    
        
        
        
        //For course name
	if($tag_type==29 && $course==1){		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
			$qrycourse = $ObjDB->QueryObject("SELECT a.fld_course_name AS coursename, fn_shortname(a.fld_course_name,1) AS shortname, a.fld_id AS courseid 
											FROM itc_course_master AS a 
											WHERE a.fld_delstatus='0' ");
		}
		else{
			if($sessmasterprfid==6){ //For District Admin
				$qrycourse = $ObjDB->QueryObject("SELECT b.fld_course_id as courseid, a.fld_course_name as coursename, fn_shortname(a.fld_course_name,1) AS shortname 
											  FROM itc_course_master AS a LEFT JOIN itc_license_course_mapping AS b ON a.fld_id=b.fld_course_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
											  	AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_course_id");

			}
			else{ //For Remaining users
				$qrycourse = $ObjDB->QueryObject("SELECT b.fld_course_id as courseid, a.fld_course_name as coursename, fn_shortname(a.fld_course_name,1) AS shortname 
											  FROM itc_course_master AS a LEFT JOIN itc_license_course_mapping AS b ON a.fld_id=b.fld_course_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0'  
											  	AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_course_id");
			}
		}		
		if($qrycourse->num_rows>0){
			while($rowcourse = $qrycourse->fetch_assoc())
			{
				extract($rowcourse);		
				$response[] = array($courseid."_course", $coursename);
			}
		}
	}
        
        //For pd name
	if($tag_type==30 && $pdlessons==1){		
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
			$qrypd = $ObjDB->QueryObject("SELECT a.fld_pd_name AS pdname, fn_shortname(a.fld_pd_name,1) AS shortname, a.fld_id AS pdid 
											FROM itc_pd_master AS a 
											WHERE a.fld_delstatus='0' ");
		}
		else{
			if($sessmasterprfid==6){ //For District Admin
				$qrypd = $ObjDB->QueryObject("SELECT b.fld_pd_id as pdid, a.fld_pd_name as pdname, fn_shortname(a.fld_pd_name,1) AS shortname 
											  FROM itc_pd_master AS a LEFT JOIN itc_license_pd_mapping AS b ON a.fld_id=b.fld_pd_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
											  	AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_pd_id");

			}
			else{ //For Remaining users
				$qrypd = $ObjDB->QueryObject("SELECT b.fld_pd_id as pdid, a.fld_pd_name as pdname, fn_shortname(a.fld_pd_name,1) AS shortname 
											  FROM itc_pd_master AS a LEFT JOIN itc_license_pd_mapping AS b ON a.fld_id=b.fld_pd_id 
											  	LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
											  WHERE a.fld_delstatus='0' AND c.fld_school_id='".$schoolid."' AND c.fld_user_id='".$indid."' AND c.fld_delstatus='0'  
											  	AND c.fld_start_date<='".date("Y-m-d H:i:s")."' AND c.fld_end_date>='".date("Y-m-d H:i:s")."' 
											  GROUP BY b.fld_pd_id");
			}
		}		
		if($qrypd->num_rows>0){
			while($rowpd = $qrypd->fetch_assoc())
			{
				extract($rowpd);		
				$response[] = array($pdid."_pd", $pdname);
			}
		}
	}
	
        // For customcontent
        if($tag_type==25 && $customcontent==1){		
		
				$qrycc = $ObjDB->QueryObject("SELECT fld_id as id,fld_contentname as name FROM itc_customcontent_master where fld_createdby='".$uid."' AND fld_delstatus='0'");
			
		if($qrycc->num_rows>0){
			while($rowcc = $qrycc->fetch_assoc())
			{
				extract($rowcc);		
				$response[] = array($id."_customcontent", $name);
			}
		}
	}
        
	//for scope and sequence report name
	if($tag_type==24 && $report==1){		
		$qryreport = "SELECT fld_id AS rid, fld_project_name AS rname FROM itc_reports_contentmanagement_sns WHERE fld_delstatus='0'";
		
		$qrytest = $ObjDB->QueryObject($qryreport);
		if($qrytest->num_rows>0){
			while($rowtest = $qrytest->fetch_assoc())
			{
				extract($rowtest);		
				$response[] = array($rid."_report", $rname);
			}
		}
	}
	
	//for activity name
	if($tag_type==2 && $activity==1){
		if($sessmasterprfid == 2 || $sessmasterprfid == 3){				
			$qry = $ObjDB->QueryObject("SELECT c.fld_id AS activityid, c.fld_activity_name AS activityname FROM itc_activity_master AS c WHERE c.fld_delstatus='0' GROUP BY activityid");
		}
		else{				
			$qry = $ObjDB->QueryObject("SELECT c.fld_id AS activityid, c.fld_activity_name AS activityname, fn_shortname(c.fld_activity_name,1) AS shortname 
										FROM itc_license_cul_mapping AS a LEFT JOIN itc_license_track AS b ON a.fld_license_id = b.fld_license_id RIGHT JOIN itc_unit_master AS d 
											ON d.fld_id=a.fld_unit_id LEFT JOIN itc_activity_master AS c ON c.fld_unit_id=d.fld_id 
										WHERE b.fld_district_id='".$districtid."' AND b.fld_school_id='".$schoolid."' AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' 
											AND '".date("Y-m-d")."' BETWEEN b.fld_start_date AND b.fld_end_date AND a.fld_active='1' AND c.fld_delstatus='0' 
											AND (c.fld_created_by=2 OR c.fld_created_by='".$uid."') 
										GROUP BY activityid");
		}
		if($qry->num_rows>0){
			while($res=$qry->fetch_assoc()){
				extract($res);
				$response[] = array($activityid."_activity", $activityname);
			}
		}
	}
	
	@include("footer.php");
	
	header('Content-type: application/json');
	echo json_encode($response);