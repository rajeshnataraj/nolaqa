<?php 
/*------
	Page - library-pd-newlessons-ajax
	Description:
		Backend page to perform the actions required for a lesson
			
	History:	
		
------*/
	
	@include("sessioncheck.php");
	
	$oper = isset($_POST['oper']) ? $_POST['oper'] : '';
	
	/*--- Check Asset ID ---*/
	if($oper=="checkassetid" and $oper != " " )
	{
            try
            {
		$pdid = isset($_POST['lid']) ? $_POST['lid'] : '0';
                /**declartion for validade module id***/
                        $validate_pdid=true;
                        if($pdid!=0)  
                        $validate_pdid=validate_datatype($pdid,'int');

                        if($validate_pdid)
                        {
                        $assetid = isset($_POST['txtassetid']) ? fnEscapeCheck($_POST['txtassetid']) : '0';

                        $count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_pd_master WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' AND fld_delstatus='0' AND fld_id<>'".$pdid."'");

                        if($count == 0){ echo "true"; }	else { echo "false"; }
                        }
            }
            catch(Exception $e)
            {
                    echo "fail";
            }
	}
	
	/*--- Save/Update a PD  ---*/
	if($oper == "savepd" and $oper != '')
	{	
		$pdid = isset($_POST['pdid']) ? $_POST['pdid'] : '0';
		$courseid = isset($_POST['courseid']) ? $_POST['courseid'] : '';
		$pdname = isset($_POST['pdname']) ? $ObjDB->EscapeStrAll($_POST['pdname']) : '';
		$pddescription = isset($_POST['pddescription']) ? $ObjDB->EscapeStr($_POST['pddescription']) : '';
		$webpd = isset($_POST['webhid']) ? $_POST['webhid'] : '';
		$webversion = isset($_POST['webversion']) ? $_POST['webversion'] : '';
		$pdicon = isset($_POST['pdicon']) ? $_POST['pdicon'] : '';
		$tags = isset($_POST['tags']) ? $_POST['tags'] : '';
		$pdtype = isset($_POST['pdtype']) ? $_POST['pdtype'] : '';		
		$assetid = isset($_POST['assetid']) ? $ObjDB->EscapeStrAll($_POST['assetid']) : ''; 		
		$list16 = isset($method['list16']) ? $method['list16'] : '';
                $list16=explode(",",$list16);
		try  
		{  
			//update lesson details
			if($pdid != '' and $pdid != '0' and $pdid != 'undefined')
			{
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_tag_type='1' AND fld_item_id='".$pdid."' AND fld_tag_id IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");
				
				fn_tagupdate($tags,30,$pdid,$uid);			
				
				//update general informations
                               	$ObjDB->NonQuery("UPDATE itc_pd_master SET fld_course_id='".$courseid."',fld_pd_name='".$pdname."', fld_pd_descr='".$pddescription."', fld_pd_icon='".$pdicon."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_asset_id='".$assetid."', fld_lesson_type='".$pdtype."' WHERE fld_id='".$pdid."'");
				
                                // update web pd version track
				$ObjDB->NonQuery("UPDATE itc_pd_version_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_pd_id='".$pdid."'");
				
				$chkwebpd = $ObjDB->Count("SELECT fld_id FROM itc_pd_version_track WHERE fld_pd_id='".$pdid."' AND fld_zip_type='1' AND fld_version='".$webversion."'");
				
                                if($chkwebpd==0){
					$ObjDB->NonQuery("INSERT INTO itc_pd_version_track (fld_pd_id, fld_version, fld_zip_type, fld_zip_name, fld_created_by, fld_created_date)VALUES('".$pdid."', '".$webversion."', '1', '".$webpd."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
				else{
					$ObjDB->NonQuery("UPDATE itc_pd_version_track SET fld_zip_name='".$webpd."', fld_delstatus='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_pd_id='".$pdid."' AND fld_zip_type='1' AND fld_version='".$webversion."'");
				}
				
                               //pd insert/update
                                    $ObjDB->NonQuery("UPDATE itc_license_pd_mapping 
                                                                     SET fld_active='0', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                     WHERE fld_license_id='".$pdid."'");
                                    if($list16[0] != '') {
                                            for($i=0;$i<sizeof($list16);$i++)
                                            {

                                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                    FROM itc_license_pd_mapping 
                                                                                                    WHERE fld_license_id='".$list16[$i]."'  AND fld_pd_id='".$pdid."'");
                                                    if($cnt==0)
                                                    {
                                                        
                                                        $ObjDB->NonQuery("INSERT INTO itc_license_pd_mapping (fld_license_id,fld_pd_id,fld_course_id,fld_active,fld_created_by, fld_created_date)
                                                                                                    VALUES('".$list16[$i]."','".$pdid."','".$courseid."','1','".$uid."', '".date("Y-m-d H:i:s")."')");
                                                    }
                                                    else
                                                    {
                                                   
                                                        $ObjDB->NonQuery("UPDATE itc_license_pd_mapping 
                                                                                                    SET fld_active='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                                                    WHERE fld_license_id='".$list16[$i]."' AND fld_pd_id='".$pdid."'");
                                                    }
                                                    
                                                    
                                                    $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_course_mapping 
                                                                                                WHERE fld_license_id='".$list16[$i]."'  AND fld_course_id='".$courseid."'");
                                                    if($cnt1==0)
                                                    {
                                                             $ObjDB->NonQuery("INSERT INTO itc_license_course_mapping (fld_license_id, fld_course_id, fld_flag, fld_created_by, fld_created_date)
                                                                                                    VALUES('".$list16[$i]."','".$courseid."','1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                                    }
                                                    else
                                                    {
                                                            $ObjDB->NonQuery("UPDATE itc_license_course_mapping 
                                                                                                    SET fld_flag='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                                                    WHERE fld_license_id='".$list16[$i]."' AND fld_course_id='".$courseid."'");
                                                    }

                                            }
                                    }
                        
                                
				echo "success";			
			}
			else{	
                            
                            
			
                            $pdid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_pd_master(fld_course_id,fld_pd_name, fld_pd_descr,fld_pd_icon, fld_access, fld_created_date, fld_created_by, fld_asset_id, fld_lesson_type) VALUES ('".$courseid."','".$pdname."', '".$pddescription."','".$pdicon."', '1','".date("Y-m-d H:i:s")."','".$uid."','".$assetid."','".$pdtype."')");
                            //this is for web pd
                            $ObjDB->NonQuery("INSERT INTO itc_pd_version_track (fld_pd_id, fld_version, fld_zip_type, fld_zip_name, fld_created_by, fld_created_date)VALUES('".$pdid."','".$webversion."','1','".$webpd."', '".$uid."', '".date("Y-m-d H:i:s")."')");	

                                //pd insert/update
                                $ObjDB->NonQuery("UPDATE itc_license_pd_mapping 
                                                                 SET fld_active='0', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                 WHERE fld_license_id='".$pdid."'");
                                if($list16[0] != '') {
                                        for($i=0;$i<sizeof($list16);$i++)
                                        {

                                                $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                FROM itc_license_pd_mapping 
                                                                                                WHERE fld_license_id='".$list16[$i]."'  AND fld_pd_id='".$pdid."'");
                                                if($cnt==0)
                                                {
                                                         $ObjDB->NonQuery("INSERT INTO itc_license_pd_mapping(fld_license_id,fld_pd_id,fld_course_id,fld_active,fld_created_by, fld_created_date)
                                                                                                VALUES('".$list16[$i]."','".$pdid."','".$courseid."','1','".$uid."', '".date("Y-m-d H:i:s")."')");
                                                }
                                                else
                                                {
                                                        $ObjDB->NonQuery("UPDATE itc_license_pd_mapping 
                                                                                                SET fld_active='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                                                WHERE fld_license_id='".$list16[$i]."' AND fld_pd_id='".$pdid."'");
                                                }
                                                
                                                $cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_course_mapping 
                                                                                                WHERE fld_license_id='".$list16[$i]."'  AND fld_course_id='".$courseid."'");
                                                    if($cnt1==0)
                                                    {
                                                             $ObjDB->NonQuery("INSERT INTO itc_license_course_mapping (fld_license_id, fld_course_id, fld_flag, fld_created_by, fld_created_date)
                                                                                                    VALUES('".$list16[$i]."','".$courseid."','1', '".$uid."', '".date("Y-m-d H:i:s")."')");
                                                    }
                                                    else
                                                    {
                                                            $ObjDB->NonQuery("UPDATE itc_license_course_mapping 
                                                                                                    SET fld_flag='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
                                                                                                    WHERE fld_license_id='".$list16[$i]."' AND fld_course_id='".$courseid."'");
                                                    }

                                        }
                                }

				/*--Tags insert-----*/		
				fn_taginsert($tags,30,$pdid,$uid);
								
				echo "success";
			}
		 
		}  
		catch (Exception $e)  
		{  
			echo "fail".$e;
		}  
	}	
		
	/*--- Delete a Lesson  ---*/
	if($oper == "deletepd" and $oper != '')
	{	
		$pdid = isset($_POST['id']) ? $_POST['id'] : '';
		
		//check wheather the lesson in licnese or not             
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_pd_mapping WHERE fld_pd_id='".$pdid."' AND fld_active='1'");
		
		if($count!=0)
		{
                    
			$ObjDB->NonQuery("UPDATE itc_pd_master SET fld_delstatus='1', fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$pdid."'");
			echo "success";
		}
		else
		{
			echo "exists";
		}
	}
	
	//change webpd verion
	if($oper=="weppdversion" and $oper != " ")
	{
		$pdid = isset($_POST['pdid']) ? $_POST['pdid'] : '';
		$currentversion = $ObjDB->SelectSingleValue("SELECT MAX(FORMAT(fld_version,1)) FROM itc_pd_version_track WHERE fld_pd_id='".$pdid."' and fld_zip_type='1'");
		if($currentversion=='')
			$newversion = 1.0;
		else
			$newversion = $currentversion+0.1;
		?>
        
            <div class="selectbox" >
                <input type="hidden" name="webversion" id="webversion" value="<?php echo number_format($newversion,1);?>" >
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">                      	
                    <span class="selectbox-option input-medium" style="width:95%;" data-option="<?php echo number_format($newversion,1);?>">Version <?php echo number_format($newversion,1);?> </span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <ul role="options">
                    <?php $qry = $ObjDB->QueryObject("select fld_version from itc_pd_version_track where fld_pd_id='".$pdid."' and fld_zip_type='1'");
						  while($res = $qry->fetch_object()){?>
                        <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changewebpdname(<?php echo number_format($res->fld_version,1);?>)">Version <?php echo number_format($res->fld_version,1); ?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        
        <?php
	}
	
        //Change the pd name according to version
	if($oper=="changewebiplname" and $oper != " ")
	{
		$pdid = isset($_POST['pdid']) ? $_POST['pdid'] : '';
		$version = isset($_POST['webversion']) ? $_POST['webversion'] : '';
		
		$currentname = $ObjDB->SelectSingleValue("SELECT fld_zip_name FROM itc_pd_version_track WHERE fld_pd_id='".$pdid."' and fld_zip_type='1' and fld_version ='".$version."'");
		echo $currentname;
	}
        
        
       

	
	@include("footer.php");