<?php 

	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	error_reporting(E_ALL);
	ini_set("display_errors","1");

if($oper == "product" and $oper != '')
{
	$proname = isset($method['pname']) ? $method['pname'] : '';
	$prokey = isset($method['pcode']) ? $method['pcode'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$productid = isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	
	//product key and asset fld id 
	$proid = explode("~",$prokey);
	$productkey = $proid[0];
	$assetid = $proid[1];
	
	$oldassetid = $ObjDB->SelectSingleValue("SELECT fld_asset_id FROM itc_sim_product WHERE fld_id='".$productid."'");
	/* get license id query start line */
	if($catid == '1')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_cul_mapping WHERE fld_lesson_id='".$assetid."' AND fld_active='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	if($catid == '2')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$assetid."' AND fld_type='1' AND fld_active='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	if($catid == '3')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$assetid."' AND fld_type='2' AND fld_active='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	if($catid == '5')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$assetid."' AND fld_type='7' AND fld_active='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	if($catid == '6')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_exp_mapping WHERE fld_exp_id='".$assetid."' AND fld_flag='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	if($catid == '8')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_pd_mapping WHERE fld_pd_id='".$assetid."' AND fld_flag='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	if($catid == '10')
	{
		$qry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mission_mapping WHERE fld_mis_id='".$assetid."' AND fld_flag='1' GROUP BY fld_license_id");
		if($qry->num_rows>0){
			while($row = $qry->fetch_assoc())
			{
				extract($row);
			}
		}
	}
	/* get license id query end line */	
	
	if($productid!='0')
	{
		$ObjDB->NonQuery("UPDATE itc_sim_product
							 SET fld_product_name='".$proname."', fld_product_key='".$productkey."', fld_asset_id='".$assetid."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$productid."' AND fld_cat_id='".$catid."'");
		
		/* Auto update for sim license mapping table start line */
		if($assetid!='')
		{
			if($oldassetid != $assetid)
			{

				$ObjDB->NonQuery("UPDATE itc_license_simproduct_mapping 
								 SET fld_active='0',fld_delstatus='1', fld_deleted_date = '".$date."' , fld_deleted_by = '".$uid."' WHERE fld_asset_id = '".$oldassetid."' AND fld_product_id ='".$productid."'");
	
				$ObjDB->NonQuery("INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_product_id,fld_asset_id, fld_type, fld_created_by, fld_created_date)
								 VALUES('".$licenseid."','".$productid."','".$assetid."','".$catid."', '".$uid."', '".$date."')");

			}
		}
		/* Auto update for sim license mapping table end line */
		
		/*---tags------*/
		$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
						 SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
						 WHERE fld_tag_type='41' and fld_item_id='".$productid."' AND 
						 fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");	

		fn_tagupdate($tags,41,$productid,$uid);	
	}
	else
	{
		$productid =$ObjDB->NonQueryWithMaxValue ("INSERT INTO itc_sim_product(fld_cat_id, fld_product_name, fld_product_key, fld_created_by, fld_created_date, fld_asset_id) VALUES ('".$catid."','".$proname."','".$productkey."','".$uid."','".$date."','".$assetid."')");
		
		/* Auto insert from sim licenses mapping table start line */ 
		if($licenseid !='')
		{
			$ObjDB->NonQuery("INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_product_id,fld_asset_id, fld_type, fld_created_by, fld_created_date)
									 VALUES('".$licenseid."','".$productid."','".$assetid."','".$catid."', '".$uid."', '".$date."')");
		}
		/* Auto insert from sim licenses mapping table end line */
		
		/*--Tags insert-----*/	
				
		fn_taginsert($tags,41,$productid,$uid);
	}
	
	echo "success~".$productid;
}

/*--- Delete the Product ---*/
if($oper=="deleteexproduct" and $oper != " " )
{
	try
	{
		$productid = isset($method['productid']) ? $method['productid'] : ''; 
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sim_product 
											  WHERE fld_id='".$productid."' 
											  AND fld_delstatus='0'");

		if($count==1)
		{
			$ObjDB->NonQuery("UPDATE itc_sim_product 
							 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$productid."'");
			echo "success";
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
