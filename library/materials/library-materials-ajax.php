<?php
/*
	Created By - Vijayalakshmi. G
	Page - library-materials-ajax.php
	History:
*/
@include("sessioncheck.php");
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';
/* save and edit the material list for expedition */
if($oper=="savematerials" and $oper != " " )
{
    
    try{
     
    $materialid = isset($method['materialid']) ? $method['materialid'] : '0';
    $usersessnid = isset($method['usersessnid']) ? $method['usersessnid'] : '0';
    $materialname = isset($method['material_name']) ? $method['material_name'] : '0'; 
    $thumbimageurl = isset($method['thumb_imageurl']) ? ($method['thumb_imageurl']) : ''; 
    $catalogurl = isset($method['catalogurl']) ? ($method['catalogurl']) : ''; 
    $uploadimg = isset($method['uploadimage']) ? $method['uploadimage'] : '';
    $materialdesc = isset($method['material_desc']) ? $method['material_desc'] : '';
	$uploadfilesize = isset($method['material_size']) ? $method['material_size'] : '0'; // created by chandru		
    
    $matdesptn = preg_replace('/%u([a-fA-F0-9]{4})/', '&#x\\1;', $materialdesc);
    $materialdescription = $ObjDB->EscapeStrAll($matdesptn);
    $materialname = $ObjDB->EscapeStrAll($materialname);
   
    $tags = isset($method['tags']) ? $method['tags'] : '';
    if($materialid!='0' && $materialid!='undefined')
    {
       
        $ObjDB->NonQuery("UPDATE itc_materials_master SET fld_materials='".$materialname."', fld_mat_desc='".$materialdescription."', fld_catalog_url='".$catalogurl."', fld_thumbimg_url='".$thumbimageurl."', fld_upload_path='".$uploadimg."', fld_file_size='".$uploadfilesize."',
							 	fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id='".$materialid."'");
		// created by chandru 
		if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
		{ 
			$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
			$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

			$size=$totsize+$uploadfilesize;
			if($userid == $uid)
			{
				$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
			}
		}
		
        fn_tagupdate($tags,27,$materialid,$uid);
        
    }
 else {
         $materials_id = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_materials_master(fld_materials, fld_mat_desc, fld_catalog_url, fld_thumbimg_url, fld_upload_path, fld_file_size, fld_sessprofile_id, fld_created_by, fld_created_date) VALUES ('".$materialname."', '".$materialdescription."', '".$catalogurl."', '".$thumbimageurl."', '".$uploadimg."', '".$uploadfilesize."', '".$usersessnid."', '".$uid."', '".$date."')");
	 
	 	// created by chandru 10-06-2016
		if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
		{ 
			$totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");
			$userid=$ObjDB->SelectSingleValueInt("SELECT fld_user_id  FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

			$size=$totsize+$uploadfilesize;
			if($userid == $uid)
			{
				$ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' where fld_user_id='".$userid."'");
			}
		}
         
        /*--Tags insert-----*/
        fn_taginsert($tags,'27',$materials_id,$uid);
      }
    echo "success";
    }
    catch(Exception $e){
		echo "invalid";
	}
}

/***** created by chandru start line ******/
	if($oper == "filedelete" and $oper != '')
	{
		$filename = isset($method['filename']) ? $method['filename'] : '';
		$result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=materialicon');
	}
/***** created by chandru end line ******/

/*--- Delete material Details ---*/
	if($oper == "deletematerials" and $oper != '')
	{		
		$materialid = isset($method['materialid']) ? $method['materialid'] : '0';
		$validate_materialid=true;
		if($materialid!=0)$validate_materialid=validate_datatype($materialid,'int');
		if($validate_materialid){
			
			
				$ObjDB->NonQuery("UPDATE itc_materials_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$materialid."'");
                                
                                if($sessmasterprfid == 6 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) 
                                { 
                                    $totsize=$ObjDB->SelectSingleValueInt("SELECT fld_bytes FROM itc_user_usedspace_details where fld_user_id='".$uid."'");

                                    $filename=$ObjDB->SelectSingleValue("SELECT fld_upload_path FROM itc_materials_master where fld_id='".$materialid."'");

                                    $filesize=$ObjDB->SelectSingleValueInt("SELECT fld_file_size FROM itc_materials_master where fld_id='".$materialid."'");

                                    $size=$totsize-$filesize;
                                    
                                    if($size<0)
                                    {
                                       $size=0; 
                                    }

                                    $ObjDB->NonQuery("UPDATE itc_user_usedspace_details SET fld_bytes='".$size."' WHERE fld_user_id='".$uid."'");

                                    $result = file_get_contents(_CONTENTURL_.'deletefile.php?file='.$filename.'&key=delete&foldername=materialicon');
                                }
                                
				echo "success";
		}
		else{
				
				echo "fail";
			}
	}
	
@include("footer.php");