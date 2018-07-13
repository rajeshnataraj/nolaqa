<?php 

	@include("sessioncheck.php");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	$date=date("Y-m-d H:i:s");
	error_reporting(E_ALL);
	ini_set("display_errors","1");

if($oper == "createproduct" and $oper != '')
{
	$proname = isset($method['pname']) ? $method['pname'] : '';
	$procode = isset($method['pcode']) ? $method['pcode'] : '';
	$vernumber = isset($method['vernumber']) ? $method['vernumber'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$productid = isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	
	$productcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_product_name) FROM itc_sim_product where fld_product_name='".$proname."' AND fld_version_number='".$vernumber."' AND fld_delstatus='0'");
	$catname=$ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_sim_category where fld_id='".$catid."' AND fld_delstatus='0'");
	
	if($productcount>0)
	{
		echo "matched";
	}
	else
	{
		$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,1 as simtype FROM itc_ipl_master as a 
										LEFT JOIN itc_ipl_version_track as b on a.fld_id=b.fld_ipl_id
										WHERE a.fld_asset_id='".$procode."' AND a.fld_lesson_type='1' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
		if($qry->num_rows==0) //Module
		{
			$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,2 as simtype FROM itc_module_master as a 
											LEFT JOIN itc_module_version_track as b on a.fld_id=b.fld_mod_id
											WHERE a.fld_asset_id='".$procode."' AND a.fld_module_type='1' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
		}
		if($qry->num_rows==0) //MathModule
		{
			$qrymodid = $ObjDB->QueryObject("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_asset_id='".$procode."' AND fld_delstatus='0'"); 
			if($qrymodid->num_rows>0)
			{
				while($row = $qrymodid->fetch_assoc())
				{
					extract($row);
					$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,3 as simtype FROM itc_mathmodule_master as a 
													LEFT JOIN itc_module_version_track as b on a.fld_module_id=b.fld_mod_id
													WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'"); 
				}
			}
			
		}
		if($qry->num_rows==0) //Expedition
		{
			$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,4 as simtype FROM itc_exp_master as a 
											LEFT JOIN itc_exp_version_track as b on a.fld_id=b.fld_exp_id
											WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
		}
		if($qry->num_rows==0) //PD
		{
			$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,5 as simtype FROM itc_pd_master as a 
											LEFT JOIN itc_pd_version_track as b on a.fld_id=b.fld_pd_id
											WHERE a.fld_asset_id='".$procode."' AND a.fld_lesson_type='1' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
		}
		if($qry->num_rows==0) //Mission
		{
			$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,6 as simtype FROM itc_mission_master as a 
											LEFT JOIN itc_mission_version_track as b on a.fld_id=b.fld_mis_id
											WHERE a.fld_asset_id='".$procode."' AND a.fld_mistype='0' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
		}
		if($qry->num_rows==0) //Unit
		{
			$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,7 as simtype FROM itc_unit_master as a 
											LEFT JOIN itc_unit_version_track as b on a.fld_id=b.fld_unit_id
											WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
		}
		if($qry->num_rows==0) //Course
		{
			$qry = $ObjDB->QueryObject("SELECT fld_id as id,8 as simtype FROM itc_course_master WHERE fld_asset_id='".$procode."' AND fld_delstatus='0'");
		}
		if($qry->num_rows==0) //Quest
		{
			$qrymodid = $ObjDB->QueryObject("SELECT fld_id FROM itc_module_master WHERE fld_asset_id='".$procode."' AND fld_module_type='7' AND fld_delstatus='0'"); 
			if($qrymodid->num_rows>0)
			{
				while($row = $qrymodid->fetch_assoc())
				{
					extract($row);
					$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,9 as simtype FROM itc_module_master as a 
													LEFT JOIN itc_module_version_track as b on a.fld_id=b.fld_mod_id
													WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' AND a.fld_module_type='7' AND b.fld_version='".$vernumber."' and b.fld_delstatus='0'"); 
				}
			}
		}
                if($qry->num_rows==0) // Nondigital
		{
                    $qry = $ObjDB->QueryObject("SELECT fld_id as id,10 as simtype FROM itc_nondigicontent_product WHERE fld_asset_id='".$procode."' AND fld_version_number='".$vernumber."' AND fld_delstatus='0'");
                }

		if($qry->num_rows>0)
		{
			while($row = $qry->fetch_assoc())
			{
				extract($row);
				
				//insert product
				$productid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_sim_product(fld_cat_id, fld_product_name, fld_asset_id, fld_product_code, fld_version_number, fld_product_type, fld_created_by, fld_created_date) VALUES ('".$catid."','".$proname."','".$id."','".$procode."','".$vernumber."','".$simtype."','".$uid."','".$date."')");
				
				//License id
				if($simtype == '1')//IPL
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_cul_mapping WHERE fld_lesson_id='".$id."' AND fld_active='1' GROUP BY fld_license_id");
				}
				if($simtype == '2')//Module
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$id."' AND fld_type='1' AND fld_active='1' GROUP BY fld_license_id");
				}
				if($simtype == '3')//MathModule
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$id."' AND fld_type='2' AND fld_active='1' GROUP BY fld_license_id");
				}
				if($simtype == '4')//Expedition
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_exp_mapping WHERE fld_exp_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '5')//PD
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_pd_mapping WHERE fld_pd_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '6')//Mission
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mission_mapping WHERE fld_mis_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '7')//Unit
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_unit_mapping WHERE fld_unit_id='".$id."' AND fld_access='1' GROUP BY fld_license_id");
				}
				if($simtype == '8')//Course
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_course_mapping WHERE fld_course_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '9')//Quest
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$id."' AND fld_type='7' AND fld_active='1' GROUP BY fld_license_id");
				}
                                if($simtype == '10') //Non Digital
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_nondigitalcontent_mapping WHERE fld_product_id='".$id."' AND fld_access='1' GROUP BY fld_license_id");
				}
				
				/* Auto insert from sim licenses mapping table start line */ 
				if($licenseqry->num_rows>0)
				{
					while($licrow = $licenseqry->fetch_assoc())
					{
						extract($licrow);
						   $ObjDB->NonQuery("INSERT INTO itc_license_simproduct_mapping(fld_license_id,fld_cat_id,fld_asset_id, fld_type,fld_product_id, fld_created_by, fld_created_date)
														VALUES('".$licenseid."','".$catid."','".$id."','".$simtype."','".$productid."', '".$uid."', '".$date."')");
						
					}
				}
				/* Auto insert from sim licenses mapping table end line */
				
				/*--Tags insert-----*/	
				fn_taginsert($tags,41,$productid,$uid);
				
				echo "success~".$productid."~".$catname;
			}
		}
		else
		{
			echo "fail";
		}
	}
	
}

//edit product
if($oper == "editproduct" and $oper != '')
{
	$proname = isset($method['pname']) ? $method['pname'] : '';
	$procode = isset($method['pcode']) ? $method['pcode'] : '';
	$vernumber = isset($method['vernumber']) ? $method['vernumber'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$productid = isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	
	$productcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_product_name) FROM itc_sim_product where fld_product_name='".$proname."' AND fld_version_number='".$vernumber."' AND fld_id!='".$productid."' AND fld_delstatus='0'");
	$catname=$ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_sim_category where fld_id='".$catid."' AND fld_delstatus='0'");

	$oldassetid = $ObjDB->SelectSingleValue("SELECT fld_asset_id FROM itc_sim_product WHERE fld_id='".$productid."'");
	
	if($productcount>0)
	{
		echo "matched";
	}
	else
	{
		if($oldassetid!=$procode) //IPL
		{
			$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,1 as simtype FROM itc_ipl_master as a 
										LEFT JOIN itc_ipl_version_track as b on a.fld_id=b.fld_ipl_id
										WHERE a.fld_asset_id='".$procode."' AND a.fld_lesson_type='1' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
			if($qry->num_rows==0) //Module
			{
				$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,2 as simtype FROM itc_module_master as a 
												LEFT JOIN itc_module_version_track as b on a.fld_id=b.fld_mod_id
												WHERE a.fld_asset_id='".$procode."' AND a.fld_module_type='1' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
			}
			if($qry->num_rows==0) //MathModule
			{
				$qrymodid = $ObjDB->QueryObject("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_asset_id='".$procode."' AND fld_delstatus='0'"); 
				if($qrymodid->num_rows>0)
				{
					while($row = $qrymodid->fetch_assoc())
					{
						extract($row);
						$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,3 as simtype FROM itc_mathmodule_master as a 
														LEFT JOIN itc_module_version_track as b on a.fld_module_id=b.fld_mod_id
														WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'"); 
					}
				}

			}
			if($qry->num_rows==0) //Expedition
			{
				$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,4 as simtype FROM itc_exp_master as a 
												LEFT JOIN itc_exp_version_track as b on a.fld_id=b.fld_exp_id
												WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
			}
			if($qry->num_rows==0) //PD
			{
				$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,5 as simtype FROM itc_pd_master as a 
												LEFT JOIN itc_pd_version_track as b on a.fld_id=b.fld_pd_id
												WHERE a.fld_asset_id='".$procode."' AND a.fld_lesson_type='1' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
			}
			if($qry->num_rows==0) //Mission
			{
				$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,6 as simtype FROM itc_mission_master as a 
												LEFT JOIN itc_mission_version_track as b on a.fld_id=b.fld_mis_id
												WHERE a.fld_asset_id='".$procode."' AND a.fld_mistype='0' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
			}
			if($qry->num_rows==0) //Unit
			{
				$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,7 as simtype FROM itc_unit_master as a 
												LEFT JOIN itc_unit_version_track as b on a.fld_id=b.fld_unit_id
												WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' and b.fld_version='".$vernumber."' and b.fld_delstatus='0'");
			}
			if($qry->num_rows==0) //Course
			{
				$qry = $ObjDB->QueryObject("SELECT fld_id as id,8 as simtype FROM itc_course_master WHERE fld_asset_id='".$procode."' AND fld_delstatus='0'");
			}
			if($qry->num_rows==0) //Quest
			{
				$qrymodid = $ObjDB->QueryObject("SELECT fld_id FROM itc_module_master WHERE fld_asset_id='".$procode."' AND fld_module_type='7' AND fld_delstatus='0'"); 
				if($qrymodid->num_rows>0)
				{
					while($row = $qrymodid->fetch_assoc())
					{
						extract($row);
						$qry = $ObjDB->QueryObject("SELECT a.fld_id as id,9 as simtype FROM itc_module_master as a 
														LEFT JOIN itc_module_version_track as b on a.fld_module_id=b.fld_mod_id
														WHERE a.fld_asset_id='".$procode."' AND a.fld_delstatus='0' a.AND fld_module_type='7' AND b.fld_version='".$vernumber."' and b.fld_delstatus='0'"); 
					}
				}
			}
			if($qry->num_rows==0) // Nondigital
			{
				$qry = $ObjDB->QueryObject("SELECT fld_id as id,10 as simtype FROM itc_nondigicontent_product WHERE fld_asset_id='".$procode."' AND fld_version_number='".$vernumber."' AND fld_delstatus='0'");
			}

			if($qry->num_rows>0)
			{
				while($row = $qry->fetch_assoc())
				{
					extract($row);

					//License id
				if($simtype == '1') //IPL
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_cul_mapping WHERE fld_lesson_id='".$id."' AND fld_active='1' GROUP BY fld_license_id");
				}
				if($simtype == '2') //Maodule
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$id."' AND fld_type='1' AND fld_active='1' GROUP BY fld_license_id");
				}
				if($simtype == '3') //Math Module
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$id."' AND fld_type='2' AND fld_active='1' GROUP BY fld_license_id");
				}
				if($simtype == '4') //Expedition
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_exp_mapping WHERE fld_exp_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '5') //PD
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_pd_mapping WHERE fld_pd_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '6') //Mission
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mission_mapping WHERE fld_mis_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '7') //Unit
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_unit_mapping WHERE fld_unit_id='".$id."' AND fld_access='1' GROUP BY fld_license_id");
				}
				if($simtype == '8') //Course
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_course_mapping WHERE fld_course_id='".$id."' AND fld_flag='1' GROUP BY fld_license_id");
				}
				if($simtype == '9') //Quest
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_mod_mapping WHERE fld_module_id='".$id."' AND fld_type='7' AND fld_active='1' GROUP BY fld_license_id");
				}
                                
                                if($simtype == '10') //Non Digital
				{
					$licenseqry = $ObjDB->QueryObject("SELECT fld_license_id AS licenseid FROM itc_license_nondigitalcontent_mapping WHERE fld_product_id='".$id."' AND fld_access='1' GROUP BY fld_license_id");
				}

					$ObjDB->NonQuery("UPDATE itc_sim_product
										 SET fld_asset_id='".$id."', fld_product_name='".$proname."', fld_product_type='".$simtype."', fld_product_code='".$procode."', fld_version_number='".$vernumber."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
										 WHERE fld_id='".$productid."' AND fld_cat_id='".$catid."'");

					/* Auto insert from sim licenses mapping table start line */ 
					if($licenseqry->num_rows>0)
					{
						while($licrow = $licenseqry->fetch_assoc())
						{
							extract($licrow);

							$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																	 FROM itc_license_simproduct_mapping 
																	 WHERE fld_license_id='".$licenseid."'  AND fld_product_id='".$productid."' AND fld_cat_id='".$catid."'");
							if($cnt==0)
							{
							   $ObjDB->NonQuery("INSERT INTO itc_license_simproduct_mapping(fld_license_id,fld_cat_id,fld_type,fld_asset_id,fld_product_id, fld_created_by, fld_created_date)
															VALUES('".$licenseid."','".$catid."','".$simtype."','".$id."','".$productid."', '".$uid."', '".$date."')");
							}
							else
							{
								  $ObjDB->NonQuery("UPDATE itc_license_simproduct_mapping 
														  SET fld_active='1',fld_cat_id='".$catid."', fld_asset_id='".$id."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
														  WHERE fld_license_id='".$licenseid."' AND fld_product_id='".$productid."' AND fld_cat_id='".$catid."'");
							}
						}
					}
					/* Auto insert from sim licenses mapping table end line */

					/*--Tags insert-----*/	
					fn_taginsert($tags,41,$productid,$uid);

					echo "success~".$productid."~".$catname;
				}
			}
		
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_sim_product
										 SET fld_product_name='".$proname."', fld_product_code='".$procode."', fld_version_number='".$vernumber."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'
										 WHERE fld_id='".$productid."' AND fld_cat_id='".$catid."'");
		}
		
	}
	
}

/*--- Copy of the Product ---*/
if($oper == "copyproduct" and $oper != '')
{
	$proname = isset($method['pname']) ? $method['pname'] : '';
	$procode = isset($method['pcode']) ? $method['pcode'] : '';
	$vernumber = isset($method['vernumber']) ? $method['vernumber'] : '';
	$catid = isset($method['catid']) ? $method['catid'] : '';
	$productid = isset($method['pid']) ? $method['pid'] : '0';
	$tags = isset($method['tags']) ? $method['tags'] : '';
	
	$productid =$ObjDB->NonQueryWithMaxValue ("INSERT INTO itc_sim_product(fld_cat_id, fld_product_name, fld_product_code, fld_version_number, fld_created_by, fld_created_date) VALUES ('".$catid."','".$proname."','".$procode."','".$vernumber."','".$uid."','".$date."')");
		
	/*--Tags insert-----*/	
	fn_taginsert($tags,41,$productid,$uid);
	
	echo "success~".$productid;
	
}


/*--- Delete the Product ---*/
if($oper=="deleteproduct" and $oper != " " )
{
	try
	{
		$productid = isset($method['productid']) ? $method['productid'] : ''; 
		$catid = isset($method['catid']) ? $method['catid'] : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sim_product 
											  WHERE fld_id='".$productid."' 
											  AND fld_delstatus='0'");
		$catname = $ObjDB->SelectSingleValue("SELECT fld_category_name FROM itc_sim_category WHERE fld_id='".$catid."' AND fld_delstatus='0'");

		if($count==1)
		{
			$ObjDB->NonQuery("UPDATE itc_sim_product 
							 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'
							 WHERE fld_id='".$productid."'");
			
			echo "success~".$catname;
		}
		else
		{
			echo "exists~";
		}
	}
	catch(Exception $e)
	{
		echo "fail~";
	}
}
