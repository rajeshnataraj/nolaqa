<?php
/*
	Created By - Muthukumar. D
	Page - library-modules-playerajax.php
	
	History:
*/
@include("sessioncheck.php");
	
$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Check Module Name ---*/
if($oper=="checkmodulename" and $oper != " " )
{
  
  try{
		
		$moduleid = isset($method['mid']) ? $method['mid'] : '0';
		
		/**declartion for validade module id***/
		$validate_moduleid=true;
		
		if($moduleid!=0)  $validate_moduleid=validate_datatype($moduleid,'int');
		$modulename = isset($method['txtmodname']) ? fnEscapeCheck($method['txtmodname']) : '';
		
		if($validate_moduleid)
		{
		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_module_master 
											  WHERE MD5(LCASE(REPLACE(fld_module_name,' ','')))='".$modulename."' 
											  AND fld_delstatus='0' AND fld_id<>'".$moduleid."'");
		if($count == 0){ echo "true"; }	else { echo "false"; }
		}
		else
		{
			echo "false";
		}
  }
  catch(Exception $e)
  {
	  echo "false";
  }
}


/*--- Check Asset ID ---*/
if($oper=="checkassetid" and $oper != " " )
{
	try
	{
	$moduleid = isset($method['mid']) ? $method['mid'] : '0';
	
	/**declartion for validade module id***/
		$validate_moduleid=true;
		if($moduleid!=0)  $validate_moduleid=validate_datatype($moduleid,'int');
		
		if($validate_moduleid)
		{
			$assetid = isset($method['txtassetid']) ? fnEscapeCheck($method['txtassetid']) : '0';
	 		$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_module_master 
												  WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' 
												  AND fld_delstatus='0' AND fld_id<>'".$moduleid."'");
			 if($count == 0){ echo "true"; }	else { echo "false"; }
		}
	}
	catch(Exception $e)
	{
		echo "fail";
	}
}

/*--- Delete the Module ---*/
if($oper=="deletemodule" and $oper != " " )
{
	try
	{
	
	$moduleid = isset($method['modid']) ? $method['modid'] : ''; 
	
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_mod_mapping 
	                                      WHERE fld_module_id='".$moduleid."' 
										  AND fld_active='1' AND fld_type='1'");
		
	if($count==0)
	{
		$mathcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mathmodule_master 
		                                          WHERE fld_module_id='".$moduleid."' 
												  AND fld_delstatus='0'");
		if($mathcount==0)
		{
			$ObjDB->NonQuery("UPDATE itc_module_master 
			                SET fld_delstatus='1',fld_deleted_by='".$uid."', fld_deleted_date='".$date."' 
			                 WHERE fld_id='".$moduleid."'");	
		
			$ObjDB->NonQuery("UPDATE itc_module_version_track 
			                 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' 
							 WHERE fld_mod_id='".$moduleid."'");	
			
			$ObjDB->NonQuery("UPDATE itc_module_play_track 
			                 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' 
							 WHERE fld_module_id='".$moduleid."'");
			
                        $ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
						 SET fld_delstatus='1', fld_deleted_date = '".$date."', fld_deleted_by = '".$uid."'  
						 WHERE fld_module_id= '".$moduleid."'");	                        
			
			echo "success";
		}
		else
		{
			echo "mathexists";
		}
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

/*--- Save and Update the Module ---*/
if($oper=="savemodule" and $oper != " " )
{
	try{	
	
        $qcount = isset($method['qcount']) ? $method['qcount'] : '0'; 
	$modid = isset($method['editid']) ? $method['editid'] : '0'; 
	$modname = isset($method['modname']) ? ($method['modname']) : ''; 
	$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '0'; 
	$assetid = isset($method['assetid']) ? $method['assetid'] : ''; 
	$modphase = isset($method['modphase']) ? $method['modphase'] : ''; 
	$modminutes = isset($method['modminutes']) ? $method['modminutes'] : ''; 
	$moddays = isset($method['moddays']) ? $method['moddays'] : ''; 
	$modversion = isset($method['modversion']) ? $method['modversion'] : ''; 
	$performance = isset($method['performance']) ? $method['performance'] : ''; 
	$points = isset($method['points']) ? $method['points'] : '';
	$filename = isset($method['filename']) ? $method['filename'] : ''; 
	$tags = isset($method['tags']) ? $method['tags'] : '';
	$quesid = isset($method['quesid']) ? $method['quesid'] : '';
	$ansid = isset($method['ansid']) ? $method['ansid'] : ''; 
	$correct = isset($method['correct']) ? $method['correct'] : ''; 
	$anstext = isset($method['anstext']) ? $method['anstext'] : '';
	$sectiontitle = isset($method['sectiontitle']) ? $method['sectiontitle'] : '';
	$attenpoints = isset($method['attenpoints']) ? $method['attenpoints'] : ''; 
	$partipoints = isset($method['partipoints']) ? $method['partipoints'] : ''; 
	$pagecnt = isset($method['pagecnt']) ? $method['pagecnt'] : '';
	$pagetitles = isset($method['pagetitles']) ? $method['pagetitles'] : ''; 
	$pagegrades = isset($method['pagegrades']) ? $method['pagegrades'] : '';
	
        $moduledescription = isset($_POST['moduledescription']) ? $ObjDB->EscapeStr($_POST['moduledescription']) : '';
        $list10 = isset($method['list10']) ? $method['list10'] : '';
	$lid=isset($method['lid']) ? $method['lid'] : '';
        $list10=explode(",",$list10);
        
	$pos = strrpos($filename, ".");
	$name = str_split($filename,$pos);
	if($name[1]==".sbook") $type=1;
	else if($name[1]==".zip") $type=0;
	$performance = array_filter(explode("@",$performance));
	$points = array_filter(explode("@",$points));
	
	/**for purpose remove unwanted scripts****/
	$anstext =  $ObjDB->EscapeStr($anstext);
	$moddesc = $ObjDB->EscapeStr($moddesc); 
	$assetid = $ObjDB->EscapeStrAll($assetid); 
	
	if($modid!='0')  /***updating module details already saved modules***/
	{
		/***updating module details in module master table **/
		$ObjDB->NonQuery("UPDATE itc_module_master 
		                 SET fld_module_name='".$modname."',  
						     fld_phase='".$modphase."', fld_minutes='".$modminutes."', 
							 fld_days='".$moddays."', fld_updated_by='".$uid."', 
							 fld_updated_date='".$date."', fld_asset_id='".$assetid."', 
							 fld_module_type='".$moduletype."', fld_module_descr='".$moduledescription."' 
						 WHERE fld_id='".$modid."'");
						 
		
		/***updating module version in module version track table **/
		
                $ObjDB->NonQuery("UPDATE itc_module_version_track 
                                                 SET fld_delstatus='1', fld_deleted_by='".$uid."', 
                                                         fld_deleted_date='".$date."' 
                                                 WHERE fld_mod_id='".$modid."'");
									 
		$cntversion=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
		                                         FROM  itc_module_version_track 
												 WHERE fld_version='".$modversion."' AND fld_file_type='".$type."' 
												       AND fld_file_name='".$name[0]."' AND fld_mod_id='".$modid."'"); // check count
		
				if($cntversion =='0'){
                                    
                                    $ObjDB->NonQuery("INSERT INTO itc_module_version_track
                                                        (fld_mod_id, fld_version, fld_file_type, fld_file_name, fld_created_by, fld_created_date)	
                                                                VALUES('".$modid."', '".$modversion."', '".$type."', '".$name[0]."', '".$uid."', '".$date."')");
					//insert if not exist
					
				}  
				else{
					
		        	$ObjDB->NonQuery("UPDATE itc_module_version_track 
                                                SET fld_version='".$modversion."', fld_file_type='".$type."', 
                                                    fld_updated_by='".$uid."', 
                                                        fld_updated_date='".$date."', fld_delstatus='0' 
                                                WHERE fld_mod_id='".$modid."' AND fld_file_name='".$name[0]."'"); //update already exist
		        }
		
		/****for updating module performance details ***/
		
		if(sizeof($performance)==3){
			
			$ObjDB->NonQuery("UPDATE itc_module_performance_master 
			                 SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' 
							 WHERE fld_module_id='".$modid."'"); /**for use deleting unwanted performance details and below code will explain the flow*****/
			
			for($i=0;$i<3;$i++){
				$performance[$i] = $ObjDB->EscapeStrAll($performance[$i]);
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
				                                    FROM itc_module_performance_master 
				                                    WHERE fld_module_id='".$modid."' AND fld_performance_name='".$performance[$i]."' 
													AND fld_points_possible='".$points[$i]."'"); // check the details exist or not in the table
					
					if($cnt!=0 || $cnt!=''){
						
						$ObjDB->NonQuery("UPDATE itc_module_performance_master 
						                 SET fld_delstatus='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$cnt."'"); // if its exist change the delstatus to 0
					}
					else{
						
						$ObjDB->NonQuery("INSERT INTO itc_module_performance_master(fld_module_id, fld_performance_name, fld_points_possible, fld_created_by, fld_created_date) 
						                 VALUES('".$modid."', '".$performance[$i]."', '".$points[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')"); // if not exist then inserted into the table
					}
			}
		}
		
		
		
		/*---tags------*/
		$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
		                 WHERE fld_tag_type='3' AND fld_item_id='".$modid."' 
						 AND fld_tag_id IN (SELECT fld_id FROM  itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");
		
		
		$quesids = array_filter(explode("@",$quesid));
		$ansids = array_filter(explode("@",$ansid));
		$anstexts = array_filter(explode("@",$anstext));
		$corrects = array_filter(explode("@",$correct));
		if(sizeof($quesids)>0)
		{
			$ObjDB->NonQuery("UPDATE itc_module_quesanswer SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."' 
			                 WHERE fld_module_id='".$modid."' AND fld_module_version='".$modversion."'");
		}
		
		$j=0;
		for($i=0;$i<sizeof($quesids);$i++)
		{
			for($asd=1; $asd<5; $asd++)
			{
				
				$ObjDB->NonQuery("INSERT INTO itc_module_quesanswer (fld_question_id, fld_answer_id, 
				                             fld_answer_text, fld_correct, fld_module_id, fld_module_version, 
											 fld_created_by, fld_created_date) 
								 VALUES('".$quesids[$i]."', '".$ansids[$j]."', '".$anstexts[$j]."', '".$corrects[$j]."', 
								        '".$modid."', '".$modversion."', '".$uid."', '".$date."')");
				$j++;
			}
		}
		
		$newpagetitle = explode("@",$pagetitles);
		$newpagegrade = explode("@",$pagegrades);
                $qcount = explode("@",$qcount);
                
		if(sizeof($newpagetitle)>1)
		{
			$ObjDB->NonQuery("UPDATE itc_module_grade SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
			                 WHERE fld_module_id='".$modid."'");
			
			$j=0;
			for($i=0;$i<sizeof($newpagetitle);$i++)
			{
				$j=$i;
				$j++;
				if($j==6)
					$session=$j;
				else
					$session=$i;
					
				if($newpagetitle[$i]=="RCA")
					$newtitle = $newpagetitle[$i]." ".$j;
				else
					$newtitle = $newpagetitle[$i];
					
				if($session==0)
					$newpoints = "10";
				else if($session==6)
					$newpoints = "100";
				else
					$newpoints = "30";
					
				$ObjDB->NonQuery("INSERT INTO itc_module_grade 
											(fld_module_id, fld_ques_count, fld_session_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date) 
								 VALUES('".$modid."', '".$qcount[$i]."', '".$session."', '".$newtitle."', '".$newpagegrade[$i]."', '".$newpoints."', '1', '".$uid."', '".$date."')");
			}
		}
		
		$totalpages = array_filter(explode("@",$pagecnt));
		$totalparti = array_filter(explode("@",$partipoints));
		$totalatten = array_filter(explode("@",$attenpoints));
		$totalsess = array_filter(explode("@",$sectiontitle));
		$sessionarray=array('Session 1','Session 2','Session 3','Session 4','Session 5','Session 6','Session 7');
		
		for($i=0;$i<sizeof($totalsess);$i++)
		{
			if(array_search($totalsess[$i],$sessionarray))
			{
			  $title=$i;
			}
			/***updating the performance master ****/
			if($totalparti[$i]!='')
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master 
				                             (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date, fld_grade) 
								 VALUES('".$modid."', 'Participation', '".$title."', '".$totalparti[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."', '1')");
			
			if($totalatten[$i]!='')
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master 
				                             (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date, fld_grade) 
								 VALUES('".$modid."', 'Attendance', '".$title."', '".$totalatten[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."', '0')");
			
			if($totalpages[$i]!='')
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master 
				                             (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date, fld_grade) 
				                 VALUES('".$modid."', 'Total Pages', '".$title."', '".$totalpages[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."', '1')");
		}
		
		
		$newmid=$modid;
                $maxid=$newmid;
                
                    //Licences insert/update
                      
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_module_id='".$maxid."'");
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					                              
                                     $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_mod_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_module_id='".$maxid."' AND fld_type='1'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$maxid."','1','1', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_module_id='".$maxid."' AND fld_type='1'");
					}
					
				}
			}
                
		fn_tagupdate($tags,3,$modid,$uid);
		
		echo "success";
	}
	
	else
	{
		 $maxid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_module_master 
		                                                (fld_module_name, fld_phase, fld_minutes, fld_days, 
														 fld_created_by, fld_created_date, fld_asset_id, fld_module_type,fld_module_descr) 
											VALUES ('".$modname."','".$modphase."', '".$modminutes."', 
											       '".$moddays."', '".$uid."', '".$date."', '".$assetid."', '".$moduletype."', '".$moduledescription."')");
		
		
		 $ObjDB->NonQuery("INSERT INTO itc_module_version_track
		                              (fld_mod_id, fld_version, fld_file_type,
		                               fld_file_name, fld_created_by, fld_created_date)	
						  VALUES('".$maxid."', '".$modversion."', '".$type."', '".$name[0]."', '".$uid."', '".$date."')");
		
		for($i=0;$i<3;$i++)
		{
                        $performance[$i] = $ObjDB->EscapeStrAll($performance[$i]);
			$ObjDB->NonQuery("INSERT INTO itc_module_performance_master
			                             (fld_module_id, fld_performance_name, fld_points_possible, fld_created_by, fld_created_date) 
							 VALUES('".$maxid."', '".$performance[$i]."', '".$points[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
		}
		
		$quesids = explode("@",$quesid);
		$ansids = explode("@",$ansid);
		$anstexts = explode("@",$anstext);
		$corrects = explode("@",$correct);
		
		$j=0;
		for($i=0;$i<sizeof($quesids);$i++)
		{
			for($asd=1; $asd<5; $asd++)
			{
				$ObjDB->NonQuery("INSERT INTO itc_module_quesanswer 
				                 (fld_question_id, fld_answer_id, fld_answer_text, fld_correct, fld_module_id, fld_module_version, 
								 fld_created_by, fld_created_date) 
								 VALUES('".$quesids[$i]."', '".$ansids[$j]."', '".$anstexts[$j]."', '".$corrects[$j]."',
								  '".$maxid."', '".$modversion."', '".$uid."', '".$date."')");
				$j++;
			}
		}
		
		$newpagetitle = explode("@",$pagetitles);
		$newpagegrade = explode("@",$pagegrades);
                $qcount = explode("@",$qcount);
		
		$j=0;
		for($i=0;$i<sizeof($newpagetitle);$i++)
		{
			$j=$i;
			$j++;
			if($j==6)
				$session=$j;
			else
				$session=$i;
				
			if($newpagetitle[$i]=="RCA")
				$newtitle = $newpagetitle[$i]." ".$j;
			else
				$newtitle = $newpagetitle[$i];
				
			if($session==0)
				$newpoints = "10";
			else if($session==6)
				$newpoints = "100";
			else
				$newpoints = "30";
				
			$ObjDB->NonQuery("INSERT INTO itc_module_grade 
										(fld_module_id, fld_ques_count, fld_session_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date) 
							 VALUES('".$maxid."', '".$qcount[$i]."', '".$session."', '".$newtitle."', '".$newpagegrade[$i]."', '".$newpoints."', '1', '".$uid."', '".$date."')");
		}
		
		$totalpages = explode("@",$pagecnt);
		$totalparti = explode("@",$partipoints);
		$totalatten = explode("@",$attenpoints);
		$totalsess = explode("@",$sectiontitle);
		
		for($i=0;$i<sizeof($totalsess);$i++)
		{
			if($totalsess[$i]=='Session 1')
				$title = '0';
			else if($totalsess[$i]=='Session 2')
				$title = '1';
			else if($totalsess[$i]=='Session 3')
				$title = '2';
			else if($totalsess[$i]=='Session 4')
				$title = '3';
			else if($totalsess[$i]=='Session 5')
				$title = '4';
			else if($totalsess[$i]=='Session 6')
				$title = '5';
			else if($totalsess[$i]=='Session 7')
				$title = '6';
				
			if($totalparti[$i]!='')
				   $ObjDB->NonQuery("INSERT INTO itc_module_performance_master 
				                                (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date, fld_grade) 
									VALUES('".$maxid."', 'Participation', '".$title."', '".$totalparti[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."', '1')");
			
			if($totalatten[$i]!='')
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master 
				                             (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date, fld_grade) 
								VALUES('".$maxid."', 'Attendance', '".$title."', '".$totalatten[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."', '0')");
			
			if($totalpages[$i]!='')
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master 
				                             (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date, fld_grade) 
								 VALUES('".$maxid."', 'Total Pages', '".$title."', '".$totalpages[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."', '1')");
		}
		$newmid=$maxid;
		
                //Licences insert/update
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_license_id='".$maxid."'");
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					
                                        
                                        
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_mod_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_module_id='".$maxid."' AND fld_type='1'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$maxid."','1','1', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_module_id='".$maxid."' AND fld_type='1'");
					}
					
				}
			}
                
		fn_taginsert($tags,3,$maxid,$uid);
		
		echo "success";
	}
  }
  catch(Exception $e)
  {
	  echo "fail";
  }
}

/*--- Load Version dropdown for Modules ---*/
if($oper=="moduleversion" and $oper != " ")
{
	$modid = isset($method['modid']) ? $method['modid'] : '';
	$currentversion = $ObjDB->SelectSingleValue("SELECT MAX(FORMAT(fld_version,1)) 
	                                            FROM itc_module_version_track WHERE fld_mod_id='".$modid."'");
	$newversion = $currentversion+0.1;
	?>        
	<input type="hidden" name="selectversion" class="required" id="selectversion" value="<?php echo number_format($newversion,1);?>">
	<a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" style="width:110px;">
		<span class="selectbox-option input-medium" data-option="<?php echo number_format($newversion,1);?>" style="width:100px;">Version <?php echo number_format($newversion,1);?></span>
		<b class="caret1"></b>
	</a>
	<div class="selectbox-options" style="min-width: 118px;">			    
		<ul role="options" style="width:118px;">
		<?php $qry = $ObjDB->QueryObject("select fld_version from itc_module_version_track where fld_mod_id='".$modid."'");
			while($res = $qry->fetch_object()){?>
			   <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changemodulename(<?php echo number_format($res->fld_version,1);?>,<?php echo $modid;?>)">Version <?php echo number_format($res->fld_version,1); ?></a></li>
			<?php }?>
		</ul>
	</div>
	<?php
}

/*--- Change the module file name according to version ---*/
if($oper=="changemodulefilename" and $oper != " ")
{
	$moduleid = isset($method['modid']) ? $method['modid'] : '';
	$version = isset($method['version']) ? $method['version'] : '';
	
	$filename = $ObjDB->SelectSingleValue("SELECT concat(fld_file_name,'.',(case when fld_file_type='1' then 'sbook' when fld_file_type='0' then 'zip' end)) FROM itc_module_version_track WHERE fld_version='".$version."' AND fld_mod_id='".$moduleid."'");
	
	echo $filename;
}

/*--- Change the module name according to version ---*/
if($oper=="changemodulename" and $oper != " ")
{
	$modulename = isset($method['modname']) ? fnEscapeCheck($method['modname']) : '';
	$moduleid = isset($method['modid']) ? $method['modid'] : '';
	$version = isset($method['modversion']) ? $method['modversion'] : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_module_version_track AS a 
	                                      LEFT JOIN itc_module_master AS b ON a.fld_mod_id=b.fld_id 
										  WHERE MD5(LCASE(REPLACE(b.fld_module_name,' ','')))='".$modulename."' 
										  AND b.fld_delstatus='0' AND a.fld_version='".$version."' 
										  AND b.fld_id<>'".$moduleid."'");
	
	if($count>0)
		echo "fail";
	else if($count==0)
		echo "success";
}



if($oper=="variabletrack" and $oper != "") {
	
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0'; 
	$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
	$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0'; 
	$sessionid = isset($method['sessionid']) ? $method['sessionid'] : ''; 
	$pageid = isset($method['pageid']) ? $method['pageid'] : ''; 
	$key = isset($method['key']) ? $ObjDB->EscapeStr($method['key']) : ''; 
	$answer = isset($method['answer']) ? $ObjDB->EscapeStr($method['answer']) : ''; 
	$testerid = isset($method['testerid']) ? $method['testerid'] : ''; 
	$testerid1 = isset($method['testerid1']) ? $method['testerid1'] : ''; 
	$edit = isset($method['edit']) ? $method['edit'] : 0; 

	if($edit==0)
	{
		$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_module_variable_track 
												WHERE fld_key='".$key."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
													AND fld_session_id='".$sessionid."' AND fld_page_id='".$pageid."' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
		
		if($count == 0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_module_variable_track (fld_tester_id, fld_module_id, fld_session_id, fld_page_id, fld_key, fld_key_value, fld_created_by, 
								fld_created_date, fld_schedule_id, fld_schedule_type) 
							VALUES ('".$testerid."', '".$moduleid."', '".$sessionid."', '".$pageid."', '".$key."', '".$answer."', '".$uid."', 
								'".$date."', '".$scheduleid."', '".$scheduletype."')");
		}
		else
		{
			$ObjDB->NonQuery("UPDATE itc_module_variable_track 
							SET fld_tester_id='".$testerid."', fld_module_id='".$moduleid."', fld_schedule_id='".$scheduleid."', fld_session_id='".$sessionid."', 
								fld_page_id='".$pageid."', fld_key='".$key."', fld_key_value='".$answer."', fld_schedule_type='".$scheduletype."', 
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."'  
							WHERE fld_id='".$count."' ");
		}
		
		if($uid1 != '' and $uid1 != '0')
		{
			$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_module_variable_track 
													WHERE fld_key='".$key."' AND fld_tester_id='".$testerid1."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_session_id='".$sessionid."' AND fld_page_id='".$pageid."' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
		
			if($count == 0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_module_variable_track (fld_tester_id, fld_module_id, fld_session_id, fld_page_id, fld_key, fld_key_value, fld_created_by, 
									fld_created_date, fld_schedule_id, fld_schedule_type) 
								VALUES ('".$testerid1."', '".$moduleid."', '".$sessionid."', '".$pageid."', '".$key."', '".$answer."', '".$uid1."', 
									'".$date."', '".$scheduleid."', '".$scheduletype."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_module_variable_track 
								SET fld_tester_id='".$testerid1."', fld_module_id='".$moduleid."', fld_schedule_id='".$scheduleid."', fld_session_id='".$sessionid."', 
									fld_page_id='".$pageid."', fld_key='".$key."', fld_key_value='".$answer."', fld_schedule_type='".$scheduletype."', 
									fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid1."' 
								WHERE fld_id='".$count."' ");
			}
		}
	}
	else {
		
		$keyvalue = $ObjDB->SelectSingleValue("SELECT fld_key_value 
												FROM itc_module_variable_track 
												WHERE fld_key='".$key."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
													AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
		
		echo $keyvalue;	
	}
}


if($oper == "savegrade" and $oper != '')
{		
	try{
		$pagetitle = isset($method['pagetitle']) ? urldecode($method['pagetitle']) : '';
		$points = isset($method['points']) ? $method['points'] : '';
		$grades = isset($method['grades']) ? $method['grades'] : '';
		$wcasess = isset($method['wcasess']) ? $method['wcasess'] : '';
		$wcapage = isset($method['wcapage']) ? $method['wcapage'] : '';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';	
		
		if($points!='')
		{
			$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_module_id='".$moduleid."'");
			
			$pagetitle = explode('~',$pagetitle);
			$points = explode('~',$points);
			$grades = explode(',',$grades);
			$wcasess = explode('~',$wcasess);
			$wcapage = explode('~',$wcapage);
			$r=2;
			
			for($i=0;$i<sizeof($pagetitle);$i++)
			{
				$type = 0;
				$page = addslashes($pagetitle[$i]);
				if($page=='Attendance')
				{
					$type=1;
					$newtitle = $page;
				}
				else if($page=='Participation')
				{
					$type=2;
					$newtitle = $page;
				}
				else if($page<>'Module Guide' and substr($page, 0, 3)<>'RCA' and $page<>'Post Test' and $page<>'Posttest' and $page<>'Pretest' and substr($page,-8)<>'Pop Quiz' and substr($page,-8)<>'Posttest')
				{
					$type=3;
					$newtitle = $page;
				}
				
				$newtitle = $page;
				if(substr($page, 0, 3)=='RCA')
				{
					$newtitle = "RCA ".$r;
					$r++;
				}

				$wcagradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_wca_grade WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='1' AND fld_module_id='".$modules."' AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_user_id='".$indid."' AND fld_school_id='".$schoolid."' AND fld_type='".$type."' AND fld_preassment_id='".$wcapage[$i]."'");
				
                                
				$wcagradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_wca_grade WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='1' AND fld_module_id='".$moduleid."' AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_school_id='".$schoolid."' AND fld_type='".$type."' AND fld_preassment_id='".$wcapage[$i]."'");
				
				if($wcagradeid!='')
				{
					$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='1', fld_grade='".$grades[$i]."', fld_points='".$points[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_preassment_id='".$wcapage[$i]."' WHERE fld_id='".$wcagradeid."'");
				}
				else
				{
                                
					$ObjDB->NonQuery("INSERT INTO itc_module_wca_grade (fld_type, fld_schedule_type, fld_schedule_id, fld_class_id, fld_module_id, fld_session_id, fld_preassment_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date, fld_school_id) VALUES('".$type."', '1', '0', '0', '".$moduleid."', '".$wcasess[$i]."', '".$wcapage[$i]."', '".$newtitle."', '".$grades[$i]."', '".$points[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."')");
				}
				
				$createids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_user_master WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_profile_id NOT IN (1,4,6,10,11) AND fld_delstatus='0' AND fld_activestatus='1'");
			
				$qrysch = $ObjDB->QueryObject("SELECT a.fld_id AS schid, '1' AS schtype
												FROM itc_class_rotation_schedule_mastertemp AS a 
												LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
												WHERE b.fld_module_id='".$moduleid."' AND a.fld_createdby IN (".$createids.") AND a.fld_moduletype='1'
												GROUP BY a.fld_id
														UNION ALL
												SELECT a.fld_id AS schid, '2' AS schtype
												FROM itc_class_dyad_schedulemaster AS a 
												LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
												WHERE b.fld_module_id='".$moduleid."' AND a.fld_createdby IN (".$createids.")
												GROUP BY a.fld_id
														UNION ALL
												SELECT a.fld_id AS schid, '3' AS schtype
												FROM itc_class_triad_schedulemaster AS a 
												LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
												WHERE b.fld_module_id='".$moduleid."' AND a.fld_createdby IN (".$createids.")
												GROUP BY a.fld_id");
												
				if($qrysch->num_rows>0)
				{
					while($rowqrysch=$qrysch->fetch_assoc())
					{
						extract($rowqrysch);
						
						if($schtype > 4)
						{
							$grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_module_wca_grade 
																	WHERE fld_schedule_id='".$schid."' AND fld_module_id='".$moduleid."' 
																		AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_type='".$type."' 
																		AND fld_preassment_id='".$wcapage[$i]."'");
							if($grade == '')
								$newgrade = $grades[$i];
						}
						else
							$newgrade = $grades[$i];
						
						if($type==0)
                                                {
                                                    $sqry = "AND fld_session_id='".$wcasess[$i]."'";
                                                }
                                                else if($type==3)
                                                {
                                                    if($schtype==4)
                                                            $newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$moduleid."'");
                                                    else
                                                            $newmodid = $moduleid;
                                                    
                                                    $perid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_performance_master WHERE fld_performance_name='".addslashes($newtitle)."' AND fld_delstatus='0' AND fld_module_id='".$newmodid."'");
                                                    
                                                    $sqry = "AND fld_preassment_id='".$perid."'";
                                                }
                                                else
                                                {
                                                    $sqry = '';
                                                }
						$qrycount = $ObjDB->QueryObject("SELECT fld_id AS fieldid, fld_teacher_points_earned AS teachpoint, fld_points_earned AS earnedpoints, fld_points_possible AS posible
																FROM itc_module_points_master 
																WHERE fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND fld_module_id='".$moduleid."' 
																	AND fld_type='".$type."' ".$sqry."");	
																	
						if($qrycount->num_rows>0)
						{
							while($rowcount=$qrycount->fetch_assoc())
							{
								extract($rowcount);
								
								if($posible!=$points[$i])
                                                                {
                                                                        $newpoint = round($posible/$points[$i],2);
                                                                        if($earnedpoints!='')
                                                                                $newearned = round($earnedpoints/$newpoint);
                                                                        if($teachpoint!='')
                                                                                $newteacher = round($teachpoint/$newpoint);
                                                                        $newpossible = $points[$i];
                                                                }
                                                                else
                                                                {
                                                                        $newpossible = $posible;
                                                                        $newearned = $earnedpoints;
                                                                        $newteacher = $teachpoint;
                                                                }
                                                                $ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_grade='".$newgrade."', fld_points_possible='".$newpossible."', fld_points_earned='".$newearned."', fld_teacher_points_earned='".$newteacher."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$fieldid."'");                                                               
							}
						}
					}
				}
				
			}
		}	
		
		echo "success";		
	
	}
	catch(Exception $e){
		echo "invalid";
	}
}

	@include("footer.php");