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
		$unitname = isset($method['unitname']) ? ($method['unitname']) : '';		
		$uniticon = isset($method['uniticon']) ? $method['uniticon'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';	
                
                
                $list10 = isset($method['list10']) ? $method['list10'] : '';
                $list9 = isset($method['list9']) ? $method['list9'] : '';
               
                $list10=explode(",",$list10);
                $list9=explode(",",$list9);
                
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_uniticon=true;
		$validate_unitid=true;
		$validate_unitname=true;
		$validate_unitnamecheck=true;
		if($unitid!=0) $validate_unitid=validate_datatype($unitid,'int');
		/**for purpose remove unwanted scripts****/
		$assetid = $ObjDB->EscapeStrAll($assetid);
		$unitname = $ObjDB->EscapeStrAll($unitname);		
		
		
		if($uniticon!='')$validate_uniticon=isImage(__FULLCNTUNITICONPATH__.$uniticon);
		
			if($validate_uniticon and $validate_unitid)
			{
			if($unitid == 0)
			{
				$maxid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sosunit_master(fld_unit_name, fld_unit_icon, fld_created_date, fld_created_by) 
				                                     VALUES ('".$unitname."','".$uniticon."','".$date."','".$uid."')");
                                $unitid = $maxid;
				
				/*--Tags insert-----*/	
				
				fn_taginsert($tags,35,$maxid,$uid);
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_sosunit_master 
				                 SET fld_unit_name='".$unitname."',fld_unit_icon='".$uniticon."', fld_updated_date='".$date."', 
								 fld_updated_by='".$uid."' WHERE fld_id='".$unitid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
				                 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
								 WHERE fld_tag_type='4' and fld_item_id='".$unitid."' AND 
								 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
						
				fn_tagupdate($tags,35,$unitid,$uid);			
			}
			
			     echo "success";
				 
			}
			else
			{
				 echo "fail";
			}
                        
                        if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					
                                        
					$cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_unit_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_unit_id='".$unitid."'");
					if($cnt1==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_sosunit_mapping (fld_license_id, fld_unit_id, fld_access, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$unitid."','1', '".$uid."', '".date("Y-m-d H:i:s")."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_sosunit_mapping 
											SET fld_access='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_unit_id='".$unitid."'");
					}
					
				}
			}
                                
                        if($list9[0]!= '') {
				for($j=0;$j<sizeof($list9);$j++)
				{
                                    $cnt2 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
									  FROM itc_license_sosunit_mapping 
									  WHERE fld_license_id='".$list9[$j]."'  AND fld_unit_id='".$unitid."'");
								
                                    if($cnt2==1)
                                    {
                                        
                                        
                                        $ObjDB->NonQuery("UPDATE itc_license_sosunit_mapping 
							  SET fld_access='0', fld_updated_date ='".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
							  WHERE fld_license_id='".$list9[$j]."'  AND fld_unit_id='".$unitid."'");
                                    }
                                }
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
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sosunit_master 
		                                      WHERE fld_id='".$unitid."' AND fld_delstatus='0'"); // this query to checking whether the unit have lessons are not
		
		if($validate_unitid){
			
			if($count!=0){
				$ObjDB->NonQuery("UPDATE itc_sosunit_master 
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
		
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_sosunit_master 
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