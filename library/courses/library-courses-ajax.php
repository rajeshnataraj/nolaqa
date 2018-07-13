<?php 
	@include("sessioncheck.php");	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	/*--- Save Course Details ---*/
	if($oper == "savecourse" and $oper != '')
	{		
		
		
		try /**Here starts with saving the details course master table**/
		{
		    
		$courseid = isset($method['courseid']) ? $method['courseid'] : 0;
		$assetid = isset($method['assetid']) ? ($method['assetid']) : '0';
		$coursename = isset($method['coursename']) ? ($method['coursename']) : '';		
		$courseicon = isset($method['courseicon']) ? $method['courseicon'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_courseicon=true;
		$validate_courseid=true;
		$validate_coursename=true;
		$validate_coursenamecheck=true;
		if($courseid!=0) $validate_courseid=validate_datatype($courseid,'int');
		$validate_coursename=validate_datas($coursename,'lettersonly'); 
		$checkcoursename = $coursename;
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_course_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_course_name,' ','')))='".$coursename."' 
											  AND fld_delstatus='0' AND fld_id<>'".$courseid."'");
		if($count != 0){ $validate_coursenamecheck=false; }
		
		/**for purpose remove unwanted scripts****/
		$assetid = $ObjDB->EscapeStrAll($assetid);
		$coursename = $ObjDB->EscapeStrAll($coursename);		
		
		
		if($courseicon!='')$validate_courseicon=isImage(__FULLCNTCOURSEICONPATH__.$courseicon);
		
			if($validate_coursename and $validate_courseicon and $validate_courseid and $validate_coursename and $validate_coursenamecheck)
			{
			if($courseid == 0)
			{
				$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_course_master(fld_course_name, fld_course_icon, fld_created_date, fld_created_by, fld_asset_id) 
				                                     VALUES ('".$coursename."','".$courseicon."','".$date."','".$uid."','".$assetid."')");
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,29,$maxid,$uid);
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_course_master 
				                 SET fld_course_name='".$coursename."',fld_course_icon='".$courseicon."', fld_updated_date='".$date."', 
								 fld_updated_by='".$uid."', fld_asset_id='".$assetid."' WHERE fld_id='".$courseid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='27' and fld_item_id='".$courseid."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,29,$courseid,$uid);			
			}
			
			     echo "success";
				 
			}
			else
			{
				 echo "fail";
			}
		}
		catch(Exception $e)
		{
			 echo "fail";
		}
		
	}
	
	/*--- Delete Course Details ---*/
	if($oper == "deletecourse" and $oper != '')
	{		
		$courseid = isset($method['courseid']) ? $method['courseid'] : '0';
		$validate_courseid=true;
		if($courseid!=0)$validate_courseid=validate_datatype($courseid,'int');
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_pd_master 
		                                      WHERE fld_course_id='".$courseid."' AND fld_delstatus='0'"); // this query to checking whether the course have lessons are not
		
		if($validate_courseid){
			
			if($count==0){
				$ObjDB->NonQuery("UPDATE itc_course_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$courseid."'");
				echo "success";
				
			}
		}
		else{
				
				echo "exists";
			}
	}
	

	
	/*--- Check the Course Name Duplication ---*/
	if($oper=="checkcoursename" and $oper != " " )
	{
		$coursesid = isset($method['uid']) ? $method['uid'] : '0';
		$coursename = isset($method['coursename']) ?  fnEscapeCheck($method['coursename']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_course_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_course_name,' ','')))='".$coursename."' 
											  AND fld_delstatus='0' AND fld_id<>'".$coursesid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	
	}
	
	
	/*--- Check Asset ID ---*/
	if($oper=="checkassetid" and $oper != " " )
	{   
	
	    $coursesid = isset($method['uid']) ? $method['uid'] : '0';
		$assetid = isset($method['txtassetid']) ? fnEscapeCheck($method['txtassetid']) : '0';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_course_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' 
											  AND fld_delstatus='0' AND fld_id<>'".$coursesid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}

	@include("footer.php");