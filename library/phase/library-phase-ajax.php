<?php 
	@include("sessioncheck.php");	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	/*--- Save Unit Details ---*/
	if($oper == "savephase" and $oper != '')
	{		
		
		
		try /**Here starts with saving the details unit master table**/
		{
                
                $phaseid = isset($method['phaseid']) ? $method['phaseid'] : 0;
                $phasename = isset($method['phasename']) ? ($method['phasename']) : '';	
                $phaseicon = isset($method['phaseicon']) ? $method['phaseicon'] : '';
		$unitid = isset($method['unitid']) ? $method['unitid'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
                
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_phaseicon=true;
		$validate_phaseid=true;
		$validate_phasename=true;
		$validate_phasenamecheck=true;
		if($phaseid!=0) $validate_phaseid=validate_datatype($phaseid,'int');
		
		/**for purpose remove unwanted scripts****/
		$phasename = $ObjDB->EscapeStrAll($phasename);		
		
		
		if($phaseicon!='')$validate_phaseicon=isImage(__FULLCNTUNITICONPATH__.$phaseicon);
		
			if($validate_phaseicon and $validate_phaseid)
			{
			if($phaseid == 0)
			{
				$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sosphase_master(fld_unit_id, fld_phase_name, fld_phase_icon, fld_created_date, fld_created_by) 
				                                     VALUES ('".$unitid."','".$phasename."','".$phaseicon."','".$date."','".$uid."')");
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,36,$maxid,$uid);
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_sosphase_master 
				                 SET fld_unit_id='".$unitid."',fld_phase_name='".$phasename."',fld_phase_icon='".$phaseicon."', fld_updated_date='".$date."', 
								 fld_updated_by='".$uid."' WHERE fld_id='".$phaseid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='4' and fld_item_id='".$phaseid."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,36,$phaseid,$uid);			
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
	if($oper == "deletephase" and $oper != '')
	{		
		$phaseid = isset($method['phaseid']) ? $method['phaseid'] : '0';
		$validate_phaseid=true;
		if($phaseid!=0)$validate_phaseid=validate_datatype($phaseid,'int');
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sosphase_master 
		                                      WHERE fld_id='".$phaseid."' AND fld_delstatus='0'"); // this query to checking whether the unit have lessons are not
		
		if($validate_phaseid){
			
			if($count!=0){
				$ObjDB->NonQuery("UPDATE itc_sosphase_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$phaseid."'");
				echo "success";
				
			}
		}
		else{
				
				echo "exists";
			}
	}
	

	
	/*--- Check the Unit Name Duplication ---*/
	if($oper=="checkphasename" and $oper != " " )
	{
		$phaseid = isset($method['uid']) ? $method['uid'] : '0';
		$phasename = isset($method['phasename']) ?  fnEscapeCheck($method['phasename']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sosphase_master 
		                                      WHERE MD5(LCASE(REPLACE(fld_phase_name,' ','')))='".$phasename."' 
											  AND fld_delstatus='0' AND fld_id<>'".$phaseid."'");
											  
		if($count == 0){ echo "true"; }	else { echo "false"; }
	
	}

	@include("footer.php");