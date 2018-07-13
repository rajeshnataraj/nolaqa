<?php
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';


/*--- Check Module Name ---*/
if($oper=="checkmodulename" and $oper != " " )
{
	$mathmoduleid = isset($method['mid']) ? $method['mid'] : '0';
	$mathmodulename = isset($method['txtmathmodname']) ? fnEscapeCheck($method['txtmathmodname']) : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_mathmodule_master WHERE MD5(LCASE(REPLACE(fld_mathmodule_name,' ','')))='".$mathmodulename."' AND fld_delstatus='0' AND fld_id<>'".$mathmoduleid."'");
	
	if($count == 0){ echo "true"; }	else { echo "false"; }
}



/*--- Load Session Day2 ---*/
if($oper=="showsessday2" and $oper != " " )
{
	$sessday1id = isset($method['sessday1id']) ? $method['sessday1id'] : '';
	?>
    <input type="hidden" name="sessday2" id="sessday2" value="<?php echo $sessday2 ;?>" onchange="$(this).valid();">
    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#" tabindex="5">
        <span class="selectbox-option input-medium" data-option=" ">Diagnostic Day 2 After Session ?</span>
        <b class="caret1"></b>
    </a>
    <div class="selectbox-options" >			    
        <ul role="options" >
            <?php for($i=$sessday1id+1;$i<8;$i++) {?>
            <li><a tabindex="-1" href="#" data-option="<?php echo $i; ?>">Session <?php echo $i; ?></a></li>
            <?php }?>
        </ul>
    </div>
	<?php
}



/*--- Load IPL Day2 ---*/
if($oper=="showiplday2" and $oper != " " )
{
	$iplday1id = isset($method['iplday1id']) ? $method['iplday1id'] : '';
	$ipl2ids = isset($method['ipl2ids']) ? $method['ipl2ids'] : '';
	?>
    <script language="javascript" type="text/javascript" charset="utf-8">		
		$(document).ready(function() {
			$('.multicheck1').click(function(e) {  
				var cnt = 0;
				$(this).toggleClass("dragWellmod"); 
				$(this).toggleClass("checkedokmod");
				$("div[id^='chk1_']").each(function() {
				if($(this).hasClass('checkedokmod')) {
						cnt++;				
					}
				});
				if(cnt==4)
				{
					var iplids1 = [];
					$("div[id^='chk1_']").each(function() {
						if($(this).hasClass('checkedokmod')) {
							iplids1.push($(this).attr('id').replace('chk1_',''));				
						}
					});
					$('#iplday2').val(iplids1);
				}
				else if(cnt>4)
				{
					$(this).toggleClass("checkedokmod");
					$(this).toggleClass("dragWellmod"); 
					showloadingalert("Select Only Four IPLs");	
					setTimeout('closeloadingalert()',1000);
					return false;
				}
				return false;
			});
		});
	</script>
    
    <?php 
	$qryipl = $ObjDB->QueryObject("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) AS iplname, a.fld_id AS iplid 
	                         FROM itc_ipl_master AS a 
							       LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
							 WHERE a.fld_delstatus='0' AND a.fld_lesson_type='1' AND b.fld_delstatus='0' AND b.fld_zip_type='1' AND a.fld_id NOT IN (".$iplday1id.") ORDER BY a.fld_ipl_name");
							 
	if($qryipl->num_rows > 0){
		$sessiplid = array();
		$sessiplname = array();
		while($resipl=$qryipl->fetch_assoc()){
			extract($resipl);
			$sessiplid[] = $iplid;
			$sessiplname[$iplid] = $iplname;
		}
	}
	
	$ipl1 = explode(",",$ipl2ids);
						
	$orderedipl = sortArrayByArray($ipl1,$sessiplid);
	for($w=0;$w<sizeof($orderedipl);$w++)
	{
		$count=0;
		for($i=0;$i<sizeof($ipl1);$i++) { if($ipl1[$i] == $orderedipl[$w]) { $count = 1; } }
		?>
		<div class="multicheck1 <?php if($count==1){?>checkedokmod<?php } else {?>dragWellmod<?php }?>" id="chk1_<?php echo $orderedipl[$w];?>">
			<div class="dragItemLable" id="<?php echo $orderedipl[$w]; ?>"><?php echo $sessiplname[$orderedipl[$w]]; ?></div>
		</div> 
		<?php                               
	}

}

/*--- Delete the Module ---*/
if($oper=="deletemathmodule" and $oper != " " )
{
	$mathmoduleid = isset($method['mathmoduleid']) ? $method['mathmoduleid'] : ''; 
	
	$validate_mathmoduleid=true;
		if($mathmoduleid!=0)$validate_mathmoduleid=validate_datatype($mathmoduleid,'int');
		
	$count=0;
	$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_mod_mapping WHERE fld_module_id='".$mathmoduleid."' AND fld_active='1' AND fld_type='2'");
	
	if($validate_mathmoduleid)
	{
		if($count==0)
		{
			$ObjDB->NonQuery("UPDATE itc_mathmodule_master SET fld_delstatus='1', fld_deleted_by='".$uid."', fld_deleted_date='".$date."' WHERE fld_id='".$mathmoduleid."'");	
		
                        $ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
						 SET fld_delstatus='1', fld_deleted_date = '".$date."', fld_deleted_by = '".$uid."'  
						 WHERE fld_module_id= '".$mathmoduleid."'");	
			echo "success";
		}
		else
		{
			echo "exists";
		}
	}
}

/*--- Save and Update the Module ---*/
if($oper=="savemathmodule" and $oper != " " )
{
	try /**Here starts with saving the details mathmodule master table**/
	{
	
	$mathmodid = isset($method['editid']) ? $method['editid'] : '0'; 
	$mathmodname = isset($method['mathmodname']) ? $ObjDB->EscapeStrAll($method['mathmodname']) : ''; 
	$mouledid = isset($method['mouledid']) ? $method['mouledid'] : '0'; 
	$modphase = isset($method['modphase']) ? $method['modphase'] : ''; 
	$modminutes = isset($method['modminutes']) ? $method['modminutes'] : ''; 
	$moddays = isset($method['moddays']) ? $method['moddays'] : ''; 
	$sessday1 = isset($method['sessday1']) ? $method['sessday1'] : ''; 
	$sessday2 = isset($method['sessday2']) ? $method['sessday2'] : ''; 
	$iplday1 = isset($method['iplday1']) ? $method['iplday1'] : ''; 
	$iplday2 = isset($method['iplday2']) ? $method['iplday2'] : ''; 
	$tags = isset($method['tags']) ? $ObjDB->EscapeStr($method['tags']) : '';
	
        $mathmoduledescription = isset($_POST['mathmoduledescription']) ? $ObjDB->EscapeStr($_POST['mathmoduledescription']) : '';
        $list10 = isset($method['list10']) ? $method['list10'] : '';
        $list10=explode(",",$list10);
        
	$assetid = $ObjDB->SelectSingleValue("SELECT fld_asset_id FROM itc_module_master WHERE fld_id='".$mouledid."'");
	
	/**validation for the parameters and these below functions are validate to return true or false***/
	$validate_mathmodname=true;
	$validate_mathmodid=true;
	
	if($mathmodid!=0)
	{
		$validate_mathmodid=validate_datatype($mathmodid,'int');
		$validate_mathmodname=validate_datas($mathmodname,'lettersonly'); 
	}
	
		
		if($validate_mathmodname and $validate_mathmodid)
		{
			if($mathmodid!='0')
			{				
				$ObjDB->NonQuery("UPDATE itc_mathmodule_master SET fld_mathmodule_name='".$mathmodname."', fld_module_id='".$mouledid."', fld_phase='".$modphase."', fld_minutes='".$modminutes."', fld_days='".$moddays."', fld_session_day1='".$sessday1."', fld_session_day2='".$sessday2."', fld_ipl_day1='".$iplday1."', fld_ipl_day2='".$iplday2."', fld_updated_by='".$uid."', fld_updated_date='".$date."',fld_mathmodule_descr='".$mathmoduledescription."'  ,fld_asset_id='".$assetid."' WHERE fld_id='".$mathmodid."'");
				
				$ObjDB->NonQuery("UPDATE itc_mathmodule_master SET fld_mathmodule_name='".$mathmodname."', fld_module_id='".$mouledid."', fld_phase='".$modphase."', fld_minutes='".$modminutes."', fld_days='".$moddays."', fld_session_day1='".$sessday1."', fld_session_day2='".$sessday2."', fld_ipl_day1='".$iplday1."', fld_ipl_day2='".$iplday2."', fld_updated_by='".$uid."', fld_updated_date='".$date."', fld_asset_id='".$assetid."' WHERE fld_id='".$mathmodid."'");
				
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_module_id='".$mathmodid."'");
			if($list10[0] != '') {
                            
				for($i=0;$i<sizeof($list10);$i++)
				{
					
                                        
                                        
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_mod_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_module_id='".$mathmodid."' AND fld_type='2'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$mathmodid."','1','2', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_module_id='".$mathmodid."' AND fld_type='2'");
					}
					
				}
			}
				/*--Tags update-----*/
				fn_tagupdate($tags,23,$mathmodid,$uid);
				
				echo "success";
			}
			
			else
			{
				$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_mathmodule_master (fld_mathmodule_name, fld_module_id, fld_phase, fld_minutes, fld_days, fld_session_day1, fld_session_day2, fld_ipl_day1, fld_ipl_day2, fld_created_by, fld_created_date, fld_asset_id,fld_mathmodule_descr)VALUES	('".$mathmodname."', '".$mouledid."', '".$modphase."', '".$modminutes."', '".$moddays."', '".$sessday1."', '".$sessday2."', '".$iplday1."', '".$iplday2."', '".$uid."', '".$date."', '".$assetid."','".$mathmoduledescription."')");
				
                                
                                 //Licences insert/update
                      
			$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
							 SET fld_active='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_module_id='".$maxid."'");
			if($list10[0] != '') {
				for($i=0;$i<sizeof($list10);$i++)
				{
					
                                        
                                        
					$cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_license_mod_mapping 
                                                                                                WHERE fld_license_id='".$list10[$i]."'  AND fld_module_id='".$maxid."' AND fld_type='2'");
					if($cnt==0)
					{
						 $ObjDB->NonQuery("INSERT INTO itc_license_mod_mapping (fld_license_id,fld_module_id,fld_active,fld_type, fld_created_by, fld_created_date)
											VALUES('".$list10[$i]."','".$maxid."','1','2', '".$uid."', '".$date."')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_mod_mapping 
											SET fld_active='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
											WHERE fld_license_id='".$list10[$i]."' AND fld_module_id='".$maxid."' AND fld_type='2'");
					}
					
				}
			}
				/*--Tags insert-----*/	
				fn_taginsert($tags,23,$maxid,$uid);
				
				echo "success";
			}
		}
		else
		{
			echo "fail";		
		}
	}
	catch(Exception $e)
	{
		 echo "fail";
	}
}

/*--- Save and Update the Modulegrades ---*/
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
			$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='2' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_module_id='".$moduleid."'");
			
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
				
				$wcagradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_wca_grade WHERE fld_class_id='0' AND fld_schedule_id='0' AND fld_schedule_type='2' AND fld_module_id='".$moduleid."' AND fld_session_id='".$wcasess[$i]."' AND fld_page_title='".$newtitle."' AND fld_school_id='".$schoolid."' AND fld_type='".$type."' AND fld_preassment_id='".$wcapage[$i]."'");
				
				if($wcagradeid!='')
				{
					$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='1', fld_grade='".$grades[$i]."', fld_points='".$points[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_preassment_id='".$wcapage[$i]."' WHERE fld_id='".$wcagradeid."'");
				}
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_module_wca_grade (fld_type, fld_schedule_type, fld_schedule_id, fld_class_id, fld_module_id, fld_session_id, fld_preassment_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date, fld_school_id) VALUES('".$type."', '2', '0', '0', '".$moduleid."', '".$wcasess[$i]."', '".$wcapage[$i]."', '".$newtitle."', '".$grades[$i]."', '".$points[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."','".$schoolid."')");
				}
				
				$createids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM itc_user_master WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_profile_id NOT IN (1,4,6,10,11) AND fld_delstatus='0' AND fld_activestatus='1'");
			
				$qrysch = $ObjDB->QueryObject("SELECT a.fld_id AS schid, '4' AS schtype
												FROM itc_class_rotation_schedule_mastertemp AS a 
												LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
												WHERE b.fld_module_id='".$moduleid."' AND a.fld_createdby IN (".$createids.") AND a.fld_moduletype='2'
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