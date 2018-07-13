<?php 
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	
	/*--- Save Custom content Details ---*/
	if($oper == "savecustomcontent" and $oper != '')
	{		
		
		
		try /**Here starts with saving the details customcontent master table**/
		{
		    
		$id = isset($method['customcontentid']) ? $method['customcontentid'] : 0;
		$pointspossible = isset($method['pointspossible']) ? $ObjDB->EscapeStrAll($method['pointspossible']) : '0';
		$contentname = isset($method['contentname']) ? $ObjDB->EscapeStrAll($method['contentname']) : '';	
		$tags = isset($method['tags']) ? $method['tags'] : '';	
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_id=true;
		$validate_contentname=true;
		$validate_points=true;
		
		if($id!=0)$validate_id=validate_datatype($id,'int');
		$validate_points=validate_datatype($pointspossible,'int');
		$validate_contentname=validate_datas($contentname,'lettersonly'); 
		
			if($validate_contentname  and $validate_id and $validate_points)
			{
			if($id == 0)
			{
				$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_customcontent_master(fld_contentname,fld_pointspossible, fld_createddate, fld_createdby) 
				                                     VALUES ('".$contentname."','".$pointspossible."','".date('Y-m-d H:i:s')."','".$uid."')");
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,25,$maxid,$uid); 
			}
			else
			{
				
				$ObjDB->NonQuery("UPDATE itc_customcontent_master
				                 SET fld_contentname='".$contentname."',fld_pointspossible='".$pointspossible."',fld_updateddate='".date('Y-m-d H:i:s')."', 
								 fld_updatedby='".$uid."' WHERE fld_id='".$id."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
								 WHERE fld_tag_type='25' and fld_item_id='".$id."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,25,$id,$uid);			
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
	
	/*--- Delete Customcontent ---*/
	if($oper == "deletecustomcontent" and $oper != '')
	{		
		$id = isset($method['ccid']) ? $method['ccid'] : '0';
		$validate_id=true;
		if($id!=0)$validate_id=validate_datatype($id,'int');
		
		if($validate_id){
			
			
				$ObjDB->NonQuery("UPDATE itc_customcontent_master 
				                 SET fld_delstatus='1', fld_deleteddate='".date('Y-m-d H:i:s')."', fld_deletedby='".$uid."' 
								 WHERE fld_id='".$id."'");
				echo "success";
				
			
		}
	}

	
	/*--- Check the content Name Duplication ---*/
	if($oper=="checkcustomcontentname" and $oper != "" )
	{
		$id = isset($method['id']) ? $method['id'] : '0';
		$contentname = isset($method['contentname']) ?  fnEscapeCheck($method['contentname']) : '';
		
	
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_customcontent_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_contentname,' ','')))='".$contentname."' 
											  AND fld_delstatus='0' AND fld_id<>'".$id."' AND fld_createdby='".$uid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	
	}
	
	@include("footer.php");