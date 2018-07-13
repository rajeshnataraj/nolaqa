<?php 
	@include("sessioncheck.php");
	
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	
	if($oper == "saveasset" and $oper != '')
	{	
		
		try
		{
			
		$assetid = isset($method['assetid']) ? $method['assetid'] : '';	
		$assetname = isset($method['assetname']) ? ($method['assetname']) : '';
		$filename = isset($method['assetfilename']) ? $method['assetfilename'] : '';	
		$assettype = isset($method['assettype']) ? $method['assettype'] : '';
		$filesize = isset($method['filesize']) ? $method['filesize'] : '';
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_assetid=true;
		$validate_filename=true;
		if($assetid!=0)   $validate_assetid=validate_datatype($assetid,'int');
		$validate_filename=checkFileIsExist(_CONTENTURL_."asset/".$filename); 
		
		/**for purpose remove unwanted scripts****/
		$assetname = $ObjDB->EscapeStrAll($assetname);
		
			if($validate_assetid)
			{
			
				if($assetid!='' and $assetid!='0')
				{	
				
					$ObjDB->NonQuery("UPDATE itc_asset_master SET fld_asset_name='".$assetname."', fld_file_name='".$filename."', fld_file_size='".$filesize."', fld_file_type='".$assettype."', 
					                        fld_updated_by='".$uid."',fld_updated_date='".$date."' 
									WHERE fld_id='".$assetid."'");	
					if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
					{ 
						$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
						$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

						$size=$totsize+$filesize;
						if($userid == $uid)
						{
							$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
						}
					}
					
				}
				else{
					 $ObjDB->NonQuery("INSERT INTO itc_asset_master (fld_asset_name, fld_file_name, fld_file_size, fld_created_by,  fld_district_id, fld_file_type,
					                              fld_school_id,fld_role_id, fld_created_date) VALUES('".$assetname."','".$filename."','".$filesize."','".$uid."',
												  '".$districtid."', '".$assettype."','".$schoolid."','".$sessroleid."','".$date."')");
					if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
					{ 
						$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
						$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

						$size=$totsize+$filesize;
						if($userid == $uid)
						{
							$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
						}
					}
					 
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
	/***** created by chandru start line ******/
	if($oper == "filedelete" and $oper != '')
	{
		$filename = isset($method['filename']) ? $method['filename'] : '';
		$result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=asset');
	}
	/***** created by chandru end line ******/
	
	if($oper == "deleteasset" and $oper != '')
	{	
		
		
		try{
			
		$assetid = isset($method['id']) ? $method['id'] : '';
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_assetid=true;
		
		if($assetid!=0)  $validate_assetid=validate_datatype($assetid,'int');
			if($validate_assetid)
                        { 
                                
		
				$ObjDB->NonQuery("UPDATE itc_asset_master SET fld_delstatus='1', fld_deleted_by='".$uid."', 
				fld_deleted_date='".$date."'  WHERE fld_id='".$assetid."'");
                                
                                if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
                                { 
                                    $totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

                                    $filename=$ObjDB->SelectSingleValue("SELECT fld_file_name FROM itc_asset_master where fld_id='".$assetid."'");

                                    $filesize=$ObjDB->SelectSingleValueInt("SELECT fld_file_size FROM itc_asset_master where fld_id='".$assetid."'");

                                    $size=$totsize-$filesize;
                                    
                                    if($size<0)
                                    {
                                        $size=0;
                                    }
                                    
                                    $ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' WHERE fld_user_id='".$uid."'");

                                    $result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=asset');
                                }
                                
                        }
                        else
                        {
                            echo "fail";	
                        }
		}
		catch(Exception $e)
		{
			echo "fail".$e->getMessage();

		}
		echo "success";
	}

	@include("footer.php");