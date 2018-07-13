<?php 
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	
	if($oper == "saverepository" and $oper != '')
	{	
		
		try
		{
			
		$repositoryid = isset($method['repositoryid']) ? $method['repositoryid'] : '';	
		$repositoryname = isset($method['repositoryname']) ? ($method['repositoryname']) : '';
		$filename = isset($method['repositoryfilename']) ? $method['repositoryfilename'] : '';	
		$repositorytype = isset($method['repositorytype']) ? $method['repositorytype'] : '';
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_repositoryid=true;
		$validate_filename=true;
		if($repositoryid!=0)   $validate_repositoryid=validate_datatype($repositoryid,'int');
		$validate_filename=checkFileIsExist(_CONTENTURL_."asset/".$filename); 
		
		/**for purpose remove unwanted scripts****/
		$repositoryname = $ObjDB->EscapeStrAll($repositoryname);
		
			if($validate_repositoryid)
			{
			
				if($repositoryid!='' and $repositoryid!='0')
				{	
				
					$ObjDB->NonQuery("UPDATE itc_repository_master SET fld_repository_name='".$repositoryname."', fld_file_name='".$filename."', fld_file_type='".$repositorytype."' ,
					                        fld_updated_by='".$uid."',fld_updated_date='".$date."' 
									WHERE fld_id='".$repositoryid."'");	
					
				}
				else{
					 $ObjDB->NonQuery("INSERT INTO itc_repository_master (fld_repository_name, fld_file_name, fld_created_by,  fld_district_id, fld_file_type,
					                              fld_school_id,fld_role_id, fld_created_date) VALUES('".$repositoryname."','".$filename."','".$uid."',
												  '".$districtid."', '".$repositorytype."', '".$schoolid."','".$sessroleid."','".$date."')");
					 
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
	
	
	
	if($oper == "deleterepository" and $oper != '')
	{	
            try{
		$repositoryid = isset($method['id']) ? $method['id'] : '';
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_repositoryid=true;
		
		if($repositoryid!=0)  $validate_repositoryid=validate_datatype($repositoryid,'int');
			if($validate_repositoryid)
                            $ObjDB->NonQuery("UPDATE itc_repository_master SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                            fld_deleted_date='".$date."'  WHERE fld_id='".$repositoryid."'");
                        else
                            echo "fail";		
            }
            catch(Exception $e)
            {
                echo "fail";
            }
            echo "success";
	}

	@include("footer.php");