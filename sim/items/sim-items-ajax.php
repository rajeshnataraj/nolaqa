<?php 

	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");


if($oper == "itemfield" and $oper !='')
{
	$fname = isset($method['fname']) ? $method['fname'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$did= isset($method['did']) ? $method['did'] : '0';
	$pid= isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	
	if($did!='0')
	{
		$ObjDB->NonQuery("UPDATE itc_sim_items
							 SET fld_define_field='".$fname."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$did."' AND fld_cat_id='".$catid."'");
		
		/*---tags------*/
		$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
						 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
						 WHERE fld_tag_type='42' and fld_item_id='".$did."' AND 
						 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
		
		fn_tagupdate($tags,42,$did,$uid);	
	}
	else
	{
		$itemsid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sim_items(fld_cat_id, fld_pro_id, fld_define_field, fld_created_by, fld_created_date) VALUES ('".$catid."','".$pid."','".$fname."','".$uid."','".$date."')");
		
				
		/*--Tags insert-----*/	
				
		fn_taginsert($tags,42,$itemsid,$uid);
		
	}
	
	$productname = $ObjDB->SelectSingleValue("SELECT fld_product_name FROM itc_sim_product WHERE fld_id='".$pid."' ANd fld_delstatus='0'");
	
	echo "success~".$itemsid."~".$productname;
	
}

if($oper == "additem" and $oper !='')
{
	$itemname = isset($method['itemname']) ? $method['itemname'] : '';
	$message = isset($method['message']) ? $method['message'] : '';
	$upload = isset($method['upload']) ? $method['upload'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$proid = isset($method['proid']) ? $method['proid'] : '';
	$desid = isset($method['desid']) ? $method['desid'] : '';
	$itemid = isset($method['ditemid']) ? $method['ditemid'] : '';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	
	if($itemid =='')
	{
		$desitemid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sim_desitem(fld_cat_id,fld_pro_id,fld_des_id,fld_item_name,fld_message_details,fld_upload_filename,fld_created_by,fld_created_date) VALUES ('".$catid."','".$proid."','".$desid."','".$itemname."','".$message."','".$upload."','".$uid."','".$date."')");
		
			
		fn_taginsert($tags,43,$desitemid,$uid);
	}
	else
	{
		$ObjDB->NonQuery("UPDATE itc_sim_desitem
							 SET fld_item_name='".$itemname."', fld_message_details='".$message."', fld_upload_filename='".$upload."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$itemid."' AND fld_cat_id='".$catid."'");
		
		/*---tags------*/
		$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
						 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
						 WHERE fld_tag_type='43' and fld_item_id='".$itemid."' AND 
						 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	
		
		fn_tagupdate($tags,43,$itemid,$uid);
	}
	
	
	echo "success~".$itemid;
}

/*--- Delete the Fields ---*/
if($oper=="deletefields" and $oper != " " )
{
	try
	{
		$fieldid = isset($method['fieldid']) ? $method['fieldid'] : ''; 
		$catid = isset($method['catid']) ? $method['catid'] : ''; 
		$pid = isset($method['pid']) ? $method['pid'] : '';
		
		$productname = $ObjDB->SelectSingleValue("SELECT fld_product_name FROM itc_sim_product WHERE fld_id='".$pid."' ANd fld_delstatus='0'");
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sim_items 
											  WHERE fld_id='".$fieldid."' 
											  AND fld_delstatus='0'");

		if($count==1)
		{
			$ObjDB->NonQuery("UPDATE itc_sim_items 
							 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$fieldid."'");
			echo "success~".$productname;
		}
		else
		{
			echo "exists";
		}
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

/*--- Delete the Items ---*/
if($oper=="deleteitems" and $oper != " " )
{
	try
	{
		$itemid = isset($method['itemid']) ? $method['itemid'] : ''; 
		$desid = isset($method['desid']) ? $method['desid'] : '';
		
		$fieldname = $ObjDB->SelectSingleValue("SELECT fld_define_field FROM itc_sim_items WHERE fld_id='".$desid."' ANd fld_delstatus='0'");
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sim_desitem 
											  WHERE fld_id='".$itemid."' 
											  AND fld_delstatus='0'");

		if($count==1)
		{
			$ObjDB->NonQuery("UPDATE itc_sim_desitem 
							 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$itemid."'");
			echo "success~".$fieldname;
		}
		else
		{
			echo "exists";
		}
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

?>