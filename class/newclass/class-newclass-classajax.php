<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Save/Update People for Class  ---*/
	if($oper == "maptoclass" and $oper != '')
	{	
		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$list1 = isset($method['list1']) ? $method['list1'] : '0';
		$list2 = isset($method['list2']) ? $method['list2'] : '0';
		$list3 = isset($method['list3']) ? $method['list3'] : '0';
		$list4 = isset($method['list4']) ? $method['list4'] : '0';
		
		$list1=explode(",",$list1);
		$list2=explode(",",$list2);
		$list3=explode(",",$list3);
		$list4=explode(",",$list4);
		
		// Teacher mapping start
		
		$ObjDB->NonQuery("UPDATE itc_class_teacher_mapping 
						 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
		
		for($i=0;$i<sizeof($list2);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_class_teacher_mapping 
												WHERE fld_class_id='".$classid."' AND fld_teacher_id='".$list2[$i]."'");
			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_teacher_mapping(fld_class_id, fld_teacher_id, fld_flag,fld_createddate,fld_createdby) 
																VALUES ('".$classid."', '".$list2[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_teacher_mapping 
								SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
								WHERE fld_class_id='".$classid."' AND fld_teacher_id='".$list2[$i]."' AND fld_id='".$cnt."'");
			}
		}
		
		// Student mapping start remove student
		for($i=0;$i<sizeof($list3);$i++)
		{
			$ObjDB->NonQuery("UPDATE itc_class_master 
								SET fld_updated_date='".date("Y-m-d H:i:s")."' 
								WHERE fld_id='".$classid."'");
											
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_class_student_mapping 
												WHERE fld_class_id='".$classid."' AND fld_student_id='".$list3[$i]."' AND fld_flag='1'");
			
			if($cnt>0){				
				//Get all schedules from the class which the student remove
				$qry_schedule = $ObjDB->QueryObject("SELECT a.fld_id AS sid, '1' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'													
													UNION 
													SELECT a.fld_id AS sid, '2' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
													ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'
													UNION 
													SELECT a.fld_id AS sid, '3' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'
													UNION 
													SELECT a.fld_id AS sid, '4' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'UNION
													SELECT a.fld_id AS sid, '5' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1' UNION
													SELECT a.fld_id AS sid, '6' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1' UNION
													SELECT a.fld_id AS sid, '7' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_pdschedule_master AS a LEFT JOIN itc_class_pdschedule_student_mapping AS b ON a.fld_id=b.fld_pdschedule_id
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1' UNION
													SELECT a.fld_id AS sid, '8' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_mission_schedule_master AS a LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'"
                                        . "                                                             UNION 
													SELECT a.fld_id AS sid, '9' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_rotation_expschedule_student_mappingtemp AS b 
													ON a.fld_id=b.fld_schedule_id
                                                                                                        UNION 
													SELECT a.fld_id AS sid, '10' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b 
													ON a.fld_id=b.fld_schedule_id
                                                                                                         UNION 
													SELECT a.fld_id AS sid, '11' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b 
													ON a.fld_id=b.fld_schedule_id");
				
				if($qry_schedule->num_rows>0){
					$licensearray = array();
					while($res_schedule=$qry_schedule->fetch_assoc()){
						extract($res_schedule);
						if($stype==1){
											 
							 $ObjDB->NonQuery("UPDATE itc_class_sigmath_student_mapping SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_sigmath_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
						}
						else if($stype==2){
							
							$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
											 
							$ObjDB->NonQuery("UPDATE itc_class_rotation_schedulegriddet 

												SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
												WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");


						}
						else if($stype==3){
							
							$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_studentmapping SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
							
							$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
											SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."'
											WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
						}
						else if($stype==4){
							
							$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_studentmapping SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
									
							$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
											 SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
											 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
						}												
						else if($stype==5){
							
							$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
									
						}
                                                else if($stype==6){
							
							$ObjDB->NonQuery("UPDATE itc_class_exp_student_mapping SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
									
						}
                                                else if($stype==7){
							
							$ObjDB->NonQuery("UPDATE itc_class_pdschedule_student_mapping SET fld_flag='0',fld_updated_date='".date('Y-m-d H:i:s')."',fld_updated_by='".$uid."' WHERE fld_pdschedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
									
						}
                                                else if($stype==8){
							
							$ObjDB->NonQuery("UPDATE itc_class_mission_student_mapping SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
									
						}
                                                else if($stype==9){
							
							$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
											 
							$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedulegriddet 

												SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
												WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");


					}
                                                else if($stype==10){
							
							$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedule_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
											 
							$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedulegriddet 

												SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
												WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");


				}
                                                
                                                else if($stype==11){
							
							$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
											 
							$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_schedulegriddet 

												SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' 
												WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");


			}			
					}
				}
			}			
			$ObjDB->NonQuery("UPDATE itc_class_student_mapping 
							 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
							 WHERE fld_class_id='".$classid."' AND fld_student_id='".$list3[$i]."' AND fld_id='".$cnt."'");
			
			
		}
		echo "success";
		
		//add students
		for($i=0;$i<sizeof($list4);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_class_student_mapping 
												WHERE fld_class_id='".$classid."' AND fld_student_id='".$list4[$i]."'");
			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_student_mapping(fld_class_id, fld_student_id, fld_flag,fld_createddate,fld_createdby) 
																VALUES ('".$classid."', '".$list4[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_student_mapping 
								 SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
								 WHERE fld_class_id='".$classid."' AND fld_student_id='".$list4[$i]."' AND fld_id='".$cnt."'");
			}
		}
		
		$ObjDB->NonQuery("UPDATE itc_class_master 
						 SET fld_step_id='3', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
						 WHERE fld_id='".$classid."'");
	}	
	
	if($oper == "saveclass" and $oper != '') 
	{
		try {
			$classid = (isset($method['classid'])) ? $method['classid'] : 0;
                        
                           $classname =  isset($method['classname']) ? $method['classname'] : '';
			//$classname =(isset( $method['classname'])) ? $ObjDB->EscapeStrAll($method['classname']) : '';
			$sdate1 =(isset( $method['sdate1'])) ?  $method['sdate1'] : '';
			$edate1 =(isset( $method['edate1'])) ?  $method['edate1'] : '';
			$period =(isset( $method['period'])) ?  $method['period'] : '';
			$term =(isset( $method['term'])) ?  $method['term'] : '';
			$shedule =(isset( $method['shedule'])) ?  $method['shedule'] : '';
			$lettergrade =(isset( $method['lettergrade'])) ?  $method['lettergrade'] : '';
			$lowerbound =(isset( $method['lowerbound'])) ?  $method['lowerbound'] : '';
			$higherbound =(isset( $method['higherbound'])) ?  $method['higherbound'] : '';
			$boxid =(isset( $method['boxid'])) ?  $method['boxid'] : '';
			$remove =(isset( $method['remove'])) ?  $method['remove'] : '';
			$grade =(isset( $method['grade'])) ?  $method['grade'] : '';
			$tags = isset($method['tags']) ? $ObjDB->EscapeStr($method['tags']) : '';
			
			$lg=explode("~",$lettergrade);
			$lb=explode("~",$lowerbound);
			$hb=explode("~",$higherbound);
			$bid=explode("~",$boxid);
			$rem=explode("~",$remove);			
			/**validation for the parameters and these below functions are validate to return true or false***/
			$validate_classid=true;
			$validate_classname=true;
			$validate_sdate1=true;
			$validate_edate1=true;
			$validate_period=true;
			$validate_shedule=true;			
			if($classid!=0) 
			$validate_classid=validate_datatype($classid,'int');
			//$validate_classname=validate_datas($classname,'lettersonly');
			$validate_sdate1=validate_datas($sdate1,'dateformat');
			$validate_edate1=validate_datas($edate1,'dateformat');
			$validate_period=validate_datatype($period,'int');
			$validate_shedule=validate_datatype($shedule,'int');	
				
                        $classname = $ObjDB->EscapeStrAll($classname);
			
                        $tempname = (isset($method['tempname'])) ? $method['tempname'] : 0;
                        $tempyes = (isset($method['tempyes'])) ? $method['tempyes'] : 0;
                        
			if($validate_classid  and $validate_sdate1 and $validate_edate1 and $validate_period and $validate_shedule){		//and $validate_classname		
				if($classid != 0)
				{
					$ObjDB->NonQuery("UPDATE itc_class_master 
									 SET fld_class_name='".$classname."',fld_start_date='".date('Y-m-d',strtotime($sdate1))."',
										 fld_end_date='".date('Y-m-d',strtotime($edate1))."',fld_period='".$period."',fld_term='".$term."', fld_shedule_type='".$shedule."', 
										 fld_updated_by='".$uid."',fld_updated_date='".date('Y-m-d H:i:s')."',fld_step_id='2' 
									 WHERE fld_id='".$classid."'");
					/*---tags------*/
					$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
									 SET fld_access='0' 
									 WHERE fld_tag_type='21' AND fld_item_id='".$classid."' AND 
									 fld_tag_id IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");			
					 fn_tagupdate($tags,21,$classid,$uid);			
				}
				else
				{
					$classid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_master(fld_class_name, fld_start_date, fld_end_date, fld_period, fld_term, fld_shedule_type
																	, fld_created_by, fld_created_date, fld_step_id, fld_district_id, fld_school_id, fld_user_id)
																VALUES('".$classname."','".date('Y-m-d',strtotime($sdate1))."','".date('Y-m-d',strtotime($edate1))."','".$period."',
																	'".$term."','".$shedule."', '".$uid."','".date('Y-m-d H:i:s')."','2','".$sendistid."','".$schoolid."','".$indid."')");
					/*--Tags insert-----*/	
					 fn_taginsert($tags,21,$classid,$uid);	
				}
				
                                if($tempyes == 1)
                                {
		
                                    $ObjDB->NonQuery("INSERT INTO itc_class_grade_template(fld_temp_name,fld_class_id, fld_created_by, fld_created_date, fld_district_id, fld_school_id)
																VALUES('".$tempname."','".$classid."','".$uid."','".date('Y-m-d H:i:s')."','".$sendistid."','".$schoolid."')");
                                    $tempid = $ObjDB->SelectSingleValueInt("SELECT max(fld_id) 
														FROM itc_class_grade_template 
														WHERE fld_class_id='".$classid."'");
                                }

					$ObjDB->NonQuery("UPDATE itc_class_grading_scale_mapping 
									SET fld_flag=0 
									WHERE fld_class_id='".$classid."' AND fld_flag=1");
				for($i=0;$i<count($lg)-1;$i++)
				{
					$count=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_class_grading_scale_mapping 
														WHERE fld_class_id='".$classid."' AND fld_boxid='".$bid[$i]."'");
					
					if($count == 0){	
						
						$ObjDB->NonQuery("INSERT INTO itc_class_grading_scale_mapping(fld_class_id, fld_boxid, fld_upper_bound, fld_lower_bound, fld_grade, fld_roundflag,fld_flag)
												VALUES('".$classid."','".$bid[$i]."','".$hb[$i]."','".$lb[$i]."','".$lg[$i]."','".$grade."','1')");
                                                if($tempyes == 1)
                                                {
                                                    $ObjDB->NonQuery("INSERT INTO itc_class_grading_scale_template_mapping(fld_temp_id,fld_class_id, fld_boxid, fld_upper_bound, fld_lower_bound, fld_grade, fld_roundflag,fld_flag,fld_createdby)
												VALUES('".$tempid."','".$classid."','".$bid[$i]."','".$hb[$i]."','".$lb[$i]."','".$lg[$i]."','".$grade."','1','".$uid."')");
                                                }                                        
						
						
					}
					else{
						$ObjDB->NonQuery("UPDATE itc_class_grading_scale_mapping 
										SET fld_upper_bound='".$hb[$i]."', fld_lower_bound='".$lb[$i]."', fld_grade='".$lg[$i]."', fld_roundflag='".$grade."',fld_flag=1 
										WHERE fld_class_id='".$classid."' AND fld_boxid='".$bid[$i]."'");
                                                if($tempyes == 1)
                                                {
                                                    $ObjDB->NonQuery("INSERT INTO itc_class_grading_scale_template_mapping(fld_temp_id,fld_class_id, fld_boxid, fld_upper_bound, fld_lower_bound, fld_grade, fld_roundflag,fld_flag,fld_createdby)
												VALUES('".$tempid."','".$classid."','".$bid[$i]."','".$hb[$i]."','".$lb[$i]."','".$lg[$i]."','".$grade."','1','".$uid."')");
					}
				}
				}
				
				echo "success~".$classid;
			}
			else{
				echo "fail";
			}
		}
		catch(Exception $e){
			echo "fail";
		}
	}	
	
	/*--- Save/Update a Class Final Step ---*/
	if($oper == "saveclassreview" and $oper != '')
	{		
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_class_master 
						 SET fld_step_id='1', fld_flag='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
						 WHERE fld_id='".$classid."' AND fld_delstatus='0'");
		
		echo "success~".$classid;
	}
	
	/*--- Check Class Name ---*/
	if($oper=="checkclassname" and $oper != " " )
	{
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$classname = isset($method['classname']) ?  fnEscapeCheck($method['classname']) : '';
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											  FROM itc_class_master 
											  WHERE MD5(LCASE(REPLACE(fld_class_name,' ','')))='".$classname."' AND fld_delstatus='0' AND fld_id<>'".$classid."' 
											  AND fld_created_by='".$uid."'");
		
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	/*--- Delete a Class  ---*/
	if($oper == "deleteclass" and $oper != '')
	{		
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_class_master 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
						 WHERE fld_id='".$classid."'");
		
		$ObjDB->NonQuery("UPDATE itc_class_indassesment_master 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
						 
		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
						 
		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
						 
		$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
						 
		$ObjDB->NonQuery("UPDATE itc_class_sigmath_master 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
		
                $ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
                
                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
                
                $ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedule_mastertemp 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
                
                $ObjDB->NonQuery("UPDATE itc_class_indasexpedition_master
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
                
                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_master
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");

        $ObjDB->NonQuery("UPDATE itc_class_mission_schedule_master
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deletedby='".$uid."' 
						 WHERE fld_class_id='".$classid."'");
		
		echo "success";
	}
	
	
	if($oper=="indloadcontent" and $oper != " ")
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '';
		$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '';
		if($sid==0){
			if($moduletype==''){
				$qry = $ObjDB->QueryObject("SELECT COUNT(fld_id) AS cnt, 2 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='1' 
											UNION ALL 
											SELECT COUNT(fld_id) AS cnt, 3 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='2'
											UNION ALL 
											SELECT COUNT(fld_id) AS cnt, 7 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='7'
											UNION ALL 
											SELECT COUNT(fld_id) AS cnt, 17 AS typ
											FROM itc_customcontent_master 
											WHERE fld_createdby='".$uid."' and fld_delstatus='0'");
				if($qry->num_rows>0){
					while($res = $qry->fetch_assoc()){
						extract($res);
						if($typ==2){ //module
							$checkmodule=$cnt;
						}
						else if($typ==3){ //math module
							$checkmathmodule=$cnt;
						}
						else if($typ==7){ //quest
							$checkquest=$cnt;
						}
                                                else if($typ==17){ //custom
							$checkcustom=$cnt;
					}
				}
				}
				
				if($checkmodule>0 && $checkmathmodule>0 && $checkquest>0 && $checkcustom>0){
					$modulename="Select module type";
					$moduletype=0;
				}
				else if($checkmodule>0 && $checkmathmodule==0 && $checkquest==0 && $checkcustom==0){
					$modulename="Module";
					$moduletype=1;
				}
				else if($checkmathmodule>0 && $checkmodule==0 && $checkquest==0 && $checkcustom==0){
					$modulename="Math Module";
					$moduletype=2;
				}
				else if($checkquest>0 && $checkmodule==0 && $checkmathmodule==0 && $checkcustom==0){
					$modulename="Quest";
					$moduletype=7;
				}
                                else if($checkcustom>0 && $checkquest==0 && $checkmodule==0 && $checkmathmodule==0){
					
                                        $modulename="Custom Content";
					$moduletype=17;
                                        
				}
                                else if($checkcustom>0 && $checkquest==0 && $checkmodule==0 && $checkmathmodule==0){
					
                                        $modulename="Select module type";
					$moduletype=18;
                                        
				}
				else{
					$modulename="Select module type";
					$moduletype=0;
				}
			}
		}
		else{
			$qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS smoduleid, a.fld_moduletype AS moduletype, (CASE WHEN a.fld_moduletype=1 THEN 'Module' WHEN a.fld_moduletype=2 
												THEN 'Math Module' WHEN a.fld_moduletype=7 THEN 'Quest' WHEN a.fld_moduletype=17 THEN 'Custom Content' END) AS modulename 
										FROM itc_class_indassesment_master AS a 
										WHERE a.fld_id='".$sid."' AND a.fld_delstatus='0'");
			extract($qry->fetch_assoc());	
			
			$trackflag=$ObjDB->SelectSingleValueInt("SELECT(SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5' AND fld_module_id='".$smoduleid."')+
(SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5' AND fld_module_id='".$smoduleid."')+
(SELECT COUNT(fld_id) FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$sid."' AND fld_test_type='5' AND fld_delstatus='0')");
		}
?>
        <div class='row <?php if($trackflag>0){?> dim <?php } ?>'>
             <div class='six columns'>
                 <input type="hidden" id="scrollhid2" value="0"/>
                Select module type<span class="fldreq">*</span>
                 <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="moduletype" id="moduletype" value="<?php echo $moduletype; ?>"  onchange="$('#wcagrades').html('');$(this).valid(); fn_indasloadmodules(<?php echo $sid; ?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php echo $modulename;?></span>
                                <b class="caret1"></b>
                            </a>
                            <?php if($moduletype==''){?>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Module">
                                    <ul role="options">
                                       	<?php if($checkcustom>0){?>
											<li><a tabindex="-1" href="#" data-option="17">Custom Content</a></li>
                                        <?php }?>
                                        <?php if($checkmathmodule>0){?>
                                            <li><a tabindex="-1" href="#" data-option="2">Math Module</a></li>
										<?php } if($checkmodule>0){?>
											<li><a tabindex="-1" href="#" data-option="1">Module</a></li>
                                        <?php } if($checkquest>0){?>
                                            <li><a tabindex="-1" href="#" data-option="7">Quest</a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                           <?php }?>
                        </div>
                    </dt>                                       
                </dl>                                       
            </div>                        
        </div>
                                
		<?php if(($licenseid!='' and $sid!=0) || $moduletype!=0){?>
        <script>fn_indasloadmodules(<?php echo $sid; ?>);</script>
        <?php }?>
        <div id="modules" <?php if($trackflag>0){?> class="dim" <?php } ?>> 
                                   
        </div>        
        <?php if($moduletype==0 || $moduletype!=17){ ?>
        <div class='row rowspacer' id="wcagrades"></div>  
        
        <div class='row rowspacer' id="extendhide">
                <div id="extenddiv" style="float:left;"> <!-- extend content -->
                   Extend content of the modules / math modules in your class
                </div>
                <div style="float:right;">
                    <input type="button" id="extendbtn" class="darkButton" value="Extend Content" onclick="fn_rotloadextendcontentwca(<?php echo $sid.",".$licenseid;?>)" /> 
                </div>
        </div>
                            
         <div id="extendcontent" class='row rowspacer'>
         </div>                        
        <?php }?>                           
        <div class="row rowspacer" style="margin-top:20px;">
            <div class="tLeft" style="color:#F00;">
            </div>
            <div class="tRight" id="modnxtstep" style="display:none;">
              <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="Save schedule" onClick="fn_saveindassesment(<?php echo $sid; ?>);" />
            </div>
        </div>
 
<?php
	}
	
	
if($oper=="indasloadmodules" and $oper!='')
{
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '';	
		
	$qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS smoduleid, a.fld_moduletype AS moduletype, (CASE WHEN a.fld_moduletype=1 THEN (SELECT CONCAT(fld_module_name,' ',
									(SELECT b.fld_version FROM itc_module_version_track AS b WHERE b.fld_mod_id=a.fld_module_id AND b.fld_delstatus='0')) FROM itc_module_master 
									WHERE fld_id=a.fld_module_id) WHEN a.fld_moduletype=2 THEN (SELECT CONCAT(fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track 
									WHERE fld_mod_id=fld_module_id AND fld_delstatus='0')) FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id) WHEN a.fld_moduletype=7 THEN (SELECT 
									CONCAT(fld_module_name,' ',(SELECT b.fld_version FROM itc_module_version_track AS b WHERE b.fld_mod_id=a.fld_module_id AND b.fld_delstatus='0'))
									FROM itc_module_master WHERE fld_id=a.fld_module_id) WHEN a.fld_moduletype = 17  THEN (SELECT CONCAT(fld_contentname,' ')
                                                                                FROM itc_customcontent_master WHERE fld_id = a.fld_module_id) END) AS smodulename 
								FROM itc_class_indassesment_master AS a 
								WHERE a.fld_id='".$scheduleid."' AND a.fld_delstatus='0'");			
	
	if($qry->num_rows>0)
	extract($qry->fetch_assoc());
?>
	<script>
            
	<?php 
        if($moduletype==17){ ?>
       $('#extendhide').hide();
        <?php }
        if($moduletype!=17){if($scheduleid!=0){?>
		fn_loadgrade(<?php echo $moduletype;?>)
        <?php }}?>
    </script>
	<div class='row rowspacer'>
         <div class='six columns'>
            Select module<span class="fldreq">*</span>
             <dl class='field row'>   
                <dt class='dropdown'>   
                    <div class="selectbox">
                        <input type="hidden" name="moduleid" id="moduleid" value="<?php echo $smoduleid; ?>" onchange="$(this).valid(); <?php if($moduletype!=17){?> $('#extendhide').show(); fn_loadgrade(<?php echo $moduletype;?>)  <?php } ?>"/>
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                            <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php if($scheduleid!=0){ echo $smodulename;} else{?>Select module  <?php }?></span>
                            <b class="caret1"></b>
                        </a>                       
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Module">
                            <ul role="options">
                                    <?php
                                        if($moduletype==1)
                                        {													
                                        	$qrymodule= $ObjDB->QueryObject("SELECT a.fld_id AS moduleid, CONCAT(a.fld_module_name,' ',c.fld_version) AS modulename
                                                                         FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
                                                                         LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_id
                                                                         WHERE  b.fld_license_id='".$licenseid."' 
                                                                         	AND b.fld_type='1' AND b.fld_active='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' 
                                                                         ORDER BY modulename ASC");		
                                        }
                                        else if($moduletype==2)
                                        {
                                            $qrymodule= $ObjDB->QueryObject("SELECT a.fld_id AS moduleid, CONCAT(a.fld_mathmodule_name,' ',c.fld_version) AS modulename 
                                                                            FROM itc_mathmodule_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
                                                                            LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_module_id
                                                                            WHERE b.fld_license_id='".$licenseid."' AND b.fld_type='2' AND b.fld_active='1' 
                                                                                AND a.fld_delstatus='0' AND c.fld_delstatus='0'
                                                                            ORDER BY modulename ASC");	
                                        }
										else if($moduletype==7)
										{													
											$qrymodule= $ObjDB->QueryObject("SELECT a.fld_id as moduleid, CONCAT(a.fld_module_name,' ',c.fld_version) as modulename 
																			FROM itc_module_master AS a 
																			LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
																			LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_id
																			WHERE b.fld_license_id='".$licenseid."' AND b.fld_type='7' AND b.fld_active='1' 
																				AND a.fld_delstatus='0' AND c.fld_delstatus='0'
																			ORDER BY a.fld_module_name");		
										}
                                        else if($moduletype==17){
                                            $qrymodule= $ObjDB->QueryObject("SELECT fld_id as moduleid,fld_contentname as modulename 
                                                                                FROM itc_customcontent_master 
                                                                                WHERE fld_createdby='".$uid."' and fld_delstatus='0' ORDER BY modulename");
                                            
                                        }
                                        if($qrymodule->num_rows>0)
                                        {
                                            while($row=$qrymodule->fetch_assoc())
                                            {
                                                extract($row);
                                    ?>
                                         <li><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>"><?php echo $modulename;?></a></li>
                                    <?php
                                            }
                                        }
                                    ?>
                            </ul>
                        </div>
                    </div>
                </dt>                                       
            </dl>                                       
        </div>                        
	</div>                    
    <?php
}
	

if($oper == "loadgrade" and $oper != '')
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '';
	$mtype = isset($method['mtype']) ? $method['mtype'] : '';
	
	if($mtype==2)
	{
		$tempmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id 
													FROM itc_mathmodule_master 
													WHERE fld_id='".$moduleid."'");
	}
	else{
		$tempmoduleid = $moduleid;
	}
	
	$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_preassment_id AS pageid, fld_grade AS grade, fld_points AS points, fld_session_id AS sessionid 
								FROM itc_module_wca_grade 
								WHERE fld_module_id='".$moduleid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
								AND fld_flag='1' AND fld_created_by='".$uid."'
								ORDER BY fld_type, sessionid, pageid");
	if($qry->num_rows==0)
	{
		if($mtype==4 or $mtype==6)
			$newtypesch = 2;
		else if($mtype==7)
			$newtypesch = 7;
		else
			$newtypesch = 1;
		$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_preassment_id AS pageid, fld_grade AS grade, fld_points AS points, fld_session_id AS sessionid 
									FROM itc_module_wca_grade 
									WHERE fld_module_id='".$moduleid."' AND fld_schedule_type='".$newtypesch."' AND fld_flag='1' 
										AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'
									ORDER BY fld_type, sessionid, pageid");
		if($qry->num_rows==0)
		{
			$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
			
			$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_preassment_id AS pageid, fld_grade AS grade, fld_points AS points, fld_session_id AS sessionid 
										FROM itc_module_wca_grade 
										WHERE fld_module_id='".$moduleid."' AND fld_schedule_type='".$newtypesch."' AND fld_flag='1' AND fld_created_by IN (".$createdids.")
										ORDER BY fld_type, sessionid, pageid");
			if($qry->num_rows==0)
			{
				if($mtype!=7)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, '0' AS pageid, fld_grade AS grade, fld_points AS points, fld_session_id AS sessionid 
											  FROM itc_module_grade 
											  WHERE fld_module_id='".$tempmoduleid."' AND fld_flag='1'		
												UNION ALL 		
											  SELECT fld_id, fld_performance_name AS title, '0' AS pageid, '1' AS grade, fld_points_possible AS points, '0' AS sessionid 
											  FROM itc_module_performance_master 
											  WHERE fld_module_id='".$tempmoduleid."' AND fld_performance_name<>'Total Pages' AND fld_delstatus='0' 
											  GROUP BY fld_performance_name 
											  ORDER BY fld_id");
				}
				else
				{
					$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_page_id AS pageid, fld_grade AS grade, fld_points AS points, fld_section_id AS sessionid 
												FROM itc_module_quest_details 
												WHERE fld_module_id='".$tempmoduleid."' AND fld_flag='1'	
													UNION ALL 		
												SELECT fld_id, fld_performance_name AS title, fld_id AS pageid, '1' AS grade, fld_points_possible AS points, fld_session_id AS sessionid 
												FROM itc_module_performance_master 
												WHERE fld_module_id='".$tempmoduleid."' AND fld_performance_name<>'Total Pages' AND fld_delstatus='0' 
												GROUP BY fld_performance_name 
												ORDER BY fld_id");
				}
			}
		}
	}
	?>
	<table cellpadding="10" cellspacing="10" border="1" id="gradedtable">
	<?php
	if($qry->num_rows>0)
	{
		$i=1;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			$evencount = ($i % 2);
			
			if($evencount != 0)
			{
				?>
				<tr height="40">
					<td style="width:20%"><label id="wca_<?php echo $fld_id."#".$sessionid."#".$pageid?>"><?php echo $title;?></label></td>
					<td align="right" style="width:15%"><input type="text" maxlength="3" id="point_<?php echo $i;?>" name="point_<?php echo $i;?>" value="<?php echo $points;?>" style="width:30%" onkeyup="ChkValidChar(this.id);"/></td>
					<td style="width:15%">
						<input type="checkbox" id="grade_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if($grade==1){echo 'checked="checked"';}?> value="" />Graded
					</td>
				<?php 
			}
			else if($evencount == 0)
			{
				?>
					<td style="width:20%"><label id="wca_<?php echo $fld_id."#".$sessionid."#".$pageid?>"><?php echo $title;?></label></td>
					<td align="right" style="width:15%"><input type="text" maxlength="3" id="point_<?php echo $i;?>" name="point_<?php echo $i;?>" value="<?php echo $points;?>" style="width:30%" onkeyup="ChkValidChar(this.id);"/></td>
					<td style="width:15%">
						<input type="checkbox" id="grade_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if($grade==1){echo 'checked="checked"';}?> value="" />Graded
					</td>
				</tr>
				<?php 
			}
			
			if($points > 100)
				$maxval = 200;
			else
				$maxval = 100;
			?>
            <input type="hidden" id="maxpoint_<?php echo $i;?>" name="maxpoint_<?php echo $i;?>" value="<?php echo $maxval;?>" />
            <?php
			$i++;
		}
	}
	?>
	</table>
	
	<script type="text/javascript" language="javascript">
		$(function(){
			var tabindex = 1;
			$('input,select').each(function() {
				if (this.type != "hidden") {
					var $input = $(this);
					$input.attr("tabindex", tabindex);
					tabindex++;
				}
			});
		});

		//Function to enter only numbers in textbox
		$("input[id^=point_]").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {					
				return false;
			}
		});
		
		//Function to set the max & min values for the textbox
		String.prototype.startsWith = function (str) {
			return (this.indexOf(str) === 0);
		}
		function ChkValidChar(id) {
			var newid = id.replace('point_','maxpoint_');
			var txtbx = document.getElementById(id).value;
			var nexttxtbx = document.getElementById(newid).value;			
			if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
			{
				document.getElementById(id).value = "";				
			}
		}
	</script>
	<?php
}
	
if($oper == "saveindassesment" and $oper != '')
{		
	try{				
		$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
		$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
		$sname = isset($_REQUEST['sname']) ? $ObjDB->EscapeStrAll($_REQUEST['sname']) : '0';
		$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '0';
		$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '0';
		$scheduletype = isset($_REQUEST['scheduletype']) ? $_REQUEST['scheduletype'] : '0';
		$students = isset($_REQUEST['students']) ? $_REQUEST['students'] : '0';
		$unstudents = isset($_REQUEST['unstudents']) ? $_REQUEST['unstudents'] : '0';
		$studenttype = isset($_REQUEST['studenttype']) ? $_REQUEST['studenttype'] : '0';
		$numberofcopies = isset($_REQUEST['numberofcopies']) ? $_REQUEST['numberofcopies'] : '0';
		$numberofrotations = isset($_REQUEST['numberofrotations']) ? $_REQUEST['numberofrotations'] : '0';
		$rotationlength = isset($_REQUEST['rotationlength']) ? $_REQUEST['rotationlength'] : '0';
		$licenseid = isset($_REQUEST['licenseid']) ? $_REQUEST['licenseid'] : '0';
		$modules = isset($_REQUEST['modules']) ? $_REQUEST['modules'] : '0';
		$moduletype = isset($_REQUEST['moduletype']) ? $_REQUEST['moduletype'] : '0';		
		$pagetitle = isset($_REQUEST['pagetitle']) ? urldecode($_REQUEST['pagetitle']) : '';
		$points = isset($_REQUEST['points']) ? $_REQUEST['points'] : '';
		$grades = isset($_REQUEST['grades']) ? $_REQUEST['grades'] : '';
		$wcasess = isset($_REQUEST['wcasess']) ? $_REQUEST['wcasess'] : '';
		$wcapage = isset($_REQUEST['wcapage']) ? $_REQUEST['wcapage'] : '';
		$extid = isset($_REQUEST['extid']) ? $_REQUEST['extid'] : '';
		$students = explode(',',$students);
		$unstudents = explode(',',$unstudents);	
		
		$validate_sid=true;
		$validate_sname=true;
		$validate_classid=true;
		$validate_scheduletype=true;
		$validate_startdate=true;
		$validate_enddate=true;
		$validate_licenseid=true;			
		if($sid!=0) 
			$validate_sid=validate_datatype($sid,'int');
		$validate_sname=validate_datas($sname,'lettersonly');
		$validate_classid=validate_datatype($classid,'int');
		$validate_licenseid=validate_datatype($licenseid,'int');
		$validate_scheduletype=validate_datatype($scheduletype,'int');
		$validate_startdate=validate_datas($startdate,'dateformat');
		$validate_enddate=validate_datas($enddate,'dateformat');
		
		if($validate_sid and $validate_sname and $validate_classid and $validate_scheduletype and $validate_startdate and $validate_licenseid and $validate_enddate){
			if($moduletype==1){
				$days = $ObjDB->SelectSingleValueInt("SELECT fld_days FROM itc_module_master WHERE fld_id='".$modules."'");
			}
			else if($moduletype==7){
				$days = $ObjDB->SelectSingleValueInt("SELECT fld_days FROM itc_module_master WHERE fld_id='".$modules."'");
			}
			else{
				$days = $ObjDB->SelectSingleValueInt("SELECT fld_days FROM itc_mathmodule_master WHERE fld_id='".$modules."'");
			}				
			if($studenttype==1){
				/*---------checing the license for student----------------------*/				
				$count=0;
				$qry = $ObjDB->QueryObject("SELECT fld_student_id 
											FROM itc_class_student_mapping 
											WHERE fld_class_id='".$classid."' AND fld_flag='1'");
				if($qry->num_rows>0){
					$students=array();
					while($res=$qry->fetch_assoc())
					{
						extract($res);
						$students[]=$fld_student_id;
						$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
															  FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
															  WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' 
															  	AND b.fld_delstatus='0'");
						if($check==0)
						{
							$count++;
						}
					}
				}
			}
			else{
				$count=0;
				$add=0;			
				for($i=0;$i<sizeof($students);$i++)
				{
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
														  FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
														  WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					if($check==0)
					{
						$count++;
					}
				}				
				for($i=0;$i<sizeof($unstudents);$i++)
				{					
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
															FROM itc_license_assign_student 
															WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' AND fld_flag='1'");
					if($check>0)
					{
						
						$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_schedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_schedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_expschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_expschedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                                                UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_modexpschedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_dyad_schedule_studentmapping AS a LEFT JOIN itc_class_dyad_schedulemaster
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_triad_schedule_studentmapping AS a LEFT JOIN itc_class_triad_schedulemaster
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_indassesment_student_mapping AS a LEFT JOIN itc_class_indassesment_master
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
																		UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
								LEFT JOIN itc_class_mission_schedule_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
						
						$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
										 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
										 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
						if($studentcount==0){
							$add++;
							$ObjDB->NonQuery("UPDATE itc_license_assign_student 
											 SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
											 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."'");
						}
					}
				}
			}
			
			$remainusersqry = $ObjDB->QueryObject("SELECT fld_remain_users AS remainusers, fld_no_of_users AS totalusers 
															FROM itc_license_track 
															WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' 
															AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");		
			extract($remainusersqry->fetch_assoc());
				
			$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																 FROM itc_license_assign_student 
																 WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
			$totalremain = $remainusers-$count;
			if($totalusers>=($assignedstudents+$count)){
				$flag=1;
			}		
			else{	
				$flag=0;
			}
			
			if($flag==1){ //if student user availale for license
				if($sid!=0){				
					$oldmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_indassesment_master WHERE fld_id='".$sid."'");				
					if($oldmoduleid!=$modules){
						if($moduletype==1)
						{
							$ObjDB->NonQuery("UPDATE itc_module_play_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
			
			$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
			
			$ObjDB->NonQuery("UPDATE itc_module_variable_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
			
			$ObjDB->NonQuery("UPDATE itc_module_answer_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
						}
						if($moduletype==2)
						{
							$ObjDB->NonQuery("UPDATE itc_module_play_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
			
			$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
			
			$ObjDB->NonQuery("UPDATE itc_module_variable_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
			
			$ObjDB->NonQuery("UPDATE itc_module_answer_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_schedule_type='5'");
							
							$qry=$ObjDB->NonQuery("SELECT fld_id FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$sid."' AND fld_test_type='5'");
							if($qry->num_rows>0)
							{
								while($row=$qry->fetch_assoc())
								{
									extract($row);
									$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_track_id='".$fld_id."'");
								}
							}
							
							$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_delstatus='1',fld_deleted_date='".date('Y-m-d H:i:s')."',fld_deleted_by='".$uid."' WHERE fld_schedule_id='".$sid."' and fld_test_type='5'");
		
						}
					}
					$ObjDB->NonQuery("UPDATE itc_class_indassesment_master 
									SET fld_schedule_name='".$sname."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."' ,
										fld_enddate='".date("Y-m-d",strtotime($enddate))."', fld_module_id='".$modules."', fld_moduletype='".$moduletype."',
										fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
									WHERE fld_id='".$sid."'");
				}
				else{
					
					$sid = $ObjDB->NonQueryWithMaxValue("INSERT into itc_class_indassesment_master (fld_class_id,fld_license_id,fld_schedule_name,fld_module_id, fld_moduletype, 	
																fld_scheduletype, fld_student_type,fld_startdate,fld_enddate,fld_created_date,fld_createdby) 
														 VALUES('".$classid."','".$licenseid."','".$sname."','".$modules."','".$moduletype."','".$scheduletype."','".$studenttype."',
																'".date("Y-m-d",strtotime($startdate))."','".date("Y-m-d",strtotime($enddate))."','".date("Y-m-d H:i:s")."','".$uid."')");					
				}
				
				$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
								 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."'");
				
				for($i=0;$i<sizeof($students);$i++){
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_class_indassesment_student_mapping 
														WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_class_indassesment_student_mapping(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) 
										 VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
										SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
										WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
					
					//tracing student
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) 
										 VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_assign_student 
										 SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
										 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
				}
				
				$ObjDB->NonQuery("UPDATE itc_class_indassesment_master 
								 SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' 
								 WHERE fld_id='".$sid."'");
								 
				$ObjDB->NonQuery("UPDATE itc_class_indassesment_extcontent_mapping 
							 SET fld_active='0' 
							 WHERE fld_schedule_id='".$sid."'"); 
							 
							
								 
				if($extid!='')	
				{			 
					
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
															FROM itc_class_indassesment_extcontent_mapping 
															WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$extid."' AND fld_schedule_type='".$moduletype."' 
															AND fld_module_id='".$modules."'");
															
															
						if($cnt==0)
						{
							 $ObjDB->NonQuery("INSERT INTO itc_class_indassesment_extcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_schedule_type,fld_module_id,fld_createdby,fld_createddate)
												VALUES('".$sid."','".$extid."','1','".$moduletype."','".$modules."','".$uid."','".date("Y-m-d H:i:s")."')");
												
								
						}
						else
						{
							
							$ObjDB->NonQuery("UPDATE itc_class_indassesment_extcontent_mapping 
												SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."'  
												WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$extid."'  AND fld_schedule_type='".$moduletype."' AND fld_module_id='".$modules."'");
												
												
						}
				}
				
				
				$ObjDB->NonQuery("UPDATE itc_class_indassesment_master 
								 SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' 
								 WHERE fld_id='".$sid."'");
				
				if($points!='')
				{
					$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_schedule_type='".$scheduletype."' AND fld_created_by='".$uid."'");
					$pagetitle = explode('~',$pagetitle);
					$points = explode('~',$points);
					$grades = explode(',',$grades);
					$wcasess = explode('~',$wcasess);
					$wcapage = explode('~',$wcapage);
					$r=2;
					for($i=0;$i<sizeof($points);$i++)
					{
						$type = 0;
						if($pagetitle[$i]=='Attendance')
						{
							$type=1;
							$newtitle = $pagetitle[$i];
						}
						else if($pagetitle[$i]=='Participation')
						{
							$type=2;
							$newtitle = $pagetitle[$i];
						}
						else if($pagetitle[$i]<>'Module Guide' and substr($pagetitle[$i], 0, 3)<>'RCA' and $pagetitle[$i]<>'Post Test' and $pagetitle[$i]<>'Posttest' and $pagetitle[$i]<>'Pretest' and substr($pagetitle[$i],-8)<>'Pop Quiz' and substr($pagetitle[$i],-8)<>'Posttest' and substr($pagetitle[$i],-7)<>'Pretest')
						{
							$type=3;
							$newtitle = $pagetitle[$i];
						}
							
						$newtitle = $pagetitle[$i];
						if(substr($pagetitle[$i], 0, 3)=='RCA')
						{
							$newtitle = "RCA ".$r;
							$r++;
						}
						
						$wcagradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_wca_grade WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_schedule_type='".$scheduletype."' AND fld_module_id='".$modules."' AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_created_by='".$uid."' AND fld_type='".$type."' AND fld_preassment_id='".$wcapage[$i]."'");
						
						if($wcagradeid!='')
							$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='1', fld_grade='".$grades[$i]."', fld_points='".$points[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_preassment_id='".$wcapage[$i]."' WHERE fld_id='".$wcagradeid."'");
						else
							$ObjDB->NonQuery("INSERT INTO itc_module_wca_grade (fld_type, fld_schedule_type, fld_schedule_id, fld_class_id, fld_module_id, fld_session_id, fld_preassment_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduletype."', '".$sid."', '".$classid."', '".$modules."', '".$wcasess[$i]."', '".$wcapage[$i]."', '".$newtitle."', '".$grades[$i]."', '".$points[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
						
						$newschtype = $ObjDB->SelectSingleValue("SELECT fld_moduletype FROM itc_class_indassesment_master WHERE fld_id='".$sid."'");
						if($newschtype!=7)
						{
							if($i<6)	
								$qrycount = $ObjDB->QueryObject("SELECT fld_id AS fieldid, fld_teacher_points_earned AS teachpoint, fld_points_earned AS earnedpoints, fld_points_possible AS posible FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type IN (5,6) AND fld_module_id='".$modules."' AND fld_session_id='".$wcasess[$i]."' AND fld_type='0'");
							else
								$qrycount = $ObjDB->QueryObject("SELECT fld_id AS fieldid, fld_teacher_points_earned AS teachpoint, fld_points_earned AS earnedpoints, fld_points_possible AS posible FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type IN (5,6) AND fld_module_id='".$modules."' AND fld_type='".$type."'");
						}
						else
						{
							if($type==3)
								$sqry = "AND fld_type='".$type."'";
							else
								$sqry = "AND fld_type='".$type."' AND fld_preassment_id='".$wcapage[$i]."'";

							$qrycount = $ObjDB->QueryObject("SELECT fld_id AS fieldid, fld_teacher_points_earned AS teachpoint, fld_points_earned AS earnedpoints, fld_points_possible AS posible FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='".$newschtype."' AND fld_module_id='".$modules."' AND fld_session_id='".$wcasess[$i]."' ".$sqry."");
						}
							
						if($qrycount->num_rows>0)
						{
							while($rowcount=$qrycount->fetch_assoc())
							{
								extract($rowcount);
								if($posible!=$points[$i])
								{
									$newpoint = round($posible/$points[$i],2);
									if($earnedpoints!='')
										$newearned = round($earnedpoints/$newpoint);
									if($teachpoint!='')
										$newteacher = round($teachpoint/$newpoint);
									$newpossible = $points[$i];
								}
								else
								{
									$newpossible = $posible;
									$newearned = $earnedpoints;
									$newteacher = $teachpoint;
								}
								$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_grade='".$grades[$i]."', fld_points_possible='".$newpossible."', fld_points_earned='".$newearned."', fld_teacher_points_earned='".$newteacher."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$fieldid."'");
							}
						}
					}
				}
				echo "success~".$sid;
				send_notification($licenseid,$schoolid,$indid);			
			}
			else{
				echo "fail";
			}
		}
		else{
			echo "invalid";
		}
	}
	catch(Exception $e){
		echo "invalid";
	}
}
	
if($oper == "classlock" and $oper != '')
{		
	$classid = isset($method['classid']) ? $method['classid'] : '0';
	$flag = isset($method['flag']) ? $method['flag'] : '0';
	$ObjDB->NonQuery("UPDATE itc_class_master SET fld_lock='".$flag."' WHERE fld_id='".$classid."'");
}

if($oper == "createstudentform" and $oper != '')
{	?>
<div class='row'>
    <div class='twelve columns '>                
        <div class='eleven columns centered insideForm'>
            <form id="studentform" name="studentform">	
                <div class="row">
                    <div class='six columns'>
                        First Name<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="fname" name="fname" placeholder='First Name' type='text' onkeyup="fn_generateuname()" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class='six columns'>
                        Last Name<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="lname" name="lname" placeholder='Last Name' type='text' onkeyup="fn_generateuname();fn_checkusername()" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class='six columns'>
                        Username<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="uname" name="uname" placeholder='User Name' type='text' onblur="$(this).valid();" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class='six columns'>
                        Password<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="password" name="password" placeholder='Password' type='text' value="<?php echo generatePassword();?>" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>                
                
                <div class='row'>
                            	<div class='five columns'>
                                 	Select Grade<!--<span class="fldreq">*</span>-->
                                    <dl class='field row'>   
                                    	<dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="grade" id="grade" value=""  onchange="$(this).valid();" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" id="clearsubject" style="width:254px;">Select Grade</span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options" style="top:-133px;">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Grade (1-12)">
                                                    <ul role="options" style="width:270px;">
														<?php                                                     
                                                        for($i=1; $i<=12;$i++){?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $i;?>"><?php echo $i; ?></a></li>
                                                        <?php 
                                                        }?>      
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>                                       
                                    </dl>
                                </div>
                </div>
                 
               <div class='row rowspacer'>
                <div style="padding-left: 1%;padding-top: 2%;">                        	
                    <input type="button" value="Save" onclick="fn_createstudent(0)" class="darkButton" />
                    <input type="button" value="Save & Continue" onclick="fn_createstudent(1)" class="darkButton" />
                    <input type="button" value="Cancel" onclick="$.fancybox.close()" class="darkButton" />
                </div>
               </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">
	function fn_generateuname()
	{		
		$('#uname').val($('#fname').val().toLowerCase().substring(0,1)+$('#lname').val().toLowerCase());		
	}
	function fn_checkusername()
	{
		$('#save').attr('disabled','disabled');
		$('#savec').attr('disabled','disabled');		
		var dataparam = "oper=checkstdname&uname="+$('#uname').val();	
		$.ajax({
			type: "POST",
			url: 'users/individuals/users-individuals-student_newstudentdb.php',
			data: dataparam,
			success: function(data)
			{
				if(trim(data)=='false')
					$('#uname').val(Math.floor(Math.random() * 8) + 1+$('#fname').val().toLowerCase().substring(0,1)+$('#lname').val().toLowerCase());
					$('#save').removeAttr('disabled');
					$('#savec').removeAttr('disabled');	
			}
		});
	}	
	$(function(){
		$("#studentform").validate({
			ignore: "",
				errorElement: "dd",
				errorPlacement: function(error, element) {
					$(element).parents('dl').addClass('error');
					error.appendTo($(element).parents('dl'));
					error.addClass('msg'); 	
			},
			rules: { 
				uname: { required: true, lettersonly: true, 
				remote:{ 
						url: "users/individuals/users-individuals-student_newstudentdb.php", 
						type:"POST",  
						data: {  
								stdid: function() {
								return '<?php echo $editid;?>';},
								oper: function() {
								return 'checkstdname';}
								  
						 },
						 async:false 
				   }},								
				password: { required: true },
				fname: { required: true },
				lname: { required: true },                                
			}, 
			messages: { 
				uname: { required: "Please enter the User name", remote: "Student username already exists" },          
				password:{  required: "Please enter the password" },
				fname:{ required: "Please enter the first name"},
				lname:{ required: "Please enter the last name"},									                               
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
			onkeyup: false,
			onblur: true
		});
	});	
</script>                            
	<?php 	
}

if($oper == "savestudent" and $oper != '')
{	
	$fname = isset($method['fname']) ? $ObjDB->EscapeStrAll($method['fname']) : '';
	$lname = isset($method['lname']) ? $ObjDB->EscapeStrAll($method['lname']) : '';
	$uname = isset($method['uname']) ? $ObjDB->EscapeStrAll($method['uname']) : '';
	$password = isset($method['password']) ? $method['password'] : '';
        $grade = isset($method['grade']) ? $method['grade'] : '0';
	$uguid = gen_uuid();
	$userid = $ObjDB->NonQueryWithMaxValue ("INSERT INTO itc_user_master(fld_uuid, fld_username, fld_password, fld_profile_id,fld_role_id, fld_fname, fld_lname, fld_district_id, 
														fld_school_id, fld_activestatus, fld_user_id, fld_created_by, fld_created_date)
													VALUES('".$uguid."', '".$uname."','".fnEncrypt($password,$encryptkey)."','10','5','".$fname."','".$lname."','".$districtid."',
														'".$schoolid."','1','".$indid."','".$uid."','".date("Y-m-d H:i:s")."')");
        $ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
										VALUES ('".$userid."','12','".$grade."')");
	echo "success~";?>
	<div class="draglinkleft" id="list3_<?php echo $userid; ?>" >
        <div class="dragItemLable" id="<?php echo $userid; ?>"><?php echo stripcslashes($lname." ".$fname); ?></div>
        <div class="clickable" id="clck_<?php echo $userid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $userid; ?>);"></div>
    </div> 
 <?php                                                    
}

if($oper == "changeeventdate" and $oper != '')
{	
	$curdate=date("Y-m-d");
	$type = isset($method['type']) ? $method['type'] : '';
	$sid = isset($method['sid']) ? $method['sid'] : '';
	$date = isset($method['date']) ? $method['date'] : '';
	$rotation = isset($method['rotation']) ? $method['rotation'] : '';
	$edate = isset($method['enddate']) ? $method['enddate'] : '';
	$stageid = isset($method['stageid']) ? $method['stageid'] : '';
	$rottype = isset($method['rottype']) ? $method['rottype'] : '';
	
	if($type=="Sigmath")
	{
		$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_start_date='".$date."',fld_end_date='".$edate."',fld_updated_date='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
        else if($type=="pdschedule")
	{
		$ObjDB->NonQuery("UPDATE itc_class_pdschedule_master SET fld_start_date='".$date."',fld_end_date='".$edate."',fld_updated_date='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
	else if($type=="assesment")
	{
		$ObjDB->NonQuery("UPDATE itc_class_indassesment_master SET fld_startdate='".$date."',fld_enddate='".$edate."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
        else if($type=="wcaexpedition")
	{
               if(date('l', strtotime($edate)) == 'Saturday')
               {
                  $enddatedup=date("Y-m-d",strtotime($edate. "+1 weekdays")); 
               }
               else if(date('l', strtotime($edate)) == 'Sunday')
               {
                   $enddatedup=date("Y-m-d",strtotime($edate. "+1 weekdays"));
               }
               else
               {
                   $enddatedup=$edate;
               }
                
		$ObjDB->NonQuery("UPDATE itc_class_indasexpedition_master SET fld_startdate='".$date."',fld_enddate='".$enddatedup."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
        else if($type=="wcamission")
	{
               if(date('l', strtotime($edate)) == 'Saturday')
               {
                  $enddatedup=date("Y-m-d",strtotime($edate. "+1 weekdays")); 
               }
               else if(date('l', strtotime($edate)) == 'Sunday')
               {
                   $enddatedup=date("Y-m-d",strtotime($edate. "+1 weekdays"));
               }
               else
               {
                   $enddatedup=$edate;
               }
		  $ObjDB->NonQuery("UPDATE itc_class_indasmission_master SET fld_startdate='".$date."',fld_enddate='".$enddatedup."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
	else if($type=="rotation")
	{
		
			$qry=$ObjDB->NonQuery("SELECT min(b.fld_rotation) as rotval,max(b.fld_rotation) as rot,a.fld_rotationlength AS length FROM itc_class_rotation_schedule_mastertemp as a LEFT JOIN
                        itc_class_rotation_scheduledate as b on a.fld_id=b.fld_schedule_id WHERE b.fld_schedule_id='".$sid."'");
            
            $row=$qry->fetch_assoc();
			extract($row);

			$datedup=date("Y-m-d",strtotime($date. "-1 days"));
			$startdatedup=date("Y-m-d",strtotime($datedup. "+1 weekdays"));

			if($rotation==$rotval)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_startdate='".$startdatedup."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
			} 
			
			for($i=$rotation;$i<=$rot+1;$i++)
			{
				$len='';
				if($i==$rotation)
				{
					if($rottype=="extend")
					{
						$startdate=$date;
						$enddate=$edate;
						$ObjDB->NonQuery("UPDATE itc_class_rotation_scheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
					}
					else if($rottype=="move")
					{
						$len=$length-1;
						$date=date("Y-m-d",strtotime($date. "-1 days"));
						$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
						$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
					}
				}
				else
				{
					$len=$length-1;
					$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
				}
				
				
				if($rottype=="move")
				{
						$ObjDB->NonQuery("UPDATE itc_class_rotation_scheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
				}
			}
			
			$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$sid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_enddate='".$rotenddate."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");
		
	}
        else if($type=="exprotation")
	{
		
			$qry=$ObjDB->NonQuery("SELECT min(b.fld_rotation) as rotval,max(b.fld_rotation) as rot,a.fld_rotationlength AS length FROM itc_class_rotation_expschedule_mastertemp as a LEFT JOIN
                        itc_class_rotation_expscheduledate as b on a.fld_id=b.fld_schedule_id WHERE b.fld_schedule_id='".$sid."'");
            
            $row=$qry->fetch_assoc();
			extract($row);

			$datedup=date("Y-m-d",strtotime($date. "-1 days"));
			$startdatedup=date("Y-m-d",strtotime($datedup. "+1 weekdays"));

			if($rotation==$rotval)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_startdate='".$startdatedup."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
			} 
			
			for($i=$rotation;$i<=$rot+1;$i++)
			{
				$len='';
				if($i==$rotation)
				{
					if($rottype=="extend")
					{
						$startdate=$date;
						$enddate=$edate;
						$ObjDB->NonQuery("UPDATE itc_class_rotation_expscheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
					}
					else if($rottype=="move")
					{
						$len=$length-1;
						$date=date("Y-m-d",strtotime($date. "-1 days"));
						$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
						$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
					}
				}
				else
				{
					$len=$length-1;
					$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
				}
				
				
				if($rottype=="move")
				{
						$ObjDB->NonQuery("UPDATE itc_class_rotation_expscheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
				}
			}
			
			$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$sid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_expschedule_mastertemp SET fld_enddate='".$rotenddate."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");
		
	}
        
        else if($type=="modexprotation")
	{
		
			$qry=$ObjDB->NonQuery("SELECT min(b.fld_rotation) as rotval,max(b.fld_rotation) as rot,a.fld_rotationlength AS length FROM itc_class_rotation_modexpschedule_mastertemp as a LEFT JOIN
                        itc_class_rotation_modexpscheduledate as b on a.fld_id=b.fld_schedule_id WHERE b.fld_schedule_id='".$sid."'");
            
            $row=$qry->fetch_assoc();
			extract($row);

			$datedup=date("Y-m-d",strtotime($date. "-1 days"));
			$startdatedup=date("Y-m-d",strtotime($datedup. "+1 weekdays"));

			if($rotation==$rotval)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedule_mastertemp SET fld_startdate='".$startdatedup."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
			} 
			
			for($i=$rotation;$i<=$rot+1;$i++)
			{
				$len='';
				if($i==$rotation)
				{
					if($rottype=="extend")
					{
						$startdate=$date;
						$enddate=$edate;
						$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpscheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
					}
					else if($rottype=="move")
					{
						$len=$length-1;
						$date=date("Y-m-d",strtotime($date. "-1 days"));
						$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
						$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
					}
				}
				else
				{
					$len=$length-1;
					$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
				}
				
				
				if($rottype=="move")
				{
						$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpscheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
				}
			}
			
			$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_modexpscheduledate WHERE fld_schedule_id='".$sid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_modexpscheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_modexpschedule_mastertemp SET fld_enddate='".$rotenddate."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");
		
	}
        
         else if($type=="missionrot")
	{
		
			$qry=$ObjDB->NonQuery("SELECT min(b.fld_rotation) as rotval,max(b.fld_rotation) as rot,a.fld_rotationlength AS length FROM itc_class_rotation_mission_mastertemp as a LEFT JOIN
                        itc_class_rotation_missionscheduledate as b on a.fld_id=b.fld_schedule_id WHERE b.fld_schedule_id='".$sid."'");
            
            $row=$qry->fetch_assoc();
			extract($row);

			$datedup=date("Y-m-d",strtotime($date. "-1 days"));
			$startdatedup=date("Y-m-d",strtotime($datedup. "+1 weekdays"));

			if($rotation==$rotval)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_startdate='".$startdatedup."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
			} 
			
			for($i=$rotation;$i<=$rot+1;$i++)
			{
				$len='';
				if($i==$rotation)
				{
					if($rottype=="extend")
					{
						$startdate=$date;
						$enddate=$edate;
						$ObjDB->NonQuery("UPDATE itc_class_rotation_missionscheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
					}
					else if($rottype=="move")
					{
						$len=$length-1;
						$date=date("Y-m-d",strtotime($date. "-1 days"));
						$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
						$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
					}
				}
				else
				{
					$len=$length-1;
					$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+".$len." weekdays"));
				}
				
				
				if($rottype=="move")
				{
						$ObjDB->NonQuery("UPDATE itc_class_rotation_missionscheduledate 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".$date."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_rotation='".$i."'");
				}
			}
			
			$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$sid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_enddate='".$rotenddate."',fld_updated_date='".$date."',fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");
		
	}
        
		else if($type=="dyad")
	{	
		if($rotation!=0 and $rotation!='')
		{	
		$qry=$ObjDB->NonQuery("SELECT fld_numberofrotation 
							  FROM itc_class_dyad_schedule_insstagemap 
							  WHERE fld_id<='".$stageid."' AND fld_flag='1' AND fld_schedule_id='".$sid."' AND fld_numberofrotation<>'0'");
		$count='';
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			
			$count=$count+$fld_numberofrotation;
		}
		 
		for($i=$rotation;$i<=$count;$i++)
		{
			if($i==$rotation)
			{
				
				if($rottype=="extend")
				{
					$startdate=$date;
					$enddate=$edate;
					$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
							 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							 WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
							 
							 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping set fld_startdate='".$startdategriddet."',fld_enddate='".$tempenddategriddete."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$fld_id."' and fld_schedule_id='".$sid."' and fld_rotation='".$fld_rotation."'");
							 
				}
				else if($rottype=="move")
				{
					$date=date("Y-m-d",strtotime($date. "-1 days"));
					$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
				}
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			
			if($rottype=="move")
			{			
				$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
							 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
							 WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
							 
							 $ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping set fld_startdate='".$startdategriddet."',fld_enddate='".$tempenddategriddete."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$fld_id."' and fld_schedule_id='".$sid."' and fld_rotation='".$fld_rotation."'");
			}
							 
							 
							 
		}
				$qrygetdate=$ObjDB->NonQuery("SELECT MIN(fld_startdate) as sdate,MAX(fld_enddate) as endate FROM itc_class_dyad_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_stageid='".$stageid."'");
			
			$rowd=$qrygetdate->fetch_assoc();
			extract($rowd);
			
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".$sdate."',fld_enddate='".$endate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."' AND fld_schedule_id='".$sid."'");	
		}
		else if($rotation=='0')
		{
			if($rottype=="extend")
				{
					$startdate=$date;
					$enddate=$edate;
				}
				else if($rottype=="move")
				{
					$date=date("Y-m-d",strtotime($date. "-1 days"));
					$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
				}
				
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='0' AND fld_stageid='".$stageid."'");
		}
		else
		{
				if($rottype=="extend")
				{
					$startdate=$date;
					$enddate=$edate;
				}
				else if($rottype=="move")
				{
					$date=date("Y-m-d",strtotime($date. "-1 days"));
					$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
				}
				
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1'  AND fld_id='".$stageid."'");
		}
		
		if($rotation=='0')
		{
			$startdate=$ObjDB->SelectSingleValue("SELECT MIN(fld_startdate) FROM itc_class_dyad_schedulegriddet WHERE fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_flag='1'");

       		$enddate=$ObjDB->SelectSingleValue("SELECT MAX(fld_enddate) FROM itc_class_dyad_schedulegriddet WHERE fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_flag='1'");
	   
	  		$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_id='".$stageid."' AND fld_schedule_id='".$sid."'");
		}
		
		if($rottype=="move")
		{
		$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
							if($qrystage->num_rows>0)
							{
							$z=0;
							$rotation=$count;
							while($rowstage=$qrystage->fetch_assoc())
							{
								extract($rowstage);
								
								if($z==0)
								{
									$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
								}
								else
								{
									$startdate="";
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
									$tempenddate="";
								}
								
								if($fld_stagetype==1)
								{
									if($fld_stagevalue==1)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==2)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==3)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==4)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==5)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
										$tempenddate=$enddate;
									}
								}
								else if($fld_stagetype==2)
								{
									$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
									$tempenddate=$enddate;
								}
								else if($fld_stagetype==3)
								{
									$days=($fld_numberofrotation*7)-1;
									
									$getrotation=$ObjDB->NonQuery("SELECT fld_rotation FROM itc_class_dyad_schedulegriddet WHERE fld_stageid='".$fld_id."' AND fld_schedule_id='".$sid."' AND fld_flag='1' GROUP BY fld_rotation");
									
									if($getrotation->num_rows>0)
									{
										$start=0;
										$startdategriddet='';
										$enddategriddet='';
										$tempenddategriddet='';
										while($rowrot=$getrotation->fetch_assoc())
										{
											extract($rowrot);
											if($startdategriddet=='')
											{
												$startdategriddet=$startdate;
												$enddategriddet=date("Y-m-d",strtotime($startdategriddet. "+6 weekdays"));
												$tempenddategriddete=$enddategriddet;
												
											}
											else
											{
												$startdategriddet=date("Y-m-d",strtotime($tempenddategriddete. "+1 weekdays"));
												$enddategriddet=date("Y-m-d",strtotime($startdategriddet. "+6 weekdays"));
												$tempenddategriddete=$enddategriddet;
												
											}
											
											$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet set fld_startdate='".$startdategriddet."',fld_enddate='".$tempenddategriddete."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$fld_id."' and fld_schedule_id='".$sid."' and fld_rotation='".$fld_rotation."'");
											
											$ObjDB->NonQuery("UPDATE itc_class_dyad_stagerotmapping set fld_startdate='".$startdategriddet."',fld_enddate='".$tempenddategriddete."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$fld_id."' and fld_schedule_id='".$sid."' and fld_rotation='".$fld_rotation."'");
											
										}
									}
									
									$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
									$tempenddate=$enddate;
								}
								
								
								$ObjDB->NonQuery("UPDATE itc_class_dyad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."' AND fld_schedule_id='".$sid."'");
								
								if($fld_stagetype==2)
								{
									$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='0' AND fld_stageid='".$fld_id."'");
								}
							
							$z++;	
								
							}
						 }
					}
						  
						  $dyadenddate=$ObjDB->SelectSingleValue("SELECT MAX(fld_enddate) FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
						$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulemaster SET fld_dyadtableflg=1,fld_enddate='".$dyadenddate."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$sid."'");
		
	}
	else if($type=="triad")
	{
		
		if($rotation!=0 and $rotation!='')
		{					  
		$qry=$ObjDB->NonQuery("SELECT fld_numberofrotation 
							  FROM itc_class_triad_schedule_insstagemap 
							  WHERE fld_id<='".$stageid."' AND fld_flag='1' AND fld_schedule_id='".$sid."' AND fld_numberofrotation<>'0'");
		$count='';
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			
			$count=$count+$fld_numberofrotation;
		}
		 
		for($i=$rotation;$i<=$count;$i++)
		{
			if($i==$rotation)
			{
				if($rottype=="extend")
				{
					$startdate=$date;
					$enddate=$edate;
					
					$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
							
							$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping set fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_rotation='".$i."'");
							
				}
				else if($rottype=="move")
				{
					$date=date("Y-m-d",strtotime($date. "-1 days"));
					$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
				}
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			
			if($rottype=="move")
			{
				$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
							
							$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping set fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_rotation='".$i."'");
			}
							
		}
			
			$qrygetdate=$ObjDB->NonQuery("SELECT MIN(fld_startdate) as sdate,MAX(fld_enddate) as endate FROM itc_class_triad_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_stageid='".$stageid."'");
			
			$rowd=$qrygetdate->fetch_assoc();
			extract($rowd);
			
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$sdate."',fld_enddate='".$endate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$stageid."' AND fld_schedule_id='".$sid."'");
			
		}
		else if($rotation=='0')
		{
			if($rottype=="extend")
				{
					$startdate=$date;
					$enddate=$edate;
				}
				else if($rottype=="move")
				{
					$date=date("Y-m-d",strtotime($date. "-1 days"));
					$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
				}
				
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='0' AND fld_stageid='".$stageid."'");
							
		}
		else
		{
			if($rottype=="extend")
				{
					$startdate=$date;
					$enddate=$edate;
				}
				else if($rottype=="move")
				{
					$date=date("Y-m-d",strtotime($date. "-1 days"));
					$startdate=date("Y-m-d",strtotime($date. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
				}
				
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1'  AND fld_id='".$stageid."'");
		}
		
		
		if($rotation=='0')
		{
			$startdate=$ObjDB->SelectSingleValue("SELECT MIN(fld_startdate) FROM itc_class_triad_schedulegriddet WHERE fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_flag='1'");

       		$enddate=$ObjDB->SelectSingleValue("SELECT MAX(fld_enddate) FROM itc_class_triad_schedulegriddet WHERE fld_stageid='".$stageid."' and fld_schedule_id='".$sid."' and fld_flag='1'");
	   
	  		 $ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$enddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_id='".$stageid."' AND fld_schedule_id='".$sid."'");
		}
		
		if($rottype=="move")
		{
		$qrystage=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_stagetype,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_id>'".$stageid."' AND fld_schedule_id='".$sid."' AND fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_flag='1' order by fld_id ASC");
							
							if($qrystage->num_rows>0)
							{
							$z=0;
							$rotation=$count;
							while($rowstage=$qrystage->fetch_assoc())
							{
								extract($rowstage);
								
								if($z==0)
								{
									$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
								}
								else
								{
									$startdate="";
									$startdate=date("Y-m-d",strtotime($tempenddate. "+1 weekdays"));
									$tempenddate="";
								}
								
								if($fld_stagetype==1)
								{
									if($fld_stagevalue==1)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==2)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+4 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==3)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==4)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
										$tempenddate=$enddate;
									}
									else if($fld_stagevalue==5)
									{
										$enddate=date("Y-m-d",strtotime($startdate. "+9 weekdays"));
										$tempenddate=$enddate;
									}
								}
								else if($fld_stagetype==2)
								{
									$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
									$tempenddate=$enddate;
								}
								else if($fld_stagetype==3)
								{
									$days=($fld_numberofrotation*7)-1;
									
									$getrotation=$ObjDB->NonQuery("SELECT fld_rotation FROM itc_class_triad_schedulegriddet WHERE fld_stageid='".$fld_id."' AND fld_schedule_id='".$sid."' AND fld_flag='1' GROUP BY fld_rotation");
									
									if($getrotation->num_rows>0)
									{
										$start=0;
										$startdategriddet='';
										$enddategriddet='';
										$tempenddategriddet='';
										while($rowrot=$getrotation->fetch_assoc())
										{
											extract($rowrot);
											if($startdategriddet=='')
											{
												$startdategriddet=$startdate;
												$enddategriddet=date("Y-m-d",strtotime($startdategriddet. "+6 weekdays"));
												$tempenddategriddete=$enddategriddet;
												
											}
											else
											{
												$startdategriddet=date("Y-m-d",strtotime($tempenddategriddete. "+1 weekdays"));
												$enddategriddet=date("Y-m-d",strtotime($startdategriddet. "+6 weekdays"));
												$tempenddategriddete=$enddategriddet;
												
											}
											
											$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet set fld_startdate='".$startdategriddet."',fld_enddate='".$tempenddategriddete."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$fld_id."' and fld_schedule_id='".$sid."' and fld_rotation='".$fld_rotation."'");
											
											
											$ObjDB->NonQuery("UPDATE itc_class_triad_stagerotmapping set fld_startdate='".$startdategriddet."',fld_enddate='".$tempenddategriddete."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' where fld_stageid='".$fld_id."' and fld_schedule_id='".$sid."' and fld_rotation='".$fld_rotation."'");
											
										}
									}
									
									$enddate=date("Y-m-d",strtotime($startdate. "+".$days." weekdays"));
									$tempenddate=$enddate;
								}
								
								

								$ObjDB->NonQuery("UPDATE itc_class_triad_schedule_insstagemap set fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$fld_id."' AND fld_schedule_id='".$sid."'");
								
								if($fld_stagetype==2)
								{
									$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$tempenddate."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='0' AND fld_stageid='".$fld_id."'");
								}
							
							$z++;	
								
							}
				}
		}
						  
						  $triadenddate=$ObjDB->SelectSingleValue("SELECT MAX(fld_enddate) FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
						$ObjDB->NonQuery("UPDATE itc_class_triad_schedulemaster SET fld_triadtableflg=1,fld_enddate='".$triadenddate."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$sid."'");
		
	}
}

if($oper=="checkrotdate" and $oper!="") 
{
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
	$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
	$rotation = isset($_REQUEST['rotation']) ? $_REQUEST['rotation'] : '';
	$edate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	$stageid = isset($_REQUEST['stageid']) ? $_REQUEST['stageid'] : '';
	
	if($type=="rotation")
	{
	$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_rotation_scheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
		$prevrotation=$rotation-1;
		
		$prerotstartdate=$ObjDB->SelectSingleValue("SELECT fld_startdate from itc_class_rotation_scheduledate where fld_schedule_id='".$sid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
		
		$condition="false";
		
		if($rotation==$rotval)
		{
			$condition="true";
		}
		else if(strtotime($date)>=strtotime($prerotstartdate))
		{
			$condition="true";
		}
		
		if($condition=="true")
		{
			echo "success";
		}
		else
		{
			$rotation=$rotation-1;
			$prevrotation=$prevrotation-1;
			
			echo "rotation".$rotation. " cannot begin before rotation".$prevrotation;
		}
	}
        else if($type=="exprotation")
	{
	$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_rotation_expscheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
		$prevrotation=$rotation-1;
		
		$prerotstartdate=$ObjDB->SelectSingleValue("SELECT fld_startdate from itc_class_rotation_expscheduledate where fld_schedule_id='".$sid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
		
		$condition="false";
		
		if($rotation==$rotval)
		{
			$condition="true";
		}
		else if(strtotime($date)>=strtotime($prerotstartdate))
		{
			$condition="true";
		}
		
		if($condition=="true")
		{
			echo "success";
		}
		else
		{
			$rotation=$rotation-1;
			$prevrotation=$prevrotation-1;
			
			echo "rotation".$rotation. " cannot begin before rotation".$prevrotation;
		}
	}
        else if($type=="modexprotation")
	{
	$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_rotation_modexpscheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
		$prevrotation=$rotation-1;
		
		$prerotstartdate=$ObjDB->SelectSingleValue("SELECT fld_startdate from itc_class_rotation_modexpscheduledate where fld_schedule_id='".$sid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
		
		$condition="false";
		
		if($rotation==$rotval)
		{
			$condition="true";
		}
		else if(strtotime($date)>=strtotime($prerotstartdate))
		{
			$condition="true";
		}
		
		if($condition=="true")
		{
			echo "success";
		}
		else
		{
			$rotation=$rotation-1;
			$prevrotation=$prevrotation-1;
			
			echo "rotation".$rotation. " cannot begin before rotation".$prevrotation;
		}
	}
        else if($type=="missionrot")
	{
	$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
		$prevrotation=$rotation-1;
		
		$prerotstartdate=$ObjDB->SelectSingleValue("SELECT fld_startdate from itc_class_rotation_missionscheduledate where fld_schedule_id='".$sid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
		
		$condition="false";
		
		if($rotation==$rotval)
		{
			$condition="true";
		}
		else if(strtotime($date)>=strtotime($prerotstartdate))
		{
			$condition="true";
		}
		
		if($condition=="true")
		{
			echo "success";
		}
		else
		{
			$rotation=$rotation-1;
			$prevrotation=$prevrotation-1;
			
			echo "rotation".$rotation. " cannot begin before rotation".$prevrotation;
		}
	}
	else if($type=="dyad")
	{
		if($rotation=='')
		{
			$stageiddec=$stageid-1;
			$enddate='';
			
			$qrygriddet=$ObjDB->QueryObject("SELECT MAX(fld_startdate) AS startdate,MAX(fld_rotation) AS rotation  FROM itc_class_dyad_schedulegriddet WHERE fld_schedule_id='".$sid."' and fld_stageid='".$stageiddec."' and fld_flag='1'");
			
			if($qrygriddet->num_rows>0)
			{
				$row=$qrygriddet->fetch_assoc();
				extract($row);
				
				if(strtotime($date)>=strtotime($startdate))
				{
					echo "success";
				}
				else
				{
					echo "Teacher led activity cannot begin before Rotation ". $rotation;
				}
			}
			else
			{
				echo "success";
			}
			
		}
		else if($rotation=='0')
		{
			$stageiddec=$stageid-1;
			$stageenddate='';
			
			$stageenddate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' and fld_id='".$stageiddec."' and fld_flag='1' and fld_enddate<>'0000-00-00'");
			
			if($stageenddate!='')
			{
				if(strtotime($date)>=strtotime($stageenddate))
				{
					echo "success";
				}
				else
				{
					echo "Orientation cannot begin before Teacher led activity";
				}
			}
			else
			{
				echo "success";
			}
		}
		else
		{
			$stageiddec=$stageid-1;
			
			$stageenddate='';
			
			$stageenddate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' and fld_id<='".$stageiddec."' and fld_flag='1' and fld_enddate<>'0000-00-00' ORDER BY fld_id DESC LIMIT 0,1");
			
				if($stageenddate!='')
				{
					if(strtotime($date)>=strtotime($stageenddate))
					{
						$msg="success";
					}
					else
					{
						$msg="Rotation ".$rotation ." cannot begin before Teacher led activity";
					}
				}
				
					$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_dyad_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
					
					$prevrotation=$rotation-1;
					
					if($prevrotation==0)
					{
						$prerotstartdate=$ObjDB->SelectSingleValue("select fld_startdate from itc_class_dyad_schedulegriddet where fld_schedule_id='".$sid."' and fld_stageid='".$stageiddec."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
					}
					else
					{
						$prerotstartdate=$ObjDB->SelectSingleValue("select fld_startdate from itc_class_dyad_schedulegriddet where fld_schedule_id='".$sid."' and fld_stageid='".$stageid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
						
					}
					
					$condition="false";
					
					if($prerotstartdate!='')
					{
						if($rotation==$rotval)
						{
							$condition="true";
						}
						else if(strtotime($date)>=strtotime($prerotstartdate))
						{
							
							$condition="true";
						}
						else if($prevrotation==0)
						{
							if(strtotime($date)>=strtotime($prerotstartdate))
							{
								$condition="true";
							}
							else
							{
								$condition="false";
							}
						}
						
						if($condition=="true")
						{
							$msg="success";
						}
						else
						{
							$rotation=$rotation;
							$prevrotation=$prevrotation;
							
							if($prevrotation=='0')
							{
								 $msg="rotation".$rotation. " cannot begin before Orientation";
							}
							else
							{
							
								$msg="rotation".$rotation. " cannot begin before rotation".$prevrotation; 
							}
						}
					}
					
					echo $msg;
			
		}
	}
	else
	{
		if($rotation=='')
		{
			$stageiddec=$stageid-1;
			$enddate='';
			
			$qrygriddet=$ObjDB->QueryObject("SELECT MAX(fld_startdate) AS startdate,MAX(fld_rotation) AS rotation  FROM itc_class_triad_schedulegriddet WHERE fld_schedule_id='".$sid."' and fld_stageid='".$stageiddec."' and fld_flag='1'");
			
			if($qrygriddet->num_rows>0)
			{
				$row=$qrygriddet->fetch_assoc();
				extract($row);
				
				
				if(strtotime($date)>=strtotime($startdate))
				{
					echo "success";
				}
				else
				{
					echo "Teacher led activity cannot begin before Rotation ".$rotation;
				}
			}
			else
			{
				echo "success";
			}
			
		}
		else if($rotation=='0')
		{
			$stageiddec=$stageid-1;
			$stageenddate='';
			
			$stageenddate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' and fld_id='".$stageiddec."' and fld_flag='1' and fld_enddate<>'0000-00-00'");
			
			if($stageenddate!='')
			{
				if(strtotime($date)>=strtotime($stageenddate))
				{
					echo "success";
				}
				else
				{
					echo "Orientation cannot begin before Teacher led activity";
				}
			}
			else
			{
				echo "success";
			}
		}
		else
		{
			
			$stageiddec=$stageid-1;
			$msg='';
			
			$stageenddate='';
			
			$stageenddate=$ObjDB->SelectSingleValue("SELECT fld_startdate FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$sid."' and fld_id<='".$stageiddec."' and fld_flag='1' and fld_enddate<>'0000-00-00' ORDER BY fld_id DESC LIMIT 0,1");
			
				if($stageenddate!='')
				{
					if(strtotime($date)>=strtotime($stageenddate))
					{
						$msg="success";
					}
					else
					{
						$msg="Rotation ".$rotation ." cannot begin before Teacher led activity";
					}
				}
				
					$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_triad_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
					
					$prevrotation=$rotation-1;
					
					if($prevrotation==0)
					{
						$prerotstartdate=$ObjDB->SelectSingleValue("select fld_startdate from itc_class_triad_schedulegriddet where fld_schedule_id='".$sid."' and fld_stageid='".$stageiddec."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
					}
					else
					{
						$prerotstartdate=$ObjDB->SelectSingleValue("select fld_startdate from itc_class_triad_schedulegriddet where fld_schedule_id='".$sid."' and fld_stageid='".$stageid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
						
					}
					
					
					
					$condition="false";
					
					if($prerotstartdate!='')
					{
						if($rotation==$rotval)
						{
							$condition="true";
						}
						else if(strtotime($date)>=strtotime($prerotstartdate))
						{
							
							$condition="true";
						}
						else if($prevrotation==0)
						{
							if(strtotime($date)>=strtotime($prerotstartdate))
							{
								$condition="true";
							}
							else
							{
								$condition="false";
							}
						}
						
						if($condition=="true")
						{
							$msg="success";
						}
						else
						{
							$rotation=$rotation;
							$prevrotation=$prevrotation;
							
							if($prevrotation=='0')
							{
								 $msg="rotation".$rotation. " cannot begin before Orientation";
							}
							else
							{
							
								$msg="rotation".$rotation. " cannot begin before rotation".$prevrotation;
							}
						}
					}
					
					echo $msg;
			
		}
	}
}


if($oper == "loadextendcontent" and $oper != ""){		
	$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';
	$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '';
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
	$sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
			
	?>
    <div class='span10 offset1'>
        <table class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Module name</th>
                    <th class='centerText'>Extend Content</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
				if($moduletype==2){
					$modulename = $ObjDB->SelectSingleValue("SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id='".$moduleid."'");
				}
				else{								
					$modulename = $ObjDB->SelectSingleValue("SELECT fld_module_name FROM itc_module_master WHERE fld_id='".$moduleid."'");
				}
				
				$texname = "Select Extend Content";
				
						if($moduletype==1)
						{
							$tablename="itc_extendtext_master";
						}
						else if($moduletype==2)
						{
							$tablename="itc_extendtextmath_master";
						}
						else
						{
							$tablename="itc_extendtextquest_master";
						}
						
						$selectext=$ObjDB->QueryObject("SELECT b.fld_ext_id AS texid,a.fld_extend_text as texname FROM ".$tablename." AS a 
									 LEFT JOIN itc_class_indassesment_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
									 WHERE b.fld_schedule_id='".$sid."' AND b.fld_module_id='".$moduleid."' AND b.fld_schedule_type='".$moduletype."' AND b.fld_active='1' AND a.fld_delstatus='0'");
									 
										 
						
						$getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS exname FROM ".$tablename." 
														  WHERE fld_module_id='".$moduleid."' AND fld_school_id='".$schoolid."' AND fld_delstatus='0'
														  UNION ALL
														  SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM ".$tablename." AS a 
														  LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
														  b.fld_license_id='".$licenseid."' AND b.fld_module_id='".$moduleid."' AND b.fld_type='".$moduletype."' 
														  AND b.fld_active='1' AND a.fld_delstatus='0'");
														  
														  
														  
						if($selectext->num_rows>0){
							$res = $selectext->fetch_assoc();
							extract($res);
						}
														 
						
					if($getcontent->num_rows>0)
					{
						$count++;
					?>
				<tr>
					<td><?php echo $modulename; ?></td>
					<td>									
						<div id="clspass">   
							<dl class='field row'>
								<div class="selectbox">
									<input type="hidden" name="exid" id="exid" value="<?php echo $texid;?>">
									<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
										<span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
										<b class="caret1"></b>
									</a>
									<div class="selectbox-options">
										<input type="text" class="selectbox-filter" placeholder="Search Class">
										<ul role="options" style="width:100%">
										   <?php 
												while($res = $getcontent->fetch_assoc()){
													extract($res);?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $exid;?>"><?php echo $exname; ?></a></li>
													<?php
												}?>      
										</ul>
									</div>
								</div> 
							</dl>
						</div>
					</td>
				</tr>
				<?php 
				}
			else
			{
			?>
			<tr>
				<td colspan="2">
					No records found
				</td>
			</tr>
		 <?php
			}
			?>
                               
            </tbody>
        </table>
    </div>
    <?php 
}

/* Expedition start */

/*   Expedition */
if($oper=="expeditionloadcontent" and $oper!='')
{
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$expid=0;
	 
	if($scheduleid!='0')
	{
		$qryschexp=$ObjDB->QueryObject("SELECT a.fld_id AS expid, a.fld_exp_name AS expname FROM itc_exp_master AS a
		  								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id 
          								WHERE b.fld_id='".$scheduleid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'");
		if($qryschexp->num_rows>0)
		{
			$rowsch=$qryschexp->fetch_assoc();
			extract($rowsch);
		}
	}
	
	
	$qryexp=$ObjDB->QueryObject("SELECT a.fld_id as expid, a.fld_exp_name as expname FROM itc_exp_master AS a
								 LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id=b.fld_exp_id 
								 WHERE b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'
								 GROUP BY expid ORDER BY expname");
	?>
								 
	<div class='row rowspacer'>
         <div class='six columns'>
             <input type="hidden" id="scrollhid4" value="0"/>
            Select Title<span class="fldreq">*</span>
             <dl class='field row'>   
                <dt class='dropdown'>   
                    <div class="selectbox">
                        <input type="hidden" name="expid" id="expid" value="<?php echo $expid;?>" onchange="fn_showexpeditionsetting(<?php echo $licenseid;?>);"/>
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                            <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php if($scheduleid!=0){ echo $expname; } else{ ?>Select Expedition  <?php } ?></span>
                            <b class="caret1"></b>
                        </a>                       
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Expedition">
                            <ul role="options">
                                    <?php
										if($qryexp->num_rows>0)
                                        {
                                            while($row=$qryexp->fetch_assoc())
                                            {
                                                extract($row);
                                    ?>
                                         <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>"><?php echo $expname;?></a></li>
                                    <?php
                                            }
                                        }
                                    ?>
                            </ul>
                        </div>
                    </div>
                </dt>                                       
            </dl>                                       
        </div>                        
	</div>
    
    <div id="expsetting" class='row rowspacer'>
    </div>
         
<?php    	
}
/* Mission */
if($oper=="missionloadcontent" and $oper!='')
{
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$missionid=0;
	
	if($scheduleid!='0')
	{
		$qryschmis=$ObjDB->QueryObject("SELECT a.fld_id AS missionid, a.fld_mis_name AS missionname FROM itc_mission_master AS a
                                                        LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id = b.fld_mis_id
                                                        WHERE b.fld_id ='".$scheduleid."' AND b.fld_delstatus = '0' AND a.fld_flag = '1' AND a.fld_delstatus = '0'");
		if($qryschmis->num_rows>0)
		{
			$rowsch=$qryschmis->fetch_assoc();
			extract($rowsch);
		}
	}
	
	$qrymis=$ObjDB->QueryObject("SELECT a.fld_id as missionid, a.fld_mis_name as missionname FROM itc_mission_master AS a
                                            LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_mis_id
                                            WHERE b.fld_license_id ='".$licenseid."' AND b.fld_delstatus = '0' AND a.fld_flag = '1' AND a.fld_delstatus = '0'
                                            GROUP BY missionid ORDER BY missionname");
	?>
								 
	<div class='row rowspacer'>
         <div class='six columns'>
            Select Title<span class="fldreq">*</span>
             <dl class='field row'>   
                <dt class='dropdown'>   
                    <div class="selectbox">
                        <input type="hidden" name="misionid" id="misionid" value="<?php echo $missionid;?>" onchange="fn_showmissionsetting(<?php echo $licenseid;?>);"/>
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                            <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php if($scheduleid!=0){ echo $missionname;} else{?>Select Mission  <?php }?></span>
                            <b class="caret1"></b>
                        </a>                       
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Mission">
                            <ul role="options">
                                    <?php
										if($qrymis->num_rows>0)
                                        {
                                            while($row=$qrymis->fetch_assoc())
                                            {
                                                extract($row);
                                    ?>
                                         <li><a tabindex="-1" href="#" data-option="<?php echo $missionid;?>"><?php echo $missionname;?></a></li>
                                    <?php
                                            }
                                        }
                                    ?>
                            </ul>
                        </div>
                    </div>
                </dt>                                       
            </dl>                                       
        </div>                        
	</div>
    
    <div id="missionsetting" class='row rowspacer'>
    </div>
         
<?php    	
}
	

if($oper=="expsetting" and $oper!='')
{
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$expid = isset($method['expid']) ? $method['expid'] : '';
        $expid1 = isset($method['expid1']) ? $method['expid1'] : '';
        $schlicenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
	$ppet='';
	$pppa='';
	$pwpa='';
	$ppsa='';
	$pwsa='';
	$exptitle=$ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$expid."' and fld_delstatus='0'");
        
        $pitscoadmins=$ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_user_master WHERE fld_profile_id='2' AND fld_delstatus='0' AND fld_activestatus='1'");	
        
        if($sessmasterprfid == 5)
	{ 	//For Teacher inv
                     
		 $qry = "SELECT fld_rub_name, fld_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins.", ".$uid.") 
					UNION SELECT fld_rub_name, fld_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' and fld_district_id = '0' and fld_school_id = '0' and fld_user_id='".$indid."'";


	}
	else if($sessmasterprfid == 7)
	{ 	//For School Admin

		$qry = "SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins.", ".$uid.") 
					UNION 
					SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' and fld_district_id = '".$sendistid."' and fld_school_id = '0' order by fld_profile_id ASC";

	}
	else
	{ 	//For Teacher

		$qry="SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' AND fld_created_by IN(".$pitscoadmins.")
                            UNION SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' 
                            and fld_district_id = '".$sendistid."' and fld_school_id = '0'
                            UNION  SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' 
                            and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
                            UNION  SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_exp_rubric_name_master WHERE fld_exp_id = '".$expid."' and fld_delstatus = '0' 
                            and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";

	}
	
?>
	<script>
	$.getScript("class/newclass/class-newclass-class.js");
	</script>
	<div class="row rowspacer"> Select Grading Rubric
		 <div>
		 	<dl class='field row' >  
				<?php
				$rubricvalues=array();
				$qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
				if($qry_for_get_all_expedition->num_rows>0)
				{
					$i=1; 
						?><table cellpadding="19px" cellspacing="19px" > <tr><?php
					while($row=$qry_for_get_all_expedition->fetch_assoc())
					{
						extract($row); 
				  		
						$chkval = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_expmis_rubricmaster 
																WHERE fld_rubric_id='".$fld_id."' AND fld_schedule_id='".$scheduleid."' 
																AND fld_delstatus='0' AND fld_schedule_type='15'");
						
						?>
						<td>
							<dt>       
								<input id="chkboxrubric_<?php echo $fld_id; ?>" type="checkbox" value="" name="chkbox" <?php if($chkval=='1' || $scheduleid=='0'){echo "checked"; } ?>  >
								<span></span>
								<?php echo $fld_rub_name; ?>
							</dt>
						</td>
						<?php
						if($i%3==0)
						{
							echo "</tr><tr>";
						}
						$i++;
					}
					?> </tr></table> <?php
				}
				?>
			</dl>
		</div>	
		
           
        </div>
        
        <div class="row rowspacer"> Select Assessment
            
        <style>
          h2.acc_trigger {
              padding: 0;
              margin: 0 0 5px 0;
              width: 100%;
              font-size: 20px;
              font-weight: normal;
              float: left;
              margin-bottom:0;
          }
          h2.acc_trigger a {
              text-decoration: none;
              display: block;
              padding: 0 0 0 15px;
          }
          </style>
          <script type="text/javascript" language="javascript">
              jQuery(document).ready(function ($) {
              //Set default open/close settings
                var divs=$('.accordion>div').hide(); //Hide/close all containers	
                $(".accordion>div:first").show();
                $(".accordion>h2>a>input:first").addClass('removeButton').removeClass('addButton');
                $(".accordion>h2>a>input:first").val('-');
                   var h2s = $(".accordion>h2").click(function () {
                if($(this).children().children('input').hasClass('addButton'))
                {
                    $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                    $(".accordion>h2>a>input").val('+');
                    $(this).children().children('input').addClass('removeButton').removeClass('addButton');
                    $(this).children().children('input').val('-');
                }
                else
                {	
                    $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                    $(".accordion>h2>a>input").val('+');
                    $(this).children().children('input').addClass('addButton').removeClass('removeButton');
                    $(this).children().children('input').val('+');	
                }
                    h2s.not(this).removeClass('active')
                    $(this).toggleClass('active')
                    divs.not($(this).next()).slideUp()
                    //var spans=$('.accordion>span').hide(); 
                    //spans.not($(this).next()).slideUp()
                    $(this).next().slideToggle()
                    return false; //Prevent the browser jump to the link anchor

                  });
              });
          </script>
            
            <div class="accordion">
                <?php
                
                    $distadminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '0'  AND fld_district_id='".$sendistid."' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '6'");
                    
                    $schladminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '$senshlid' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '7'");
                    
                    $qrytexp = $ObjDB->QueryObject("SELECT fld_exp_name as expname, fld_id as expid FROM itc_exp_master WHERE fld_id='".$expid."' and 
                                                                        fld_delstatus='0'");

                    if ($qrytexp->num_rows > 0) {
                        $a = 0;
                        $x=0;
                        $y=0;
                        $z=0;
                        while ($rowtexp = $qrytexp->fetch_assoc()) {

                            extract($rowtexp);
                            ?>
                            <h2 class="acc_trigger"><a href="#"><input type="button" class="addButton" value="+" style="margin-right: 10px;"><?php echo $expname;?></a></h2>
                            <?php

                                if ($qrytexp->num_rows > 0) {
                                    ?>
                                    <div class="acc_container">
                                    <?php
                                    // Expedition Test starts
                                        $checkexpcount1 = $ObjDB->QueryObject("select fld_id
                                                                            from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                            and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' 
                                                                                UNION ALL
                                                                            SELECT a.fld_id 
                                                                                FROM itc_test_master AS a
                                                                            LEFT JOIN
                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                            LEFT JOIN
                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'");

                                            ?>
                                        <div class='row rowspacer' style="margin-top:0px;">
                                        <div class='six columns' class="block" name="" id="" style="text-indent: 20px; margin-top:0px;" > <?php echo $expname;?></div>

                                            <?php 
                                            $checkexpcount = $checkexpcount1->num_rows>0;
                                            if($checkexpcount>0){
                                                $checkexptestprecount1 = $ObjDB->QueryObject("select fld_id
                                                                            from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                            and  fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' 
                                                                                UNION ALL
                                                                            SELECT a.fld_id 
                                                                                FROM itc_test_master AS a
                                                                            LEFT JOIN
                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                            LEFT JOIN
                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'");
                                                $checkexptestprecount = $checkexptestprecount1->num_rows>0;
                                                if($checkexptestprecount>0){
                                                    
                                                        $expretestname= "Select Pretest";
                                                        $expretestid=0;
                                                ?>
                                                <div class='three columns'> 
                                                    <?php
                                                        if($scheduleid!=0){   
                                                            
                                                            $exppretestdetail= $ObjDB->QueryObject("select a.fld_pretest as expretestid,b.fld_test_name as expretestname
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='0' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                        and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$scheduleid."' and a.fld_schtype_id='15' and b.fld_delstatus = '0'"); 
                                                            $exppretestcount = $exppretestdetail->num_rows>0; 
                                                            if($exppretestcount !=0)
                                                            {
                                                               $rowexppretest=$exppretestdetail->fetch_assoc();
                                                               extract($rowexppretest);                                                                     
                                                            }
                                                            if($expretestid==0 or $expretestid==''){
                                                                $expretestname="None";
                                                                $expretestid=0;
                                                            }
                                                            $exppreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_exp_test_id='".$expretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                        }
                                                    ?>
                                                    <dl class='field row <?php if($exppreplaycnt!=0){echo "dim";}?>'>   
                                                        <dt class='dropdown'>   
                                                            <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                <input type="hidden" name="exppre_<?php echo $expid."_0_0_0";?>" id="" value="<?php echo $expretestid;?>"/>
                                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                    <span class="selectbox-option input-medium" data-option="<?php echo $expretestid;?>" id="clearsubject"><?php  echo $expretestname; ?></span>
                                                                    <b class="caret1"></b>
                                                                </a>                       
                                                                <div class="selectbox-options" style="width:210px;" >
                                                                    <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                    <ul role="options">
                                                                            <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                            <?php

                                                                                $qrypre1= $ObjDB->QueryObject("SELECT a.fld_id AS expretestid,a.fld_test_name AS expretestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'  
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0' 
                                                                                                                UNION ALL
                                                                                                                select fld_id AS expretestid,fld_test_name AS expretestname
                                                                                                        from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                                                                and  fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY expretestname");  

                                                                                if($qrypre1->num_rows>0)
                                                                                {
                                                                                    while($rowpre1=$qrypre1->fetch_assoc())
                                                                                    {
                                                                                        extract($rowpre1);
                                                                            ?>
                                                                                 <li><a tabindex="-1" href="#" data-option="<?php echo $expretestid;?>"><?php echo $expretestname;?></a></li>

                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </dt>                                       
                                                    </dl>  
                                                </div>
                                                <?php } else{ ?> <div class='three columns'> <dl class='field row'><input type="hidden" name="exppre_<?php echo $expid."_0_0_0";?>" id="" value="0"/>  </dl></div> <?php } ?>



                                                <?php 
                                                $checkexptestpostcount1 = $ObjDB->QueryObject("select fld_id
                                                                            from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                            and  fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' 
                                                                                UNION ALL
                                                                            SELECT a.fld_id 
                                                                                FROM itc_test_master AS a
                                                                            LEFT JOIN
                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                            LEFT JOIN
                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                    AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                    and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."'
                                                                                    and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'");
                                                $checkexptestpostcount = $checkexptestpostcount1->num_rows>0;
                                                if($checkexptestpostcount>0){
                                                   
                                                        $expposttestname="Select Posttest";
                                                        $expposttestid=0;
                                                    
                                                ?>
                                                <div class='three columns'> 
                                                    <?php
                                                        if($scheduleid!=0){                                                                
                                                            $expposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as expposttestid,b.fld_test_name as expposttestname
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='0' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                        and a.fld_texpid ='".$expid."' and a.fld_sch_id= '".$scheduleid."' and a.fld_schtype_id='15' and b.fld_delstatus = '0'"); 
                                                            $expposttestcount = $expposttestdetail->num_rows>0; 
                                                            if($expposttestcount !=0)
                                                            {
                                                               $rowexpposttest=$expposttestdetail->fetch_assoc();
                                                               extract($rowexpposttest);                                                                     
                                                            }
                                                            if($expposttestid==0 or $expposttestid==''){
                                                                $expposttestname="None";
                                                                $expposttestid=0;
                                                            }
                                                            $exppostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_exp_test_id='".$expposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                        }                                                         
                                                    ?>
                                                    <dl class='field row <?php if($exppostplaycnt!=0){echo "dim";}?>'>   
                                                        <dt class='dropdown'>   
                                                            <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                <input type="hidden" name="" id="exppost_<?php echo $a;?>" value="<?php echo $expposttestid;?>"/>
                                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                    <span class="selectbox-option input-medium" data-option="<?php echo $expposttestid;?>" id="clearsubject"><?php  echo $expposttestname;?></span>
                                                                    <b class="caret1"></b>
                                                                </a>                       
                                                                <div class="selectbox-options" style="width:210px;">
                                                                    <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                    <ul role="options">
                                                                        <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                            <?php

                                                                                $qrpost1= $ObjDB->QueryObject("SELECT a.fld_id AS expposttestid,a.fld_test_name AS expposttestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid = '0' and b.fld_license_id='".$schlicenseid."' 
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0' 
                                                                                                                UNION ALL
                                                                                                                select fld_id AS expposttestid,fld_test_name AS expposttestname
                                                                                from itc_test_master where fld_destid = '0' and fld_taskid=0 and fld_resid=0
                                                                                                                and  fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY expposttestname");  

                                                                                if($qrpost1->num_rows>0)
                                                                                {
                                                                                    while($rowpost1=$qrpost1->fetch_assoc())
                                                                                    {
                                                                                        extract($rowpost1);
                                                                            ?>
                                                                                 <li><a tabindex="-1" href="#" data-option="<?php echo $expposttestid;?>"><?php echo $expposttestname;?></a></li>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </dt>                                       
                                                    </dl>
                                                </div>
                                                 <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> <?php }
                                            }   
                                            ?> </div><?php
                                    // Expedition Test ends
                                    //Destination Test starts

                                    $qrytdest = $ObjDB->QueryObject("SELECT fld_dest_name as destname, fld_id as destid
                                                                     FROM itc_exp_destination_master WHERE fld_exp_id='".$expid."' AND fld_delstatus='0'");//limit 0,10
                                    if ($qrytdest->num_rows > 0) {
                                            $b=1;
                                            while ($rowtdest = $qrytdest->fetch_assoc()) {
                                                extract($rowtdest);
                                                $checkdestcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                FROM itc_test_master AS a
                                                                                                LEFT JOIN
                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                LEFT JOIN
                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' 
                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and b.fld_license_id='".$schlicenseid."' and a.fld_delstatus = '0'
                                                                                                UNION ALL
                                                                                                select fld_id
                                                                                                from itc_test_master where fld_destid !='0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");

                                            ?>
                                        <div class='row rowspacer' style="margin-top:0px;">
                                                <div class='six columns' class="block" style="text-indent: 25px; margin-top:0px;" > <?php echo "D"."$b".".".$destname;?></div>

                                            <?php 
                                                $checkdestcount = $checkdestcount1->num_rows>0;
                                                if($checkdestcount>0){
                                                $checkdesttestprecount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                FROM itc_test_master AS a
                                                                                                LEFT JOIN
                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                LEFT JOIN
                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                UNION ALL
                                                                                                select fld_id
                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checkdesttestprecount = $checkdesttestprecount1->num_rows>0;
                                                if($checkdesttestprecount>0){
                                                        $destpretestname ="Select Pretest";
                                                        $destpretestid=0;
                                                   
                                                ?>
                                                <div class='three columns'> 
                                                    <?php
                                                        if($scheduleid!=0){                                                                
                                                            $destpretestdetail= $ObjDB->QueryObject("select a.fld_pretest as destpretestid,b.fld_test_name as destpretestname
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                        and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='15' and b.fld_delstatus='0'"); 
                                                            $destpretestcount = $destpretestdetail->num_rows>0; 
                                                            if($destpretestcount !=0)
                                                            {
                                                               $rowdetpretest=$destpretestdetail->fetch_assoc();
                                                               extract($rowdetpretest);                                                                     
                                                            }
                                                            if($destpretestid==0 or $destpretestid==''){
                                                                $destpretestname="None";
                                                                $destpretestid=0;
                                                            }
                                                            $destpreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_dest_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_dest_test_id='".$destpretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                        }                                                         
                                                    ?>
                                                    <dl class='field row <?php if($destpreplaycnt!=0){echo "dim";}?>'>   
                                                        <dt class='dropdown'>   
                                                            <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                <input type="hidden" name="destpre_<?php echo $expid."_".$destid."_0_0";?>" id="" value="<?php echo $destpretestid;?>"/>
                                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                    <span class="selectbox-option input-medium" data-option="<?php echo $destpretestid;?>" id="clearsubject"><?php  echo $destpretestname; ?></span>
                                                                    <b class="caret1"></b>
                                                                </a>                       
                                                                <div class="selectbox-options" style="width:210px;">
                                                                    <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                    <ul role="options">
                                                                        <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                            <?php

                                                                                $qrypre2 = $ObjDB->QueryObject("SELECT a.fld_id AS destpretestid, a.fld_test_name AS destpretestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS destpretestid,fld_test_name AS destpretestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                                and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY destpretestname");

                                                                                if($qrypre2->num_rows>0)
                                                                                {
                                                                                    while($rowpre2=$qrypre2->fetch_assoc())
                                                                                    {
                                                                                        extract($rowpre2);
                                                                            ?>
                                                                                 <li><a tabindex="-1" href="#" data-option="<?php echo $destpretestid;?>"><?php echo $destpretestname;?></a></li>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </dt>                                       
                                                    </dl>  
                                                </div>
                                                <?php } else{ ?> <div class='three columns'> <dl class='field row'><input type="hidden" name="destpre_<?php echo $expid."_".$destid."_0_0";?>" id="" value="0"/>  </dl></div> <?php } ?>



                                                <?php 
                                                $checkdesttestpostcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                FROM itc_test_master AS a
                                                                                                LEFT JOIN
                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                LEFT JOIN
                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                UNION ALL
                                                                                                select fld_id
                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checkdesttestpostcount = $checkdesttestpostcount1->num_rows>0;
                                                if($checkdesttestpostcount>0){
                                                   
                                                         $destposttestname="Select Posttest";
                                                         $destposttestid=0;
                                                   
                                                ?>
                                                <div class='three columns'> 
                                                    <?php
                                                        if($scheduleid!=0){                                                                
                                                            $destposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as destposttestid,b.fld_test_name as destposttestname
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='0' and fld_tresid=0 
                                                                                                        and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='15' and b.fld_delstatus='0'"); 
                                                            $destposttestcount = $destposttestdetail->num_rows>0; 
                                                            if($destposttestcount !=0)
                                                            {
                                                               $rowdestposttest=$destposttestdetail->fetch_assoc();
                                                               extract($rowdestposttest);                                                                     
                                                            }
                                                            if($destposttestid==0 or $destposttestid==''){
                                                                $destposttestname="None";
                                                                $destposttestid=0;
                                                                
                                                            }
                                                            $destpostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_dest_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_dest_test_id='".$destposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                        }                                                         
                                                    ?>
                                                    <dl class='field row <?php if($destpostplaycnt!=0){echo "dim";}?>'>   
                                                        <dt class='dropdown'>   
                                                            <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                <input type="hidden" name="" id="destpost_<?php echo $x; ?>" value="<?php echo $destposttestid;?>"/>
                                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                    <span class="selectbox-option input-medium" data-option="<?php echo $destposttestid;?>" id="clearsubject"><?php  echo $destposttestname;?></span>
                                                                    <b class="caret1"></b>
                                                                </a>                       
                                                                <div class="selectbox-options" style="width:210px;">
                                                                    <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                    <ul role="options">
                                                                        <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                            <?php
                                                                                $qrpost2 = $ObjDB->QueryObject("SELECT a.fld_id AS destposttestid, a.fld_test_name AS destposttestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and fld_destid != '0' and fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_taskid=0 and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS destposttestid,fld_test_name AS destposttestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid=0 and fld_resid=0
                                                                                                                and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY destposttestname");

                                                                                if($qrpost2->num_rows>0)
                                                                                {
                                                                                    while($rowpost2=$qrpost2->fetch_assoc()) 
                                                                                    {
                                                                                        extract($rowpost2);
                                                                            ?>
                                                                                 <li><a tabindex="-1" href="#" data-option="<?php echo $destposttestid;?>"><?php echo $destposttestname;?></a></li>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </dt>                                       
                                                    </dl>
                                                </div>
                                                <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> <?php } 


                                                $x++;
                                            }
                                            ?></div> <?php

                                    //Destination Test Ends 
                                    //Task Test Starts 
                                    $qryttask = $ObjDB->QueryObject("SELECT fld_task_name as taskname, fld_id as taskid
                                                                                FROM itc_exp_task_master WHERE fld_dest_id='".$destid."' AND fld_delstatus='0'");//limit 0,10
                                    if ($qryttask->num_rows > 0) {
                                        $d=1;
                                        while ($rowttask = $qryttask->fetch_assoc()) {
                                        extract($rowttask);
                                            $checktaskcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1' and b.fld_license_id='".$schlicenseid."'
                                                                                            and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' 
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0'");
                                            $checktaskcount = $checktaskcount1->num_rows>0; 
                                            ?>
                                          <div class='row rowspacer' style="margin-top:0px;">
                                                <div class='six columns' class="block" style="text-indent: 30px; margin-top:0px;"  > <?php echo "T"."$d".".".$taskname;?></div>

                                            <?php 
                                            if($checktaskcount>0){
                                                $checktasktestprecount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checktasktestprecount = $checktasktestprecount1->num_rows>0; 
                                                    
                                                if($checktasktestprecount>0){
                                                    
                                                         $taskpretestname="Select Pretest";
                                                         $taskpretestid=0;
                                                   
                                                ?>
                                                <div class='three columns'> 
                                                    <?php
                                                        if($scheduleid!=0){                                                                
                                                            $taskpretestdetail= $ObjDB->QueryObject("select a.fld_pretest as taskpretestid,b.fld_test_name as taskpretestname
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid=0 
                                                                                                        and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='15' and b.fld_delstatus='0'"); 
                                                            $taskpretestcount = $taskpretestdetail->num_rows>0; 
                                                            if($taskpretestcount !=0)
                                                            {
                                                               $rowtaskpretest=$taskpretestdetail->fetch_assoc();
                                                               extract($rowtaskpretest);                                                                     
                                                            }
                                                            if($taskpretestid==0 or $taskpretestid==''){
                                                                $taskpretestname="None";
                                                                $taskpretestid=0;
                                                            }
                                                            $taskppreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_task_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_task_test_id='".$taskpretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                        }                                                         
                                                    ?>
                                                    <dl class='field row <?php if($taskppreplaycnt!=0){echo "dim";}?>'>   
                                                        <dt class='dropdown'>   
                                                            <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                <input type="hidden" name="taskpre_<?php echo $expid."_".$destid."_".$taskid."_0";?>" id="" value="<?php echo $taskpretestid;?>"/>
                                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                    <span class="selectbox-option input-medium" data-option="<?php echo $taskpretestid;?>" id="clearsubject"><?php echo $taskpretestname;?></span>
                                                                    <b class="caret1"></b>
                                                                </a>                       
                                                                <div class="selectbox-options" style="width:210px;">
                                                                    <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                    <ul role="options">
                                                                        <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                            <?php
                                                                                $qrypre3 = $ObjDB->QueryObject("SELECT a.fld_id AS taskpretestid,a.fld_test_name AS taskpretestname 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id AS taskpretestid,fld_test_name AS taskpretestname
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '1' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY taskpretestname");

                                                                                if($qrypre3->num_rows>0)
                                                                                {
                                                                                    while($rowpre3=$qrypre3->fetch_assoc())
                                                                                    {
                                                                                        extract($rowpre3);
                                                                            ?>
                                                                                 <li><a tabindex="-1" href="#" data-option="<?php echo $taskpretestid;?>"><?php echo $taskpretestname;?></a></li>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </dt>                                       
                                                    </dl>  
                                                </div>
                                                <?php } else{ ?> <div class='three columns'> <dl class='field row'><input type="hidden" name="taskpre_<?php echo $expid."_".$destid."_".$taskid."_0";?>" id="" value="0"/>  </dl></div> <?php } ?>


                                                <?php 
                                                $checktasktestpostcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                $checktasktestpostcount = $checktasktestpostcount1->num_rows>0;
                                                    
                                                if($checktasktestpostcount>0){
                                                    
                                                         $taskposttestname="Select Posttest";
                                                         $taskposttestid=0;
                                                    
                                                ?>
                                                <div class='three columns'>
                                                    <?php
                                                        if($scheduleid!=0){                                                                
                                                            $taskposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as taskposttestid,b.fld_test_name as taskposttestname
                                                                                                        from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid=0 
                                                                                                        and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='15' and b.fld_delstatus='0'"); 
                                                            $taskposttestcount = $taskposttestdetail->num_rows>0; 
                                                            if($taskposttestcount !=0)
                                                            {
                                                               $rowtaskposttest=$taskposttestdetail->fetch_assoc();
                                                               extract($rowtaskposttest);                                                                     
                                                            }
                                                            if($taskposttestid==0 or $taskposttestid==''){
                                                                $taskposttestname="None";
                                                                $taskposttestid=0;
                                                            }
                                                            $taskpostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_task_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_task_test_id='".$taskposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                        }                                                         
                                                    ?>
                                                    <dl class='field row <?php if($taskpostplaycnt!=0){echo "dim";}?>'>   
                                                        <dt class='dropdown'>   
                                                            <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                <input type="hidden" name="" id="taskpost_<?php echo $y; ?>" value="<?php echo $taskposttestid;?>"/>
                                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                    <span class="selectbox-option input-medium" data-option="<?php echo $taskposttestid;?>" id="clearsubject"><?php echo $taskposttestname;?></span>
                                                                    <b class="caret1"></b>
                                                                </a>                       
                                                                <div class="selectbox-options" style="width:210px;">
                                                                    <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                    <ul role="options">
                                                                        <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                            <?php
                                                                                $qrpost3 = $ObjDB->QueryObject("SELECT a.fld_id AS taskposttestid,a.fld_test_name AS taskposttestname
                                                                                            FROM itc_test_master AS a
                                                                                            LEFT JOIN
                                                                                            `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                            LEFT JOIN
                                                                                            `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                            WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                            AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                            and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and a.fld_destid='".$destid."' and b.fld_license_id='".$schlicenseid."'
                                                                                            and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and a.fld_resid=0 and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                            UNION ALL
                                                                                            select fld_id AS taskposttestid,fld_test_name AS taskposttestname
                                                                                            from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' and fld_resid=0
                                                                                            and fld_prepostid = '2' and fld_ass_type='1' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                            and fld_expt='".$expid."' and fld_delstatus = '0' ORDER BY taskposttestname");

                                                                                if($qrpost3->num_rows>0)
                                                                                {
                                                                                    while($rowpost3=$qrpost3->fetch_assoc()) 
                                                                                    {
                                                                                        extract($rowpost3);
                                                                            ?>
                                                                                 <li><a tabindex="-1" href="#" data-option="<?php echo $taskposttestid;?>"><?php echo $taskposttestname;?></a></li>
                                                                            <?php
                                                                                    }
                                                                                }
                                                                            ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </dt>                                       
                                                    </dl>
                                                </div>
                                                <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> 

                                                    <?php } 
                                                    $y++;
                                            }
                                            ?>  </div> <?php
                                            //Task test ends
                                                    //Res test starts
                                                        $qrytres = $ObjDB->QueryObject("SELECT fld_res_name as resname, fld_id as resid
                                                                                                    FROM itc_exp_resource_master WHERE fld_task_id='".$taskid."' AND fld_delstatus='0'");//limit 0,10
                                                        if ($qrytres->num_rows > 0) {
                                                            $e=1;
                                                            while ($rowtres = $qrytres->fetch_assoc()) {
                                                                extract($rowtres);
                                                                $checkrescount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and (a.fld_prepostid = '2' or a.fld_prepostid = '1') and a.fld_ass_type='1' and a.fld_destid != '0' 
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' and b.fld_license_id='".$schlicenseid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and (fld_prepostid = '2' or fld_prepostid = '1') and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                                $checkrescount=$checkrescount1->num_rows>0;
                                                                ?>
                                                                <div class='row rowspacer' style="margin-top:0px;">
                                                                    <div class='six columns' class="block" style="text-indent: 35px; margin-top:0px;" > <?php echo "R"."$e".".".$resname;?></div>

                                                                <?php
                                                                if($checkrescount>0){
                                                                    $checkrestestprecount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '1' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                                    $checkrestestprecount=$checkrestestprecount1->num_rows>0;
                                                                        
                                                                    if($checkrestestprecount>0){
                                                                        
                                                                            $respretestname="Select Pretest";
                                                                            $respretestid=0;
                                                                       
                                                                    ?>
                                                                    <div class='three columns'> 
                                                                        <?php
                                                                            if($scheduleid!=0){                                                                
                                                                                
                                                                                $respretestdetail= $ObjDB->QueryObject("select a.fld_pretest as respretestid,b.fld_test_name as respretestname
                                                                                                                            from itc_exp_ass as a left join itc_test_master as b on a.fld_pretest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid='".$resid."' 
                                                                                                                            and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='15' and b.fld_delstatus='0'"); 
                                                                                $respretestcount = $respretestdetail->num_rows>0; 
                                                                                if($respretestcount !=0)
                                                                                {
                                                                                   $rowrespretest=$respretestdetail->fetch_assoc();
                                                                                   extract($rowrespretest);                                                                     
                                                                                }
                                                                                if($respretestid==0 or $respretestid==''){
                                                                                    $respretestname="None";
                                                                                    $respretestid=0;
                                                                                }
                                                                                $respreplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_res_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_res_id='".$resid."' and fld_res_test_id='".$respretestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                                            }                                                         
                                                                        ?>
                                                                        <dl class='field row <?php if($respreplaycnt!=0){ echo "dim"; } ?>'>   
                                                                            <dt class='dropdown'>   
                                                                                <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                                    <input type="hidden" name="respre_<?php echo $expid."_".$destid."_".$taskid."_".$resid;?>" id="" value="<?php echo $respretestid;?>"/>
                                                                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                                        <span class="selectbox-option input-medium" data-option="<?php echo $respretestid;?>" id="clearsubject"><?php echo $respretestname;?></span>
                                                                                        <b class="caret1"></b>
                                                                                    </a>                       
                                                                                    <div class="selectbox-options" style="width:210px;">
                                                                                        <input type="text" class="selectbox-filter" placeholder="Search Pretest">
                                                                                        <ul role="options">
                                                                                            <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                                                <?php
                                                                                                    $qrypre4 = $ObjDB->QueryObject("SELECT a.fld_id AS respretestid,a.fld_test_name AS respretestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '1' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS respretestid,fld_test_name AS respretestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '1' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' order by respretestname");

                                                                                                    if($qrypre4->num_rows>0)
                                                                                                    {
                                                                                                        while($rowpre4=$qrypre4->fetch_assoc())
                                                                                                        {
                                                                                                            extract($rowpre4);
                                                                                                ?>
                                                                                                     <li><a tabindex="-1" href="#" data-option="<?php echo $respretestid;?>"><?php echo $respretestname;?></a></li> 
                                                                                                <?php
                                                                                                        }
                                                                                                    }
                                                                                                ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </dt>                                       
                                                                        </dl>  
                                                                    </div>
                                                                    <?php } else{ ?> <div class='three columns'> <dl class='field row'> <input type="hidden" name="respre_<?php echo $expid."_".$destid."_".$taskid."_".$resid;?>" id="" value="0"/> </dl></div> <?php } ?>


                                                                    <?php                                                                        
                                                                    $checkrestestpostcount1 = $ObjDB->QueryObject("SELECT a.fld_id 
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '2' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0'");
                                                                    $checkrestestpostcount=$checkrestestpostcount1->num_rows>0;
                                                                        
                                                                    if($checkrestestpostcount>0){
                                                                        
                                                                            $resposttestname="Select Posttest";
                                                                            $resposttestid=0;
                                                                        
                                                                    ?>
                                                                    <div class='three columns'>
                                                                        <?php
                                                                            if($scheduleid!=0){                                                                                      
                                                                                $resposttestdetail= $ObjDB->QueryObject("select a.fld_posttest as resposttestid,b.fld_test_name as resposttestname
                                                                                                                            from itc_exp_ass as a left join itc_test_master as b on a.fld_posttest=b.fld_id where fld_tdestid='".$destid."' and fld_ttaskid='".$taskid."' and fld_tresid='".$resid."' 
                                                                                                                            and fld_texpid ='".$expid."' and fld_sch_id= '".$scheduleid."' and fld_schtype_id='15' and b.fld_delstatus='0'"); 
                                                                                $resposttestcount = $resposttestdetail->num_rows>0; 
                                                                                if($resposttestcount !=0)
                                                                                {
                                                                                   $rowresposttest=$resposttestdetail->fetch_assoc();
                                                                                   extract($rowresposttest);                                                                     
                                                                                }
                                                                                if($resposttestid==0 or $resposttestid==''){
                                                                                    $resposttestname="None";
                                                                                    $resposttestid=0;
                                                                                }
                                                                                $respostplaycnt = $ObjDB->SelectSingleValueInt("select count(fld_id)
                                                                                                    from itc_exp_res_testplay_track 
                                                                                                    where fld_exp_id='".$expid."' and fld_dest_id='".$destid."' and fld_task_id='".$taskid."' and fld_res_id='".$resid."' and fld_res_test_id='".$resposttestid."' and fld_schedule_id= '".$scheduleid."' and fld_schedule_type='15'");
                                                                            }                                                         
                                                                        ?>
                                                                        <dl class='field row <?php if($respostplaycnt!=0){echo "dim";}?>'>   
                                                                            <dt class='dropdown'>   
                                                                                <div class="selectbox" style="width:200px;font-size: 14px; height: 20px; line-height: 20px;">
                                                                                    <input type="hidden" name="" id="respost_<?php echo $z; ?>" value="<?php echo $resposttestid;?>"/>
                                                                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" style="font-size: 14px; height: 20px; line-height: 20px;">
                                                                                        <span class="selectbox-option input-medium" data-option="<?php echo $resposttestid;?>" id="clearsubject"><?php  echo $resposttestname;?></span>
                                                                                        <b class="caret1"></b>
                                                                                    </a>                       
                                                                                    <div class="selectbox-options" style="width:210px;">
                                                                                        <input type="text" class="selectbox-filter" placeholder="Search Posttest">
                                                                                        <ul role="options">
                                                                                            <li><a tabindex="-1" href="#" data-option="0"><?php echo "None";?></a></li>
                                                                                                <?php
                                                                                                    $qrpost4 = $ObjDB->QueryObject("SELECT a.fld_id AS resposttestid,a.fld_test_name AS resposttestname
                                                                                                                FROM itc_test_master AS a
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                                LEFT JOIN
                                                                                                                `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                                WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                and a.fld_prepostid = '2' and a.fld_ass_type='1' and a.fld_destid != '0' and b.fld_license_id='".$schlicenseid."'
                                                                                                                and a.fld_destid='".$destid."' and a.fld_taskid !='0' and a.fld_taskid='".$taskid."' 
                                                                                                                and a.fld_resid !='0' and a.fld_resid='".$resid."' and fld_profile_id='2' and a.fld_expt='".$expid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                                select fld_id AS resposttestid,fld_test_name AS resposttestname
                                                                                                                from itc_test_master where fld_destid != '0' and fld_destid='".$destid."' and fld_taskid !='0' and fld_taskid='".$taskid."' 
                                                                                                                and fld_resid !='0' and fld_resid='".$resid."' and fld_prepostid = '2' and fld_ass_type='1' 
                                                                                                                and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                                and fld_expt='".$expid."' and fld_delstatus = '0' order by resposttestname");

                                                                                                    if($qrpost4->num_rows>0)
                                                                                                    {
                                                                                                        while($rowpost4=$qrpost4->fetch_assoc()) 
                                                                                                        {
                                                                                                            extract($rowpost4);
                                                                                                ?>
                                                                                                     <li><a tabindex="-1" href="#" data-option="<?php echo $resposttestid;?>"><?php echo $resposttestname;?></a></li>
                                                                                                <?php
                                                                                                        }
                                                                                                    }
                                                                                                ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </dt>                                       
                                                                        </dl>
                                                                    </div>
                                                                    <?php } else{ ?> <div class='three columns'> <dl class='field row'>  </dl></div> <?php } 

                                                                    $z++;
                                                                }
                                                                ?> </div><?php
                                                            $e++;
                                                            }// res while
                                                        }// res if
                                                        $d++;
                                                    } //task While
                                                }//task if
                                                $b++;
                                            } // Dest while

                                        }// Dest if
                                        ?>
                                    </div>
                                    <?php

                                    //$b++;
                                }
                                 $a++;
                        }
                   
                }?>
                
            </div>
  
        </div>
        
         <div class='row rowspacer'>
                <div id="extenddiv" style="float:left;"> <!-- extend content -->
                   Materials list of the expedition in your class
                </div>
                <div style="float:right;">
                    <input type="button" id="extendbtn" class="darkButton" value="Materials List" onClick="fn_extndcontforexpedn(<?php echo $scheduleid;?>)" /> 
                </div>
        </div>
     <!-- Extend Content added start line created by chandru  -->   
        <div class='row rowspacer'>
                <div id="extenddiv1" style="float:left;"> <!-- extend content -->
                    Extend Content List in your assignment
                </div>
                <div style="float:right;">
                    <input type="button" id="extendbtn" class="darkButton" value="Extend Content List" onClick="fn_extndcontforexpedcont(<?php echo $scheduleid;?>)" /> 
                </div>
        </div>
     <!-- Extend Content added start line -->
         <script>
                <?php if($scheduleid!=0){?>                        
                        setTimeout('fn_extndcontforexpedn(<?php echo $scheduleid;?>,1)',3000);
                         setTimeout('fn_extndcontforexpedcont(<?php echo $scheduleid;?>,1)',3000);
                <?php }?>
        </script>
         <div id="expextendcontent" class='row rowspacer'></div>    
            <div id="expeextendcontent" class='row rowspacer'></div>    
         <div class="row rowspacer" style="margin-top:50px;">
            <div class="tLeft" style="color:#F00;"></div>
            <div class="tRight" id="modnxtstep">
              <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="Save schedule" onClick="fn_saveindassesmentexpedition(<?php echo $scheduleid; ?>);" />
            </div>
     </div>
<?php
}
/* Expendition settings END */

if($oper=="missionsetting" and $oper!='')
{
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$missionid = isset($method['missionid']) ? $method['missionid'] : '';
        $schlicenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
	$ppet='';
	$pppa='';
	$pwpa='';
	$ppsa='';
	$pwsa='';
	
	$missiontitle=$ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$missionid."' and fld_delstatus='0'");
	if($scheduleid!=0)
	{
		$qrymissionset=$ObjDB->QueryObject("SELECT fld_pointspossible, fld_mistype, fld_percentageweight FROM itc_class_mission_grade WHERE fld_schedule_id ='".$scheduleid."' AND fld_mis_id ='".$missionid."' AND fld_flag = '1'");
                
		
		
		if($qrymissionset->num_rows>0)
		{
			while($row=$qrymissionset->fetch_assoc())
			{
				extract($row);
				if($fld_missiontype==1)
				{
					$ppet=$fld_pointspossible;
				}
				else if($fld_missiontype==2)
				{
					$pppa=$fld_pointspossible;
					$pwpa=$fld_percentageweight;
				}
				else if($fld_missiontype==3)
				{
					$ppsa=$fld_pointspossible;
					$pwsa=$fld_percentageweight;
				}
				
			}
		}
	}
        
        
	if($sessmasterprfid == 5)
	{ 	//For Teacher inv
		 $qry = "SELECT fld_rub_name, fld_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' AND fld_created_by IN (2 , ".$uid.") 
					UNION SELECT fld_rub_name, fld_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' and fld_district_id = '0' and fld_school_id = '0' and fld_user_id='".$indid."'";
	}
	else if($sessmasterprfid == 7)
	{ 	//For School Admin
		$qry = "SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' AND fld_created_by IN (2 , ".$uid.") 
					UNION 
					SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' and fld_district_id = '".$sendistid."' and fld_school_id = '0' order by fld_profile_id ASC";
	}
	else
	{ 	//For Teacher
		$qry="SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' AND fld_created_by ='2'
				UNION SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' 
				and fld_district_id = '".$sendistid."' and fld_school_id = '0'
				UNION  SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' 
				and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
				UNION  SELECT fld_rub_name, fld_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id = '".$missionid."' and fld_delstatus = '0' 
				and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";
	}         
	
        
?>
	<script>
	$.getScript("class/newclass/class-newclass-class.js");
	</script>
	<div class="row rowspacer"> Select Grading Rubric
		<div >
		 	<dl class='field row' >  
				<?php
				$rubricvalues=array();
				$qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
				if($qry_for_get_all_expedition->num_rows>0)
				{
					$i=1; 
					?><table cellpadding="19px" cellspacing="19px" > <tr><?php
					while($row=$qry_for_get_all_expedition->fetch_assoc())
					{
						extract($row); 
				  		
						$chkval = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_expmis_rubricmaster 
																WHERE fld_rubric_id='".$fld_id."' AND fld_schedule_id='".$scheduleid."' 
																AND fld_delstatus='0' AND fld_schedule_type='18'");
						
						?>
						<td>
							<dt>       
								<input id="chkboxrubric_<?php echo $fld_id; ?>" type="checkbox" value="" name="chkbox" <?php if($chkval=='1' || $scheduleid=='0'){echo "checked"; } ?>  >
								<span></span>
								<?php echo $fld_rub_name; ?>
							</dt>
						</td>
						<?php
						if($i%3==0)
						{
							echo "</tr><tr>";
						}
						$i++;
					}
					?> </tr></table> <?php
				}
				?>
			</dl>
		</div>
                </div>
        
        <div class="row rowspacer"> Select Mission Assessment
            
            <style>
              h2.acc_trigger {
                  padding: 0;
                  margin: 0 0 5px 0;
                  width: 100%;
                  font-size: 20px;
                  font-weight: normal;
                  float: left;
                  margin-bottom:0;
              }
              h2.acc_trigger a {
                  text-decoration: none;
                  display: block;
                  padding: 0 0 0 15px;
              }
            </style>
            <script type="text/javascript" language="javascript">
                jQuery(document).ready(function ($) {
                //Set default open/close settings
                  var divs=$('.accordion>div').hide(); //Hide/close all containers	
                  $(".accordion>div:first").show();
                  $(".accordion>h2>a>input:first").addClass('removeButton').removeClass('addButton');
                  $(".accordion>h2>a>input:first").val('-');
                     var h2s = $(".accordion>h2").click(function () {
                  if($(this).children().children('input').hasClass('addButton'))
                  {
                      $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                      $(".accordion>h2>a>input").val('+');
                      $(this).children().children('input').addClass('removeButton').removeClass('addButton');
                      $(this).children().children('input').val('-');
                  }
                  else
                  {	
                      $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                      $(".accordion>h2>a>input").val('+');
                      $(this).children().children('input').addClass('addButton').removeClass('removeButton');
                      $(this).children().children('input').val('+');	
                  }
                      h2s.not(this).removeClass('active')
                      $(this).toggleClass('active')
                      divs.not($(this).next()).slideUp()
                      //var spans=$('.accordion>span').hide(); 
                      //spans.not($(this).next()).slideUp()
                      $(this).next().slideToggle()
                      return false; //Prevent the browser jump to the link anchor

                    });
                });
            </script>

            <div class="accordion">
                <?php
                    $distadminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '0'  AND fld_district_id='".$sendistid."' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '6'");
                    
                     $schladminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '$senshlid' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '7'");
                    $qrytmis = $ObjDB->QueryObject("SELECT fld_mis_name as misname, fld_id as misid FROM itc_mission_master WHERE fld_id IN(".$missionid.")");

                    if ($qrytmis->num_rows > 0) {
                        $a = 0;
                        $x=0;
                        $y=0;
                        $z=0;
                        while ($rowtmis = $qrytmis->fetch_assoc()) {

                            extract($rowtmis);
                            ?>
                            <h2 class="acc_trigger"><a href="#"><input type="button" class="addButton" value="+" >&nbsp;<?php echo $misname;?></a></h2>
                            <?php

                                if ($qrytmis->num_rows > 0) {
                                    ?>
                                        <div class="acc_container">
                                            
                                                        <table cellpadding="19px" cellspacing="19px" > 
                                                            <tr>
                                                                <?php
                                                                
                                                                $qrymistestdetail= $ObjDB->QueryObject("SELECT a.fld_id AS testid, a.fld_test_name AS testname FROM itc_test_master AS a
                                                                                                        LEFT JOIN
                                                                                                        `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                        LEFT JOIN
                                                                                                        `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                        WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                        AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                        and a.fld_ass_type='2' and b.fld_license_id='".$schlicenseid."'
                                                                                                                        and fld_profile_id='2' and a.fld_mist='".$misid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                        select fld_id AS testid, fld_test_name AS testname
                                                                                                        from itc_test_master where fld_ass_type='2' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                        and fld_mist='".$misid."' and fld_delstatus = '0'");
                                                                if ($qrymistestdetail->num_rows > 0) {
                                                                    $i=1;
                                                                    while ($rowtmis1 = $qrymistestdetail->fetch_assoc()) {
                                                                    extract($rowtmis1);
                                                                    $chkvalmis = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_ass 
																WHERE fld_test_id='".$testid."' AND fld_sch_id='".$scheduleid."' 
																AND fld_flag='1' AND fld_schtype_id='18'");
                                                                    ?>
                                                                <td>
                                                                    <dt style="margin-left: 25px;">
                                                                        <input id="chkboxtest_<?php echo $testid; ?>" type="checkbox" value="" name="chkbox" <?php if($chkvalmis=='1' || $scheduleid=='0'){echo "checked"; } ?>  >
                                                                        <span></span>
                                                                        <?php echo $testname; ?>
                                                                    </dt>
                                                                </td>
                                                                <?php
                                                                    if($i%3==0)
                                                                    {
                                                                            echo "</tr><tr>";
                                                                    }
                                                                    $i++;
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                        </table>
                                                    
                                        </div>
                                    <?php
                                }
                                 $a++;
                        }

                }?>
            </div>
        </div>
        
        

        
         <div class="row rowspacer" style="margin-top:50px;">
            <div class="tLeft" style="color:#F00;">
            </div>
            <div class="tRight" id="modnxtstep">
              <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="Save schedule" onClick="fn_saveindassesmentmission(<?php echo $scheduleid; ?>);" />
            </div>
     </div>
<?php
} 
/* Mission setting END */

if($oper == "saveindassesmentexpedition" and $oper != '')
{
					 	
	try{				
		$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
		$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
		$sname = isset($_REQUEST['sname']) ? $_REQUEST['sname'] : '0';
		$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '0';
		$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '0';
		$scheduletype = isset($_REQUEST['scheduletype']) ? $_REQUEST['scheduletype'] : '0';
		$students = isset($_REQUEST['students']) ? $_REQUEST['students'] : '0';
		$unstudents = isset($_REQUEST['unstudents']) ? $_REQUEST['unstudents'] : '0';
		$studenttype = isset($_REQUEST['studenttype']) ? $_REQUEST['studenttype'] : '0';
		$licenseid = isset($_REQUEST['licenseid']) ? $_REQUEST['licenseid'] : '0';
		$expeditionid = isset($_REQUEST['expeditionid']) ? $_REQUEST['expeditionid'] : '0';			
		
                $expextendid=isset($_REQUEST['extid']) ? $_REQUEST['extid'] : '0';
		
                
                $expedextendid=isset($_REQUEST['extid1']) ? $_REQUEST['extid1'] : '0';
                $extid = isset($method['extids']) ? $method['extids'] : '0';
                $expids = isset($method['expids']) ? $method['expids'] : '0'; /***********Chandru Updated by one or more Extend Content option code start here*********/
		$selectallexpids = isset($method['selectallexpids']) ? $method['selectallexpids'] : '0'; /***********Chandru Updated by one or more Extend Content option code start here*********/
                
		$selectchkboxids = isset($method['selectchkboxids']) ? $method['selectchkboxids'] : '0';     //Mohan M     
		
                
		$students = explode(',',$students);
		$unstudents = explode(',',$unstudents);	
		
                $expids = explode(',',$expids); /***********Chandru Updated by [18-12-2015] one or more Extend Content option code start here*********/
                $selectallexpids = explode('~',$selectallexpids); /***********Chandru Updated by [18-12-2015] one or more Extend Content option code start here*********/
                
	  	$selectchkboxids = explode(',',$selectchkboxids);  //Mohan M  
	  	
		$validate_sid=true;
		$validate_sname=true;
		$validate_classid=true;
		$validate_scheduletype=true;
		$validate_startdate=true;
		$validate_enddate=true;
		$validate_licenseid=true;			
                
		if($sid!=0){
                $validate_sid=validate_datatype($sid,'int');
		$validate_sname=validate_datas($sname,'lettersonly');
		$validate_classid=validate_datatype($classid,'int');
		$validate_licenseid=validate_datatype($licenseid,'int');
		$validate_scheduletype=validate_datatype($scheduletype,'int');
		$validate_startdate=validate_datas($startdate,'dateformat');
		$validate_enddate=validate_datas($enddate,'dateformat');
                }
		
                
                // Sangeetha    
               $exptest = isset($_REQUEST['exptest']) ? $_REQUEST['exptest'] : '0';
               $desttest = isset($_REQUEST['desttest']) ? $_REQUEST['desttest'] : '0';
               $tasktest = isset($_REQUEST['tasktest']) ? $_REQUEST['tasktest'] : '0';
               $restest = isset($_REQUEST['restest']) ? $_REQUEST['restest'] : '0';

               
		if($validate_sid and $validate_sname and $validate_classid and $validate_scheduletype and $validate_startdate and $validate_licenseid and $validate_enddate){
					
			if($studenttype==1){
				/*---------checing the license for student----------------------*/				
				$count=0;
				$qry = $ObjDB->QueryObject("SELECT fld_student_id 
											FROM itc_class_student_mapping 
											WHERE fld_class_id='".$classid."' AND fld_flag='1'");
				if($qry->num_rows>0){
					$students=array();
					while($res=$qry->fetch_assoc())
					{
						extract($res);
						$students[]=$fld_student_id;
						$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
															  FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
															  WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' 
															  	AND b.fld_delstatus='0'");
						if($check==0)
						{
							$count++;
						}
					}
				}
			}
			else{
				$count=0;
				$add=0;			
				for($i=0;$i<sizeof($students);$i++)
				{
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
														  FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
														  WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					if($check==0)
					{
						$count++;
					}
				}				
				for($i=0;$i<sizeof($unstudents);$i++)
				{					
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
															FROM itc_license_assign_student 
															WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' AND fld_flag='1'");
					if($check>0)
					{
						
						$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_schedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_schedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_expschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_expschedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                                                UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_modexpschedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_dyad_schedule_studentmapping AS a LEFT JOIN itc_class_dyad_schedulemaster
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_triad_schedule_studentmapping AS a LEFT JOIN itc_class_triad_schedulemaster
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 

																		FROM itc_class_indassesment_student_mapping AS a LEFT JOIN itc_class_indassesment_master
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_exp_student_mapping AS a LEFT JOIN itc_class_indasexpedition_master
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
																		UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
								LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
								LEFT JOIN itc_class_mission_schedule_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
						
						$ObjDB->NonQuery("UPDATE itc_class_exp_student_mapping 
										 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
										 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
						if($studentcount==0){
							$add++;
							$ObjDB->NonQuery("UPDATE itc_license_assign_student 
											 SET fld_flag='0',fld_updated_date='".date('Y-m-d H:i:s')."',fld_updated_by='".$uid."'
											 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."'");
						}
					}
				}
			}
			
			$remainusersqry = $ObjDB->QueryObject("SELECT fld_remain_users AS remainusers, fld_no_of_users AS totalusers 
															FROM itc_license_track 
															WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' 
															AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");		
			extract($remainusersqry->fetch_assoc());
				
			$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																 FROM itc_license_assign_student 
																 WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
			$totalremain = $remainusers-$count;
			if($totalusers>=($assignedstudents+$count)){
				$flag=1;
			}		
			else{	
				$flag=0;
			}
			
			if($flag==1){ //if student user availale for license
				if($sid!=0){				
				
									
					$ObjDB->NonQuery("UPDATE itc_class_indasexpedition_master 
									SET fld_schedule_name='".$ObjDB->EscapeStrAll($sname)."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."' ,
										fld_enddate='".date("Y-m-d",strtotime($enddate))."', fld_exp_id='".$expeditionid."', 
										fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
									WHERE fld_id='".$sid."'");									
					
					
				}
				else{
					
					$sid = $ObjDB->NonQueryWithMaxValue("INSERT into itc_class_indasexpedition_master (fld_class_id,fld_license_id,fld_schedule_name,fld_exp_id, 	
														fld_scheduletype, fld_student_type,fld_startdate,fld_enddate,fld_created_date,fld_createdby) 
														 VALUES('".$classid."','".$licenseid."','".$ObjDB->EscapeStrAll($sname)."','".$expeditionid."','".$scheduletype."','".$studenttype."',
																'".date("Y-m-d",strtotime($startdate))."','".date("Y-m-d",strtotime($enddate))."','".date("Y-m-d H:i:s")."','".$uid."')");						
					 			
				}
				
                                // inline test ass starts - karthi
                                $expval = explode("~",$exptest);
                                if($exptest !=''){
                                    for($i=0;$i<sizeof($expval);$i++)
                                    {
                                        $expval1 = explode("_",$expval[$i]);
                                        $texpid = $expval1[0];
                                        $tdestid = $expval1[1];
                                        $ttaskid = $expval1[2];
                                        $tresid = $expval1[3];
                                        $tpreid = $expval1[4];
                                        $tpostid = $expval1[5];
                                        if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                                        if($tpostid=="undefined" or $tpostid==""){$tpostid=0;}

                                        $exptestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                                 and fld_texpid='".$texpid."' and fld_tdestid='0' and fld_ttaskid='0' and fld_tresid='0'");
                                        if($exptestcount == 0)
                                        {
                                             $expqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                                         VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");

                                        }
                                         else {
                                             $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                               where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                               fld_tdestid='0' and fld_ttaskid='0' and fld_tresid='0'");                       
                                         }
                                    }
                               }

                               if($desttest !=''){
                               $destval = explode("~",$desttest);
                                    for($i=0;$i<sizeof($destval);$i++)
                                    {
                                        $destval1 = explode("_",$destval[$i]);
                                        $texpid = $destval1[0];
                                        $tdestid = $destval1[1];
                                        $ttaskid = $destval1[2];
                                        $tresid = $destval1[3];
                                        $tpreid = $destval1[4];
                                        $tpostid = $destval1[5];
                                        if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                                        if($tpostid=="undefined" or $tpostid==""){ $tpostid=0;}
                                        $desttestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                                 and fld_texpid='".$texpid."' and fld_tdestid='".$tdestid."' and fld_ttaskid='0' and fld_tresid='0'");
                                        if($desttestcount == 0)
                                        {
                                             $destqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                                         VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
                                        }
                                        else
                                        {

                                            $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                               where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                               fld_tdestid='".$tdestid."' and fld_ttaskid='0' and fld_tresid='0'");
                                        }
                                    }
                               }

                               if($tasktest!=''){
                                   $taskval = explode("~",$tasktest);
                                    for($i=0;$i<sizeof($taskval);$i++)
                                    {
                                        $taskval1 = explode("_",$taskval[$i]);
                                        $texpid = $taskval1[0];
                                        $tdestid = $taskval1[1];
                                        $ttaskid = $taskval1[2];
                                        $tresid = $taskval1[3];
                                        $tpreid = $taskval1[4];
                                        $tpostid = $taskval1[5];

                                        if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                                        if($tpostid=="undefined" or $tpostid==""){$tpostid=0;}
                                        $tasktestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                                 and fld_texpid='".$texpid."' and fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='0'");
                                        if($tasktestcount == 0)
                                        {
                                             $taskqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                                         VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
                                        }
                                        else
                                        {
                                            $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                               where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                               fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='0'");
                                        }
                                    }
                                }
                                if($restest){
                                    $resval = explode("~",$restest);
                                    for($i=0;$i<sizeof($resval);$i++)
                                    {
                                        $resval1 = explode("_",$resval[$i]);
                                        $texpid = $resval1[0];
                                        $tdestid = $resval1[1];
                                        $ttaskid = $resval1[2];
                                        $tresid = $resval1[3];
                                        $tpreid = $resval1[4];
                                        $tpostid = $resval1[5];
                                        if($tpreid=="undefined" or $tpreid==""){ $tpreid=0;}
                                        if($tpostid=="undefined" or $tpostid==""){$tpostid=0;}
                                        $restestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) from itc_exp_ass where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' 
                                                                                 and fld_texpid='".$texpid."' and fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='".$tresid."'");
                                        if($restestcount == 0)
                                        {
                                             $resqry = $ObjDB->NonQuery("INSERT into itc_exp_ass (fld_class_id,fld_sch_id,fld_schtype_id,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_pretest,fld_posttest,fld_created_date,fld_created_by,fld_school_id,fld_user_id) 
                                                                                                                                         VALUES('".$classid."','".$sid."','".$scheduletype."','".$texpid."','".$tdestid."','".$ttaskid."','".$tresid."','".$tpreid."','".$tpostid."','".date("Y-m-d H:i:s")."','".$uid."','".$schoolid."','".$indid."')");
                                        }
                                         else {
                                             $ObjDB->NonQuery("UPDATE itc_exp_ass set fld_pretest='".$tpreid."', fld_posttest='".$tpostid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                               where fld_class_id='".$classid."' and fld_sch_id='".$sid."' and fld_schtype_id='".$scheduletype."' and fld_texpid='".$texpid."' and 
                                                               fld_tdestid='".$tdestid."' and fld_ttaskid='".$ttaskid."' and fld_tresid='".$tresid."'");
                                         }
                                    }
                                }
                                // inline test ass Ends - karthi
				
				$ObjDB->NonQuery("UPDATE itc_class_exp_student_mapping 
								 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."'");
				
				for($i=0;$i<sizeof($students);$i++){
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_class_exp_student_mapping 
														WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_class_exp_student_mapping(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) 
										 VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_class_exp_student_mapping 
										SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
										WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
					
					//tracing student
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) 
										 VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date("Y-m-d H:i:s")."','".$uid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_assign_student 
										 SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
										 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
				}
				
				$ObjDB->NonQuery("UPDATE itc_class_indasexpedition_master 
								 SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' 
								 WHERE fld_id='".$sid."'");
                                if($expextendid!='' && $expextendid != 'undefined')	
				{
								 
                                   $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_indasexpedition_extcontent_mapping 
									   WHERE fld_schedule_id='".$sid."' AND fld_exp_id='".$expeditionid."' AND fld_active='1'"); 
                                   if($cnt==0)
                                    {
				
                                             $ObjDB->NonQuery("INSERT INTO itc_class_indasexpedition_extcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_exp_id,fld_createdby,fld_createddate)
                                                                                    VALUES('".$sid."','".$expextendid."','1','".$expeditionid."','".$uid."','".date("Y-m-d H:i:s")."')");


                                    }
                                    else
                                    {

                                            $ObjDB->NonQuery("UPDATE itc_class_indasexpedition_extcontent_mapping 
                                                                                    SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_ext_id='".$expextendid."'  
                                                                                    WHERE fld_schedule_id='".$sid."' AND fld_exp_id='".$expeditionid."'");


                                    }
                    }
								 
                                /****************If the Expedition has already have some Assessments or not Code Start Here Mohan************************/
                                $qryexptest = $ObjDB->QueryObject("SELECT fld_id AS testid FROM itc_test_master WHERE fld_ass_type='1' AND fld_expt='".$expeditionid."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
                                if($qryexptest->num_rows>0)
                                {
                                    while($rowexptest = $qryexptest->fetch_assoc())
                                    {
                                        extract($rowexptest);
				
                                        $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master WHERE fld_id='".$testid."' AND fld_delstatus='0' AND fld_ass_type='1' AND  fld_created_by='".$uid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping 
                                                                SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                WHERE fld_test_id='".$testid."'  AND fld_class_id='".$classid."' AND fld_schedule_id='".$sid."'");  

                                        for($i=0;$i<sizeof($students);$i++)
                                        {

                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_exp_id='".$expeditionid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'  AND fld_created_by='".$uid."'");

                                            if($count == 0)
                                            {	

                                                $ObjDB->NonQuery("INSERT INTO itc_test_inline_student_mapping(fld_test_id, fld_exp_id, fld_class_id, fld_schedule_id, 
                                                                    fld_student_id, fld_points_possible, fld_flag,fld_created_by, fld_created_date)
                                                                    VALUES('".$testid."','".$expeditionid."','".$classid."','".$sid."','".$students[$i]."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else
                                            {
                                                $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                        WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                                }
                                /****************If the Expedition has already have some Assessments or not Code End Here Mohan************************/

                                /***********Chandru Updated by one or more Extend Content option code start here*********/
                                if($scheduletype==15)
                                {
                                    $ObjDB->NonQuery("UPDATE itc_class_indasexpedition_expextcontent_mapping 
                                                         SET fld_active='0' 
                                                         WHERE fld_schedule_id='".$sid."'");
                                   
                                    if($expids[0] != '')
                                    {
                                        for($i=0;$i<sizeof($expids);$i++)
                                        {
                                            $templistmod = explode('_',$expids[$i]);
                                            
                                            if($templistmod[0]!='' and $templistmod[0]!=0)
                                            {
                                                
                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_indasexpedition_expextcontent_mapping WHERE fld_schedule_id = '".$sid."'
                                                                                                AND fld_ext_id = '".$templistmod[0]."' AND fld_exp_id = '".$templistmod[1]."'");
                                                
                                                if($cnt==0)
                                                {
                                                    
                                                    $ObjDB->NonQuery("INSERT INTO itc_class_indasexpedition_expextcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_exp_id,fld_createdby,fld_createddate)
                                                                                        VALUES('".$sid."','".$templistmod[0]."','1','".$templistmod[1]."','".$uid."','".date("Y-m-d H:i:s")."')");
                                                }
                                                else
                                                {
                                                    $ObjDB->NonQuery("UPDATE itc_class_indasexpedition_expextcontent_mapping 
                                                                                SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."' 
                                                                                WHERE fld_schedule_id='".$sid."' AND fld_ext_id='".$templistmod[0]."' AND fld_exp_id='".$templistmod[1]."'");
                                                }
                                            }					
                                        }
                                    }                                
                                   
                                    //  print_r($selectallmodids); 19/12/2015

                                    if($selectallexpids[0] != '')
                                    { /******Select All Extend Content***** 19-12-2015 */
                                       for($i=0;$i<(sizeof($selectallexpids)-1);$i++)
                                        {
                                            $selectallexpids[$i] = ltrim($selectallexpids[$i],",");
                                            $templistmod = explode(',',$selectallexpids[$i]);
                                         

                                            $getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS exname FROM itc_exp_extendtext_master
                                                                                    WHERE fld_exp_id='".$templistmod[0]."' AND fld_school_id='".$schoolid."' AND fld_delstatus='0'
                                                                                    UNION ALL
                                                                                    SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM itc_exp_extendtext_master AS a 
                                                                                    LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
                                                                                    b.fld_license_id='".$templistmod[1]."' AND b.fld_module_id='".$templistmod[0]."' AND b.fld_type='1' 
                                                                                    AND b.fld_active='1' AND a.fld_delstatus='0'");
                                            if($getcontent->num_rows>0)
                                            {
                                                while($res = $getcontent->fetch_assoc())
                                                {
                                                    extract($res);

                                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                FROM itc_class_indasexpedition_expextcontent_mapping 
                                                                                                WHERE fld_schedule_id='".$sid."'  AND fld_ext_id='".$exid."'  
                                                                                                AND fld_exp_id='".$templistmod[0]."'");
                                                    if($cnt==0)
                                                    {
                                                            $ObjDB->NonQuery("INSERT INTO itc_class_indasexpedition_expextcontent_mapping (fld_schedule_id,fld_ext_id,fld_active,fld_schedule_type,fld_exp_id,fld_createdby,fld_createddate,fld_select_all)
                                                                                           VALUES('".$sid."','".$exid."','1','1','".$templistmod[0]."','".$uid."','".date("Y-m-d H:i:s")."','1')");
                                                    }
                                                    else
                                                    {
                                                            $ObjDB->NonQuery("UPDATE itc_class_indasexpedition_expextcontent_mapping 
                                                                                    SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_select_all='1'
                                                                                    WHERE fld_schedule_id='".$sid."' AND fld_ext_id='$exid' AND fld_schedule_type='1' AND fld_exp_id='".$templistmod[0]."'");
                                                    }

                                                }
                                            }
                                        }
                                    } /******Select All Extend Content******/
/****************Mohan M  Feb 20 2016******************/	
if($selectchkboxids[0] != '')
{
	$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
						 SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
						 	WHERE fld_schedule_id='".$sid."'");
									

	for($m=0;$m<sizeof($selectchkboxids);$m++)
	{
		$templistrubric = explode('~',$selectchkboxids[$m]);

		if($templistrubric[0]!='' and $templistrubric[1]!=0)
		{

			$classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."' AND fld_delstatus='0'");
			$schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_indasexpedition_master WHERE fld_id='".$sid."' AND fld_scheduletype='15' AND fld_delstatus='0'");
			$expname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$templistrubric[1]."' AND fld_delstatus='0'");
			$rubricname = $ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_exp_rubric_name_master WHERE fld_id='".$templistrubric[0]."' AND fld_delstatus='0'");

			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_expmis_rubricmaster WHERE fld_schedule_id = '".$sid."'
									AND fld_rubric_id = '".$templistrubric[0]."' AND fld_expmisid = '".$templistrubric[1]."' AND fld_schedule_type='15'");

			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_expmis_rubricmaster (fld_class_id, fld_schedule_id, fld_schedule_type, fld_expmisid, fld_rubric_id, fld_created_by, fld_created_date,fld_class_name,fld_schedule_name,fld_expmisname,fld_rubric_name)
								VALUES('".$classid."','".$sid."','15','".$templistrubric[1]."','".$templistrubric[0]."','".$uid."','".date("Y-m-d H:i:s")."', '".$ObjDB->EscapeStrAll($classname)."', '".$ObjDB->EscapeStrAll($schedulename)."','".$ObjDB->EscapeStrAll($expname)."','".$ObjDB->EscapeStrAll($rubricname)."')");
                                }
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster 
											SET fld_delstatus='0',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."',  
											fld_class_name='".$ObjDB->EscapeStrAll($classname)."',fld_schedule_name='".$ObjDB->EscapeStrAll($schedulename)."',fld_expmisname='".$ObjDB->EscapeStrAll($expname)."',fld_rubric_name='".$ObjDB->EscapeStrAll($rubricname)."'
											WHERE fld_schedule_id='".$sid."' AND fld_rubric_id='".$templistrubric[0]."' AND fld_expmisid='".$templistrubric[1]."'");
			}
		}					
	}
}
else
{
	$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
						 SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
						 	WHERE fld_schedule_id='".$sid."'");
	
}
/****************Mohan M  Feb 20 2016******************/									
                                }
                             /***********Mohan M Updated by one or more Extend Content option code End here*********/
                                
                                
				echo "success~".$sid;
				send_notification($licenseid,$schoolid,$indid);			
			}
			else{
				echo "fail";
			}
		}
		else{
			echo "invalid";
		}
	}
	catch(Exception $e){
		echo "invalid";
	}
}

/* Save mission assesment*/
if($oper == "saveindassesmentmission" and $oper != '')
{
					 	
	try{				
		$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
		$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
		$sname = isset($_REQUEST['sname']) ? $_REQUEST['sname'] : '0';
		$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '0';
		$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '0';
		$scheduletype = isset($_REQUEST['scheduletype']) ? $_REQUEST['scheduletype'] : '0';
		$students = isset($_REQUEST['students']) ? $_REQUEST['students'] : '0';
		$unstudents = isset($_REQUEST['unstudents']) ? $_REQUEST['unstudents'] : '0';
		$studenttype = isset($_REQUEST['studenttype']) ? $_REQUEST['studenttype'] : '0';
		$licenseid = isset($_REQUEST['licenseid']) ? $_REQUEST['licenseid'] : '0';
		$missionid = isset($_REQUEST['missionid']) ? $_REQUEST['missionid'] : '0';	
                
                $missionextendid=isset($_REQUEST['extid']) ? $_REQUEST['extid'] : '0';
		
	 	$selectchkboxids = isset($method['selectchkboxids']) ? $method['selectchkboxids'] : '0';     //Mohan M     
		$selectchkboxids = explode(',',$selectchkboxids);  //Mohan M   
		
                $selectchkboxtestids = isset($method['selectchkboxtestids']) ? $method['selectchkboxtestids'] : '0';     //karthi    
		$selectchkboxtestids = explode(',',$selectchkboxtestids);  //karthi 
		
		$students = explode(',',$students);
		$unstudents = explode(',',$unstudents);	
		
		$validate_sid=true;
		$validate_sname=true;
		$validate_classid=true;
		$validate_scheduletype=true;
		$validate_startdate=true;
		$validate_enddate=true;
		$validate_licenseid=true;			
		if($sid!=0){ 
                $validate_sid=validate_datatype($sid,'int');}
		$validate_sname=validate_datas($sname,'lettersonly');
		$validate_classid=validate_datatype($classid,'int');
		$validate_licenseid=validate_datatype($licenseid,'int');
		$validate_scheduletype=validate_datatype($scheduletype,'int');
		$validate_startdate=validate_datas($startdate,'dateformat');
		$validate_enddate=validate_datas($enddate,'dateformat');
		
		if($validate_sid and $validate_sname and $validate_classid and $validate_scheduletype and $validate_startdate and $validate_licenseid and $validate_enddate){
					
			if($studenttype==1){
				/*---------checing the license for student----------------------*/				
				$count=0;
                                
				$qry = $ObjDB->QueryObject("SELECT fld_student_id 
                                                                                FROM itc_class_student_mapping 
                                                                                WHERE fld_class_id='".$classid."' AND fld_flag='1'");
				if($qry->num_rows>0){
					$students=array();
					while($res=$qry->fetch_assoc())
					{
						extract($res);
						$students[]=$fld_student_id;
						$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                                        FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                        WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' 
                                                                                              AND b.fld_delstatus='0'");
						if($check==0)
						{
							$count++;
						}
					}
				}
			}
			else{
				$count=0;
				$add=0;			
				for($i=0;$i<sizeof($students);$i++)
				{
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                                FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					if($check==0)
					{
						$count++;
					}
				}				
				for($i=0;$i<sizeof($unstudents);$i++)
				{					
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                                FROM itc_license_assign_student 
                                                                                WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' AND fld_flag='1'");
					if($check>0)
					{
						
						$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_rotation_schedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_schedule_mastertemp
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                    
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_rotation_expschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_expschedule_mastertemp
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                    
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_rotation_mission_student_mappingtemp AS a LEFT JOIN itc_class_rotation_mission_mastertemp
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                    
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_modexpschedule_mastertemp
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                    
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_dyad_schedule_studentmapping AS a LEFT JOIN itc_class_dyad_schedulemaster
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_triad_schedule_studentmapping AS a LEFT JOIN itc_class_triad_schedulemaster
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                FROM itc_class_indassesment_student_mapping AS a LEFT JOIN itc_class_indassesment_master
                                                                                                                AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                                AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
                                                                                                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                UNION ALL 
                                                                                                                
                                                                                                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a
                                                                                                                LEFT JOIN itc_class_indasmission_master AS b ON a.fld_schedule_id = b.fld_id
                                                                                                                WHERE a.fld_student_id ='".$unstudents[$i]."' AND a.fld_flag = '1' AND b.fld_license_id ='".$licenseid."' AND a.fld_schedule_id <> '".$sid."' 
                                                                                                                ) AS o");
						
						$ObjDB->NonQuery("UPDATE itc_class_mission_student_mapping
                                                                                SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
                                                                                WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
						if($studentcount==0){
							$add++;
                                                       $ObjDB->NonQuery("UPDATE itc_license_assign_student 
                                                                                SET fld_flag='0',fld_updated_date='".date('Y-m-d H:i:s')."',fld_updated_by='".$uid."'
                                                                                WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."'");
						}
					}
				}
			}
			
			$remainusersqry = $ObjDB->QueryObject("SELECT fld_remain_users AS remainusers, fld_no_of_users AS totalusers 
                                                                                FROM itc_license_track 
                                                                                WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' 
                                                                                AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");		
			extract($remainusersqry->fetch_assoc());
				
			$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                FROM itc_license_assign_student 
                                                                                WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
$totalremain = $remainusers-$count;
			if($totalusers>=($assignedstudents+$count)){
				$flag=1;
			}		
			else{	
				$flag=0;
			}
			
			if($flag==1){ //if student user availale for license
				if($sid!=0){				
				
									
					$ObjDB->NonQuery("UPDATE itc_class_indasmission_master 
									SET fld_schedule_name='".$ObjDB->EscapeStrAll($sname)."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."' ,
                                                                            fld_enddate='".date("Y-m-d",strtotime($enddate))."', fld_mis_id='".$missionid."', 
                                                                            fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
									WHERE fld_id='".$sid."'");
									
				}
				else{
					
					$sid = $ObjDB->NonQueryWithMaxValue("INSERT into itc_class_indasmission_master(fld_class_id,fld_license_id,fld_schedule_name,fld_mis_id,fld_scheduletype,fld_student_type,fld_startdate,fld_enddate,  
                                                                                fld_created_date,fld_createdby) 
                                                                                VALUES('".$classid."','".$licenseid."','".$ObjDB->EscapeStrAll($sname)."','".$missionid."','".$scheduletype."','".$studenttype."',
                                                                                '".date("Y-m-d",strtotime($startdate))."','".date("Y-m-d",strtotime($enddate))."','".date("Y-m-d H:i:s")."','".$uid."')");
                                        
				}
                               
				
				$ObjDB->NonQuery("UPDATE itc_class_mission_student_mapping 
								 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
								 WHERE fld_schedule_id='".$sid."'");
				
				for($i=0;$i<sizeof($students);$i++){
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                FROM itc_class_mission_student_mapping 
                                                                                WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_class_mission_student_mapping(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) 
										 VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_class_mission_student_mapping 
										SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
										WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
					
					//tracing student
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) 
										 VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date("Y-m-d H:i:s")."','".$uid."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_assign_student 
										 SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
										 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
				}
				
				$ObjDB->NonQuery("UPDATE itc_class_indasmission_master 
								 SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' 
								 WHERE fld_id='".$sid."'");
                                if($missionextendid!='' && $missionextendid!= 'undefined')	
				{
								 
                                   $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_indasmission_extcontent_mapping 
									   WHERE fld_schedule_id='".$sid."' AND fld_mis_id='".$missionid."' AND fld_active='1'"); 
                                   if($cnt==0)
                                    {
				
                                             $ObjDB->NonQuery("INSERT INTO itc_class_indasmission_extcontent_mapping(fld_schedule_id,fld_ext_id,fld_active,fld_mis_id,fld_createdby,fld_createddate)
                                                                                    VALUES('".$sid."','".$missionextendid."','1','".$missionid."','".$uid."','".date("Y-m-d H:i:s")."')");


                                    }
                                    else
                                    {

                                            $ObjDB->NonQuery("UPDATE itc_class_indasmission_extcontent_mapping 
                                                                                    SET fld_active='1',fld_updatedby='".$uid."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_ext_id='".$missionextendid."'  
                                                                                    WHERE fld_schedule_id='".$sid."' AND fld_mis_id='".$missionid."'");


                                    }
                                }
								 
				
                                /****************If the Mission has already have some Assessments or not Code Start Here Mohan************************/
                                $qrymistest = $ObjDB->QueryObject("SELECT fld_id AS testid FROM itc_test_master WHERE fld_ass_type='2' AND fld_mist='".$missionid."' AND fld_delstatus='0' AND fld_created_by='".$uid."'");
                                if($qrymistest->num_rows>0)
                                {
                                    while($rowmistest = $qrymistest->fetch_assoc())
                                    {
                                        extract($rowmistest);

                                        $possiblepointfortestm = $ObjDB->SelectSingleValueInt("SELECT fld_score FROM itc_test_master WHERE fld_id='".$testid."' AND fld_delstatus='0' AND fld_ass_type='2' AND  fld_created_by='".$uid."'");

                                        $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping 
                                                                SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$sid."'");  

                                        for($n=0;$n<sizeof($students);$n++)
                                        {

                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_mission_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_mis_id='".$missionid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_student_id='".$students[$n]."'  AND fld_created_by='".$uid."'");

                                            if($count == 0)
                                            {	

                                                $ObjDB->NonQuery("INSERT INTO itc_test_mission_inline_student_mapping(fld_test_id, fld_mis_id, fld_class_id, fld_schedule_id, 
                                                                    fld_student_id, fld_points_possible, fld_flag,fld_created_by, fld_created_date)
                                                                    VALUES('".$testid."','".$missionid."','".$classid."','".$sid."','".$students[$n]."','".$possiblepointfortestm."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else
                                            {
                                                $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                        WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                                }
                                /****************If the Mission has already have some Assessments or not Code End Here Mohan************************/
/****************Mohan M  Feb 20 2016******************/	
if($selectchkboxids[0] != '')
{
	$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
						 SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
						 	WHERE fld_schedule_id='".$sid."'");

								 
	for($m=0;$m<sizeof($selectchkboxids);$m++)
	{
		$templistrubric = explode('~',$selectchkboxids[$m]);
				
		if($templistrubric[0]!='' and $templistrubric[1]!=0)
		{
			$classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."' AND fld_delstatus='0'");
			$schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_indasmission_master WHERE fld_id='".$sid."' AND fld_scheduletype='18' AND fld_delstatus='0'");
			$expname = $ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$templistrubric[1]."' AND fld_delstatus='0'");
			$rubricname = $ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_mis_rubric_name_master WHERE fld_id='".$templistrubric[0]."' AND fld_delstatus='0'");

			$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_expmis_rubricmaster WHERE fld_schedule_id = '".$sid."'
									AND fld_rubric_id = '".$templistrubric[0]."' AND fld_expmisid = '".$templistrubric[1]."' AND fld_schedule_type='18'");

			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_expmis_rubricmaster (fld_class_id, fld_schedule_id, fld_schedule_type, fld_expmisid, fld_rubric_id, fld_created_by, fld_created_date,fld_class_name,fld_schedule_name,fld_expmisname,fld_rubric_name)
								VALUES('".$classid."','".$sid."','18','".$templistrubric[1]."','".$templistrubric[0]."','".$uid."','".date("Y-m-d H:i:s")."', '".$ObjDB->EscapeStrAll($classname)."', '".$ObjDB->EscapeStrAll($schedulename)."','".$ObjDB->EscapeStrAll($expname)."','".$ObjDB->EscapeStrAll($rubricname)."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster 
											SET fld_delstatus='0',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."',  
											fld_class_name='".$ObjDB->EscapeStrAll($classname)."',fld_schedule_name='".$ObjDB->EscapeStrAll($schedulename)."',fld_expmisname='".$ObjDB->EscapeStrAll($expname)."',fld_rubric_name='".$ObjDB->EscapeStrAll($rubricname)."'
											WHERE fld_schedule_id='".$sid."' AND fld_rubric_id='".$templistrubric[0]."' AND fld_expmisid='".$templistrubric[1]."'");
			}
		}					
	}
}
else
{
	$ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
						 SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
						 	WHERE fld_schedule_id='".$sid."'");
}
/****************Mohan M  Feb 20 2016******************/
								 
                                /****************Karthi  sep 28 2016******************/	
                                if($selectchkboxtestids[0] != '')
                                {
                                        $ObjDB->NonQuery("UPDATE itc_mis_ass
                                                                                 SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                                        WHERE fld_sch_id='".$sid."'");
				

                                        for($n=0;$n<sizeof($selectchkboxtestids);$n++)
                                        {
                                                $templisttest = explode('~',$selectchkboxtestids[$n]);

                                                if($templisttest[0]!='' and $templisttest[1]!=0)
                                                {
                                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_ass WHERE fld_sch_id = '".$sid."'
                                                                                                AND fld_test_id = '".$templisttest[0]."' AND fld_mis_id = '".$templisttest[1]."' AND fld_schtype_id='18'");

                                                        if($cnt==0)
                                                        {
                                                                $ObjDB->NonQuery("INSERT INTO itc_mis_ass (fld_class_id, fld_sch_id, fld_schtype_id, fld_mis_id, fld_test_id, fld_created_by, fld_created_date,fld_school_id,fld_user_id)
                                                                                    VALUES('".$classid."','".$sid."','18','".$templisttest[1]."','".$templisttest[0]."','".$uid."','".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."')");
                                                        }
                                                        else
                                                        {
                                                                $ObjDB->NonQuery("UPDATE itc_mis_ass 
                                                                                    SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                                                    WHERE fld_sch_id='".$sid."' AND fld_test_id='".$templisttest[0]."' AND fld_mis_id='".$templisttest[1]."'");
                                                        }
                                                }					
                                        }
                                }
                                else
                                {
                                        $ObjDB->NonQuery("UPDATE itc_mis_ass
                                                            SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                   WHERE fld_sch_id='".$sid."'");
                                }
                                /****************Karthi  sep 28 2016******************/
								 
				
                                echo "success~".$sid;
				send_notification($licenseid,$schoolid,$indid);			
			}
			else{
				echo "fail";
			}
		}
		else{
			echo "invalid";
		}
	}
	catch(Exception $e){
		echo "invalid";
	}
}


/* Load extended expendition content */
if($oper == "loadexpextendcontent" and $oper != ""){
    $expid = isset($method['expednid']) ? $method['expednid'] : '';
    $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
    $sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
    ?>
    <div class='span10 offset1'>
        <table class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Expedition Name</th>
                    <th class='centerText'>Materials List</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
			$expednname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$expid."'");
			$texname = "Select Extend Content";
			$tablename="itc_exp_extendmaterials_master";
                        $moduletype=15;

		$selectext=$ObjDB->QueryObject("SELECT b.fld_ext_id AS texid,a.fld_extend_text as texname FROM ".$tablename." AS a 
                                                    LEFT JOIN itc_class_indasexpedition_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
                                                    WHERE b.fld_schedule_id='".$sid."' AND b.fld_exp_id='".$expid."' AND b.fld_active='1' AND a.fld_delstatus='0'");
									 
			$getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS extname FROM ".$tablename." 
                                                                WHERE fld_exp_id='".$expid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'
                                                                UNION ALL
                                                                SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM ".$tablename." AS a 
                                                                LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
                                                                b.fld_license_id='".$licenseid."' AND b.fld_module_id='".$expid."' AND b.fld_type='".$moduletype."' 
                                                                AND b.fld_active='1' AND a.fld_delstatus='0'"); 
					
						
			if($selectext->num_rows>0){
                                $res = $selectext->fetch_assoc();
                                extract($res);
                        }											 
						
					if($getcontent->num_rows>0)
					{
						$count++;
					?>
				<tr>
					<td><?php echo $expednname; ?></td>
					<td>									
						<div id="clspass">   
							<dl class='field row'>
								<div class="selectbox">
									<input type="hidden" name="exid" id="exid" value="<?php echo $texid;?>">
									<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
										<span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
										<b class="caret1"></b>
									</a>
									<div class="selectbox-options">
										<input type="text" class="selectbox-filter" placeholder="Search Materials">
										<ul role="options" style="width:100%">
										   <?php 
												while($res = $getcontent->fetch_assoc()){
													extract($res);?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $exid;?>"><?php echo $extname; ?></a></li>
													<?php
												}?>      
										</ul>
									</div>
								</div> 
							</dl>
						</div>
					</td>
				</tr>
				<?php 
				}
			else
			{
			?>
			<tr>
				<td colspan="2">
					No records found
				</td>
			</tr>
		 <?php
			}
			?>
                               
            </tbody>
        </table>
    </div>
 <?php   
}
/* Load extended expendition content END */

/* Extend Content list Create by chandra */
if($oper == "loadexpextendcontent1" and $oper != "")
{
    $expid = isset($method['expednid']) ? $method['expednid'] : '';
    $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
    $sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
    
    ?>
    <div class='span10 offset1'>
        <table class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Expedition Name</th>
                    <th class='centerText'>Extend Content List</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $expednname = $ObjDB->SelectSingleValue("SELECT fld_exp_name FROM itc_exp_master WHERE fld_id='".$expid."'");
                $texname = "Select Extend Content";
                $tablename="itc_exp_extendtext_master";
                $moduletype=15;
               
                $selectext=$ObjDB->QueryObject("SELECT b.fld_exp_id AS texid,a.fld_extend_text as textname,b.fld_select_all AS selectall FROM ".$tablename." AS a 
                                                            LEFT JOIN itc_class_indasexpedition_expextcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
                                                            WHERE b.fld_schedule_id='".$sid."' AND b.fld_exp_id='".$expid."' AND b.fld_active='1' AND a.fld_delstatus='0'");




                if($selectext->num_rows>0)
                {
                    $cut=0;
                    while($res = $selectext->fetch_assoc())
                    {
                        extract($res);
                        if($selectall=='0')
                        {
                            if($cut=='0'){
                                $texname=$textname;
                            }
                            else if($cut>='3'){
                                 $texname=$texname."...";
                            }
                            else{
                                $texname=$texname.",".$textname;
                            }
                            $cut++;
                        }
                        else{
                            $texname='Select all';
                        }

                    }
                }											 
                                
                 $getcontent = $ObjDB->QueryObject("SELECT fld_id AS exid,fld_extend_text AS extname FROM ".$tablename." 
                                                        WHERE fld_exp_id='".$expid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'
                                                        UNION ALL
                                                        SELECT a.fld_id AS exid,a.fld_extend_text AS exname FROM ".$tablename." AS a 
                                                        LEFT JOIN itc_license_extcontent_mapping AS b ON a.fld_id=b.fld_ext_id WHERE 
                                                        b.fld_license_id='".$licenseid."' AND b.fld_module_id='".$expid."' AND b.fld_type='".$moduletype."' 
                                                        AND b.fld_active='1' AND a.fld_delstatus='0'"); 

                if($getcontent->num_rows>0)
                {
                    $count++;
                    ?>
                    <tr>
                        <td><?php echo $expednname; ?></td> 
                        <td>									
                            <div id="clspass">   
                                <dl class='field row'>
                                    <div class="selectbox">
                                        <input type="hidden" name="exid1" id="exid1_<?php echo $expid;?>" value="<?php echo $texid."~".".$expid";?>">
                                        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option="" style="width:97%"><div id="expname_<?php echo $expid; ?>"><?php echo $texname;?></div></span>
                                                <b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search Expedition">
                                            <ul role="options" style="width:100%">
                                                <li><span onclick="fn_selectallmod(<?php echo $expid; ?>);">Select All</span> </li>
                                               <?php 
                                                while($res = $getcontent->fetch_assoc())
                                                {
                                                    extract($res);
													
													$extcount = $ObjDB->SelectSingleValue("SELECT count(b.fld_exp_id) FROM ".$tablename." AS a 
																												LEFT JOIN itc_class_indasexpedition_expextcontent_mapping AS b ON a.fld_id=b.fld_ext_id 
																												WHERE b.fld_schedule_id='".$sid."' AND b.fld_exp_id='".$expid."' AND a.fld_id = '".$exid."' AND b.fld_active='1' AND a.fld_delstatus='0'");
												?>
                                                    <li><input type="checkbox" <?php if($extcount!='0' && $selectall!='1'){ ?> checked="checked"<?php } ?>  name="mod_<?php echo $exid."_".$expid;?>" class="ads_Checkbox_<?php echo $expid;?>" value="<?php echo $exid."_".$expid."_".$extname;?>" id="mod_<?php echo $exid."_".$expid;?>" onclick="fn_fillnameformod(<?php echo $expid; ?>);">&nbsp;<?php echo $extname; ?></li>
                                                    <?php
                                                } ?>      
                                            </ul>
                                        </div>
                                    </div> 
                                </dl>
                            </div>
                            <input type="hidden" name="selectallexp_<?php echo $expid.",".$licenseid; ?>" id="selectallexp_<?php echo $expid; ?>" value="1" />
                        </td>
                    </tr>
                    <?php 
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="2"> No records found  </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
 <?php   
}
/* Extend Content list end line by chandra */

/* Load extended mission content */
if($oper == "loadmissionextendcontent" and $oper != ""){
    $missionid = isset($method['missionid']) ? $method['missionid'] : '';
    $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '';
    $sid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
    ?>
    <div class='span10 offset1'>
        <table class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th>Mission Name</th>
                    <th class='centerText'>Materials List</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
			$missionname = $ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$missionid."' AND fld_delstatus='0'");
			$texname = "Select Extend Content";
			$tablename="itc_mis_extendmaterials_master";
                        $moduletype=18;
                        

		$selectext=$ObjDB->QueryObject("SELECT b.fld_ext_id AS texid, a.fld_extend_text as texname FROM ".$tablename." AS a
                                                       LEFT JOIN itc_class_indasmission_extcontent_mapping AS b ON a.fld_id = b.fld_ext_id
                                                       WHERE b.fld_schedule_id ='".$sid."' AND b.fld_mis_id ='".$missionid."' AND b.fld_active = '1' AND a.fld_delstatus = '0'");
                
									 
		$getcontent = $ObjDB->QueryObject("SELECT fld_id AS misid, fld_extend_text AS extname FROM ".$tablename."
                                                            WHERE fld_mis_id ='".$missionid."' AND fld_created_by ='".$uid."' AND fld_delstatus = '0' 
                                                            UNION ALL
                                                            SELECT a.fld_id AS misid, a.fld_extend_text AS exname FROM ".$tablename." AS a
                                                            LEFT JOIN itc_license_miscontent_mapping AS b ON a.fld_id = b.fld_mis_id 
                                                            WHERE b.fld_license_id ='".$licenseid."' AND b.fld_module_id ='".$missionid."' AND b.fld_type ='".$moduletype."'
                                                            AND b.fld_active = '1' AND a.fld_delstatus = '0'"); 
					
						
			if($selectext->num_rows>0){
                                $res = $selectext->fetch_assoc();
                                extract($res);
                        }											 
						
					if($getcontent->num_rows>0)
					{
						$count++;
					?>
				<tr>
					<td><?php echo $missionname; ?></td>
					<td>									
						<div id="clspass">   
							<dl class='field row'>
								<div class="selectbox">
									<input type="hidden" name="misid" id="misid" value="<?php echo $texid;?>">
									<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
										<span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
										<b class="caret1"></b>
									</a>
									<div class="selectbox-options">
										<input type="text" class="selectbox-filter" placeholder="Search Materials">
										<ul role="options" style="width:100%">
										   <?php 
												while($res = $getcontent->fetch_assoc()){
													extract($res);?>
													<li><a tabindex="-1" href="#" data-option="<?php echo $misid;?>"><?php echo $extname; ?></a></li>
													<?php
												}?>      
										</ul>
									</div>
								</div> 
							</dl>
						</div>
					</td>
				</tr>
				<?php 
				}
			else
			{
			?>
			<tr>
				<td colspan="2">
					No records found
				</td>
			</tr>
		 <?php
			}
			?>
                               
            </tbody>
        </table>
    </div>
 <?php   
}/* Load extended mission content END */

/**
 * 
 *for save wca exp lock status
 * 
 */




if($oper == "wcaexplock" and $oper != '')
{		
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$flag = isset($method['flag']) ? $method['flag'] : '0';
	
	$ObjDB->NonQuery("UPDATE itc_class_indasexpedition_master SET fld_lock='".$flag."' WHERE fld_id='".$scheduleid."'");
}
if($oper == "wcamislock" and $oper != '')
{		
	$schid = isset($method['schid']) ? $method['schid'] : '0';
	$flag = isset($method['flag']) ? $method['flag'] : '0';
	$ObjDB->NonQuery("UPDATE itc_class_indasmission_master SET fld_lock='".$flag."' WHERE fld_id='".$schid."'");
}

if($oper=="clsgradetemplate"){
    $tempid = isset($method['tempid']) ? $method['tempid'] : '0';

    $qry=$ObjDB->QueryObject("SELECT a.fld_grade, a.fld_lower_bound, a.fld_upper_bound, a.fld_roundflag AS graderounding 
											  FROM itc_class_grading_scale_template_mapping AS a, itc_class_grade_template AS b 
											  WHERE a.fld_temp_id=b.fld_id AND b.fld_delstatus='0' 
											  	AND b.fld_id='".$tempid."'");											
    if($qry->num_rows > 0)
    {
        $count=1;
        while($row=$qry->fetch_assoc())
        {
            extract($row); ?>
            <div class="row" id="TextBoxDiv<?php echo $count;?>">
                <div class="three columns">
                    <dl class='field row'>
                        <dt class='text'>
                            <input type='textbox' id='lettergrade<?php echo $count;?>' name="lettergrade<?php echo $count;?>" class="gradedet"  maxlength="6" value="<?php echo $fld_grade;?>"  onchange='fn_divshow(1);'/>
                        </dt>
                    </dl>
                </div>
                <div class="three columns">
                    <dl class='field row'>
                        <dt class='text'>
                        <input type='textbox' id='lowerbound<?php echo $count; ?>' name="lowerbound<?php echo $count;?>" maxlength="3" value="<?php echo $fld_lower_bound;?>" onkeypress="return isNumber(event)"  onchange='fn_divshow(1);'/><span style="position: absolute;right:10px;">%</span>
                        </dt>
                    </dl>
                </div>
                <div class="three columns">
                    <dl class='field row'>
                        <dt class='text'>
                            <input type='textbox' id='higherbound<?php echo $count; ?>'  name="higherbound<?php echo $count;?>" maxlength="4" value="<?php echo $fld_upper_bound;?>" onkeypress="return isNumber(event)"  onchange='fn_divshow(1);'/>
                            <span style="position: absolute;right:10px;">%</span>
                        </dt>
                    </dl>
                </div>
            </div>
            <?php
            $count++;
        }
        $cnt = $count-1;
        echo "~".$graderounding."~".$cnt;
    }
  
}

/* Expedition End */
	@include("footer.php");