<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Save/Update student for test  ---*/
	if($oper == "maptotest" and $oper != '')
	{	
		$testid = (isset($_REQUEST['testid'])) ? $_REQUEST['testid'] : 0;
		$list3 = isset($_REQUEST['list1']) ? $_REQUEST['list1'] : '0';
		$list4 = isset($_REQUEST['list2']) ? $_REQUEST['list2'] : '0';
		$clasid= isset($_REQUEST['clasid']) ? $_REQUEST['clasid'] : '0';
		$sdate1 =(isset( $_REQUEST['sdate1'])) ?  $_REQUEST['sdate1'] : '';
		$edate1 =(isset( $_REQUEST['edate1'])) ?  $_REQUEST['edate1'] : '';
		$predate =(isset( $_REQUEST['predate'])) ?  $_REQUEST['predate'] : '';
		
		$list3=explode(",",$list3);
		$list4=explode(",",$list4);
		
		// Student mapping start
		for($i=0;$i<sizeof($list3);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_student_mapping WHERE fld_test_id='".$testid."' AND 
													fld_student_id='".$list3[$i]."' AND fld_flag='1' AND fld_class_id='".$clasid."' 
													AND fld_start_date='".date('Y-m-d',strtotime($sdate1))."' AND 
													fld_end_date='".date('Y-m-d',strtotime($edate1))."'");
															  
			$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
							WHERE fld_id='".$cnt."'");
		}
		if($list4[0] !=''){
			for($i=0;$i<sizeof($list4);$i++)
			{
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_student_mapping WHERE fld_test_id='".$testid."' 
				                                      AND fld_student_id='".$list4[$i]."' AND fld_flag='1' AND fld_class_id='".$clasid."' 
													  AND fld_start_date='".date('Y-m-d',strtotime($predate))."' ");
				
				$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
								WHERE fld_id='".$count."'");
				
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_student_mapping WHERE fld_test_id='".$testid."' 
				                                     AND fld_student_id='".$list4[$i]."' AND fld_class_id='".$clasid."' 
													 AND fld_start_date='".date('Y-m-d',strtotime($sdate1))."' 
													 AND fld_end_date='".date('Y-m-d',strtotime($edate1))."'");
				if($cnt==0 or $cnt=='')
				{
					$ObjDB->NonQuery("INSERT INTO itc_test_student_mapping(fld_test_id, fld_student_id, fld_created_by,fld_created_date, 
										fld_flag, fld_class_id, fld_start_date, fld_end_date) 
					                 VALUES ('".$testid."', '".$list4[$i]."', '".$uid."', '".$date."' , '1', '".$clasid."', 
									 		'".date('Y-m-d',strtotime($sdate1))."','".date('Y-m-d',strtotime($edate1))."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
									WHERE fld_id='".$cnt."'");
				}
			}
		}
	}
	
	
	/*--- Save/Update class for test  ---*/
	if($oper == "maptoclass" and $oper != '')
	{	
		$testid = (isset($_REQUEST['testid'])) ? $_REQUEST['testid'] : 0;
		$list1 = isset($_REQUEST['list1']) ? $_REQUEST['list1'] : '0';
		$list2 = isset($_REQUEST['list2']) ? $_REQUEST['list2'] : '0';
		
		$list1=explode(",",$list1);
		$list2=explode(",",$list2);
		// Class mapping start
		for($i=0;$i<sizeof($list1);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_class_mapping 
			                                    WHERE fld_test_id='".$testid."' AND fld_class_id='".$list1[$i]."' 
												AND fld_flag='1'");
			
			$ObjDB->NonQuery("UPDATE itc_test_class_mapping SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
							WHERE fld_test_id='".$testid."' AND fld_class_id='".$list1[$i]."' AND fld_id='".$cnt."'");
			
			$unstudentid = $ObjDB->QueryObject("SELECT fld_student_id FROM `itc_test_student_mapping` 
			                                   WHERE fld_class_id='".$list1[$i]."' AND fld_test_id='".$testid."' AND fld_flag='1'");
					if($unstudentid->num_rows > 0){
						while($row = $unstudentid->fetch_object()){
							$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
											WHERE fld_test_id='".$testid."' AND fld_class_id='".$list1[$i]."' 
												AND fld_student_id='".$row->fld_student_id."'");
						}
					}
			
			
		}
		
		if($list2[0] !=''){
			for($i=0;$i<sizeof($list2);$i++)
			{
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_class_mapping 
				                                     WHERE fld_test_id='".$testid."' AND fld_class_id='".$list2[$i]."'");
				
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_test_class_mapping(fld_test_id, fld_class_id, fld_created_by, fld_created_date, fld_flag) 
					                VALUES ('".$testid."', '".$list2[$i]."', '".$uid."', '".$date."' , '1')");
					
					$studentid = $ObjDB->QueryObject("SELECT fld_student_id FROM `itc_class_student_mapping` 
					                                 WHERE fld_class_id='".$list2[$i]."' AND fld_flag='1'");
					if($studentid->num_rows > 0){
						while($row = $studentid->fetch_object()){
							
							$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_student_mapping 
							                                     WHERE fld_test_id='".$testid."' AND fld_student_id='".$row->fld_student_id."'");
							if($cnt==0)
							{
									$ObjDB->NonQuery("INSERT INTO itc_test_student_mapping(fld_test_id, fld_student_id, fld_class_id, 
														fld_created_by, fld_created_date, fld_flag) 
									                 VALUES ('".$testid."', '".$row->fld_student_id."', '".$list2[$i]."', '".$uid."', '".$date."' , '1')");
							}
							else
							{
								$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
								                 WHERE fld_test_id='".$testid."' AND fld_class_id='".$list2[$i]."' 
												 AND fld_student_id='".$row->fld_student_id."' AND fld_id='".$cnt."'");
							}
							
						}
					}
				
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_test_class_mapping SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."'  
									WHERE fld_test_id='".$testid."' AND fld_class_id='".$list2[$i]."' AND fld_id='".$cnt."'");
					
					$selstudentid = $ObjDB->QueryObject("SELECT fld_student_id FROM `itc_test_student_mapping` 
					                                    WHERE fld_class_id='".$list2[$i]."' AND fld_test_id='".$testid."' AND fld_flag='0'");
					if($selstudentid->num_rows > 0){
						while($row = $selstudentid->fetch_object()){
							$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='1' ,fld_updated_by='".$uid."',fld_updated_date='".$date."'  
							                 WHERE fld_test_id='".$testid."' AND fld_class_id='".$list2[$i]."' 
											 AND fld_student_id='".$row->fld_student_id."'");
						}
					}
				}
			}
		}
	
	}
	
	
	if($oper == "deletestu" and $oper != '')
	{		
		$fieldid = isset($_REQUEST['fieldid']) ? $_REQUEST['fieldid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_flag='0',fld_deleted_by='".$uid."',fld_deleted_date='".$date."',fld_teacher_points_earned='' 
		                 WHERE fld_id='".$fieldid."'");
			
                $qryclass = $ObjDB->QueryObject("SELECT fld_test_id AS testid, fld_student_id AS studentid, fld_class_id AS classid FROM itc_test_student_mapping 
		                                  WHERE fld_id='".$fieldid."'");
                
                $row=$qryclass->fetch_assoc();
                extract($row);
                
                $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
                                        WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$fieldid."' 
                                            AND fld_student_id='".$studentid."'");
                
			
		echo "success";
	}
	
	/*******oper for reasign the test for the particular student********/
	if($oper == "reassigntest" and $oper != '')
	{
		$testid = isset($_REQUEST['testid']) ? $_REQUEST['testid'] : '0';
		$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
		$studentid = isset($_REQUEST['studentid']) ? $_REQUEST['studentid'] : '0';
		$predate = isset($_REQUEST['predate']) ? $_REQUEST['predate'] : '0';
		$startdate1 = isset($_REQUEST['startdate1']) ? $_REQUEST['startdate1'] : '0';
		$enddate1 = isset($_REQUEST['enddate1']) ? $_REQUEST['enddate1'] : '0';
		
		$startdate=date('Y-m-d',strtotime($startdate1));
		$stumapid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_test_student_mapping 
		                                  WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' 
										  AND fld_student_id='".$studentid."' AND fld_start_date='".$predate."'");
		
 		$ObjDB->NonQuery("UPDATE itc_test_student_mapping SET fld_max_attempts='0', fld_teacher_points_earned='', fld_start_date='".$startdate."', 
		                        fld_end_date='".date('Y-m-d',strtotime($enddate1))."', fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
								  WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' 
								  AND fld_student_id='".$studentid."' AND fld_flag='1' AND fld_id='".$stumapid."'");
		 
		 
		$ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
						WHERE fld_test_id='".$testid."' AND fld_student_id='".$studentid."' AND fld_delstatus='0'");
		 
                 $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_delstatus='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
                                        WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."' 
                                            AND fld_student_id='".$studentid."'");
                
	}

	@include("footer.php");