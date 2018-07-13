<?php 
/*------
	Page - library-lessons-newlessons-ajax
	Description:
		Backend page to perform the actions required for a lesson
			
	History:	
		
------*/
	
	@include("sessioncheck.php");
	
	$oper = isset($_POST['oper']) ? $_POST['oper'] : '';
	
	/*--- Check Asset ID ---*/
	if($oper=="checkassetid" and $oper != " " )
	{
		$lessonid = isset($_POST['lid']) ? $_POST['lid'] : '0';
		$assetid = isset($_POST['txtassetid']) ? fnEscapeCheck($_POST['txtassetid']) : '0';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_ipl_master WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' AND fld_delstatus='0' AND fld_id<>'".$lessonid."'");
		
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	/*--- Save/Update a Lesson  ---*/
	if($oper == "savelessons" and $oper != '')
	{	
		$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '0';
		$unitid = isset($_POST['unitid']) ? $_POST['unitid'] : '';
		$lessonsname = isset($_POST['lessonsname']) ? $ObjDB->EscapeStrAll($_POST['lessonsname']) : '';
		$ipldescription = isset($_POST['ipldescription']) ? $ObjDB->EscapeStr($_POST['ipldescription']) : '';
		$points = isset($_POST['Points']) ? $_POST['Points'] : '';
		$days = isset($_POST['Days']) ? $_POST['Days'] : '';
		$minutes = isset($_POST['Minutes']) ? $_POST['Minutes'] : '';
		$webipl = isset($_POST['webhid']) ? $_POST['webhid'] : '';
		$webversion = isset($_POST['webversion']) ? $_POST['webversion'] : '';
		$iplicon = isset($_POST['iplicon']) ? $_POST['iplicon'] : '';
		$tags = isset($_POST['tags']) ? $_POST['tags'] : '';
		$lessontype = isset($_POST['lessontype']) ? $_POST['lessontype'] : '';		
		$assetid = isset($_POST['assetid']) ? $ObjDB->EscapeStrAll($_POST['assetid']) : ''; 		
		
                $list10 = isset($method['list10']) ? $method['list10'] : '';
                $list9 = isset($method['list9']) ? $method['list9'] : '';
               
                $list10=explode(",",$list10);
                $list9=explode(",",$list9);

		try  
		{  
			//update lesson details
			if($lessonid != '' and $lessonid != '0' and $lessonid != 'undefined')
			{
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_tag_type='1' AND fld_item_id='".$lessonid."' AND fld_tag_id IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");
				
				fn_tagupdate($tags,1,$lessonid,$uid);			
				
				//update general informations
				$ObjDB->NonQuery("UPDATE itc_ipl_master SET fld_unit_id='".$unitid."', fld_ipl_name='".$lessonsname."', fld_ipl_descr='".$ipldescription."', fld_ipl_points='".$points."', fld_ipl_days='".$days."', fld_ipl_minutes='".$minutes."', fld_ipl_icon='".$iplicon."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_asset_id='".$assetid."', fld_lesson_type='".$lessontype."' WHERE fld_id='".$lessonid."'");
				
				// update web ipl version track
				$ObjDB->NonQuery("UPDATE itc_ipl_version_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_ipl_id='".$lessonid."'");
				
				$chkweblesson = $ObjDB->Count("SELECT fld_id FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_version='".$webversion."'");
				if($chkweblesson==0){
					$ObjDB->NonQuery("INSERT INTO itc_ipl_version_track (fld_ipl_id, fld_version, fld_zip_type, fld_zip_name, fld_created_by, fld_created_date)VALUES('".$lessonid."', '".$webversion."', '1', '".$webipl."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
				else{
					$ObjDB->NonQuery("UPDATE itc_ipl_version_track SET fld_zip_name='".$webipl."', fld_delstatus='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_version='".$webversion."'");
				}
				
				echo "success";			
			}
			else{		
			
				$lessonid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_ipl_master(fld_unit_id, fld_ipl_name, fld_ipl_descr, fld_ipl_points, fld_ipl_days, fld_ipl_minutes, fld_ipl_icon, fld_access, fld_created_date, fld_created_by, fld_asset_id, fld_lesson_type) VALUES ('".$unitid."', '".$lessonsname."', '".$ipldescription."', '".$points."', '".$days."', '".$minutes."','".$iplicon."', '1','".date("Y-m-d H:i:s")."','".$uid."','".$assetid."','".$lessontype."')");
			
				//this is for web ipl
				$ObjDB->NonQuery("INSERT INTO itc_ipl_version_track (fld_ipl_id,fld_version,fld_zip_type,fld_zip_name,fld_created_by, fld_created_date)VALUES('".$lessonid."','".$webversion."','1','".$webipl."', '".$uid."', '".date("Y-m-d H:i:s")."')");	
			
                                
				/*--Tags insert-----*/		
				fn_taginsert($tags,1,$lessonid,$uid);
								
				echo "success";
			}
                        
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					
                                       $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
									    FROM itc_license_cul_mapping 
									    WHERE fld_license_id='".$list10[$i]."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$lessonid."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_cul_mapping (fld_license_id,fld_unit_id,fld_lesson_id, fld_created_by, fld_created_date)
										 VALUES('".$list10[$i]."','".$unitid."','".$lessonid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_cul_mapping 
										 SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
										 WHERE fld_license_id='".$list10[$i]."' AND fld_unit_id='".$unitid."' AND fld_lesson_id='".$lessonid."'");
					}
                                        
                                        
					$cnt1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_unit_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_unit_id='".$unitid."'");
					if($cnt1==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_unit_mapping (fld_license_id, fld_unit_id, fld_access, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$unitid."','1', '".$uid."', '".date("Y-m-d H:i:s")."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_unit_mapping 
											SET fld_access='1', fld_updated_date = '".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_unit_id='".$unitid."'");
					}
					
				}
			}
                                
                        if($list9[0]!= '') {
				for($j=0;$j<sizeof($list9);$j++)
				{
                                    $cnt2 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
									  FROM itc_license_cul_mapping 
									  WHERE fld_license_id='".$list9[$j]."'  AND fld_lesson_id='".$lessonid."'");
								
                                    if($cnt2==1)
                                    {
                                        
                                        
                                        $ObjDB->NonQuery("UPDATE itc_license_cul_mapping 
							  SET fld_active='0', fld_updated_date ='".date("Y-m-d H:i:s")."' , fld_updated_by = '".$uid."' 
							  WHERE fld_license_id='".$list9[$j]."'  AND fld_lesson_id='".$lessonid."'");
			}
                                }
                           }
		 
		}  
		catch (Exception $e)  
		{  
			echo "fail".$e;
		}  
	}	
		
	/*--- Delete a Lesson  ---*/
	if($oper == "deletelesson" and $oper != '')
	{	
		$lessonid = isset($_POST['id']) ? $_POST['id'] : '';
		
		//check wheather the lesson in licnese or not
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_cul_mapping WHERE fld_lesson_id='".$lessonid."' AND fld_active='1'");
		
		if($count==0)
		{
			$ObjDB->NonQuery("UPDATE itc_ipl_master SET fld_delstatus='1', fld_deleted_by='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$lessonid."'");
			echo "success";
		}
		else
		{
			echo "exists";
		}
	}
	
	//chante webipl verion
	if($oper=="wepiplversion" and $oper != " ")
	{
		$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
		$currentversion = $ObjDB->SelectSingleValue("SELECT MAX(FORMAT(fld_version,1)) FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' and fld_zip_type='1'");
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
                    <?php $qry = $ObjDB->QueryObject("select fld_version from itc_ipl_version_track where fld_ipl_id='".$lessonid."' and fld_zip_type='1'");
						  while($res = $qry->fetch_object()){?>
                        <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changewebiplname(<?php echo number_format($res->fld_version,1);?>)">Version <?php echo number_format($res->fld_version,1); ?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        
        <?php
	}
		
	if($oper=="changewebiplname" and $oper != " ")
	{
		$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
		$version = isset($_POST['webversion']) ? $_POST['webversion'] : '';
		
		$currentname = $ObjDB->SelectSingleValue("SELECT fld_zip_name FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' and fld_zip_type='1' and fld_version ='".$version."'");
		echo $currentname;
	}
	
	@include("footer.php");