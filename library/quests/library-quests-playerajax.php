<?php
@include("../../sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';

/*--- Check Quest Name ---*/
if($oper=="checkquestname" and $oper != " " )
{
	$questid = isset($_REQUEST['mid']) ? $_REQUEST['mid'] : '0';
	$questname = isset($_REQUEST['txtquestname']) ? fnEscapeCheck($_REQUEST['txtquestname']) : '';
	$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_module_master WHERE MD5(LCASE(REPLACE(fld_module_name,' ','')))='".$questname."' AND fld_delstatus='0' AND fld_id<>'".$questid."' AND fld_module_type='7'");
	if($count == 0){ echo "true"; }	else { echo "false"; }
}

/*--- Check Asset ID ---*/
if($oper=="checkassetid" and $oper != " " )
{
	$questid = isset($_REQUEST['mid']) ? $_REQUEST['mid'] : '0';
	$assetid = isset($_REQUEST['txtassetid']) ? fnEscapeCheck($_REQUEST['txtassetid']) : '0';	
	$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_module_master WHERE MD5(LCASE(REPLACE(fld_asset_id,' ','')))='".$assetid."' AND fld_delstatus='0' AND fld_id<>'".$questid."' AND fld_module_type='7'");
	if($count == 0){ echo "true"; }	else { echo "false"; }
}

/*--- Delete the Quest ---*/
if($oper=="deletequest" and $oper != " " )
{
	$questid = isset($_REQUEST['questid']) ? $_REQUEST['questid'] : ''; 
	
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_mod_mapping WHERE fld_module_id='".$questid."' AND fld_active='1' AND fld_type='7'");
		
	if($count==0)
	{
		$ObjDB->NonQuery("UPDATE itc_module_master SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_id='".$questid."' AND fld_module_type='7'");	
		
		$ObjDB->NonQuery("UPDATE itc_module_version_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_mod_id='".$questid."'");	
		
		$ObjDB->NonQuery("UPDATE itc_module_play_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_module_id='".$questid."'");
		echo "success";
	}
	else
	{
		echo "exists";
	}
}

/*--- Save and Update the Quest ---*/
if($oper=="savequest" and $oper != " " )
{
        $qcount = isset($_REQUEST['qcount']) ? $_REQUEST['qcount'] : '0'; 
	$questid = isset($_REQUEST['editid']) ? $_REQUEST['editid'] : '0'; 
	$questname = isset($_REQUEST['questname']) ? $ObjDB->EscapeStrAll($_REQUEST['questname']) : ''; 
	$questtype = isset($_REQUEST['questtype']) ? $_REQUEST['questtype'] : '0'; 
	$assetid = isset($_REQUEST['assetid']) ? $ObjDB->EscapeStrAll($_REQUEST['assetid']) : ''; 
	$questphase = isset($_REQUEST['questphase']) ? $_REQUEST['questphase'] : ''; 
	$questminutes = isset($_REQUEST['questminutes']) ? $_REQUEST['questminutes'] : ''; 
	$questdays = isset($_REQUEST['questdays']) ? $_REQUEST['questdays'] : ''; 
	$questversion = isset($_REQUEST['questversion']) ? $_REQUEST['questversion'] : ''; 
	$performance = isset($_REQUEST['performance']) ? $ObjDB->EscapeStr($_REQUEST['performance']) : ''; 
	$points = isset($_REQUEST['points']) ? $_REQUEST['points'] : '';
	$filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : ''; 
	$tags = isset($_REQUEST['tags']) ? $_REQUEST['tags'] : '';
	$quesid = isset($_REQUEST['quesid']) ? $_REQUEST['quesid'] : '';
	$ansid = isset($_REQUEST['ansid']) ? $_REQUEST['ansid'] : ''; 
	$correct = isset($_REQUEST['correct']) ? $_REQUEST['correct'] : ''; 
	$anstext = isset($_REQUEST['anstext']) ? $ObjDB->EscapeStr($_REQUEST['anstext']) : '';
	$sectiontitle = isset($_REQUEST['sectiontitle']) ? $_REQUEST['sectiontitle'] : '';
	$pagecnt = isset($_REQUEST['pagecnt']) ? $_REQUEST['pagecnt'] : '';
	$perchapter = isset($_REQUEST['perchapter']) ? $_REQUEST['perchapter'] : '';
	$grades = isset($_REQUEST['hidgrade']) ? $_REQUEST['hidgrade'] : '';
	$pageids = isset($_REQUEST['parenttitle']) ? $_REQUEST['parenttitle'] : ''; 
	$pagetitle = isset($_REQUEST['hidtitle']) ? $_REQUEST['hidtitle'] : '';
	$quiztitle = isset($_REQUEST['quiztitle']) ? $_REQUEST['quiztitle'] : '';
		
        $questdescription = isset($_POST['questdescription']) ? $ObjDB->EscapeStr($_POST['questdescription']) : '';
        $list10 = isset($method['list10']) ? $method['list10'] : '';
        $list10=explode(",",$list10);
           
	$pos = strrpos($filename, ".");
	$name = str_split($filename,$pos);
	if($name[1]==".sbook")
		$type=1;
	else if($name[1]==".zip")
		$type=0;
	
	$performance = array_filter(explode("@",$performance));
	$points = explode("@",$points);
	$perchapter = explode("@",$perchapter);
	
	if($questid!='0')
	{
		$ObjDB->NonQuery("UPDATE itc_module_master SET fld_module_name='".$questname."',fld_phase='".$questphase."', fld_minutes='".$questminutes."', fld_days='".$questdays."', fld_updated_by='".$uid."', fld_updated_date='".$date."',fld_module_descr='".$questdescription."' ,fld_asset_id='".$assetid."', fld_module_type='7' WHERE fld_id='".$questid."' AND fld_module_type='7'");
		
		$ObjDB->NonQuery("UPDATE itc_module_version_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_mod_id='".$questid."'");
		
		
                $cntversion=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM  itc_module_version_track 
												 WHERE fld_version='".$questversion."' AND fld_file_type='".$type."' 
												       AND fld_file_name='".$name[0]."' AND fld_mod_id='".$questid."'"); // check count
                
                
		if($cntversion!='0')
		{
			$ObjDB->NonQuery("UPDATE itc_module_version_track SET fld_version='".$questversion."', fld_file_type='".$type."', fld_file_name='".$name[0]."',
                                             fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_delstatus='0' WHERE fld_mod_id='".$questid."'");
                        
                       
		}
		
		else
		{
			$ObjDB->NonQuery("INSERT INTO itc_module_version_track(fld_mod_id, fld_version, fld_file_type, fld_file_name, fld_created_by, fld_created_date)	VALUES('".$questid."', '".$questversion."', '".$type."', '".$name[0]."', '".$uid."', '".$date."')");			
		}
		
		/*---tags------*/
		$ObjDB->NonQuery("update itc_main_tag_mapping set fld_access='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  where fld_tag_type='25' and fld_item_id='".$questid."' and fld_tag_id IN(select fld_id from itc_main_tag_master where fld_created_by='".$uid."' and fld_delstatus='0' )");
		if(sizeof($performance) !=0 and $performance!='' )
		{ 
		//need to change
		$ObjDB->NonQuery("UPDATE itc_module_performance_master SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_module_id='".$questid."'");
		for($i=0;$i<sizeof($performance);$i++)
		{
			if($perchapter[$i]=='Chapter 1')
				$newsessionid = '0';
			else if($perchapter[$i]=='Chapter 2')
				$newsessionid = '1';
			else if($perchapter[$i]=='Chapter 3')
				$newsessionid = '2';
			else if($perchapter[$i]=='Chapter 4')
				$newsessionid = '3';
			else if($perchapter[$i]=='Chapter 5')
				$newsessionid = '4';
			else if($perchapter[$i]=='Chapter 6')
				$newsessionid = '5';
			else if($perchapter[$i]=='Chapter 7')
				$newsessionid = '6';
			else if($perchapter[$i]=='Chapter 8')
				$newsessionid = '7';
			else if($perchapter[$i]=='Chapter 9')
				$newsessionid = '8';
			else if($perchapter[$i]=='Chapter 10')
				$newsessionid = '9';
			else if($perchapter[$i]=='Chapter 11')
				$newsessionid = '10';
			else if($perchapter[$i]=='Chapter 12')
				$newsessionid = '11';
				
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_performance_master WHERE fld_module_id='".$questid."' AND fld_performance_name='".$performance[$i]."' AND fld_points_possible='".$points[$i]."' AND fld_session_id='".$newsessionid."'");
                        if($cnt!=0 || $cnt!='')
                        {
				$ObjDB->NonQuery("UPDATE itc_module_performance_master SET fld_delstatus='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$cnt."'");
                        }
                        else
                        {
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master(fld_module_id, fld_performance_name, fld_points_possible, fld_session_id, fld_created_by, fld_created_date) VALUES('".$questid."', '".$performance[$i]."', '".$points[$i]."', '".$newsessionid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
                        }
		}
		
		$quesids = explode("@",$quesid);
		$ansids = explode("@",$ansid);
		$anstexts = explode("@",$anstext);
		$corrects = explode("@",$correct);
		
		if(sizeof($quesids)>1)
		{
			$ObjDB->NonQuery("UPDATE itc_module_quesanswer SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_module_id='".$questid."' AND fld_module_version='".$questversion."'");
			
			$j=0;
			for($i=0;$i<sizeof($quesids);$i++)
			{
				for($asd=1; $asd<5; $asd++)
				{
					$ObjDB->NonQuery("INSERT INTO itc_module_quesanswer (fld_question_id, fld_answer_id, fld_answer_text, fld_correct, fld_module_id, fld_module_version, fld_created_by, fld_created_date) VALUES('".$quesids[$i]."', '".$ansids[$j]."', '".$anstexts[$j]."', '".$corrects[$j]."', '".$questid."', '".$questversion."', '".$uid."', '".$date."')");
					$j++;
				}
			}
		}
		
		$totalpages = explode("@",$pagecnt);
		$totalsess = explode("@",$sectiontitle);
		
		if(sizeof($totalsess)>1)
		{
			$ObjDB->NonQuery("UPDATE itc_module_performance_master SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_module_id='".$questid."'");
			
			for($i=0;$i<sizeof($totalsess);$i++)
			{
				if($totalsess[$i]=='Chapter 1')
					$title = '0';
				else if($totalsess[$i]=='Chapter 2')
					$title = '1';
				else if($totalsess[$i]=='Chapter 3')
					$title = '2';
				else if($totalsess[$i]=='Chapter 4')
					$title = '3';
				else if($totalsess[$i]=='Chapter 5')
					$title = '4';
				else if($totalsess[$i]=='Chapter 6')
					$title = '5';
				else if($totalsess[$i]=='Chapter 7')
					$title = '6';
				else if($totalsess[$i]=='Chapter 8')
					$title = '7';
				else if($totalsess[$i]=='Chapter 9')
					$title = '8';
				else if($totalsess[$i]=='Chapter 10')
					$title = '9';
				else if($totalsess[$i]=='Chapter 11')
					$title = '10';
				else if($totalsess[$i]=='Chapter 12')
					$title = '11';
					
				if($totalpages[$i]!='')
					$ObjDB->NonQuery("INSERT INTO itc_module_performance_master (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date) VALUES('".$questid."', 'Total Pages', '".$title."', '".$totalpages[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
			}
		}
		
		
		$sesstitle = explode("@",$quiztitle);
		$newtitle = explode("@",$pagetitle);
		$pageid = explode("@",$pageids);
		$grades = explode("@",$grades);
		$qcount = explode("@",$qcount);
                
		if(sizeof($grades)>0)
		{
			$ObjDB->NonQuery("UPDATE itc_module_quest_details SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_module_id='".$questid."'");
			
			for($i=0;$i<sizeof($grades);$i++)
			{
				if($newtitle[$i]=="Posttest")
					$quizpoint = "200";
				else
					$quizpoint = "20";
				
				if($pageid[$i]==1001)
					$pageid[$i] = 0;	
				
				if($sesstitle[$i]=='Chapter 1')
					$session = '0';
				else if($sesstitle[$i]=='Chapter 2')
					$session = '1';
				else if($sesstitle[$i]=='Chapter 3')
					$session = '2';
				else if($sesstitle[$i]=='Chapter 4')
					$session = '3';
				else if($sesstitle[$i]=='Chapter 5')
					$session = '4';
				else if($sesstitle[$i]=='Chapter 6')
					$session = '5';
				else if($sesstitle[$i]=='Chapter 7')
					$session = '6';
				else if($sesstitle[$i]=='Chapter 8')
					$session = '7';
				else if($sesstitle[$i]=='Chapter 9')
					$session = '8';
				else if($sesstitle[$i]=='Chapter 10')
					$session = '9';
				else if($sesstitle[$i]=='Chapter 11')
					$session = '10';
				else if($sesstitle[$i]=='Chapter 12')
					$session = '11';
							
				$ObjDB->NonQuery("INSERT INTO itc_module_quest_details (fld_module_id, fld_ques_count, fld_section_id, fld_section_title, fld_page_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date) VALUES('".$questid."', '".$qcount[$i]."', '".$session."', '".$sesstitle[$i]."', '".$pageid[$i]."', '".$newtitle[$i]."', '".$grades[$i]."', '".$quizpoint."', '1', '".$uid."', '".$date."')");
			}
		}
		
		}
		
                 //quest insert/update
                      
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_module_id='".$questid."'");
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					
                                        
                                        
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_mod_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_module_id='".$questid."' AND fld_type='7'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$questid."','1','7', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_module_id='".$questid."' AND fld_type='7'");
					}
					
				}
			}
                
                
		fn_tagupdate($tags,26,$questid,$uid);
		echo "success";
	}
	
	else
	{
		$ObjDB->NonQuery("INSERT INTO itc_module_master (fld_module_name,fld_phase, fld_minutes, fld_days, fld_created_by, fld_created_date, fld_asset_id, fld_module_type,fld_module_descr) VALUES ('".$questname."','".$questphase."', '".$questminutes."', '".$questdays."', '".$uid."', '".$date."', '".$assetid."', '7','".$questdescription."')");
		
		$questid=$ObjDB->SelectSingleValueInt("select max(fld_id) from itc_module_master");
		
		$ObjDB->NonQuery("INSERT INTO itc_module_version_track(fld_mod_id, fld_version, fld_file_type, fld_file_name, fld_created_by, fld_created_date)	VALUES('".$questid."', '".$questversion."', '".$type."', '".$name[0]."', '".$uid."', '".$date."')");
		
		
		//need to change
		for($i=0;$i<sizeof($performance);$i++)
		{
			if($perchapter[$i]=='Chapter 1')
				$newsessionid = '0';
			else if($perchapter[$i]=='Chapter 2')
				$newsessionid = '1';
			else if($perchapter[$i]=='Chapter 3')
				$newsessionid = '2';
			else if($perchapter[$i]=='Chapter 4')
				$newsessionid = '3';
			else if($perchapter[$i]=='Chapter 5')
				$newsessionid = '4';
			else if($perchapter[$i]=='Chapter 6')
				$newsessionid = '5';
			else if($perchapter[$i]=='Chapter 7')
				$newsessionid = '6';
			else if($perchapter[$i]=='Chapter 8')
				$newsessionid = '7';
			else if($perchapter[$i]=='Chapter 9')
				$newsessionid = '8';
			else if($perchapter[$i]=='Chapter 10')
				$newsessionid = '9';
			else if($perchapter[$i]=='Chapter 11')
				$newsessionid = '10';
			else if($perchapter[$i]=='Chapter 12')
				$newsessionid = '11';
				
			$ObjDB->NonQuery("INSERT INTO itc_module_performance_master(fld_module_id, fld_performance_name, fld_points_possible, fld_session_id, fld_created_by, fld_created_date) VALUES('".$questid."', '".$performance[$i]."', '".$points[$i]."', '".$newsessionid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
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
				$ObjDB->NonQuery("INSERT INTO itc_module_quesanswer (fld_question_id, fld_answer_id, fld_answer_text, fld_correct, fld_module_id, fld_module_version, fld_created_by, fld_created_date) VALUES('".$quesids[$i]."', '".$ansids[$j]."', '".$anstexts[$j]."', '".$corrects[$j]."', '".$questid."', '".$questversion."', '".$uid."', '".$date."')");
				$j++;
			}
		}
		
		$totalpages = explode("@",$pagecnt);
		$totalsess = explode("@",$sectiontitle);
		
		for($i=0;$i<sizeof($totalsess);$i++)
		{
			if($totalsess[$i]=='Chapter 1')
				$title = '0';
			else if($totalsess[$i]=='Chapter 2')
				$title = '1';
			else if($totalsess[$i]=='Chapter 3')
				$title = '2';
			else if($totalsess[$i]=='Chapter 4')
				$title = '3';
			else if($totalsess[$i]=='Chapter 5')
				$title = '4';
			else if($totalsess[$i]=='Chapter 6')
				$title = '5';
			else if($totalsess[$i]=='Chapter 7')
				$title = '6';
			else if($totalsess[$i]=='Chapter 8')
				$title = '7';
			else if($totalsess[$i]=='Chapter 9')
				$title = '8';
			else if($totalsess[$i]=='Chapter 10')
				$title = '9';
			else if($totalsess[$i]=='Chapter 11')
				$title = '10';
			else if($totalsess[$i]=='Chapter 12')
				$title = '11';
				
			if($totalpages[$i]!='')
				$ObjDB->NonQuery("INSERT INTO itc_module_performance_master (fld_module_id, fld_performance_name, fld_session_id, fld_points_possible, fld_created_by, fld_created_date) VALUES('".$questid."', 'Total Pages', '".$title."', '".$totalpages[$i]."', '".$uid."', '".date("Y-m-d H:i:s")."')");
		}
		
		$sesstitle = explode("@",$quiztitle);
		$newtitle = explode("@",$pagetitle);
		$pageid = explode("@",$pageids);
		$grades = explode("@",$grades);
		$qcount = explode("@",$qcount);
                
		for($i=0;$i<sizeof($grades);$i++)
		{
			if($newtitle[$i]=="Posttest")
				$quizpoint = "200";
			else
				$quizpoint = "20";
			
			if($newtitle[$i]==1001)
				$newtitle[$i] = 0;	
			
			if($sesstitle[$i]=='Chapter 1')
				$session = '0';
			else if($sesstitle[$i]=='Chapter 2')
				$session = '1';
			else if($sesstitle[$i]=='Chapter 3')
				$session = '2';
			else if($sesstitle[$i]=='Chapter 4')
				$session = '3';
			else if($sesstitle[$i]=='Chapter 5')
				$session = '4';
			else if($sesstitle[$i]=='Chapter 6')
				$session = '5';
			else if($sesstitle[$i]=='Chapter 7')
				$session = '6';
			else if($sesstitle[$i]=='Chapter 8')
				$session = '7';
			else if($sesstitle[$i]=='Chapter 9')
				$session = '8';
			else if($sesstitle[$i]=='Chapter 10')
				$session = '9';
			else if($sesstitle[$i]=='Chapter 11')
				$session = '10';
			else if($sesstitle[$i]=='Chapter 12')
				$session = '11';
					
			$ObjDB->NonQuery("INSERT INTO itc_module_quest_details (fld_module_id, fld_ques_count, fld_section_id, fld_section_title, fld_page_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date) VALUES('".$questid."', '".$qcount[$i]."', '".$session."', '".$sesstitle[$i]."', '".$pageid[$i]."', '".$newtitle[$i]."', '".$grades[$i]."', '".$quizpoint."', '1', '".$uid."', '".$date."')");
		}
                
                //Licences insert/update
                      
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_module_id='".$questid."'");
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					                                       
                                        
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_mod_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_module_id='".$questid."' AND fld_type='7'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$questid."','1','7', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_module_id='".$questid."' AND fld_type='7'");
					}
					
				}
			}
                
                
		fn_taginsert($tags,26,$maxid,$uid);
		
		echo "success";
	}
}

/*--- Load Version dropdown for Quests ---*/
if($oper=="questversion" and $oper != " ")
{
	$questid = isset($_REQUEST['questid']) ? $_REQUEST['questid'] : '';
	$currentversion = $ObjDB->SelectSingleValue("SELECT MAX(FORMAT(fld_version,1)) FROM itc_module_version_track WHERE fld_mod_id='".$questid."'");
	$newversion = $currentversion+0.1;
	?>        
	<input type="hidden" name="selectversion" class="required" id="selectversion" value="<?php echo number_format($newversion,1);?>">
	<a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" style="width:110px;">
		<span class="selectbox-option input-medium" data-option="<?php echo number_format($newversion,1);?>" style="width:100px;">Version <?php echo number_format($newversion,1);?></span>
		<b class="caret1"></b>
	</a>
	<div class="selectbox-options" style="min-width: 118px;">			    
		<ul role="options" style="width:118px;">
		<?php $qry = $ObjDB->QueryObject("select fld_version from itc_module_version_track where fld_mod_id='".$questid."'");
			while($res = $qry->fetch_object()){?>
			   <li><a  href="#" data-option="<?php echo $res->fld_version; ?>" onclick="fn_changequestname(<?php echo number_format($res->fld_version,1);?>)">Version <?php echo number_format($res->fld_version,1); ?></a></li>
			<?php }?>
		</ul>
	</div>
	<?php
}

/*--- Change the quest file name according to version ---*/
if($oper=="changequestfilename" and $oper != " ")
{
	$questid = isset($method['questid']) ? $method['questid'] : '';
	$version = isset($method['version']) ? $method['version'] : '';
	
	$filename = $ObjDB->SelectSingleValue("SELECT concat(fld_file_name,'.',(case when fld_file_type='1' then 'sbook' when fld_file_type='0' then 'zip' end)) FROM itc_module_version_track WHERE fld_version='".$version."' AND fld_id='".$questid."'");
	
	echo $filename;
}

/*--- Change the quest name according to version ---*/
if($oper=="changequestname" and $oper != " ")
{
	$questname = isset($_REQUEST['questname']) ? fnEscapeCheck($_REQUEST['questname']) : '';
	$questid = isset($_REQUEST['questid']) ? $_REQUEST['questid'] : '';
	$version = isset($_REQUEST['questversion']) ? $_REQUEST['questversion'] : '';
	$count = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_module_version_track AS a LEFT JOIN itc_module_master AS b ON a.fld_mod_id=b.fld_id WHERE MD5(LCASE(REPLACE(b.fld_module_name,' ','')))='".$questname."' AND b.fld_delstatus='0' AND a.fld_version='".$version."' AND b.fld_id<>'".$questid."' AND b.fld_module_type='7'");
	
	if($count>0)
		echo "fail";
	else if($count==0)
		echo "success";
}

/*--- Load Sessions in Dropdown ---*/
if($oper=="showsess" and $oper != " " )
{
	$sectionid = isset($_REQUEST['sectionid']) ? $_REQUEST['sectionid'] : '';
	$sections = isset($_REQUEST['sections']) ? $_REQUEST['sections'] : '';
	$sections = explode(",",$sections);
	if($sessprofileid==10)
		$count = sizeof($sections);
	else
		$count = sizeof($sections)-1;
	?>
	<select id="xmlFileSelect" name="xmlFileSelect" onchange="fn_showbutton(0),fn_secdropdown(this.value,0,1);" style="width:99%;">
		<?php
		for($i=0;$i<sizeof($sections);$i++)
		{
			?>
			<option value="<?php echo $i;?>" <?php if($sectionid==$i) { ?> selected="selected" <?php }?>><?php echo $sections[$i];?></option>
			<?php
		}
		?>
	</select>
	<?php
}

/*--- Load Page Names for a Session as List ---*/
if($oper=="showpages" and $oper != " " )
{
	$pages = isset($_REQUEST['pages']) ? $_REQUEST['pages'] : '';
	$sectionid = isset($_REQUEST['sectionid']) ? $_REQUEST['sectionid'] : '';
	$pageid = isset($_REQUEST['pageid']) ? $_REQUEST['pageid'] : '';
	$questid = isset($_REQUEST['questid']) ? $_REQUEST['questid'] : '';
	$scheduleid = isset($_REQUEST['scheduleid']) ? $_REQUEST['scheduleid'] : '0';
	$pages = explode(",",$pages);
	
	for($j=0;$j<sizeof($pages);$j++)
	{
		if($j==$pageid)
		{
			$count=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_page_id='".$pageid."' AND fld_section_id='".$sectionid."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$uid."' AND fld_schedule_id='".$scheduleid."'");
			if($count==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_module_play_track (fld_tester_id, fld_section_id, fld_module_id, fld_page_id, fld_page_name, fld_read_status, fld_created_by, fld_created_date, fld_schedule_id) VALUES ('".$uid."', '".$sectionid."', '".$moduleid."', '".$pageid."', '".$pages[$pageid]."', '1', '".$uid."', '".$date."', '".$scheduleid."')");
			}
		}
		$status=$ObjDB->SelectSingleValueInt("SELECT fld_read_status FROM itc_module_play_track WHERE fld_page_id='".$j."' AND fld_section_id='".$sectionid."' AND fld_schedule_id='".$scheduleid."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$uid."'"); 
			
		?>
		<div id="<?php echo $j;?>" class="<?php if($pageid==$j) { echo "select"; } else if($status==1) { echo "nextselect"; }?>"  onclick="fn_showbutton(0),fn_showpagenames(<?php echo $sectionid;?>,<?php echo $j;?>)" style="cursor:pointer"> <?php echo $pages[$j];?>
        </div>
        <?php
	}
}

/*--- Tracking the Answers ---*/
if($oper=="answertrack" and $oper != " " )
{
	$scheduleid = isset($_REQUEST['scheduleid']) ? $_REQUEST['scheduleid'] : '0';
	$questid = isset($_REQUEST['questid']) ? $_REQUEST['questid'] : '0'; 
	$sessionid = isset($_REQUEST['sessionid']) ? $_REQUEST['sessionid'] : ''; 
	$pageid = isset($_REQUEST['pageid']) ? $_REQUEST['pageid'] : ''; 
	$answerid = isset($_REQUEST['answerid']) ? $_REQUEST['answerid'] : ''; 
	$ansoption = isset($_REQUEST['ansoption']) ? $_REQUEST['ansoption'] : ''; 
	$ansoption1 = isset($_REQUEST['ansoption1']) ? $_REQUEST['ansoption1'] : ''; 
	$anstext = isset($_REQUEST['anstext']) ? $_REQUEST['anstext'] : ''; 
	$anstext1 = isset($_REQUEST['anstext1']) ? $_REQUEST['anstext1'] : ''; 
	$assid = isset($_REQUEST['assid']) ? $_REQUEST['assid'] : ''; 
	$attempts = isset($_REQUEST['attempts']) ? $_REQUEST['attempts'] : ''; 
	$correct = isset($_REQUEST['correct']) ? $_REQUEST['correct'] : ''; 
	$earned = isset($_REQUEST['earned']) ? $_REQUEST['earned'] : ''; 
	$possible = isset($_REQUEST['possible']) ? $_REQUEST['possible'] : ''; 
	$questionid = isset($_REQUEST['questionid']) ? $_REQUEST['questionid'] : ''; 
	$questiontext = isset($_REQUEST['questiontext']) ? $_REQUEST['questiontext'] : ''; 
	$testerid = isset($_REQUEST['testerid']) ? $_REQUEST['testerid'] : ''; 
	$grade = isset($_REQUEST['grade']) ? $_REQUEST['grade'] : ''; 
	
	if($correct=="true")
		$anscorrect=1;
	else
		$anscorrect=0;
	
	$ObjDB->NonQuery("UPDATE itc_module_answer_track SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".date("Y-m-d H:i:s")."'  WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sessionid."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' AND fld_question_id='".$questionid."'");
		
	$ObjDB->NonQuery("INSERT INTO itc_module_answer_track (fld_tester_id, fld_module_id, fld_schedule_id, fld_session_id, fld_page_id, fld_assessment_id, fld_question_id, fld_question_text, fld_answer_id, fld_answer_option, fld_answer_option1, fld_answer_text, fld_answer_text1, fld_correct, fld_attempts, fld_points_possible, fld_points_earned, fld_created_by, fld_created_date, fld_grade) VALUES ('".$testerid."', '".$moduleid."', '".$scheduleid."', '".$sessionid."', '".$pageid."', '".$assid."', '".$questionid."', '".$questiontext."', '".$answerid."', '".$ansoption."', '".$ansoption1."', '".$anstext."', '".$anstext1."', '".$anscorrect."', '".$attempts."', '".$possible."', '".$earned."', '".$uid."', '".$date."', '".$grade."')");
}

/*--- Load Score and Eligibility ---*/
if($oper=="showscore" and $oper != " " )
{
	$sectionid = isset($_REQUEST['sectionid']) ? $_REQUEST['sectionid'] : '';
	$pageid = isset($_REQUEST['pageid']) ? $_REQUEST['pageid'] : '';
	$scheduleid = isset($_REQUEST['scheduleid']) ? $_REQUEST['scheduleid'] : '0';
	$questid = isset($_REQUEST['questid']) ? $_REQUEST['questid'] : '';
	$testerid = isset($_REQUEST['testerid']) ? $_REQUEST['testerid'] : '';
	$testerid1 = isset($_REQUEST['testerid1']) ? $_REQUEST['testerid1'] : '';
	
	$score = $ObjDB->SelectSingleValue("SELECT SUM(fld_points_earned) FROM itc_module_answer_track WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sectionid."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0'");
		
	$score1 = $ObjDB->SelectSingleValue("SELECT SUM(fld_points_earned) FROM itc_module_answer_track WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sectionid."' AND fld_tester_id='".$testerid1."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0'");
	
	$eligible = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_module_answer_track WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sectionid."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0'");
	
	$eligible1 = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id) FROM itc_module_answer_track WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sectionid."' AND fld_tester_id='".$testerid1."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' AND fld_delstatus='0'");
	
	echo $score."~".$score1."~".$eligible."~".$eligible1;
}

/*--- Tracking the Variables ---*/
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
			$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='7' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_module_id='".$moduleid."'");
			
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
				
				$wcagradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_wca_grade WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='7' AND fld_module_id='".$moduleid."' AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_school_id='".$schoolid."' AND fld_type='".$type."' AND fld_preassment_id='".$wcapage[$i]."'");
				
				if($wcagradeid!='')
				{
					$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='1', fld_grade='".$grades[$i]."', fld_points='".$points[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_preassment_id='".$wcapage[$i]."' WHERE fld_id='".$wcagradeid."'");
				}
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_module_wca_grade (fld_type, fld_schedule_type, fld_schedule_id, fld_class_id, fld_module_id, fld_session_id, fld_preassment_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date, fld_school_id) VALUES('".$type."', '7', '0', '0', '".$moduleid."', '".$wcasess[$i]."', '".$wcapage[$i]."', '".$newtitle."', '".$grades[$i]."', '".$points[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."')");
				}
				
				$createids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_user_master WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_profile_id NOT IN (1,4,6,10,11) AND fld_delstatus='0' AND fld_activestatus='1'");
			
				$qrysch = $ObjDB->QueryObject("SELECT fld_id AS schid, '7' AS schtype
												FROM itc_class_indassesment_master 
												WHERE fld_module_id='".$moduleid."' AND fld_createdby IN (".$createids.") AND fld_moduletype='7'
												GROUP BY fld_id");
												
				if($qrysch->num_rows>0)
				{
					while($rowqrysch=$qrysch->fetch_assoc())
					{
						extract($rowqrysch);
						
						$grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_module_wca_grade 
																	WHERE fld_schedule_id='".$schid."' AND fld_module_id='".$moduleid."' 
																		AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_type='".$type."' 
																		AND fld_preassment_id='".$wcapage[$i]."'");
						if($grade == '')
							$newgrade = $grades[$i];
						
                                                if($type==0)
                                                {
                                                    $sqry = "AND fld_session_id='".$wcasess[$i]."'";
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