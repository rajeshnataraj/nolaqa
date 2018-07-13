<?php 
/*
	Page - licenses-newlicense-newlicenseajax
	Description:
	This is used for all backgroud operation for license creation and edit. Comments coming from licenses-newlicense.js
	
	Actions Performed:
	
 * Update by: Vijayalakshmi PHP Programmer(extend contend for expedition (type=15)
	
	History:
*/

@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*-----Check the license name-----*/
if($oper=="checklicensename" and $oper != "" )
{
	$licenseid = isset($method['id']) ? $method['id'] : '0';
	$licensename = (isset($method['licennsename']) ? fnEscapeCheck($method['licennsename']) : ''); 	
	
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
										  FROM itc_license_master 
										  WHERE MD5(LCASE(REPLACE(fld_license_name,' ','')))='".$licensename."' AND fld_delstatus='0' AND fld_id<>'".$licenseid."'");
	if($count == 0){ echo "true"; }	else { echo "false"; }
}

/*---save license---*/
if($oper=="savelicense" and $oper != " " )
{
	try /**Here starts with saving the details unit master table**/
	{
		$id = isset($method['id']) ? $method['id'] : 0;
		$licennsename = isset($method['licennsename']) ? $ObjDB->EscapeStrAll($method['licennsename']) : '';
		$amount = isset($method['amount']) ? $method['amount'] : '0';
                $sales=isset($method['sales']) ? $method['sales'] : '0';
		$duration = isset($method['duration']) ? $method['duration'] : '';
		$licensetype = isset($method['licensetype']) ? $method['licensetype'] : '';	
                $contenttype = isset($method['contenttype']) ? $method['contenttype'] : '';
		$tags = isset($method['tags']) ? $method['tags'] : '';		
		$month = isset($method['month']) ? $method['month'] : '';
		$list2 = isset($method['list2']) ? $method['list2'] : '';	
		$list4 = isset($method['list4']) ? $method['list4'] : '';	
		$list6 = isset($method['list6']) ? $method['list6'] : '';	
		$list8 = isset($method['list8']) ? $method['list8'] : '';
		$list10 = isset($method['list10']) ? $method['list10'] : '';
		$list12 = isset($_POST['list12']) ? $_POST['list12'] : '';
		$list14 = isset($_POST['list14']) ? $_POST['list14'] : '';
                $list16 = isset($method['list16']) ? $method['list16'] : '';//courses
                $list24 = isset($method['list24']) ? $method['list24'] : '';//pd lessons
                $list26 = isset($method['list26']) ? $method['list26'] : '';//Missions
                $list28 = isset($method['list28']) ? $method['list28'] : '';//Documents
                $list18 = isset($method['list18']) ? $method['list18'] : '';//sosunits
                $list20 = isset($method['list20']) ? $method['list20'] : '';//sosphases
                $list22 = isset($method['list22']) ? $method['list22'] : '';//sosvideos
				$list30 = isset($method['list30']) ? $method['list30'] : '';//simproduct
                $list32 = isset($method['list32']) ? $method['list32'] : '';//nondigitalcontent
		$extids = isset($_POST['extids']) ? $_POST['extids'] : '';
		
                if($id==0)
                {
                    $type="save";
                }
                else
                {
                    $type="update";
                }
		
		$list4=explode(",",$list4);	
		$list6=explode(",",$list6);	
		$list8=explode(",",$list8);
		$list10=explode(",",$list10);
		$list12=explode(",",$list12);
		$list14=explode(",",$list14);
                $list16=explode(",",$list16);
                $list18=explode(",",$list18);
                $list20=explode(",",$list20);
                $list22=explode(",",$list22);
                $list24=explode(",",$list24);
                $list26=explode(",",$list26);
                $list28=explode(",",$list28);
                $list30=explode(",",$list30); 
                $list32=explode(",",$list32); 
		$extid=explode(",",$extids);
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_id=true;
		$validate_licennsename=true;
		$validate_duration=true;
		$validate_licensetype=true;
		$validate_month=true;
		if($id!=0) 
                    $validate_id=validate_datatype($id,'int');
		$validate_licennsename=validate_datas($licennsename,'lettersonly');
                
                if($amount === '0')
                {
                    $validate_amount='1';
                }
                else {
                    $validate_amount=validate_datatype($amount,'float');
                }
		
		$validate_duration=validate_datatype($duration,'int');
		$validate_licensetype=validate_datatype($licensetype,'int');
		$validate_month=validate_datatype($month,'int');
                
                if($validate_id and $validate_licennsename and  $validate_duration and $validate_month and $validate_licensetype)
		{
			if($id==0)   //if new license
			{
				$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_license_master(fld_license_name,fld_license_type,fld_duration_type,fld_duration, fld_created_date, 
																 fld_created_by, fld_amount, fld_status,fld_salesorder,fld_content_type) 
													 VALUES('".$licennsename."','".$licensetype."', '".$month."','".$duration."','".$date."','2','".$amount."','".$uid."','".$sales."','".$contenttype."')");
				 
				 /*--Tags insert-----*/
				 fn_taginsert($tags,18,$maxid,$uid);		
			}
			else if($id!=0) // existing license
			{
				$maxid = $id;
				//update basic details of license
				$ObjDB->NonQuery("UPDATE itc_license_master 
									SET fld_license_name = '".$licennsename."' , fld_license_type = '".$licensetype."' ,fld_duration_type = '".$month."' , 
									fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' ,fld_duration = '".$duration."' ,fld_amount = '".$amount."',fld_salesorder = '".$sales."' 
								 WHERE fld_id = '".$id."'");
				 	 
				 
				 //set all the unit and lesson
				 $ObjDB->NonQuery("UPDATE itc_license_cul_mapping 
									SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
								  WHERE fld_license_id='".$maxid."'");		 
				 /*---tags------*/
				 
				 //set disable the license tag from the particular user tags
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
									SET fld_access='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
								 WHERE fld_tag_type='18' AND fld_item_id='".$id."' AND fld_tag_id IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' 
									AND fld_delstatus='0')");
				
				fn_tagupdate($tags,18,$id,$uid);
			}	
			
                        
                   if($contenttype==1)
                   {
                        if($type=="save")
                        {
			//Modules insert/update
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_license_id='".$maxid."'");
			if($list4[0] != '') {
				for($i=0;$i<sizeof($list4);$i++)
				{
					$templist = explode('~',$list4[$i]);
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_mod_mapping 
														WHERE fld_license_id='".$maxid."'  AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$maxid."','".$templist[0]."','1','".$templist[1]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$maxid."' AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");
					}
					
				}
			}
                        }
			
			//Expeditions insert/update
			$ObjDB->NonQuery("UPDATE itc_license_exp_mapping 
							 SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'  
							 WHERE fld_license_id='".$maxid."'");
			if($list14[0] != '') {
				for($i=0;$i<sizeof($list14);$i++)
				{
					$templist = explode('~',$list14[$i]);
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_exp_mapping 
														WHERE fld_license_id='".$maxid."'  AND fld_exp_id='".$templist[0]."' AND fld_dest_id='".$templist[2]."'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_exp_mapping (fld_license_id,fld_exp_id,fld_flag,fld_dest_id, fld_created_by, fld_created_date)
											VALUES('".$maxid."','".$templist[0]."','1','".$templist[2]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_exp_mapping 
											SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'  
											WHERE fld_license_id='".$maxid."' AND fld_exp_id='".$templist[0]."' AND fld_dest_id='".$templist[2]."'");
					}
					
				}
			}
			
                        
                        //Missions insert/update
			$ObjDB->NonQuery("UPDATE itc_license_mission_mapping 
							 SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'  
							 WHERE fld_license_id='".$maxid."'");
			if($list26[0] != '') {
				for($i=0;$i<sizeof($list26);$i++)
				{
					$templist = explode('~',$list26[$i]);
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_mission_mapping 
														WHERE fld_license_id='".$maxid."'  AND fld_mis_id='".$templist[0]."' AND fld_dest_id='".$templist[2]."'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mission_mapping (fld_license_id,fld_mis_id,fld_flag,fld_dest_id, fld_created_by, fld_created_date)
											VALUES('".$maxid."','".$templist[0]."','1','".$templist[2]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mission_mapping 
											SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'  
											WHERE fld_license_id='".$maxid."' AND fld_mis_id='".$templist[0]."' AND fld_dest_id='".$templist[2]."'");
					}
					
				}
			}
			
			//extend insert/update
			$ObjDB->NonQuery("UPDATE itc_license_extcontent_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'   
							 WHERE fld_license_id='".$maxid."'");
			if($extid[0] != '') {
				for($i=0;$i<sizeof($extid);$i++)
				{
					$templist = explode('~',$extid[$i]);
					if($templist[0]!='' and $templist[0]!=0){
                                
						$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
															FROM itc_license_extcontent_mapping 
															WHERE fld_license_id='".$maxid."'  AND fld_ext_id='".$templist[0]."' AND fld_type='".$templist[1]."' 
															AND fld_module_id='".$templist[2]."'");
						if($cnt==0)
						{
							 $ObjDB->NonQuery("INSERT INTO itc_license_extcontent_mapping (fld_license_id,fld_ext_id,fld_active,fld_type,fld_module_id, fld_created_by, fld_created_date)
												VALUES('".$maxid."','".$templist[0]."','1','".$templist[1]."','".$templist[2]."', '".$uid."', '".$date."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_license_extcontent_mapping 
												SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'   
												WHERE fld_license_id='".$maxid."' AND fld_ext_id='".$templist[0]."' AND fld_type='".$templist[1]."' AND fld_module_id='".$templist[2]."'");
						}
					}					
				}
			}
			
			//Quests insert/update
			if($list12[0] != '') {
				for($i=0;$i<sizeof($list12);$i++)
				{
					$templist = explode('~',$list12[$i]);
					$cnt = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_license_mod_mapping where fld_license_id='".$maxid."' and fld_module_id='".$templist[0]."' and fld_type='".$templist[1]."'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("insert into itc_license_mod_mapping (fld_license_id, fld_module_id, fld_active, fld_type, fld_created_by, fld_created_date)values('".$maxid."','".$templist[0]."','1','".$templist[1]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("update itc_license_mod_mapping set fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' where fld_license_id='".$maxid."' and fld_module_id='".$templist[0]."' and fld_type='".$templist[1]."' ");
					}
				}
			}
			
			//Assessment insert/update
			$ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
							 SET fld_access='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_license_id='".$maxid."'");
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_assessment_mapping 
														WHERE fld_license_id='".$maxid."'  AND fld_assessment_id='".$list10[$i]."'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_assessment_mapping (fld_license_id,fld_assessment_id,fld_access, fld_created_by, fld_created_date)
											VALUES('".$maxid."','".$list10[$i]."','1', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_assessment_mapping 
											SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$maxid."' AND fld_assessment_id='".$list10[$i]."'");
					}
				}
			}
			
			//Ipls insert/update
			 $ObjDB->NonQuery("UPDATE itc_license_unit_mapping 
							  SET fld_access='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							  WHERE fld_license_id='".$maxid."'");
			 if($list8[0]!=''){
				 for($i=0;$i<sizeof($list8);$i++)
				 {
					$tmp=explode("~",$list8[$i]);	
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_cul_mapping 
														WHERE fld_license_id='".$maxid."' AND fld_unit_id='".$tmp[0]."' AND fld_lesson_id='".$tmp[1]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_cul_mapping (fld_license_id,fld_unit_id,fld_lesson_id, fld_created_by, fld_created_date)
										 VALUES('".$maxid."','".$tmp[0]."','".$tmp[1]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_cul_mapping 
										 SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$maxid."' AND fld_unit_id='".$tmp[0]."' AND fld_lesson_id='".$tmp[1]."'");
					}
				 }
			 }  
			 //Units insert/update
			 if($list6[0]!=''){
				 for($i=0;$i<sizeof($list6);$i++)
				 { 
					$cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														 FROM itc_license_unit_mapping 
														 WHERE fld_license_id='".$maxid."' AND fld_unit_id='".$list6[$i]."'");			
					if($cnt1==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_unit_mapping (fld_license_id,fld_unit_id, fld_created_by, fld_created_date)
											VALUES('".$maxid."','".$list6[$i]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_unit_mapping 
										 SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$maxid."' AND fld_unit_id='".$list6[$i]."'");
					}			
				 }
			 }	
		   
		   	/*** Sim Product insert/update Start line ***/
			$ObjDB->NonQuery("UPDATE itc_license_simproduct_mapping 
						  SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
						  WHERE fld_license_id='".$maxid."'");
			if($list30[0]!='')
			{
				 for($i=0;$i<sizeof($list30);$i++)
				 {
					$tmp=explode("~",$list30[$i]);	

					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_simproduct_mapping
														WHERE fld_license_id='".$maxid."' AND fld_asset_id='".$tmp[1]."' AND fld_product_id='".$tmp[0]."'");
                                       
                                        $catid = $ObjDB->SelectSingleValueInt("select fld_cat_id from itc_sim_product where fld_id='".$tmp[0]."'");
                                        
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_simproduct_mapping (fld_license_id,fld_cat_id,fld_product_id,fld_asset_id, fld_type, fld_created_by, fld_created_date)
										 VALUES('".$maxid."','".$catid."','".$tmp[0]."','".$tmp[1]."','".$tmp[2]."', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_simproduct_mapping 
										 SET fld_active='1', fld_cat_id='".$catid."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
									 WHERE fld_license_id='".$maxid."' AND fld_asset_id='".$tmp[1]."' AND fld_product_id='".$tmp[0]."' ");
					}
				 }
			}
		  /*** Sim Product insert/update End line ***/  
                         
			//courses insert/update
			$ObjDB->NonQuery("UPDATE itc_license_course_mapping 
						 SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
						 WHERE fld_license_id='".$maxid."'");

			if($list16[0]!='')
			{
				for($i=0;$i<sizeof($list16);$i++)
				{ 
				$course = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											 FROM itc_license_course_mapping 
											 WHERE fld_license_id='".$maxid."' AND fld_course_id='".$list16[$i]."'");			
				if($course==0)
				{
				$ObjDB->NonQuery("INSERT INTO itc_license_course_mapping (fld_license_id,fld_course_id, fld_created_by, fld_created_date)
								VALUES('".$maxid."','".$list16[$i]."', '".$uid."', '".$date."')");
				}
				else
				{
				$ObjDB->NonQuery("UPDATE itc_license_course_mapping 
							 SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_license_id='".$maxid."' AND fld_course_id='".$list16[$i]."'");
				}			
				}
			}
                         
                        //Non digital content insert/update
                                $ObjDB->NonQuery("UPDATE itc_license_nondigitalcontent_mapping 
                                                         SET fld_access='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                         WHERE fld_license_id='".$maxid."'");

                                if($list32[0]!='')
                                {
                                        for($i=0;$i<sizeof($list32);$i++)
                                        { 
                                        $nondigi = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                 FROM itc_license_nondigitalcontent_mapping 
                                                                                                 WHERE fld_license_id='".$maxid."' AND fld_product_id='".$list32[$i]."'");			
                                        if($nondigi==0)
                                        {
                                        $ObjDB->NonQuery("INSERT INTO itc_license_nondigitalcontent_mapping (fld_license_id,fld_product_id, fld_created_by, fld_created_date)
                                                                        VALUES('".$maxid."','".$list32[$i]."', '".$uid."', '".$date."')");
                                        }
                                        else
                                        {
                                        $ObjDB->NonQuery("UPDATE itc_license_nondigitalcontent_mapping 
                                                                 SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                 WHERE fld_license_id='".$maxid."' AND fld_product_id='".$list32[$i]."'");
                                        }			
                                        }
                                }
                                
                         
                         //pdlessons insert/update
                                    $ObjDB->NonQuery("UPDATE itc_license_pd_mapping 
                                                                     SET fld_active='0', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                     WHERE fld_license_id='".$maxid."'");

                                    if($list24[0] != '') {
                                            for($i=0;$i<sizeof($list24);$i++)
                                            {
                                                    $tmp=explode("~",$list24[$i]);
                                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                    FROM itc_license_pd_mapping 
                                                                                                    WHERE fld_license_id='".$maxid."' AND fld_course_id='".$tmp[0]."'  AND fld_pd_id='".$tmp[1]."'");
                                                    if($cnt==0)
                                                    {
                                                        
                                                        $ObjDB->NonQuery("INSERT INTO itc_license_pd_mapping (fld_license_id,fld_course_id,fld_pd_id,fld_flag,fld_created_by, fld_created_date)
                                                                                                    VALUES('".$maxid."','".$tmp[0]."','".$tmp[1]."','1','".$uid."', '".date("Y-m-d H:i:s")."')");
                                                    }
                                                    else
                                                    {
                                                   
                                                        $ObjDB->NonQuery("UPDATE itc_license_pd_mapping 
                                                                                                    SET fld_flag='1',fld_active='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                                                    WHERE fld_license_id='".$maxid."' AND fld_course_id='".$tmp[0]."' AND fld_pd_id='".$tmp[1]."'");
                                                    }

                                            }
                                    }//pd
                                    }
                           else
                           {
                               $ObjDB->NonQuery("UPDATE itc_license_sosunit_mapping 
										 SET fld_access='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$maxid."'");
                         
                                if($list18[0]!=''){
                                        for($i=0;$i<sizeof($list18);$i++)
                                        { 
                                               
                                               $unit = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                                        FROM itc_license_sosunit_mapping 
                                                                                                                        WHERE fld_license_id='".$maxid."' AND fld_unit_id='".$list18[$i]."'");			
                                               if($unit==0)
                                               {
                                                        $ObjDB->NonQuery("INSERT INTO itc_license_sosunit_mapping (fld_license_id,fld_unit_id, fld_created_by, fld_created_date)
                                                                                               VALUES('".$maxid."','".$list18[$i]."', '".$uid."', '".$date."')");
                                               }
                                               else
                                               {
                                                       $ObjDB->NonQuery("UPDATE itc_license_sosunit_mapping 
                                                                                        SET fld_access='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                        WHERE fld_license_id='".$maxid."' AND fld_unit_id='".$list18[$i]."'");
                                               }			
                                        }
                                }
                                
                                $ObjDB->NonQuery("UPDATE itc_license_sosphase_mapping 
										 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$maxid."'");
                                
                                if($list20[0]!=''){
                                        for($i=0;$i<sizeof($list20);$i++)
                                        { 
                                               $tmp=explode("~",$list20[$i]);
                                               $unit = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                                        FROM itc_license_sosphase_mapping 
                                                                                                                        WHERE fld_license_id='".$maxid."' AND fld_phase_id='".$tmp[1]."'");			
                                               if($unit==0)
                                               {
                                                        $ObjDB->NonQuery("INSERT INTO itc_license_sosphase_mapping (fld_license_id,fld_unit_id,fld_phase_id,fld_created_by, fld_created_date)
                                                                                               VALUES('".$maxid."','".$tmp[0]."','".$tmp[1]."','".$uid."', '".$date."')");
                                               }
                                               else
                                               {
                                                       $ObjDB->NonQuery("UPDATE itc_license_sosphase_mapping 
                                                                                        SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                        WHERE fld_license_id='".$maxid."' AND fld_phase_id='".$tmp[1]."'");
                                               }			
                                        }
                                }
                                
                                $ObjDB->NonQuery("UPDATE itc_license_sosvideo_mapping 
										 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$maxid."'");
                                
                                if($list22[0]!=''){
                                        for($i=0;$i<sizeof($list22);$i++)
                                        { 
                                               $tmp=explode("~",$list22[$i]);
                                               $unit = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                                        FROM itc_license_sosvideo_mapping 
                                                                                                                        WHERE fld_license_id='".$maxid."' AND fld_video_id='".$tmp[2]."'");			
                                               if($unit==0)
                                               {
                                                        $ObjDB->NonQuery("INSERT INTO itc_license_sosvideo_mapping (fld_license_id,fld_unit_id,fld_phase_id,fld_video_id,fld_created_by, fld_created_date)
                                                                                               VALUES('".$maxid."','".$tmp[0]."','".$tmp[1]."','".$tmp[2]."','".$uid."', '".$date."')");
                                               }
                                               else
                                               {
                                                       $ObjDB->NonQuery("UPDATE itc_license_sosvideo_mapping 
                                                                                        SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                        WHERE fld_license_id='".$maxid."' AND fld_video_id='".$tmp[2]."'");
                                               }			
                                        }
                                }
                                
                                
                                $ObjDB->NonQuery("UPDATE itc_license_sosdocument_mapping 
										 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$maxid."'");
                                
                                if($list28[0]!=''){
                                        for($i=0;$i<sizeof($list28);$i++)
                                        { 
                                               $tmp=explode("~",$list28[$i]);
                                               $doccount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                                        FROM itc_license_sosdocument_mapping 
                                                                                                                        WHERE fld_license_id='".$maxid."' AND fld_document_id='".$tmp[2]."'");			
                                               if($doccount==0)
                                               {
                                                        $ObjDB->NonQuery("INSERT INTO itc_license_sosdocument_mapping (fld_license_id,fld_unit_id,fld_phase_id,fld_document_id,fld_created_by, fld_created_date)
                                                                                               VALUES('".$maxid."','".$tmp[0]."','".$tmp[1]."','".$tmp[2]."','".$uid."', '".$date."')");
                                               }
                                               else
                                               {
                                                       $ObjDB->NonQuery("UPDATE itc_license_sosdocument_mapping 
                                                                                        SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                                                        WHERE fld_license_id='".$maxid."' AND fld_document_id='".$tmp[2]."'");
                                               }			
                                        }
                                }
                                
                           }
                         
			echo "success";
		}
		else{
			echo "fail";
		}
	}
	catch(Exception $e)
	{
		 echo "fail";
	}
}

//Load lesson after dragging unit from license form
if($oper=="loadlessons" and $oper != " " )
{    
	$unitids = isset($method['unitids']) ? $method['unitids'] : 0;
	$licenseid = isset($method['id']) ? $method['id'] : 0;
	$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
	if($unitids=='')
	{ 
     $unitids=0;
	}	
  ?> 
  <script>
  	/*-------Lessons------*/
			$(function(){
                $('#testrailvisible7').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible8').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list7").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list7'){
                                fn_movealllistitems('list7','list8',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list8" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list8'){
                                fn_movealllistitems('list7','list8',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list7, #list8").disableSelection();
            });
  </script> 
   <div class='six columns'>
        <div class="dragndropcol">
            <?php
                    //get lessons from the selected units
                         $qrylessons=$ObjDB->QueryObject("SELECT b.fld_unit_id AS unitid, CONCAT(b.fld_ipl_name,' ',a.fld_version) AS iplname,fn_shortname(CONCAT(b.fld_ipl_name,' ',a.fld_version),25) AS shortiplname, b.fld_id AS iplid 
														 FROM itc_ipl_master AS b LEFT JOIN itc_ipl_version_track AS a ON a.fld_ipl_id=b.fld_id
														 WHERE b.fld_access='1' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_zip_type='1' 
														 	AND b.fld_unit_id IN (".$unitids.") AND b.fld_id 
															NOT IN (SELECT fld_lesson_id FROM itc_license_cul_mapping WHERE fld_license_id='".$licenseid."' AND fld_active='1') 
														 ORDER BY iplname");
            ?>
            <div class="dragtitle">IPLs available (<span id="leftipls"><?php echo $qrylessons->num_rows ;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list7" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this,'#list7');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible7" >
                <div id="list7" class="dragleftinner droptrue">
                    <?php 
						
                        if($qrylessons->num_rows > 0){
                            while($reslesson=$qrylessons->fetch_assoc()){
                                extract($reslesson);
                            ?>
                                <div class="draglinkleft" id="list7_<?php echo $iplid; ?>" name="<?php echo $unitid."~".$iplid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $iplid;?>" title="<?php echo $iplname;?>"><?php echo $shortiplname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $iplid; ?>" onclick="fn_movealllistitems('list7','list8',1,<?php echo $iplid; ?>);"></div>
                                </div> 
                            <?php                               
                            }
                        }
                    ?>    
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list7','list8',0,0);" style="cursor: pointer;cursor:hand;width:  91px;float: right;">Add all IPLs.</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
                    <?php 
					//get lessons from the selected units, which is selected for the license 
                    $qrylessonsselect=$ObjDB->QueryObject("SELECT a.fld_unit_id AS unitid, CONCAT(a.fld_ipl_name,' ',c.fld_version) AS iplname,fn_shortname(CONCAT(a.fld_ipl_name,' ',c.fld_version),25) AS shortiplname,a.fld_id AS iplid 
														  FROM itc_ipl_master AS a LEFT JOIN itc_license_cul_mapping AS b ON a.fld_id=b.fld_lesson_id 
														  	LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=a.fld_id
														  WHERE a.fld_access='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_zip_type='1' 
														  	 AND a.fld_unit_id IN (".$unitids.") AND fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY iplname");
                    $qrylessonunselect=$ObjDB->QueryObject("SELECT b.fld_lesson_id as lesson_id 
                                                                FROM itc_class_sigmath_student_mapping as a
                                                                LEFT JOIN itc_class_sigmath_lesson_mapping AS b 
                                                                ON a.fld_sigmath_id = b.fld_sigmath_id
                                                                LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=b.fld_sigmath_id
                                                                WHERE  a.fld_license_id = '".$licenseid."' 
                                                                AND a.fld_flag='1'  AND c.fld_delstatus='0'");
                    $filter_greyout=array(); 
                    while($lessonunselect=$qrylessonunselect->fetch_assoc()){
                    extract($lessonunselect);
                    array_push($filter_greyout,$lesson_id);
                    } 
            ?>
            <div class="dragtitle">IPLs in your license (<span id="rightipls"><?php echo $qrylessonsselect->num_rows ;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list8" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_8_search" name="list_8_search" onKeyUp="search_list(this,'#list8');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible8">
                <div id="list8" class="dragleftinner droptrue">
                    <?php 
					
                        if($qrylessonsselect->num_rows > 0){
                            while($resassignedlesson=$qrylessonsselect->fetch_assoc()){
                                extract($resassignedlesson);
                                 $dimlesson = array_diff(array($iplid),$filter_greyout);
                                ?>
                                    <div class="draglinkright<?php if(empty($dimlesson)) { echo ' dim'; }?>" id="list8_<?php echo $iplid; ?>" name="<?php echo $unitid."~".$iplid; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $iplid;?>" title="<?php echo $iplname;?>"><?php echo $shortiplname; ?></div>
                                        <div class="clickable" id="clck_<?php echo $iplid; ?>" onclick="fn_movealllistitems('list7','list8',1,<?php echo $iplid; ?>);"></div>
                                    </div>
                                <?php 
                            }
                        }
                    ?>	
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list8','list7',0,0);" style="cursor: pointer;cursor:hand;width:  155px;float: right;">Remove all IPLs.</div>
        </div>
    </div> 
<?php 
}


//Load product after dragging ipls from licens form
if($oper=="loadproduct" and $oper != " " )
{    
	error_reporting(E_ALL);
    ini_set('display_errors', '1');
	
	$unitids = isset($method['unitids']) ? $method['unitids'] : 0;
	$iplids = isset($method['iplids']) ? $method['iplids'] : 0;
	$moduleids = isset($method['maduleids']) ? $method['maduleids'] : 0;
	$expids = isset($method['expids']) ? $method['expids'] : 0;
	$questids = isset($method['questids']) ? $method['questids'] : 0;
	$pdids = isset($method['pdids']) ? $method['pdids'] : 0;
	$missionids = isset($method['missionids']) ? $method['missionids'] : 0;
	$courseids = isset($method['courseids']) ? $method['courseids'] : 0;
	$licenseid = isset($method['id']) ? $method['id'] : 0;
        $nondigitalcontentids= isset($method['nondigitalcontentids']) ? $method['nondigitalcontentids'] : 0;
	$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
	
	if($unitids == '') // Unit
	{
		$unitids=0;
	}
	
        if($nondigitalcontentids == '') // Non digital content
	{
		$nondigitalcontentids=0;
	}
	
	if($iplids == '') // IPL
	{
		$iplids=0;
	}
	
	if($moduleids == '') // Module
	{
		$modid=0;
	}
	else
	{
		$moduleids = explode(',',$moduleids);
		$modid = '';
		$modtype = '';
		for($i=0;$i<sizeof($moduleids);$i++)
		{
			$moduleid  = explode('_',$moduleids[$i]); 
			if($modid=='')
			{
				$modid=$moduleid[0];
			}
			else
			{
				$modid=$modid.",".$moduleid[0];
			}
			if($modtype=='')
			{
				$modtype=$moduleid[1];
			}
			else
			{
				$modtype=$modtype.",".$moduleid[1];
			}
		}
	}
	
	if($expids == '') // Expedition
	{
		$expid=0;
	}
	else
	{
		$expids = explode(',',$expids);
		$expid = '';
		for($i=0;$i<sizeof($expids);$i++)
		{
			$expid1  = explode('_',$expids[$i]); 
			if($expid=='')
			{
				$expid=$expid1[0];
			}
			else
			{
				$expid=$expid.",".$expid1[0];
			}
		}
	}
	
	if($questids == '') // Quest
	{
		$queid=0;
	}
	else
	{
		$questids = explode(',',$questids);
		$queid = '';
		for($i=0;$i<sizeof($questids);$i++)
		{
			$queids  = explode('_',$questids[$i]); 
			if($queid=='')
			{
				$queid=$queids[0];
			}
			else
			{
				$queid=$queid.",".$queids[0];
			}
		}
	}
	
	if($pdids == '') // PD
	{
		$pdid=0;
	}
	else
	{
		$pdids = explode(',',$pdids);
		$pdid = '';
		for($i=0;$i<sizeof($pdids);$i++)
		{
			$pdidss  = explode('~',$pdids[$i]); 
			if($pdid=='')
			{
				$pdid=$pdidss[0];
			}
			else
			{
				$pdid=$pdid.",".$pdidss[0];
			}
		}
	}
	
	if($missionids == '') //Missions
	{
		$misid=0;
	}
	else
	{
		$missionids = explode(',',$missionids);
		$misid = '';
		for($i=0;$i<sizeof($missionids);$i++)
		{
			$misids  = explode('_',$missionids[$i]); 
			if($misid=='')
			{
				$misid=$misids[0];
			}
			else
			{
				$misid=$misid.",".$misid[0];
			}
		}
	}
	
        if($nondigitalcontentids == '') //Nondigital
	{
		$nondigid=0;
	}
	else
	{
		$nondigitalcontentids = explode(',',$nondigitalcontentids);
		$nondigid = '';
		for($i=0;$i<sizeof($nondigitalcontentids);$i++)
		{
			
			if($nondigid=='')
			{
				$nondigid=$nondigitalcontentids[$i];
			}
			else
			{
				$nondigid=$nondigid.",".$nondigitalcontentids[$i];
			}
		}
	}
	
	if($courseids == '') // Course
	{
		$courseids=0;
	}
	
  ?> 
  <script>
  	/*-------Lessons------*/
			$(function(){
                $('#testrailvisible29').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible30').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list29").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list29'){
                                fn_movealllistitems('list29','list30',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list30" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list30'){
                                fn_movealllistitems('list29','list30',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list29, #list30").disableSelection();
            });
  </script> 
   <div class='six columns' style="display:none;">
        <div class="dragndropcol">
            <?php
                    //get product from the selected IPL,Modules,Math Modules,Quest,Expedition,PD,Missions
	
						$qryproduct=$ObjDB->QueryObject("SELECT w.* FROM ((SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname,1 AS type, 'Unit' AS catname
																				FROM itc_sim_product WHERE fld_delstatus='0'AND fld_asset_id in(".$unitids.") AND fld_product_type='7' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname,1 AS type, 'IPL' AS catname
																				FROM itc_sim_product WHERE fld_delstatus='0'AND fld_asset_id in(".$iplids.") AND fld_product_type='1' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname, 2 AS type, 'Module' AS catname 
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$modid.") AND fld_product_type='2' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0')
																				)
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname, 3 AS type, 'Math Module' AS catname 
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$modid.") AND fld_product_type='3' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname, 5 AS type, 'Quest' AS catname
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$queid.") AND fld_product_type='9' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname, 6 AS type, 'Expeditions' AS catname 
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$expid.") AND fld_product_type='4' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname, 8 AS type, 'PD' AS catname 
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$pdid.") AND fld_product_type='5' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname, 10 AS type, 'Missions' AS catname 
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$misid.") AND fld_product_type='6' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname,1 AS type, 'Course' AS catname
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$courseids.") AND fld_product_type='8' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
                                                                                                                                                UNION ALL
																		(SELECT fld_id AS productid,fld_cat_id AS catid,fld_asset_id AS assetid,CONCAT(fld_product_name) AS productname,
																				fn_shortname(CONCAT(fld_product_name),25) AS shortproductname,11 AS type, 'Non Digital' AS catname
																				FROM itc_sim_product WHERE fld_delstatus='0' AND fld_asset_id in(".$nondigid.") AND fld_product_type='10' AND fld_id
																				NOT IN (SELECT fld_product_id FROM itc_license_simproduct_mapping
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_delstatus='0'))
																		) AS w 
			  															ORDER BY w.productname");
                         
            ?>
            <div class="dragtitle">SIM Products available (<span id="leftproduct"><?php echo $qryproduct->num_rows ;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list29" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_29_search" name="list_29_search" onKeyUp="search_list(this,'#list29');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible29" >
                <div id="list29" class="dragleftinner droptrue">
                    <?php 
						
						if($qryproduct->num_rows > 0){  // Product
							while($resproduct=$qryproduct->fetch_assoc()){
								extract($resproduct);
							?>
								<div class="draglinkleft" id="list29_<?php echo $productid; ?>" name="<?php echo $productid."~".$assetid."~".$type; ?>" >
									<div class="dragItemLable tooltip" id="<?php echo $productid;?>" title="<?php echo $productname;?>"><?php echo $shortproductname; ?></div>
									<div class="clickable" id="clck_<?php echo $productid ?>" onclick="fn_movealllistitems('list29','list30',1,<?php echo $productid; ?>);"></div>
								</div> 
							<?php                               
							}
						}
                       
                    ?>    
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list29','list30',0,0);" style="cursor: pointer;cursor:hand;width:  165px;float: right;">Add all SIM Products.</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
                    <?php 
					//get product from the selected product, which is selected for the license 
	
                    $qryproductselect=$ObjDB->QueryObject("SELECT w.* FROM ((SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 1 AS type,'Unit' AS catname 
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$unitids.") AND a.fld_product_type='7' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid )
																		UNION ALL
																	 	(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 1 AS type,'IPL' AS catname 
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$iplids.") AND a.fld_product_type='1' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid )
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 2 AS type,'Module' AS catname  
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$modid.") AND a.fld_product_type='2' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid) 
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 3 AS type,'Math Module' AS catname  
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$modid.") AND a.fld_product_type='3' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 5 AS type,'Quest' AS catname 
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$queid.") AND a.fld_product_type='9' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 6 AS type,'Expeditions' AS catname  
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$expid.") AND a.fld_product_type='4' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 8 AS type,'PD' AS catname  
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$pdid.") AND a.fld_product_type='5' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 10 AS type, 'Missions' AS catname 
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$misid.") AND a.fld_product_type='6' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
																		UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 8 AS type, 'Course' AS catname 
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_asset_id in(".$courseids.") AND a.fld_product_type='8' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
                                                                                                                                                                    UNION ALL
																		(SELECT a.fld_id AS productid,a.fld_cat_id AS catid,a.fld_asset_id AS assetid,CONCAT(a.fld_product_name) AS productname,
																				fn_shortname(CONCAT(a.fld_product_name),25) AS shortproductname, 11 AS type, 'Non Digital' AS catname 
																				FROM itc_sim_product AS a
																				LEFT JOIN itc_license_simproduct_mapping AS b ON a.fld_id = b.fld_product_id
																				WHERE a.fld_delstatus='0' AND a.fld_asset_id in(".$nondigid.") AND a.fld_product_type='10' AND b.fld_license_id='".$licenseid."' AND b.fld_active='1' GROUP BY productid)
																		) AS w 
			  															ORDER BY w.productname");
                   
            ?>
            <div class="dragtitle">SIM Products in your license (<span id="rightproduct"><?php echo $qryproductselect->num_rows ;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list30" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_30_search" name="list_30_search" onKeyUp="search_list(this,'#list30');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible30">
                <div id="list30" class="dragleftinner droptrue">
                    <?php 
					
                        if($qryproductselect->num_rows > 0){
                            while($resassignedproduct=$qryproductselect->fetch_assoc()){
                                extract($resassignedproduct);
								$filter_greyout=array();
							 	$dimproduct = array_diff(array($assetid),$filter_greyout);
                                ?>
                                    <div class="draglinkright<?php if(empty($dimproduct)) { echo ' dim'; }?>" id="list30_<?php echo $productid; ?>" name="<?php echo $productid."~".$assetid; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $productid;?>" title="<?php echo $productname;?>"><?php echo $shortproductname; ?></div>
                                        <div class="clickable" id="clck_<?php echo $productid; ?>" onclick="fn_movealllistitems('list29','list30',1,<?php echo $productid; ?>);"></div>
                                    </div>
                                <?php 
                            }
                        }
                    ?>	
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list30','list29',0,0);" style="cursor: pointer;cursor:hand;width:  186px;float: right;">Remove all SIM Products.</div>
        </div>
    </div> 
<?php 
}

//Load phases after dragging unit from license form
if($oper=="loadphases" and $oper != " " )
{    
	$unitids = isset($method['unitids']) ? $method['unitids'] : '0';
	$licenseid = isset($method['id']) ? $method['id'] : 0;
	$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
	if($unitids=='')
	{ 
     $unitids=0;
	}	
  ?> 
  <script>
  	/*-------Lessons------*/
			$(function(){
                $('#testrailvisible19').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible20').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list19").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list19'){
                                fn_movealllistitems('list19','list20',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list20" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list20'){
                                fn_movealllistitems('list19','list20',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list19, #list20").disableSelection();
            });
  </script> 
   <div class='six columns'>
        <div class="dragndropcol">
            <?php
                 //get phases from the selected units
                         $qryphases=$ObjDB->QueryObject("SELECT a.`fld_id` AS phaseid,a.fld_unit_id as unitid, a.`fld_phase_name` AS phasename, ISNULL(b.fld_phase_id) AS chkphase
																			FROM itc_sosphase_master a
																			LEFT JOIN itc_sosvideo_master b ON a.`fld_id`=b.`fld_phase_id`
																			WHERE a.fld_delstatus='0' AND a.fld_unit_id IN (".$unitids.") AND a.fld_id 
																			NOT IN (SELECT fld_phase_id FROM itc_license_sosphase_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1') 
																			GROUP BY a.`fld_id`
																			ORDER BY a.fld_phase_name");
            ?>
            <div class="dragtitle">Phases available (<span id="leftsosphases"><?php echo $qryphases->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list19" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_19_search" name="list_19_search" onKeyUp="search_list(this,'#list19');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible19" >
                <div id="list19" class="dragleftinner droptrue">
                    <?php 
						
                        if($qryphases->num_rows > 0){
                            while($resphases=$qryphases->fetch_assoc()){
                                extract($resphases);
                            ?>
                                <div class="draglinkleft" id="list19_<?php echo $phaseid; ?>" name="<?php echo $unitid."~".$phaseid."~"."0"; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $phaseid;?>" title="<?php echo $phasename;?>"><?php echo $phasename; ?></div>
                                    <div class="clickable" id="clck_<?php echo $phaseid; ?>" onclick="fn_movealllistitems('list19','list20',1,<?php echo $phaseid; ?>);"></div>
                                </div> 
                            <?php                               
                            }
                        }
                    ?>    
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list19','list20',0,0);" style="cursor: pointer;cursor:hand;width:  110px;float: right;">Add all Phases.</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <?php
                  //get phases from the selected units, which is selected for the license 
                    $qryphaseselect=$ObjDB->QueryObject("SELECT a.fld_unit_id AS unitid, a.fld_phase_name AS phasename,a.fld_id AS phaseid 
														  FROM itc_sosphase_master AS a LEFT JOIN itc_license_sosphase_mapping AS b ON a.fld_id=b.fld_phase_id 
														  WHERE  a.fld_delstatus='0' 
														  	 AND a.fld_unit_id IN (".$unitids.") AND b.fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY phasename");
                                 
            ?>
            <div class="dragtitle">Phases in your license (<span id="rightsosphases"><?php echo $qryphaseselect->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list20" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_20_search" name="list_20_search" onKeyUp="search_list(this,'#list20');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible20">
                <div id="list20" class="dragleftinner droptrue">
                    <?php 
                        if($qryphaseselect->num_rows > 0){
                            while($resassignedphase=$qryphaseselect->fetch_assoc()){
                                extract($resassignedphase);
                                 
                                ?>
                                    <div class="draglinkright" id="list20_<?php echo $phaseid; ?>" name="<?php echo $unitid."~".$phaseid."~"."0"; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $phaseid;?>" title="<?php echo $phasename;?>"><?php echo $phasename; ?></div>
                                        <div class="clickable" id="clck_<?php echo $phaseid; ?>" onclick="fn_movealllistitems('list19','list20',1,<?php echo $phaseid; ?>);"></div>
                                    </div>
                                <?php 
                            }
                        }
                    ?>	
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list20','list19',0,0);" style="cursor: pointer;cursor:hand;width:  140px;float: right;">Remove all Phases.</div>
        </div>
    </div> 
<?php 
}

//Load video after dragging phases from license form
if($oper=="loadvideo" and $oper != " " )
{    
	$phaseids = isset($method['phaseids']) ? $method['phaseids'] : '0';
        $unitids = isset($method['unitids']) ? $method['unitids'] : '0';
	$licenseid = isset($method['id']) ? $method['id'] : 0;
	$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
	if($unitids=='')
	{ 
            $unitids=0;
	}	
        
        if($phaseids=='')
	{ 
            $phaseids=0;
	}	
  ?> 
  <script>
  	/*-------Lessons------*/
			$(function(){
                $('#testrailvisible21').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible22').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list21").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list21'){
                                fn_movealllistitems('list21','list22',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list22" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list22'){
                                fn_movealllistitems('list21','list22',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list21, #list22").disableSelection();
            });
  </script> 
   <div class='six columns'>
        <div class="dragndropcol">
            <?php
                     //get phases from the selected units
                    
                         $qryvideos=$ObjDB->QueryObject("SELECT fld_id AS videoid,fld_unit_id as unitid,fld_phase_id as phaseid,fld_video_name
 AS videoname
																			FROM itc_sosvideo_master 
																			WHERE fld_delstatus='0' AND fld_unit_id IN (".$unitids.") AND fld_phase_id IN (".$phaseids.") AND fld_id
 
																			NOT IN (SELECT fld_video_id FROM itc_license_sosvideo_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1') 
																			GROUP BY fld_id
																			ORDER BY fld_video_name");
            ?>
            <div class="dragtitle">Videos available (<span id="leftsosvideos"><?php echo $qryvideos->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list21" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_21_search" name="list_21_search" onKeyUp="search_list(this,'#list21');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible21" >
                <div id="list21" class="dragleftinner droptrue">
                    <?php 
                            if($qryvideos->num_rows > 0){
                            while($resvideos=$qryvideos->fetch_assoc()){
                                extract($resvideos);
                            ?>
                                <div class="draglinkleft" id="list21_<?php echo $videoid; ?>" name="<?php echo $unitid."~".$phaseid."~".$videoid."~"."0"; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $videoid;?>" title="<?php echo $videoname;?>"><?php echo $videoname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $videoid; ?>" onclick="fn_movealllistitems('list21','list22',1,<?php echo $videoid; ?>);"></div>
                                </div> 
                            <?php                               
                            }
                        }
                    ?>    
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list21','list22',0,0);" style="cursor: pointer;cursor:hand;width:  110px;float: right;">Add all Videos.</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <?php
                    //get phases from the selected units, which is selected for the license 
                    
                    $qryvideoselect=$ObjDB->QueryObject("SELECT a.fld_unit_id AS unitid, a.fld_video_name AS videoname,a.fld_id AS videoid,a.fld_phase_id as phaseid
														  FROM itc_sosvideo_master AS a LEFT JOIN itc_license_sosvideo_mapping AS b ON a.fld_id=b.fld_video_id 
														  WHERE  a.fld_delstatus='0' 
														  	 AND a.fld_unit_id IN (".$unitids.") AND a.fld_phase_id IN (".$phaseids.") AND b.fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY videoname");
            ?>
            <div class="dragtitle">Videos in your license (<span id="rightsosvideos"><?php echo $qryvideoselect->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list22" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_22_search" name="list_22_search" onKeyUp="search_list(this,'#list22');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible22">
                <div id="list22" class="dragleftinner droptrue">
                    <?php 
                        if($qryvideoselect->num_rows > 0){
                            while($resassignedvideo=$qryvideoselect->fetch_assoc()){
                                extract($resassignedvideo);
                                 
                                ?>
                                    <div class="draglinkright" id="list22_<?php echo $videoid."~"."0"; ?>" name="<?php echo $unitid."~".$phaseid."~".$videoid."~0"; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $videoid."~"."0";?>" title="<?php echo $videoname;?>"><?php echo $videoname; ?></div>
                                        <div class="clickable" id="clck_<?php echo $videoid."~"."0"; ?>" onclick="fn_movealllistitems('list21','list22',1,<?php echo $videoid; ?>);"></div>
                                    </div>
                                <?php 
                            }
                        }
                    ?>	
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list22','list21',0,0);" style="cursor: pointer;cursor:hand;width:  140px;float: right;">Remove all Videos.</div>
        </div>
    </div> 
<?php 
}

//Load documents after dragging phases from license form
if($oper=="loaddocument" and $oper != " " )
{    

	$phaseids = isset($method['phaseids']) ? $method['phaseids'] : '0';
        $unitids = isset($method['unitids']) ? $method['unitids'] : '0';
	$licenseid = isset($method['id']) ? $method['id'] : 0;
	$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
	if($unitids=='')
	{ 
            $unitids=0;
	}	
        
        if($phaseids=='')
	{ 
            $phaseids=0;
	}	
  ?> 
  <script>
  	/*-------Lessons------*/
			$(function(){
                $('#testrailvisible27').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible28').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list27").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list27'){
                                fn_movealllistitems('list27','list28',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list28" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list28'){
                                fn_movealllistitems('list27','list28',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list27, #list28").disableSelection();
            });
  </script> 
   <div class='six columns'>
        <div class="dragndropcol">
            <?php
                     //get phases from the selected units
                    
                         $qrydocuments=$ObjDB->QueryObject("SELECT fld_id AS docid,fld_unit_id as unitid,fld_phase_id as phaseid,fld_document_name
 AS docname
																			FROM itc_sosdocument_master 
																			WHERE fld_delstatus='0' AND fld_unit_id IN (".$unitids.") AND fld_phase_id IN (".$phaseids.") AND fld_id
 
																			NOT IN (SELECT fld_document_id FROM itc_license_sosdocument_mapping 
																						WHERE fld_license_id='".$licenseid."' AND fld_active='1') 
																			GROUP BY fld_id
																			ORDER BY docname");
            ?>
            <div class="dragtitle">Documents available (<span id="leftsosdocs"><?php echo $qrydocuments->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list27" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_27_search" name="list_27_search" onKeyUp="search_list(this,'#list27');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible27" >
                <div id="list27" class="dragleftinner droptrue">
                    <?php 
                            if($qrydocuments->num_rows > 0){
                            while($resdocuments=$qrydocuments->fetch_assoc()){
                                extract($resdocuments);
                            ?>
                                <div class="draglinkleft" id="list27_<?php echo $docid; ?>" name="<?php echo $unitid."~".$phaseid."~".$docid."~"."0"; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $docid;?>" title="<?php echo $docname;?>"><?php echo $docname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $docid; ?>" onclick="fn_movealllistitems('list27','list28',1,<?php echo $docid; ?>);"></div>
                                </div> 
                            <?php                               
                            }
                        }
                    ?>    
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list27','list28',0,0);" style="cursor: pointer;cursor:hand;width:  110px;float: right;">Add all Documents.</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <?php
                    //get phases from the selected units, which is selected for the license 
                    
                    $qrydocselect=$ObjDB->QueryObject("SELECT a.fld_unit_id AS unitid, a.fld_document_name AS docname,a.fld_id AS docid,a.fld_phase_id as phaseid
														  FROM itc_sosdocument_master AS a LEFT JOIN itc_license_sosdocument_mapping AS b ON a.fld_id=b.fld_document_id 
														  WHERE  a.fld_delstatus='0' 
														  	 AND a.fld_unit_id IN (".$unitids.") AND a.fld_phase_id IN (".$phaseids.") AND b.fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY docname");
            ?>
            <div class="dragtitle">Documents in your license (<span id="rightsosdocs"><?php echo $qrydocselect->num_rows;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list28" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_28_search" name="list_28_search" onKeyUp="search_list(this,'#list28');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible28">
                <div id="list28" class="dragleftinner droptrue">
                    <?php 
                        if($qrydocselect->num_rows > 0){
                            while($resassigneddoc=$qrydocselect->fetch_assoc()){
                                extract($resassigneddoc);
                                 
                                ?>
                                    <div class="draglinkright" id="list28_<?php echo $docid."~"."0"; ?>" name="<?php echo $unitid."~".$phaseid."~".$docid."~0"; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $docid."~"."0";?>" title="<?php echo $docname;?>"><?php echo $docname; ?></div>
                                        <div class="clickable" id="clck_<?php echo $docid."~"."0"; ?>" onclick="fn_movealllistitems('list27','list28',1,<?php echo $docid; ?>);"></div>
                                    </div>
                                <?php 
                            }
                        }
                    ?>	
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list28','list27',0,0);" style="cursor: pointer;cursor:hand;width:  140px;float: right;">Remove all Documents.</div>
        </div>
    </div> 
<?php 
}


//Load pdlesson after dragging course from license form
if($oper=="loadpdlessons" and $oper != " " )
{  
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	$courseids = isset($method['courseids']) ? $method['courseids'] : 0;
	$licenseid = isset($method['id']) ? $method['id'] : 0;
	$licenseholders = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0'");
	if($courseids=='')
	{ 
            $courseids=0;
	}	
  ?> 
  <script>
  	/*-------Lessons------*/
			$(function(){
                $('#testrailvisible23').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible24').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list23").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list23'){
                                fn_movealllistitems('list23','list24',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list24" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list24'){
                                fn_movealllistitems('list23','list24',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list23, #list24").disableSelection();
            });
  </script> 
   <div class='six columns'>
        <div class="dragndropcol">
            <?php
                    //get pdlessons from the selected courses
                   
                    
                         $qrylessons=$ObjDB->QueryObject("SELECT b.fld_course_id AS courseid, CONCAT(b.fld_pd_name,' ',a.fld_version) AS pdname,fn_shortname(CONCAT(b.fld_pd_name,' ',a.fld_version),25) AS shortpdname, b.fld_id AS pdid 
														 FROM itc_pd_master AS b LEFT JOIN itc_pd_version_track AS a ON a.fld_pd_id=b.fld_id
														 WHERE b.fld_access='1' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_zip_type='1' 
														 	AND b.fld_course_id IN (".$courseids.") AND b.fld_id 
															NOT IN (SELECT fld_pd_id FROM itc_license_pd_mapping WHERE fld_license_id='".$licenseid."' AND fld_active='1') 
														 ORDER BY pdname");
            ?>
            <div class="dragtitle">PD Lessons available (<span id="leftpdlessons"><?php echo $qrylessons->num_rows ;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list23" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_23_search" name="list_23_search" onKeyUp="search_list(this,'#list23');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible23" >
                <div id="list23" class="dragleftinner droptrue">
                    <?php 
                   
                        if($qrylessons->num_rows > 0){
                            while($reslesson=$qrylessons->fetch_assoc()){
                                extract($reslesson);
                            ?>
                                <div class="draglinkleft" id="list23_<?php echo $pdid; ?>" name="<?php echo $courseid."~".$pdid."~"."0"; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $pdid;?>" title="<?php echo $pdname;?>"><?php echo $shortpdname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $pdid; ?>" onclick="fn_movealllistitems('list23','list24',1,<?php echo $pdid; ?>);"></div>
                                </div> 
                            <?php                               
                            }
                        }
                    ?>    
                </div>
            </div>
            <div class="dragAllLink"  onclick="fn_movealllistitems('list23','list24',0,0);" style="cursor: pointer;cursor:hand;width:  150px;float: right;">Add all PD Lessons.</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
                    <?php 
					//get pdlessons from the selected courses, which is selected for the license 
                    $qrylessonsselect=$ObjDB->QueryObject("SELECT a.fld_course_id AS courseid, CONCAT(a.fld_pd_name,' ',c.fld_version) AS pdname,fn_shortname(CONCAT(a.fld_pd_name,' ',c.fld_version),25) AS shortpdname,a.fld_id AS pdid 
														  FROM itc_pd_master AS a LEFT JOIN itc_license_pd_mapping AS b ON a.fld_id=b.fld_pd_id 
														  	LEFT JOIN itc_pd_version_track AS c ON c.fld_pd_id=a.fld_id
														  WHERE a.fld_access='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_zip_type='1' 
														  	 AND a.fld_course_id IN (".$courseids.") AND fld_license_id='".$licenseid."' 
														  	 AND b.fld_active='1' 
														  ORDER BY pdname");
                                  //below this change line
                    $qrylessonunselect=$ObjDB->QueryObject("SELECT b.fld_lesson_id as lesson_id 
                                                                FROM itc_class_pdschedule_student_mapping as a
                                                                LEFT JOIN itc_class_pdschedule_lesson_mapping AS b 
                                                                ON a.fld_pdschedule_id = b.fld_pdschedule_id
                                                                LEFT JOIN itc_class_pdschedule_master AS c ON c.fld_id=b.fld_pdschedule_id
                                                                WHERE  a.fld_license_id = '".$licenseid."' 
                                                                AND a.fld_flag='1'  AND c.fld_delstatus='0'");
                    $filter_greyout=array(); 
                    while($lessonunselect=$qrylessonunselect->fetch_assoc()){
                    extract($lessonunselect);
                    array_push($filter_greyout,$lesson_id);
                    } 
            ?>
            <div class="dragtitle">PD Lessons in your license (<span id="rightpdlessons"><?php echo $qrylessonsselect->num_rows ;?></span>)</div>
            <div class="draglinkleftSearch" id="s_list24" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_24_search" name="list_24_search" onKeyUp="search_list(this,'#list24');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible24">
                <div id="list24" class="dragleftinner droptrue">
                    <?php 
					
                     if($qrylessonsselect->num_rows > 0){
                            while($resassignedlesson=$qrylessonsselect->fetch_assoc()){
                                extract($resassignedlesson);
                                 $dimlesson = array_diff(array($pdid),$filter_greyout);
                                ?>
                                    <div class="draglinkright<?php if(empty($dimlesson)) { echo ' dim'; }?>" id="list24_<?php echo $pdid."~"."0"; ?>" name="<?php echo $courseid."~".$pdid."~"."0"; ?>">
                                        <div class="dragItemLable tooltip" id="<?php echo $pdid."~"."0";?>" title="<?php echo $pdname;?>"><?php echo $shortpdname; ?></div>
                                        <div class="clickable" id="clck_<?php echo $pdid."~"."0"; ?>" onclick="fn_movealllistitems('list23','list24',1,<?php echo $pdid; ?>);"></div>
                                    </div>
                                <?php 
                            }
                        }
                    ?>	
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list24','list23',0,0);" style="cursor: pointer;cursor:hand;width:  177px;float: right;">Remove all PD Lessons.</div>
        </div>
    </div> 
<?php 
}



//delete license
if($oper=="deletelicense" and $oper != " " )
{
	$licenseid = isset($method['id']) ? $method['id'] : '';
	try
	{		
		$ObjDB->NonQuery("UPDATE itc_license_master 
						 SET fld_delstatus='1', fld_deleted_date = '".$date."', fld_deleted_by = '".$uid."'  
						 WHERE fld_id = '".$licenseid."'");	
		echo "success";
		
	}
	catch(Exception $e)
	{
		 echo "fail";
	}
}


if($oper == "updatelicense" and $oper != ""){	
	/*lecense details getting----------*/
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	$distid = isset($_REQUEST['distid']) ? $_REQUEST['distid'] : '';
	$editid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$licensecount = isset($_REQUEST['licensecount']) ? $_REQUEST['licensecount'] : '';
	$ddllicense = isset($_REQUEST['ddllicense']) ? $_REQUEST['ddllicense'] : '';
	$numusers = isset($_REQUEST['numusers']) ? $_REQUEST['numusers'] : '';
	$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '';
	$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';
	$graceipl = isset($_REQUEST['graceipl']) ? $_REQUEST['graceipl'] : '';
	$gracemod = isset($_REQUEST['gracemod']) ? $_REQUEST['gracemod'] : '';
	$renewal = isset($_REQUEST['renewal']) ? $_REQUEST['renewal'] : '';
	$rcount = isset($_REQUEST['rcount']) ? $_REQUEST['rcount'] : '';
	$ddllicense = explode('~',$ddllicense);
	$numusers = explode('~',$numusers);
	$startdate = explode('~',$startdate);
	$enddate = explode('~',$enddate);
	$graceipl = explode('~',$graceipl);
	$gracemod = explode('~',$gracemod);
	$renewal = explode('~',$renewal);
	$rcount = explode('~',$rcount);	
        $texid ='';
	
	/*-------add license track for district, school, individual----------*/
	if($type=='district'){  //district
		/*-------add license track for district----------*/
		$distname = $ObjDB->SelectSingleValue("SELECT fld_district_name 
												FROM itc_district_master 
												WHERE fld_id='".$editid."'");
		for($i=0;$i<sizeof($ddllicense)-1;$i++){			
			
			$lid = explode(',',$ddllicense[$i]);
			
			$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_track 
												WHERE fld_district_id='".$editid."' AND fld_school_id=0 AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
			if($chk==0){
				$prelid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_track 
														WHERE fld_district_id='".$editid."' AND fld_school_id='0' AND fld_license_id='".$lid[0]."' AND fld_delstatus='0'");
				if($renewal[$i]==1)
					$auto="yes / ".$rcount[$i]." times";
				else
					$auto="no";
				if($prelid>0){
					$subject = "Lease Renewal";					
					$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';
				}
				else{
					$subject = " Assigned Lease";
					$content = '<tr><td valign="top" align="left">The License below has been assigned:</td></tr>';
				}
				$ObjDB->NonQuery("UPDATE itc_license_track 
								SET fld_upgrade='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
								WHERE fld_district_id='".$editid."' AND fld_school_id='0' AND fld_license_id='".$lid[0]."'");	
				$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_district_id, fld_no_of_users, fld_remain_users, fld_start_date, 
																fld_end_date,fld_created_by,fld_created_date,fld_ipl_count,fld_mod_count,fld_auto_renewal,fld_renewal_count) 
														VALUES('".$lid[0]."','".$editid."','".$numusers[$i]."','".$numusers[$i]."','".date('Y-m-d',strtotime($startdate[$i]))."',
														'".date('Y-m-d',strtotime($enddate[$i]))."','".$uid."','".$date."','".$graceipl[$i]."','".$gracemod[$i]."','".$renewal[$i]."',
														'".$rcount[$i]."')");
				
				//send notifications to users
				$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
															FROM itc_license_master 
															WHERE fld_id='".$lid[0]."' AND fld_delstatus='0'");				
				$html_txt = '';
				$headers = '';
							
				
				$up = "'";
				
				$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id FROM itc_user_master WHERE fld_district_id='".$editid."' AND fld_school_id='0' AND fld_user_id='0' AND fld_profile_id=6 AND fld_delstatus='0'");
				
				if($qry->num_rows>0)
				{
					while($rowqry = $qry->fetch_assoc())
					{
						extract($rowqry);
						
						if($fld_email!='')
						{
							
							$subj = $licensename." - ".$subject;
							$random_hash = md5(date('r', time())); 
											
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
							$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n"; 														
							$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
							Automatic Renew: '.$auto.'<br />
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';						
							$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
							$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
							$client->call('SendJangoMailTransactional', $param, '', '', false, true);							
						}
					}
				}		
				//for pitsco admin
				$html_txt = '';
				$headers = '';		
				$subj = $licensename."-".$distname."(District purchase) - ".$subject;
				$random_hash = md5(date('r', time())); 
								
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
				$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";
				$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
							Automatic Renew: '.$auto.'<br />
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';
				$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
				$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
				$client->call('SendJangoMailTransactional', $param, '', '', false, true);				
				//end notification				
			}
			else {				
				$preusersqry = $ObjDB->QueryObject("SELECT fld_no_of_users AS prevtotusers, fld_remain_users AS prevremainusers
													FROM itc_license_track 
													WHERE fld_id='".$lid[1]."'");	
				extract($preusersqry->fetch_assoc());		
				$totusers = $numusers[$i] - $prevtotusers;
				
				$ObjDB->NonQuery("UPDATE itc_license_track 
								 SET fld_delstatus='0', fld_no_of_users='".($prevtotusers+$totusers)."', fld_remain_users='".($prevremainusers+$totusers)."',
									 fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', 
									 fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_ipl_count='".$graceipl[$i]."', 
									 fld_mod_count='".$gracemod[$i]."', fld_auto_renewal='".$renewal[$i]."', fld_renewal_count='".$rcount[$i]."' 
								 WHERE fld_district_id='".$editid."' AND fld_school_id=0 AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
				$ObjDB->NonQuery("UPDATE itc_license_track 
								 SET fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
								 WHERE fld_district_id='".$editid."' AND fld_license_id='".$lid[0]."' AND fld_distlictrack_id='".$lid[1]."'");
			}
			
		}		
	}
	else if($type=='school'){  //school
		/*-------update license track for School----------*/
	for($i=0;$i<sizeof($ddllicense)-1;$i++){
		
		$lid = explode(',',$ddllicense[$i]);
		$distqry = $ObjDB->QueryObject("SELECT fld_start_date, fld_end_date 
										FROM itc_license_track 
										WHERE fld_district_id = '".$distid."' AND fld_school_id ='0' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[2]."'"); 
		$res = $distqry->fetch_Object();
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											FROM itc_license_track 
											WHERE fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
		if($chk==0){
			$prelid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
													FROM itc_license_track 
													WHERE fld_district_id='".$distid."' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' AND fld_delstatus='0'");
			if($renewal[$i]==1)
				$auto="yes / ".$rcount[$i]." times";
			else
				$auto="no";
			if($prelid>0){
				$subject = "Lease Renewal";					
				$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';
			}
			else{
				$subject = " Assigned Lease";
				$content = '<tr><td valign="top" align="left">The License below has been assigned:</td></tr>';
			}
			$ObjDB->NonQuery("UPDATE itc_license_track 
							SET fld_upgrade='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							WHERE fld_school_id='".$editid."' AND fld_district_id='".$distid."' AND fld_license_id='".$lid[0]."'");
			
			
			$licensedet = $ObjDB->QueryObject("SELECT a.fld_remain_users AS distremainusers, b.`fld_duration_type` AS lictype, b.fld_duration as licduration 
											  FROM itc_license_track AS a, `itc_license_master` AS b 
											  WHERE a.`fld_license_id`=b.`fld_id` AND a.fld_license_id='".$lid[0]."' AND a.fld_school_id ='0' and a.fld_id='".$lid[2]."'");
			$rowlicense = $licensedet->fetch_assoc();
			extract($rowlicense);			
			
			
			$pcount = $distremainusers;
			$remusers = $pcount- $numusers[$i];
			
			$ObjDB->NonQuery("UPDATE itc_license_track 
							 SET fld_remain_users ='".$remusers."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_license_id='".$lid[0]."' AND fld_school_id=0 and fld_id='".$lid[2]."'");
					
			$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_district_id, fld_school_id, fld_distlictrack_id, fld_no_of_users, fld_remain_users, fld_start_date, 
																fld_end_date, fld_created_by, fld_created_date) 
															VALUES('".$lid[0]."','".$distid."','".$editid."','".$lid[2]."','".$numusers[$i]."','".$numusers[$i]."','".date('Y-m-d',
																	strtotime($startdate[$i]))."','".date('Y-m-d',strtotime($enddate[$i]))."','".$uid."','".$date."')");
			
			//send notifications to users
				$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
														 FROM itc_license_master 
														 WHERE fld_id='".$lid[0]."' AND fld_delstatus='0'");
				$html_txt = '';
				$headers = '';	
				$up = "'";
				
				$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id 
											FROM itc_user_master 
											WHERE fld_district_id='".$distid."' AND fld_school_id='".$editid."' AND fld_user_id='0' AND fld_profile_id<>10 AND fld_delstatus='0'");
				
				if($qry->num_rows>0)
				{
					while($rowqry = $qry->fetch_assoc())
					{
						extract($rowqry);
						
						if($fld_email!='')
						{
							
							$subj = $licensename." - ".$subject;
							$random_hash = md5(date('r', time())); 
											
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
							$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";														
							$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />							
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';						
							$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
							$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
							$client->call('SendJangoMailTransactional', $param, '', '', false, true);							
						}
					}
				}	
				//end notification
		}
		else {
			
			/*--------- Tracking User Count ---------*/			
			$shldet = $ObjDB->QueryObject("SELECT a.fld_no_of_users AS prevtotusers, a.fld_remain_users AS prevremainusers, b.`fld_duration_type` AS lictype, b.fld_duration AS licduration 
											FROM itc_license_track AS a LEFT JOIN `itc_license_master` AS b ON a.`fld_license_id`=b.`fld_id` 
											WHERE a.fld_school_id='".$editid."' AND a.fld_license_id='".$lid[0]."' AND a.fld_id='".$lid[1]."'");
			$res=$shldet->fetch_assoc();
			extract($res);
			
			if($prevtotusers < $numusers[$i]) {
				$curradditionalusers = 	($numusers[$i] - $prevtotusers);
				$finaltotusers = ($prevtotusers + $curradditionalusers);
				$finalremusers = ($prevremainusers + $curradditionalusers);
			}
			else {
				$curradditionalusers = 	($prevtotusers - $numusers[$i]);
				$finaltotusers = ($prevtotusers - $curradditionalusers);
				$finalremusers = ($prevremainusers - $curradditionalusers);
			}
			
				$ObjDB->NonQuery("UPDATE itc_license_track 
								SET fld_no_of_users='".$finaltotusers."', fld_remain_users='".$finalremusers."', fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', 
									fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_delstatus='0' 
								WHERE fld_school_id='".$editid."'AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
			
			/*--------- Decrease User Count in Distirct Table ---------*/
			 	$totalusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users 
															FROM itc_license_track 
															WHERE fld_district_id='".$distid."' AND fld_school_id='0' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[2]."'");
				
				if($prevtotusers < $numusers[$i]) {
					$finaldistusers = ($totalusers - $curradditionalusers);
				}
				else {
					$finaldistusers = ($totalusers + $curradditionalusers);
				}
			
				if($curradditionalusers != 0){
					$ObjDB->NonQuery("UPDATE itc_license_track 
									SET fld_remain_users='".$finaldistusers."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
									WHERE fld_district_id='".$distid."' AND fld_school_id='0' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[2]."'");
				}		
			}
		}
	}
	else if($type=='schoolpurchase'){  //school purchase
		/*-------add license track for district----------*/
		$shlname = $ObjDB->SelectSingleValue("SELECT fld_school_name 
											 FROM itc_school_master 
											 WHERE fld_id='".$editid."'");
		for($i=0;$i<sizeof($ddllicense)-1;$i++){			
			
			$lid = explode(',',$ddllicense[$i]);		
			
			$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_track 
												WHERE fld_school_id='".$editid."' AND fld_district_id=0 AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
			if($chk==0){
				$prelid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_track 
														WHERE fld_district_id='0' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' AND fld_delstatus='0'");
				if($renewal[$i]==1)
					$auto="yes / ".$rcount[$i]." times";
				else
					$auto="no";
				if($prelid>0){
					$subject = "Lease Renewal";					
					$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';
				}
				else{
					$subject = " Assigned Lease";
					$content = '<tr><td valign="top" align="left">The License below has been assigned:</td></tr>';
				}
				$ObjDB->NonQuery("UPDATE itc_license_track 
								SET fld_upgrade='0',fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
								WHERE fld_school_id='".$editid."' AND fld_district_id='0' AND fld_license_id='".$lid[0]."'");				
				$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_school_id, fld_no_of_users, fld_remain_users, fld_start_date, 
																	fld_end_date, fld_created_by, fld_created_date,fld_ipl_count,fld_mod_count,fld_auto_renewal,fld_renewal_count) 
																VALUES('".$lid[0]."','".$editid."','".$numusers[$i]."','".$numusers[$i]."','".date('Y-m-d',strtotime($startdate[$i]))."',
																'".date('Y-m-d',strtotime($enddate[$i]))."','".$uid."','".$date."','".$graceipl[$i]."','".$gracemod[$i]."','".$renewal[$i]."',
																'".$rcount[$i]."')");
				
				//send notifications to users
				$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
															FROM itc_license_master 
															WHERE fld_id='".$lid[0]."' AND fld_delstatus='0'");
				$html_txt = '';
				$headers = '';
				$up = "'";
				
				$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id 
											FROM itc_user_master 
											WHERE fld_district_id='0' AND fld_school_id='".$editid."' AND fld_user_id='0' AND fld_profile_id<>10 AND fld_delstatus='0'");
				
				if($qry->num_rows>0)
				{
					while($rowqry = $qry->fetch_assoc())
					{
						extract($rowqry);
						
						if($fld_email!='')
						{
							
							$subj = $licensename." - ".$subject;
							$random_hash = md5(date('r', time())); 
											
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
							$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";												
							$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
							Automatic Renew: '.$auto.'<br />
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';						
							$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
							$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
							$client->call('SendJangoMailTransactional', $param, '', '', false, true);
						}
					}
				}		
				//for pitsco admin
				$html_txt = '';
				$headers = '';		
				$subj = $licensename."-".$shlname."(School purchase) - ".$subject;
				$random_hash = md5(date('r', time())); 
								
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
				$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";		
				$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
							Automatic Renew: '.$auto.'<br />
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';
				$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
				$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
				$client->call('SendJangoMailTransactional', $param, '', '', false, true);				
				//end notification
			}
			else {
				$preusersqry = $ObjDB->QueryObject("SELECT fld_no_of_users AS prevtotusers, fld_remain_users AS prevremainusers
													FROM itc_license_track 
													WHERE fld_id='".$lid[1]."'");	
				extract($preusersqry->fetch_assoc());				
				$totusers = $numusers[$i] - $prevtotusers;	
				$ObjDB->NonQuery("UPDATE itc_license_track 
								 SET fld_delstatus='0', fld_no_of_users='".($prevtotusers+$totusers)."', fld_remain_users='".($prevremainusers+$totusers)."', 
									 fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', 
									 fld_ipl_count='".$graceipl[$i]."', fld_mod_count='".$gracemod[$i]."', fld_auto_renewal='".$renewal[$i]."', fld_renewal_count='".$rcount[$i]."' 
								 WHERE fld_district_id='0' AND fld_school_id='".$editid."' AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");		
				
			}
			
		}	
		
	}
	
	else if($type=='individual'){  //individualuser
		/*-------add license track for district----------*/
		$iname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) 
											FROM itc_user_master 
											WHERE fld_id='".$editid."'");
		for($i=0;$i<sizeof($ddllicense)-1;$i++){
			$lid = explode(',',$ddllicense[$i]);	
			
			$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_track 
												WHERE fld_user_id='".$editid."' AND fld_school_id=0 AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
			if($chk==0){
				$prelid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_license_track 
														WHERE fld_district_id='0' AND fld_school_id='0' AND fld_user_id='".$editid."' AND fld_license_id='".$lid[0]."' 
															AND fld_delstatus='0'");
				if($renewal[$i]==1)
					$auto="yes / ".$rcount[$i]." times";
				else
					$auto="no";
				if($prelid>0){
					$subject = "Lease Renewal";					
					$content = '<tr><td valign="top" align="left">The License below has been renewed:</td></tr>';
				}
				else{
					$subject = " Assigned Lease";
					$content = '<tr><td valign="top" align="left">The License below has been assigned:</td></tr>';
				}
				$ObjDB->NonQuery("UPDATE itc_license_track 
								 SET fld_upgrade='0',fld_delstatus='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
								 WHERE fld_user_id='".$editid."' AND fld_school_id='0' AND fld_license_id='".$lid[0]."'");
				$ObjDB->NonQuery("INSERT INTO itc_license_track (fld_license_id,fld_user_id, fld_no_of_users, fld_remain_users, fld_start_date, fld_end_date, fld_created_by, fld_created_date, fld_ipl_count, 	
																	fld_mod_count,fld_auto_renewal,fld_renewal_count) 
																VALUES('".$lid[0]."','".$editid."','".$numusers[$i]."','".$numusers[$i]."','".date('Y-m-d',strtotime($startdate[$i]))."',
																'".date('Y-m-d',strtotime($enddate[$i]))."','".$uid."','".$date."','".$graceipl[$i]."','".$gracemod[$i]."','".$renewal[$i]."',
																'".$rcount[$i]."')");
				//send notifications to users
				$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
														 FROM itc_license_master 
														 WHERE fld_id='".$lid[0]."' AND fld_delstatus='0'");
				$html_txt = '';
				$headers = '';
				$up = "'";
				
				$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_id, fld_profile_id 
											FROM itc_user_master 
											WHERE fld_district_id='0' AND fld_school_id='0' AND fld_user_id='".$editid."' AND fld_profile_id<>10 AND fld_delstatus='0'");
				
				if($qry->num_rows>0)
				{
					while($rowqry = $qry->fetch_assoc())
					{
						extract($rowqry);
						
						if($fld_email!='')
						{
							
							$subj = $licensename." - ".$subject;
							$random_hash = md5(date('r', time())); 
											
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
							$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";														
							$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
							Automatic Renew: '.$auto.'<br />
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';						
							$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
							$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
							$client->call('SendJangoMailTransactional', $param, '', '', false, true);
						}
					}
				}		
				//for pitsco admin
				$html_txt = '';
				$headers = '';		
				$subj = $licensename."-".$iname."(Home purchase) - ".$subject;
				$random_hash = md5(date('r', time())); 
								
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
				$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";		
				$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
							<tr><td valign="top" align="left">Lease: '.$licensename.'<br />
							Start date: '.date("m/d/Y",strtotime($startdate[$i])).'<br />
							End date: '.date("m/d/Y",strtotime($enddate[$i])).'<br />
							Automatic Renew: '.$auto.'<br />
							Available seats: '.$numusers[$i].'<br /><br />
							</td></tr>'.fn_getcontent($lid[0]).'</table>';
				$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
				$client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
				$client->call('SendJangoMailTransactional', $param, '', '', false, true);
				//end notification				
			}
			else {
				$ObjDB->NonQuery("UPDATE itc_license_track 
								 SET fld_delstatus='0', fld_no_of_users='".$numusers[$i]."', fld_start_date='".date('Y-m-d',strtotime($startdate[$i]))."', 
								 fld_end_date='".date('Y-m-d',strtotime($enddate[$i]))."', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."', fld_ipl_count='".$graceipl[$i]."', fld_mod_count='".$gracemod[$i]."', 
								 fld_auto_renewal='".$renewal[$i]."', fld_renewal_count='".$rcount[$i]."' 
								 WHERE fld_user_id='".$editid."' AND fld_school_id=0 AND fld_license_id='".$lid[0]."' AND fld_id='".$lid[1]."'");
			}
			
		}		
	}	
}
/*
 * selecting the extend content for lessons,Modules,etc...
 */
if($oper == "loadextendcontent" and $oper != ""){		
	$licenseid = isset($_REQUEST['licenseid']) ? $_REQUEST['licenseid'] : '';
	$list4 = isset($_REQUEST['list4']) ? $_REQUEST['list4'] : '';
	$list4=explode(",",$list4);	
        $listexd = isset($_REQUEST['list4']) ? $_REQUEST['list4'] : '';
        $listexd=explode(",",$listexd);
      
	$chk = 0;
        $expchk = 0;
	$tempvar = 0;
	$unicexpname = array();
        $unicexpname1 = array();
	?>
    <div class='span10 offset1'>
        <table class='table table-hover table-striped table-bordered'>
            <thead class='tableHeadText'>
                <tr>
                    <th style="width:50%">Expedition / Module name</th>
                    <th class='centerText'>Extend Content</th>                    
                </tr>
            </thead>
            <tbody>
                <?php 
				   if($list4[0] != '') {
                                       $cntexp=0;
                                       $tempexp = '';
						for($i=0;$i<sizeof($list4);$i++)
						{
                                                    
							
							$templist = explode('~',$list4[$i]);
           
           
							 if($templist[1]==7){
                                                                    $moduleid = $templist[0];
                                    $modulename = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name, ' ', b.fld_version,'/ Quest') FROM itc_module_master as a LEFT JOIN itc_module_version_track AS b  ON b.fld_mod_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND  b.fld_delstatus = '0' AND a.fld_id='".$moduleid."'");
                                                                    $tablename = "itc_extendtextquest_master";
                                                            }
                                                            else if($templist[1]==2){
                                                                    $moduleid = $templist[0];
                                    $modulename = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_mathmodule_name,' ',b.fld_version,'/ MathModule') FROM itc_mathmodule_master as a LEFT JOIN itc_module_version_track b ON  b.fld_mod_id=a.fld_module_id 
															WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id='".$moduleid."'");
                                                                    $tablename = "itc_extendtextmath_master";
                                                            }
                                                            else if($templist[1] == 15) {
                                                                    $cntexp++;
                                                                    $moduleid = $templist[0];
                                                                    $forexpedn =$moduleid;
                                                                    $modulename = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_exp_name,'/ Expedition') FROM itc_exp_master WHERE fld_id='".$moduleid."'");
                                                                    $tablename = "itc_exp_extendmaterials_master";
                                    $modulename1 = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_exp_name,'/ Expedition') FROM itc_exp_master WHERE fld_id='".$moduleid."'");

                                    $tablename1 = "itc_exp_extendtext_master";
                                                            }
                                                            else if($templist[1] == 18) {
                                                                    $cntexp++;
                                                                    $moduleid = $templist[0];
                                                                    $forexpedn =$moduleid;
                                                                    $modulename = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_mis_name,'/ Mission') FROM itc_mission_master WHERE fld_id='".$moduleid."'");
                                                                    $tablename = "itc_mis_extendmaterials_master";
                                                            }
                                                            else if($templist[1] == 1) {
                                                                    $moduleid = $templist[0];

                                    $modulename = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name, ' ', b.fld_version,'/ Module') FROM itc_module_master as a LEFT JOIN itc_module_version_track AS b  ON b.fld_mod_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND  b.fld_delstatus = '0' AND a.fld_id='".$moduleid."'");
                                                                    $tablename = "itc_extendtext_master";
                                                            }
                            	
                 /** select the extend content for expedition **/     
                                                           if($templist[1] == 15)
                                                            {
                                                                     
                                 $getexpcontent = $ObjDB->QueryObject("SELECT a.fld_id AS exid, a.fld_extend_text AS exname FROM ".$tablename." AS a 
                                                                                                        LEFT JOIN `itc_user_master` AS b ON a.`fld_created_by`=b.`fld_id` 
                                                                                                        WHERE a.fld_exp_id='".$moduleid."' AND (b.fld_profile_id=2 OR b.fld_profile_id=3) AND a.fld_delstatus='0'");
                

                                $getexpcontent1 = $ObjDB->QueryObject("SELECT a.fld_id AS exid, a.fld_extend_text AS exname FROM ".$tablename1." AS a 
                                                                                                       LEFT JOIN `itc_user_master` AS b ON a.`fld_created_by`=b.`fld_id` 
                                                                                                       WHERE a.fld_exp_id='".$moduleid."' AND (b.fld_profile_id=2 OR b.fld_profile_id=3) AND a.fld_delstatus='0'");
                                                                }
                                    /** select the extend content for expedition **/     
                                    if($templist[1] == 18)
                                    {

                                    $getmiscontent= $ObjDB->QueryObject("SELECT a.fld_id AS exid, a.fld_extend_text AS exname FROM ".$tablename." AS a 
                                                                                LEFT JOIN `itc_user_master` AS b ON a.`fld_created_by`=b.`fld_id` 
                                                                                WHERE a.fld_mis_id='".$moduleid."' AND (b.fld_profile_id=2 OR b.fld_profile_id=3) AND a.fld_delstatus='0'");

                                    }
               /* end select the extend content for expedition */
                                    if($templist[1] != 15 and $templist[1] != 18)  
                                    {
                                                                                        $getcontent = $ObjDB->QueryObject("SELECT a.fld_id AS exid, a.fld_extend_text AS exname 
                                                                                                                                                        FROM ".$tablename." AS a LEFT JOIN `itc_user_master` AS b ON a.`fld_created_by`=b.`fld_id` 
                                                                                                                                                        WHERE a.fld_module_id='".$moduleid."' AND (b.fld_profile_id=2 OR b.fld_profile_id=3) AND a.fld_delstatus='0'");
                                                   }
                                           
							$texname = "Select Extend Content";
                           
							if($getcontent->num_rows>0){
								$tempvar = 1;
								
								if($licenseid!=0){
									$selectext = $ObjDB->QueryObject("SELECT a.fld_id AS texid, a.fld_extend_text AS texname 
																		FROM ".$tablename." AS a LEFT JOIN `itc_license_extcontent_mapping` AS b ON a.`fld_id`=b.`fld_ext_id` 
																		WHERE b.fld_module_id='".$moduleid."' AND b.`fld_type`='".$templist[1]."' AND b.`fld_active`='1' 
																		AND b.fld_license_id='".$licenseid."' AND a.fld_delstatus='0'");
									if($selectext->num_rows>0){
										$res = $selectext->fetch_assoc();
										extract($res);
									}

									$chk = $ObjDB->SelectSingleValueInt("SELECT (SELECT COUNT(a.fld_id) FROM `itc_class_rotation_extcontent_mapping` AS a 
LEFT JOIN `itc_class_rotation_schedule_mastertemp` AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_ext_id='".$texid."' AND a.fld_active='1' AND b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' AND a.fld_module_id = '".$moduleid."')+ (SELECT COUNT(a.fld_id) FROM `itc_class_indassesment_extcontent_mapping` AS a LEFT JOIN `itc_class_indassesment_master` AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_ext_id='".$texid."' AND a.fld_ext_id<>0 AND a.fld_active='1' AND b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' AND a.fld_module_id = '".$moduleid."')+(SELECT COUNT(a.fld_id) FROM `itc_class_expmaterial_extcontent_mapping` AS a LEFT JOIN `itc_class_indasexpedition_master` AS b 
                                                                                    ON a.fld_schedule_id=b.fld_id WHERE a.fld_ext_id='".$texid."' AND a.fld_ext_id<>0 AND a.fld_active='1' 
                                                                                    AND b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' AND a.fld_exp_id = '".$moduleid."') AS d");
									
								}
                                                            if($templist[1] != 15 AND $templist[1] != 18) {
								?>
							<tr>
                            	<td><?php echo $modulename; ?></td>
                                <td>									
                                    <div id="clspass">   
                                        <dl class='field row <?php if($chk!=0) echo "dim"; $chk=0; ?>'>
                                            <div class="selectbox">
                                                <input type="hidden" name="exid_<?php echo $moduleid;?>" id="exid_<?php echo $moduleid;?>" value="<?php echo $texid."~".$templist[1]."~".$moduleid;?>">
                                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                                    <ul role="options" style="width:100%">                                                    
                                                       <?php 
															while($res = $getcontent->fetch_assoc()){
																extract($res);
																?>
                                                                <li><a tabindex="-1" href="#" data-option="<?php echo $exid."~".$templist[1]."~".$moduleid;?>"><?php echo $exname; ?></a></li>
                                                                <?php
                                                            }?>      
                                                    </ul>
                                                </div>
                                            </div> 
                                        </dl>
                                    </div>
								</td>
                            </tr>
							<?php 
							}  //ends for if($templist[1] != 15)....
							}  //ends for if($getcontent->num_rows>0)
                                                         /* starts get expedition extend content in select box ***/
                                                        
                        if($templist[1] == 15)
                        {
                            if($getexpcontent->num_rows>0){
                                           $tempvar = 1;
                                 if($licenseid!=0){

                                    $selectext = $ObjDB->QueryObject("SELECT a.fld_id AS texid, a.fld_extend_text AS texname 
                                                                        FROM ".$tablename." AS a LEFT JOIN `itc_license_extcontent_mapping` AS b ON a.`fld_id`=b.`fld_ext_id` 
                                                                        WHERE b.fld_module_id='".$moduleid."' AND b.`fld_type`='".$templist[1]."' AND b.`fld_active`='1' 
                                                                        AND b.fld_license_id='".$licenseid."' AND a.fld_delstatus='0'");
                                 
                                 if($selectext->num_rows>0){
                                    $res = $selectext->fetch_assoc();
                                    extract($res);

                                  $expchk = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM `itc_class_indasexpedition_extcontent_mapping` AS a LEFT JOIN `itc_class_indasexpedition_master` AS b 
                                                                            ON a.fld_schedule_id=b.fld_id WHERE a.fld_ext_id='".$texid."' AND a.fld_active='1' AND b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'");
                                 }

                            }
                        if(!(in_array($modulename,$unicexpname))) {
                        	array_push($unicexpname,$modulename);
                            ?>
                            <tr>
                                <td><?php echo $modulename; ?></td>
                                <td>									
                                    <div id="clspass">   
                                    <dl class='field row <?php if($expchk!=0) echo "dim"; $expchk=0; ?>'>
                                        <div class="selectbox">
                                            <input type="hidden" name="exid_<?php echo $moduleid;?>" id="exid_<?php echo $moduleid;?>" value="<?php echo $texid."~".$templist[1]."~".$moduleid;?>">
                                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
                                                <b class="caret1"></b>
                                            </a>
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search Class">
                                             <ul role="options" style="width:100%">                                                    
                                                <?php 
                                                        while($res = $getexpcontent->fetch_assoc()){
                                                                extract($res);
                                                                ?>
                                                                <li><a tabindex="-1" href="#" data-option="<?php echo $exid."~".$templist[1]."~".$moduleid;?>"><?php echo $exname; ?></a></li>
                                                <?php    }  ?>      
                                        </ul>
                                        </div>
                                        </div> 
                                    </dl>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            }  // ends for in_array($modulename,$unicexpname))
                        }  //ends for if($getexpcontent->num_rows>0)
                    }
                    
                    
                    /* Expedition New Coding*/

                    // Expedition
                    if($listexd[0] != '') 
                    {

                    $cntexp=0;
                    $tempexp = '';
                    for($i=0;$i<sizeof($listexd);$i++)
                    {
                        $templist = explode('~',$listexd[$i]);
                        if($templist[1] == 15) 
                        {
                                $cntexp++;
                                $moduleid = $templist[0];
                                $forexpedn =$moduleid;
                                $modulename = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_exp_name,'/ Expedition') FROM itc_exp_master WHERE fld_id='".$moduleid."'");
                                $tablename = "itc_exp_extendmaterials_master";
                                $modulename1 = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_exp_name,'/ Expedition') FROM itc_exp_master WHERE fld_id='".$moduleid."'");

                                $tablename1 = "itc_exp_extendtext_master";

                                $getexpcontent1 = $ObjDB->QueryObject("SELECT a.fld_id AS exid, a.fld_extend_text AS exname FROM ".$tablename1." AS a 
                                                                                                LEFT JOIN `itc_user_master` AS b ON a.`fld_created_by`=b.`fld_id` 
                                                                                                    WHERE a.fld_exp_id='".$moduleid."' AND (b.fld_profile_id=2 OR b.fld_profile_id=3) AND a.fld_delstatus='0'");



                                $texname = "Select Extend Content";
                                if($getexpcontent1->num_rows>0)
                                {
                                    $tempvar = 1;
                                    if($licenseid!=0)
                                    {
                                        $selectext1 = $ObjDB->QueryObject("SELECT a.fld_id AS texid, a.fld_extend_text AS texname 
                                                                                                        FROM ".$tablename1." AS a LEFT JOIN `itc_license_extcontent_mapping` AS b ON a.`fld_id`=b.`fld_ext_id` 
                                                                                                        WHERE b.fld_module_id='".$moduleid."' AND b.`fld_type`='".$templist[1]."' AND b.`fld_active`='1' 
                                                                                                        AND b.fld_license_id='".$licenseid."' AND a.fld_delstatus='0'");

                                        if($selectext1->num_rows>0)
                                        {
                                                $res1 = $selectext1->fetch_assoc();
                                                extract($res1);
                                                $expchk = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM `itc_class_indasexpedition_extcontent_mapping` AS a LEFT JOIN `itc_class_indasexpedition_master` AS b 
                                                                                                                                        ON a.fld_schedule_id=b.fld_id WHERE a.fld_ext_id='".$texid."' AND a.fld_active='1' AND b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'");
                                        }

                                    }           

                                        if(!(in_array($modulename1,$unicexpname1))) 
                                        {
                                            array_push($unicexpname1,$modulename1);

                                                ?>

                                                <tr>
                                                    <td><?php echo $modulename1."/ Extend"; ?></td>
                                                    <td>									
                                                        <div id="clspass">   
                                                            <dl class='field row <?php if($expchk!=0) echo "dim"; $expchk=0; ?>'>
                                                                <div class="selectbox">
                                                                    <input type="hidden" name="exid_<?php echo $moduleid;?>" id="exid_<?php echo $moduleid;?>" value="<?php echo $texid."~".$templist[1]."~".$moduleid;?>">
                                                                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                                            <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
                                                                            <b class="caret1"></b>
                                                                    </a>
                                                                    <div class="selectbox-options">
                                                                            <input type="text" class="selectbox-filter" placeholder="Search Class">
                                                                            <ul role="options" style="width:100%">                                                    
                                                                            <?php 
                                                                                    while($res = $getexpcontent1->fetch_assoc())
                                                                                    {
                                                                                    extract($res);
                                                                            ?>
                                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $exid."~".$templist[1]."~".$moduleid;?>"><?php echo $exname; ?></a></li>
                                                                            <?php    }  ?>      
                                                                            </ul>
                                                                    </div>
                                                                </div> 
                                                            </dl>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php 
                                        }  // ends for in_array($modulename,$unicexpname))
                                }  //ends for if($getexpcontent->num_rows>0)
                            }

                                    /* ends get expedition extend content in select box ***/                       
                      }    // ends for loop
                    }     // ends if condn $listexd[0] != '' 

                    /* Expedition New Coding End Line*/
                        
                    // Mission extend content starts
                    if($templist[1] == 18)
                    {
                        if($getmiscontent->num_rows>0){
                                           $tempvar = 1;
                                 if($licenseid!=0){

                                    $selectext = $ObjDB->QueryObject("SELECT a.fld_id AS texid, a.fld_extend_text AS texname 
                                                                        FROM ".$tablename." AS a LEFT JOIN `itc_license_extcontent_mapping` AS b ON a.`fld_id`=b.`fld_ext_id` 
                                                                        WHERE b.fld_module_id='".$moduleid."' AND b.`fld_type`='".$templist[1]."' AND b.`fld_active`='1' 
                                                                        AND b.fld_license_id='".$licenseid."' AND a.fld_delstatus='0'");
                                 
                                 if($selectext->num_rows>0){
                                    $res = $selectext->fetch_assoc();
                                    extract($res);

                                  $mischk = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM `itc_class_indasmission_extcontent_mapping` AS a LEFT JOIN `itc_class_indasmission_master` AS b 
                                                                            ON a.fld_schedule_id=b.fld_id WHERE a.fld_ext_id='".$texid."' AND a.fld_active='1' AND b.fld_license_id='".$licenseid."' AND b.fld_delstatus='0'");
                                 }

                            }
                        if(!(in_array($modulename,$unicexpname))) {
                        	array_push($unicexpname,$modulename);
                            ?>
                            <tr>
                                <td><?php echo $modulename; ?></td>
                                <td>									
                                    <div id="clspass">   
                                    <dl class='field row <?php if($mischk!=0) echo "dim"; $mischk=0; ?>'>
                                        <div class="selectbox">
                                            <input type="hidden" name="exid_<?php echo $moduleid;?>" id="exid_<?php echo $moduleid;?>" value="<?php echo $texid."~".$templist[1]."~".$moduleid;?>">
                                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="" style="width:97%"><?php echo $texname;?></span>
                                                <b class="caret1"></b>
                                            </a>
                                        <div class="selectbox-options">
                                            <input type="text" class="selectbox-filter" placeholder="Search Class">
                                             <ul role="options" style="width:100%">                                                    
                                                <?php 
                                                        while($res1 = $getmiscontent->fetch_assoc()){
                                                                extract($res1);
                                                                ?>
                                                                <li><a tabindex="-1" href="#" data-option="<?php echo $exid."~".$templist[1]."~".$moduleid;?>"><?php echo $exname; ?></a></li>
                                                <?php    }  ?>      
                                        </ul>
                                        </div>
                                        </div> 
                                    </dl>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            }  // ends for in_array($modulename,$unicexpname))
                        }
                    }
                // Mission extend content ends
                    
     /* ends get expedition extend content in select box ***/                       
						}    // ends for loop
					}     // ends if condn $list4[0] != ''
				  if($tempvar==0){ ?>
                <tr><td colspan="2">No records</td></tr>
                <?php	
                }?>                               
            </tbody>
        </table>
    </div>
    <?php 
}


if($oper == "loadmodulesave" and $oper != ""){
    
        $licenseid = isset($method['licenseid']) ? $method['licenseid'] : 0;
	$chkid = isset($method['chkid']) ? $method['chkid'] : '';
        $movemods = isset($method['movemods']) ? $method['movemods'] : '';
        $list4 = isset($method['list4']) ? $method['list4'] : '';
        $list4=explode(",",$list4);
	 
// Moving all modules from left to right in license

	if($chkid == 0) {

		if($list4[0] != '') {

			for($i=0;$i<sizeof($list4);$i++)
			{
				$templist = explode('~',$list4[$i]);

				$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_mod_mapping 
							WHERE fld_license_id='".$licenseid."'  AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");
			if($movemods == 1) {

													
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$licenseid."','".$templist[0]."','1','".$templist[1]."', '".$uid."', '".$date."')");
					}
					else
					{

						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$licenseid."' AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");
					}

}
else
{

$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$licenseid."' AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");

}
			}

		}
	   
	}
	elseif($chkid == 1)
	{
		  $templist = explode('_',$list4[0]);
		
		  $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_mod_mapping 
							WHERE fld_license_id='".$licenseid."'  AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");
			if($movemods == 5) {
				if($cnt==0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
				VALUES('".$licenseid."','".$templist[0]."','1','".$templist[1]."', '".$uid."', '".$date."')");
				}
				else
				{

					$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
						SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
						WHERE fld_license_id='".$licenseid."' AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");
				}
			}
			else
			{

				$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
						SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
						WHERE fld_license_id='".$licenseid."' AND fld_module_id='".$templist[0]."' AND fld_type='".$templist[1]."'");

			}

	}


 
}


//Load assessment after dragging content from licens form
if($oper=="loadassessment" and $oper != " " )
{    
	error_reporting(E_ALL);
        ini_set('display_errors', '1');
	
	
	$iplids = isset($method['iplids']) ? $method['iplids'] : 0;
	$moduleids = isset($method['maduleids']) ? $method['maduleids'] : 0;
	$expids = isset($method['expids']) ? $method['expids'] : 0;
	$questids = isset($method['questids']) ? $method['questids'] : 0;
	$pdids = isset($method['pdids']) ? $method['pdids'] : 0;
	$missionids = isset($method['missionids']) ? $method['missionids'] : 0;
	
	$licenseid = isset($method['id']) ? $method['id'] : 0;
	$mathmodid='';
	
	
	if($iplids == '') // IPL
	{
		$iplids=0;
	}
	
	if($moduleids == '') // Module
	{
		$modid=0;
	}
	else
	{
		$moduleids = explode(',',$moduleids);
		$modid = '';
		$mathmodid = '';
		for($i=0;$i<sizeof($moduleids);$i++)
		{
			$moduleid  = explode('_',$moduleids[$i]); 
			if($modid=='' and $moduleid[1]==1)
			{
				$modid=$moduleid[0];
			}
			else if($modid!='' and $moduleid[1]==1)
			{
				$modid=$modid.",".$moduleid[0];
			}
                        
                        if($mathmodid=='' and $moduleid[1]==2)
			{
				$mathmodid=$moduleid[0];
			}
			else if($mathmodid!='' and $moduleid[1]==2)
			{
				$mathmodid=$mathmodid.",".$moduleid[0];
			}
			
		}
	}
        
        if($modid == '') // Expedition
	{
		$modid=0;
	}
        
        if($mathmodid == '') // Expedition
	{
		$mathmodid=0;
	}
	
	if($expids == '') // Expedition
	{
		$expid=0;
	}
	else
	{
		$expids = explode(',',$expids);
		$expid = '';
		for($i=0;$i<sizeof($expids);$i++)
		{
			$expid1  = explode('_',$expids[$i]); 
			if($expid=='')
			{
				$expid=$expid1[0];
			}
			else
			{
				$expid=$expid.",".$expid1[0];
			}
		}
	}
	
	if($questids == '') // Quest
	{
		$queid=0;
	}
	else
	{
		$questids = explode(',',$questids);
		$queid = '';
		for($i=0;$i<sizeof($questids);$i++)
		{
			$queids  = explode('_',$questids[$i]); 
			if($queid=='')
			{
				$queid=$queids[0];
			}
			else
			{
				$queid=$queid.",".$queids[0];
			}
		}
	}
	
	if($pdids == '') // PD
	{
		$pdid=0;
	}
	else
	{
		$pdids = explode(',',$pdids);
		$pdid = '';
		for($i=0;$i<sizeof($pdids);$i++)
		{
			$pdidss  = explode('~',$pdids[$i]); 
			if($pdid=='')
			{
				$pdid=$pdidss[0];
			}
			else
			{
				$pdid=$pdid.",".$pdidss[0];
			}
		}
	}
	
	if($missionids == '') //Missions
	{
		$misid=0;
	}
	else
	{
		$missionids = explode(',',$missionids);
		$misid = '';
		for($i=0;$i<sizeof($missionids);$i++)
		{
			$misids  = explode('_',$missionids[$i]); 
			if($misid=='')
			{
				$misid=$misids[0];
			}
			else
			{
				$misid=$misid.",".$misid[0];
			}
		}
	}
        
       ?>
  
  <script>
      
      $(function(){
                $('#testrailvisible9').slimscroll({
                    width: '410px',
                    height:'366px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                   wheelStep: '1'
                });
                
                $('#testrailvisible10').slimscroll({
                    width: '410px',
                    height:'370px',
                    size: '7px',
                    alwaysVisible: true,
                    railVisible: true,
                    allowPageScroll: false,
                    railColor: '#F4F4F4',
                    opacity: 1,
                    color: '#d9d9d9',
                      wheelStep: '1'
                });
            
                $("#list9").sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {                        
                        $("div[class='draglinkright']").each(function(){ 
                            if($(this).parent().attr('id')=='list9'){
                                fn_movealllistitems('list9','list10',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $( "#list10" ).sortable({
                    connectWith: ".droptrue",
                    dropOnEmpty: true,
                    receive: function(event, ui) {
                        $("div[class='draglinkleft']").each(function(){ 
                            if($(this).parent().attr('id')=='list10'){
                                fn_movealllistitems('list9','list10',1,$(this).children(":first").attr('id'));
                            }
                        });
                    }
                });
            
                $("#list9, #list10").disableSelection();
            });
  </script>
  
        
                      <div class='six columns'>
                            <div class="dragndropcol">
                                <?php
                                
                                   
                                                 $testid='';
                                                 $qrytestid=$ObjDB->QueryObject("SELECT w.* FROM(SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='1' and fld_product_id in (".$expid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='2' and fld_product_id in (".$iplids.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='3' and fld_product_id in (".$modid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='4' and fld_product_id in (".$mathmodid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='5' and fld_product_id in (".$misid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='6' and fld_product_id in (".$pdid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='0' and fld_content_id='7' and fld_product_id in (".$queid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='1' and fld_expt in (".$expid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_id as assessment_id from itc_test_master where fld_ass_type='2' and fld_mist in (".$misid.") and fld_delstatus='0' and fld_flag='1'
                                                                                 UNION ALL
                                                                                 SELECT fld_assessment_id as assessment_id FROM itc_license_assessment_mapping WHERE fld_license_id='".$licenseid."' AND fld_access='1') as w group by w.assessment_id");
                                                 
                                                 if($qrytestid->num_rows>0)
                                                 {
                                                     while($row=$qrytestid->fetch_assoc())
                                                     {
                                                         extract($row);
                                                         
                                                           if($testid=='')
                                                           {
                                                                   $testid=$assessment_id;
                                                           }
                                                           else
                                                           {
                                                                   $testid=$testid.",".$assessment_id;
                                                           }
                                                     }
                                                 }
                                                 
                                                 if($testid=='')
                                                {
                                                        $testid=0;
                                                }
                                                                           
                                                         
                                        //If the license is new one get all the assessments which is created by pitscoadmin, otherwise get the assessment except particular license  
                                        	 $qryass=$ObjDB->QueryObject("SELECT a.fld_id AS assid, a.fld_test_name AS assname 
											 							 FROM itc_test_master AS a LEFT JOIN itc_user_master AS b ON a.fld_created_by=b.fld_id 
																		 WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_id NOT IN (".$testid.") 
																				AND (b.fld_profile_id IN (2,3)) 
																		 ORDER BY a.fld_test_name");
                                ?>
                                <div class="dragtitle">Assessments available (<span id="leftassessments"><?php echo $qryass->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list9" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible9" >
                                    <div id="list9" class="dragleftinner droptrue">
                                        <?php 
										
                                            if($qryass->num_rows > 0){
                                                while($resass=$qryass->fetch_assoc()){
                                                    extract($resass);
                                                ?>
                                                    <div class="draglinkleft" id="list9_<?php echo $assid; ?>" >
                                                        <div class="dragItemLable" id="<?php echo $assid; ?>"><?php echo $assname; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $assid; ?>" onclick="fn_movealllistitems('list9','list10',1,<?php echo $assid; ?>);"></div>
                                                    </div> 
                                                <?php
                                                }
                                            }
                                        ?>    
                                    </div>
                                </div>
                                <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width:  153px;float: right; ">Add all Assessments.</div>
                            </div>
                        </div>
                        <div class='six columns'>
                            <div class="dragndropcol">
                                        <?php 
                                        
                                        
										//get the assessment which is mapping with the license
                                        $qryunitselect=$ObjDB->QueryObject("SELECT fld_id as assid,fld_test_name as assname 
                                                                                FROM itc_test_master where fld_id in (".$testid.") and fld_delstatus='0' and fld_flag='1'
                                                                               
																			GROUP BY fld_id 
																			ORDER BY fld_test_name");
                                        $qryassunselect=$ObjDB->QueryObject("SELECT fld_test_id FROM itc_test_student_mapping where fld_flag = 1 AND fld_test_id 
                                                                            IN (select fld_assessment_id from itc_license_assessment_mapping where fld_license_id = '".$licenseid."' AND fld_access='1')");
                                        //below this change line
                                       $qryassunselect=$ObjDB->QueryObject("SELECT c.fld_test_id as assid
                                                                                FROM itc_test_master AS a 
                                                                               LEFT JOIN itc_license_assessment_mapping AS b ON a.fld_id=b.fld_assessment_id 
                                                                                LEFT JOIN itc_test_student_mapping AS c ON c.fld_test_id=b.fld_assessment_id 
                                                                                WHERE a.fld_delstatus='0' AND b.fld_license_id='".$licenseid."' AND b.fld_access='1' AND c.fld_flag = '1' 
                                                                                GROUP BY a.fld_id");
                                                 
                                            $filter_greyout=array(); 
                                            while($assunselect=$qryassunselect->fetch_assoc()){
                                            extract($assunselect);
                                            array_push($filter_greyout,$assid);
                                          }
                                ?>
                                <div class="dragtitle">Assessments in your license (<span id="rightassessments"><?php echo $qryunitselect->num_rows ;?></span>)</div>
                                <div class="draglinkleftSearch" id="s_list10" >
                                   <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class="dragWell" id="testrailvisible10">
                                    <div id="list10" class="dragleftinner droptrue">
                                        <?php 
										
                                            if($qryunitselect->num_rows > 0){
                                                while($resassignedunit=$qryunitselect->fetch_assoc()){
                                                    extract($resassignedunit);
                                                    $dimass = array_diff(array($assid),$filter_greyout);
                                                    ?>
                                                        <div class="draglinkright<?php if(empty($dimass)) { echo ' dim'; }?>" id="list10_<?php echo $assid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $assid; ?>"><?php echo $assname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $assid; ?>" onclick="fn_movealllistitems('list9','list10',1,<?php echo $assid; ?>);"></div>
                                                        </div>
                                                    <?php 
                                                }
                                            }
                                        ?>	
                                    </div>
                                </div>
                                <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);" style="cursor: pointer;cursor:hand;width:  183px;float: right; ">Remove all Assessments.</div>
                            </div>
                        </div>
                    
                
  
       <?php
	
	
}
	
 
  
@include("footer.php");