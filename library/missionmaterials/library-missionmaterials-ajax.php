<?php
/*
	Created By - Mohan. M
	Page - library-missionmaterials-ajax.php
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
    
    $matdesptn = preg_replace('/%u([a-fA-F0-9]{4})/', '&#x\\1;', $materialdesc);
    $materialdescription = $ObjDB->EscapeStrAll($matdesptn);
    $materialname = $ObjDB->EscapeStrAll($materialname);
   
    $tags = isset($method['tags']) ? $method['tags'] : '';
    
    if($materialid!='0' && $materialid!='undefined')
    {
       
        $ObjDB->NonQuery("UPDATE itc_mis_materials_master SET fld_materials='".$materialname."', fld_mat_desc='".$materialdescription."', fld_catalog_url='".$catalogurl."', fld_thumbimg_url='".$thumbimageurl."', fld_upload_path='".$uploadimg."', 
							 	fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id='".$materialid."'");
        fn_tagupdate($tags,34,$materialid,$uid);
        
    }
 else {
         $materials_id = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mis_materials_master(fld_materials, fld_mat_desc, fld_catalog_url, fld_thumbimg_url, fld_upload_path, fld_sessprofile_id, fld_created_by, fld_created_date) VALUES ('".$materialname."', '".$materialdescription."', '".$catalogurl."', '".$thumbimageurl."', '".$uploadimg."', '".$usersessnid."', '".$uid."', '".$date."')");
         
        /*--Tags insert-----*/
        fn_taginsert($tags,'34',$materials_id,$uid);
      }
    echo "success";
    }
    catch(Exception $e){
		echo "invalid";
	}
}
/*--- Delete material Details ---*/
	if($oper == "deletematerials" and $oper != '')
	{		
		$materialid = isset($method['materialid']) ? $method['materialid'] : '0';
		$validate_materialid=true;
		if($materialid!=0)$validate_materialid=validate_datatype($materialid,'int');		
		if($validate_materialid){
			
			
				$ObjDB->NonQuery("UPDATE itc_mis_materials_master 
				                 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
								 WHERE fld_id='".$materialid."'");
				echo "success";
		}
		else{
				
				echo "fail";
			}
	}
	
@include("footer.php");