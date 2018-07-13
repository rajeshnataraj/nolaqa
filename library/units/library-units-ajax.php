<?php 
	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	/*--- Save Unit Details ---*/
	if($oper == "saveunits" and $oper != '')
	{		
		
		
		try /**Here starts with saving the details unit master table**/
		{
		    
		$unitid = isset($method['unitid']) ? $method['unitid'] : 0;
		$assetid = isset($method['assetid']) ? ($method['assetid']) : '0';
		$unitname = isset($method['unitname']) ? ($method['unitname']) : '';		
		$uniticon = isset($method['uniticon']) ? $method['uniticon'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_uniticon=true;
		$validate_unitid=true;
		$validate_unitname=true;
		$validate_unitnamecheck=true;
		if($unitid!=0) $validate_unitid=validate_datatype($unitid,'int');
		$validate_unitname=validate_datas($unitname,'lettersonly'); 
		$checkunitname = $unitname;
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_unit_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_unit_name,' ','')))='".$unitname."' 
											  AND fld_delstatus='0' AND fld_id<>'".$unitid."'");
		if($count != 0){ $validate_unitnamecheck=false; }
		
		/**for purpose remove unwanted scripts****/
		$assetid = $ObjDB->EscapeStrAll($assetid);
		$unitname = $ObjDB->EscapeStrAll($unitname);		
		
		
		if($uniticon!='')$validate_uniticon=isImage(__FULLCNTUNITICONPATH__.$uniticon);
		
			if($validate_unitname and $validate_uniticon and $validate_unitid and $validate_unitname and $validate_unitnamecheck)
			{
			if($unitid == 0)
			{
				$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_unit_master(fld_unit_name, fld_unit_icon, fld_created_date, fld_created_by, fld_asset_id) 
				                                     VALUES ('".$unitname."','".$uniticon."','".$date."','".$uid."','".$assetid."')");
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,4,$maxid,$uid);
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_unit_master 
				                 SET fld_unit_name='".$unitname."',fld_unit_icon='".$uniticon."', fld_updated_date='".$date."', 
								 fld_updated_by='".$uid."', fld_asset_id='".$assetid."' WHERE fld_id='".$unitid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='4' and fld_item_id='".$unitid."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,4,$unitid,$uid);			
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
	
	/*--- Delete Unit Details ---*/
	if($oper == "deleteunits" and $oper != '')
	{		
		$unitid = isset($method['unitid']) ? $method['unitid'] : '0';
		$validate_unitid=true;
		if($unitid!=0)$validate_unitid=validate_datatype($unitid,'int');
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_ipl_master 
		                                      WHERE fld_unit_id='".$unitid."' AND fld_delstatus='0'"); // this query to checking whether the unit have lessons are not
		
		if($validate_unitid){
			
			if($count==0){
				$ObjDB->NonQuery("UPDATE itc_unit_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$unitid."'");
				echo "success";
				
			}
		}
		else{
				
				echo "exists";
			}
	}
	

	
	/*--- Check the Unit Name Duplication ---*/
	if($oper=="checkunitname" and $oper != " " )
	{
		$unitsid = isset($method['uid']) ? $method['uid'] : '0';
		$unitname = isset($method['unitname']) ?  fnEscapeCheck($method['unitname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_unit_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_unit_name,' ','')))='".$unitname."' 
											  AND fld_delstatus='0' AND fld_id<>'".$unitsid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	
	}
	
	
	/*--- Check Asset ID ---*/
	if($oper=="checkassetid" and $oper != " " )
	{   
	
	    $unitsid = isset($method['uid']) ? $method['uid'] : '0';
		$assetid = isset($method['txtassetid']) ? fnEscapeCheck($method['txtassetid']) : '0';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_unit_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' 
											  AND fld_delstatus='0' AND fld_id<>'".$unitsid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}

	@include("footer.php");