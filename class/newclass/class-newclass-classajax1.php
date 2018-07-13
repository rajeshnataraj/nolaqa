<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Save/Update People for Class  ---*/
	if($oper == "maptoclass" and $oper != '')
	{	
		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$list1 = isset($method['list1']) ? $method['list1'] : '0';
		$list2 = isset($method['list2']) ? $method['list2'] : '0';
		$list3 = isset($method['list3']) ? $method['list3'] : '0';
		$list4 = isset($method['list4']) ? $method['list4'] : '0';
		
		$list1=explode(",",$list1);
		$list2=explode(",",$list2);
		$list3=explode(",",$list3);
		$list4=explode(",",$list4);
		
		// Teacher mapping start
		
		$ObjDB->NonQuery("UPDATE itc_class_teacher_mapping 
						 SET fld_flag='0' 
						 WHERE fld_class_id='".$classid."'");
		
		for($i=0;$i<sizeof($list2);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_class_teacher_mapping 
												WHERE fld_class_id='".$classid."' AND fld_teacher_id='".$list2[$i]."'");
			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_teacher_mapping(fld_class_id, fld_teacher_id, fld_flag) 
																VALUES ('".$classid."', '".$list2[$i]."', '1')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_teacher_mapping 
								SET fld_flag='1' 
								WHERE fld_class_id='".$classid."' AND fld_teacher_id='".$list2[$i]."' AND fld_id='".$cnt."'");
			}
		}
		
		// Student mapping start remove student
		for($i=0;$i<sizeof($list3);$i++)
		{			
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_class_student_mapping 
												WHERE fld_class_id='".$classid."' AND fld_student_id='".$list3[$i]."' AND fld_flag='1'");
			
			if($cnt>0){				
				//Get all schedules from the class which the student remove
				$qry_schedule = $ObjDB->QueryObject("SELECT a.fld_id AS sid, '1' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'													
													UNION 
													SELECT a.fld_id AS sid, '2' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
													ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'
													UNION 
													SELECT a.fld_id AS sid, '3' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'
													UNION 
													SELECT a.fld_id AS sid, '4' AS stype, a.fld_license_id AS licenseid 
													FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_schedule_id 
													WHERE a.fld_class_id='".$classid."' AND b.fld_student_id='".$list3[$i]."' AND b.fld_flag='1'");
				
				if($qry_schedule->num_rows>0){
					$licensearray = array();
					while($res_schedule=$qry_schedule->fetch_assoc()){
						extract($res_schedule);
						if($stype==1){
							
							$ObjDB->NonQuery("DELETE FROM itc_class_sigmath_student_mapping 
											 WHERE fld_sigmath_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
						}
						else if($stype==2){
							
							$ObjDB->NonQuery("DELETE FROM itc_class_rotation_schedule_student_mappingtemp 
											 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
							$ObjDB->NonQuery("UPDATE itc_class_rotation_schedulegriddet 
												SET fld_flag='0' 
												WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");


						}
						else if($stype==3){
							
							$ObjDB->NonQuery("DELETE FROM itc_class_dyad_schedule_studentmapping 
											 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
							
							$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
											SET fld_flag='0' 
											WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
						}
						else if($stype==4){
							
							$ObjDB->NonQuery("DELETE FROM itc_class_triad_schedule_studentmapping 
											 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
									
							$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
											 SET fld_flag='0' 
											 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$list3[$i]."'");
						}												
						
						
					}
				}
			}			
			$ObjDB->NonQuery("UPDATE itc_class_student_mapping 
							 SET fld_flag='0' 
							 WHERE fld_class_id='".$classid."' AND fld_student_id='".$list3[$i]."' AND fld_id='".$cnt."'");
			
			
		}
		echo "success";
		
		//add students
		for($i=0;$i<sizeof($list4);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_class_student_mapping 
												WHERE fld_class_id='".$classid."' AND fld_student_id='".$list4[$i]."'");
			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_student_mapping(fld_class_id, fld_student_id, fld_flag) 
																VALUES ('".$classid."', '".$list4[$i]."', '1')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_student_mapping 
								 SET fld_flag='1' 
								 WHERE fld_class_id='".$classid."' AND fld_student_id='".$list4[$i]."' AND fld_id='".$cnt."'");
			}
		}
		
		$ObjDB->NonQuery("UPDATE itc_class_master 
						 SET fld_step_id='3', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
						 WHERE fld_id='".$classid."'");
	}	
	
	if($oper == "saveclass" and $oper != '') 
	{
		try {
			$classid = (isset($method['classid'])) ? $method['classid'] : 0;
			$classname =(isset( $method['classname'])) ? $ObjDB->EscapeStrAll($method['classname']) : '';
			$sdate1 =(isset( $method['sdate1'])) ?  $method['sdate1'] : '';
			$edate1 =(isset( $method['edate1'])) ?  $method['edate1'] : '';
			$period =(isset( $method['period'])) ?  $method['period'] : '';
			$term =(isset( $method['term'])) ?  $method['term'] : '';
			$shedule =(isset( $method['shedule'])) ?  $method['shedule'] : '';
			$lettergrade =(isset( $method['lettergrade'])) ?  $method['lettergrade'] : '';
			$lowerbound =(isset( $method['lowerbound'])) ?  $method['lowerbound'] : '';
			$higherbound =(isset( $method['higherbound'])) ?  $method['higherbound'] : '';
			$boxid =(isset( $method['boxid'])) ?  $method['boxid'] : '';
			$remove =(isset( $method['remove'])) ?  $method['remove'] : '';
			$grade =(isset( $method['grade'])) ?  $method['grade'] : '';
			$tags = isset($method['tags']) ? $ObjDB->EscapeStr($method['tags']) : '';
			
			$lg=explode("~",$lettergrade);
			$lb=explode("~",$lowerbound);
			$hb=explode("~",$higherbound);
			$bid=explode("~",$boxid);
			$rem=explode("~",$remove);			
			/**validation for the parameters and these below functions are validate to return true or false***/
			$validate_classid=true;
			$validate_classname=true;
			$validate_sdate1=true;
			$validate_edate1=true;
			$validate_period=true;
			$validate_shedule=true;			
			if($classid!=0) 
			$validate_classid=validate_datatype($classid,'int');
			$validate_classname=validate_datas($classname,'lettersonly');
			$validate_sdate1=validate_datas($sdate1,'dateformat');
			$validate_edate1=validate_datas($edate1,'dateformat');
			$validate_period=validate_datatype($period,'int');
			$validate_shedule=validate_datatype($shedule,'int');	
				
			if($validate_classid and $validate_classname and $validate_sdate1 and $validate_edate1 and $validate_period and $validate_shedule){				
				if($classid != 0)
				{
					$ObjDB->NonQuery("UPDATE itc_class_master 
									 SET fld_class_name='".$classname."',fld_start_date='".date('Y-m-d',strtotime($sdate1))."',
										 fld_end_date='".date('Y-m-d',strtotime($edate1))."',fld_period='".$period."',fld_term='".$term."', fld_shedule_type='".$shedule."', 
										 fld_updated_by='".$uid."',fld_updated_date='".date('Y-m-d H:i:s')."',fld_step_id='2' 
									 WHERE fld_id='".$classid."'");
					/*---tags------*/
					$ObjDB->NonQuery("UPDATE itc_main_tag_mapping 
									 SET fld_access='0' 
									 WHERE fld_tag_type='21' AND fld_item_id='".$classid."' AND 
									 fld_tag_id IN(SELECT fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0')");			
					 fn_tagupdate($tags,21,$classid,$uid);			
				}
				else
				{
					$classid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_master(fld_class_name, fld_start_date, fld_end_date, fld_period, fld_term, fld_shedule_type
																	, fld_created_by, fld_created_date, fld_step_id, fld_district_id, fld_school_id, fld_user_id)
																VALUES('".$classname."','".date('Y-m-d',strtotime($sdate1))."','".date('Y-m-d',strtotime($edate1))."','".$period."',
																	'".$term."','".$shedule."', '".$uid."','".date('Y-m-d H:i:s')."','2','".$sendistid."','".$schoolid."','".$indid."')");
					/*--Tags insert-----*/	
					 fn_taginsert($tags,21,$classid,$uid);	
				}
				
		
				for($i=0;$i<count($rem);$i++)
				{
					$ObjDB->NonQuery("UPDATE itc_class_grading_scale_mapping 
									SET fld_flag=0 
									WHERE fld_boxid='".$rem[$i]."' AND fld_class_id='".$classid."' AND fld_flag=1");
				}
				
				for($i=0;$i<count($lg)-1;$i++)
				{
					$count=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
														FROM itc_class_grading_scale_mapping 
														WHERE fld_class_id='".$classid."' AND fld_boxid='".$bid[$i]."'");
					
					if($count == 0){	
						
						$ObjDB->NonQuery("INSERT INTO itc_class_grading_scale_mapping(fld_class_id, fld_boxid, fld_upper_bound, fld_lower_bound, fld_grade, fld_roundflag,fld_flag)
												VALUES('".$classid."','".$bid[$i]."','".$hb[$i]."','".$lb[$i]."','".$lg[$i]."','".$grade."','1')");
						
					}
					else{
						$ObjDB->NonQuery("UPDATE itc_class_grading_scale_mapping 
										SET fld_upper_bound='".$hb[$i]."', fld_lower_bound='".$lb[$i]."', fld_grade='".$lg[$i]."', fld_roundflag='".$grade."',fld_flag=1 
										WHERE fld_class_id='".$classid."' AND fld_boxid='".$bid[$i]."'");
					}
				}
				
				echo "success~".$classid;
			}
			else{
				echo "fail";
			}
		}
		catch(Exception $e){
			echo "fail";
		}
	}	
	
	/*--- Save/Update a Class Final Step ---*/
	if($oper == "saveclassreview" and $oper != '')
	{		
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_class_master 
						 SET fld_step_id='1', fld_flag='1', fld_updated_by='".$uid."', fld_updated_date='".$date."' 
						 WHERE fld_id='".$classid."' AND fld_delstatus='0'");
		
		echo "success~".$classid;
	}
	
	/*--- Check Class Name ---*/
	if($oper=="checkclassname" and $oper != " " )
	{
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		$classname = isset($method['classname']) ?  fnEscapeCheck($method['classname']) : '';
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
											  FROM itc_class_master 
											  WHERE MD5(LCASE(REPLACE(fld_class_name,' ','')))='".$classname."' AND fld_delstatus='0' AND fld_id<>'".$classid."' 
											  AND fld_created_by='".$uid."'");
		
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	/*--- Delete a Class  ---*/
	if($oper == "deleteclass" and $oper != '')
	{		
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_class_master 
						 SET fld_delstatus='1', fld_deleted_date='".$date."', fld_deleted_by='".$uid."' 
						 WHERE fld_id='".$classid."'");
		
		$ObjDB->NonQuery("DELETE FROM itc_class_rotation_schedule_mastertemp where fld_class_id='".$classid."'");
		
		$ObjDB->NonQuery("DELETE FROM itc_class_sigmath_master where fld_class_id='".$classid."'");
		
		$ObjDB->NonQuery("DELETE FROM itc_class_dyad_schedulemaster where fld_class_id='".$classid."'");
		
		$ObjDB->NonQuery("DELETE FROM itc_class_triad_schedulemaster where fld_class_id='".$classid."'");
		
		echo "success";
	}
	
	
	if($oper=="indloadcontent" and $oper != " ")
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '';
		$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '';
		if($sid==0){
			if($moduletype==''){
				$qry = $ObjDB->QueryObject("SELECT COUNT(fld_id) AS cnt, 2 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='1' 
											UNION ALL 
											SELECT COUNT(fld_id) AS cnt, 3 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='2'
											UNION ALL 
											SELECT COUNT(fld_id) AS cnt, 7 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='7'");
				if($qry->num_rows>0){
					while($res = $qry->fetch_assoc()){
						extract($res);
						if($typ==2){ //module
							$checkmodule=$cnt;
						}
						else if($typ==3){ //math module
							$checkmathmodule=$cnt;
						}
						else if($typ==7){ //quest
							$checkquest=$cnt;
						}
					}
				}
				
				if($checkmodule>0 && $checkmathmodule>0 && $checkquest>0){
					$modulename="Select module type";
					$moduletype=0;
				}
				else if($checkmodule>0 && $checkmathmodule==0 && $checkquest==0){
					$modulename="Module";
					$moduletype=1;
				}
				else if($checkmathmodule>0 && $checkmodule==0 && $checkquest==0){
					$modulename="Math Module";
					$moduletype=2;
				}
				else if($checkquest>0 && $checkmodule==0 && $checkmathmodule==0){
					$modulename="Quest";
					$moduletype=7;
				}
				else{
					$modulename="Select module type";
					$moduletype=0;
				}
			}
		}
		else{
			$qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS smoduleid, a.fld_moduletype AS moduletype, (CASE WHEN a.fld_moduletype=1 THEN 'Module' WHEN a.fld_moduletype=2 
												THEN 'Math Module' WHEN a.fld_moduletype=7 THEN 'Quest' END) AS modulename 
										FROM itc_class_indassesment_master AS a 
										WHERE a.fld_id='".$sid."' AND a.fld_delstatus='0'");
			extract($qry->fetch_assoc());	
		}
?>
        <div class='row'>
             <div class='six columns'>
                Select module type<span class="fldreq">*</span>
                 <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="moduletype" id="moduletype" value="<?php echo $moduletype; ?>"  onchange="$(this).valid(); fn_indasloadmodules(<?php echo $sid; ?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php echo $modulename;?></span>
                                <b class="caret1"></b>
                            </a>
                            <?php if($moduletype==''){?>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Module">
                                    <ul role="options">
                                        <?php if($checkmodule>0){?>
                                            <li><a tabindex="-1" href="#" data-option="1">Module</a></li>
                                        <?php }?>
                                        <?php if($checkmathmodule>0){?>
                                            <li><a tabindex="-1" href="#" data-option="2">Math Module</a></li>
                                        <?php } if($checkquest>0){?>
                                            <li><a tabindex="-1" href="#" data-option="7">Quest</a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                           <?php }?>
                        </div>
                    </dt>                                       
                </dl>                                       
            </div>                        
        </div>
                                
		<?php if(($licenseid!='' and $sid!=0) || $moduletype!=0){?>
        <script>fn_indasloadmodules(<?php echo $sid; ?>);</script>
        <?php }?>
        <div id="modules"> 
                                   
        </div>                                
        <div class="row rowspacer" style="margin-top:20px;">
            <div class="tLeft" style="color:#F00;">
            </div>
            <div class="tRight" id="modnxtstep" style="display:none;">
              <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="Save schedule" onClick="fn_saveindassesment(<?php echo $sid; ?>);" />
            </div>
        </div>
 
<?php
	}
	
	
if($oper=="indasloadmodules" and $oper!='')
{
	$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
	$moduletype = isset($method['moduletype']) ? $method['moduletype'] : '';	
		
	$qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS smoduleid, a.fld_moduletype AS moduletype, (CASE WHEN a.fld_moduletype=1 THEN (SELECT CONCAT(fld_module_name,' ',
									(SELECT b.fld_version FROM itc_module_version_track AS b WHERE b.fld_mod_id=a.fld_module_id AND b.fld_delstatus='0')) FROM itc_module_master 
									WHERE fld_id=a.fld_module_id) WHEN a.fld_moduletype=2 THEN (SELECT CONCAT(fld_mathmodule_name,' ',(SELECT fld_version FROM itc_module_version_track 
									WHERE fld_mod_id=fld_module_id AND fld_delstatus='0')) FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id) WHEN a.fld_moduletype=7 THEN (SELECT 
									CONCAT(fld_module_name,' ',(SELECT b.fld_version FROM itc_module_version_track AS b WHERE b.fld_mod_id=a.fld_module_id AND b.fld_delstatus='0'))
									FROM itc_module_master WHERE fld_id=a.fld_module_id) END) AS smodulename 
								FROM itc_class_indassesment_master AS a 
								WHERE a.fld_id='".$scheduleid."' AND a.fld_delstatus='0'");			
	
	if($qry->num_rows>0)
	extract($qry->fetch_assoc());
?>
	
	<div class='row rowspacer'>
         <div class='six columns'>
            Select module<span class="fldreq">*</span>
             <dl class='field row'>   
                <dt class='dropdown'>   
                    <div class="selectbox">
                        <input type="hidden" name="moduleid" id="moduleid" value="<?php echo $smoduleid; ?>" onchange="$(this).valid();<?php if($moduletype!=7) {?> fn_loadgrade(<?php echo $moduletype;?>)<?php }?>"/>
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox">
                            <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php if($scheduleid!=0){ echo $smodulename;} else{?>Select module  <?php }?></span>
                            <b class="caret1"></b>
                        </a>                       
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter" placeholder="Search Module">
                            <ul role="options">
                                    <?php
                                        if($moduletype==1)
                                        {													
                                        $qrymodule= $ObjDB->QueryObject("SELECT a.fld_id AS moduleid, CONCAT(a.fld_module_name,' ',c.fld_version) AS modulename
                                                                         FROM itc_module_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
                                                                         LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_id
                                                                         WHERE  b.fld_license_id='".$licenseid."' 
                                                                         	AND b.fld_type='1' AND b.fld_active='1' AND a.fld_delstatus='0' AND c.fld_delstatus='0' 
                                                                         ORDER BY a.fld_id DESC");		
                                        }
                                        else if($moduletype==2)
                                        {
                                            $qrymodule= $ObjDB->QueryObject("SELECT a.fld_id AS moduleid, CONCAT(a.fld_mathmodule_name,' ',c.fld_version) AS modulename 
                                                                            FROM itc_mathmodule_master AS a LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
                                                                            LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_module_id
                                                                            WHERE b.fld_license_id='".$licenseid."' AND b.fld_type='2' AND b.fld_active='1' 
                                                                                AND a.fld_delstatus='0' AND c.fld_delstatus='0'
                                                                            ORDER BY a.fld_id DESC");	
                                        }
										else if($moduletype==7)
										{													
											$qrymodule= $ObjDB->QueryObject("SELECT a.fld_id as moduleid, CONCAT(a.fld_module_name,' ',c.fld_version) as modulename 
																			FROM itc_module_master AS a 
																			LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
																			LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_id
																			WHERE b.fld_license_id='".$licenseid."' AND b.fld_type='7' AND b.fld_active='1' 
																				AND a.fld_delstatus='0' AND c.fld_delstatus='0'
																			ORDER BY a.fld_module_name");		
										}
                                        if($qrymodule->num_rows>0)
                                        {
                                            while($row=$qrymodule->fetch_assoc())
                                            {
                                                extract($row);
                                    ?>
                                         <li><a tabindex="-1" href="#" data-option="<?php echo $moduleid;?>"><?php echo $modulename;?></a></li>
                                    <?php
                                            }
                                        }
                                    ?>
                            </ul>
                        </div>
                    </div>
                </dt>                                       
            </dl>                                       
        </div>                        
	</div>                    
    <?php
}
	

if($oper == "loadgrade" and $oper != '')
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '';
	$mtype = isset($method['mtype']) ? $method['mtype'] : '';
	
	if($mtype==2)
	{
		$tempmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id 
													FROM itc_mathmodule_master 
													WHERE fld_id='".$moduleid."'");
	}
	else{
		$tempmoduleid = $moduleid;
	}
	
	$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_grade AS grade, fld_points AS points 
								FROM itc_module_wca_grade 
								WHERE fld_module_id='".$moduleid."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
								AND fld_flag='1' AND fld_created_by='".$uid."'");
	if($qry->num_rows==0)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_grade AS grade, (CASE WHEN fld_page_title='Module Guide' THEN '10' WHEN fld_page_title='Post Test' 
									THEN '100' WHEN fld_page_title<>'Module Guide' THEN '30' END) AS points 
								  FROM itc_module_grade 
								  WHERE fld_module_id='".$tempmoduleid."' AND fld_flag='1'		
								  UNION ALL 		
								  SELECT fld_id, fld_performance_name AS title, '1' AS grade, fld_points_possible AS points 
								  FROM itc_module_performance_master 
								  WHERE fld_module_id='".$tempmoduleid."' AND fld_performance_name<>'Total Pages' AND fld_delstatus='0' 
								  GROUP BY fld_performance_name 
								  ORDER BY fld_id");
	}
	?>
	<table cellpadding="10" cellspacing="10" border="1" id="gradedtable">
	<?php
	if($qry->num_rows>0)
	{
		$i=1;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			?>
			<tr height="40" id="wca_<?php echo $fld_id?>">
				<td align="right" style="width:40%"><label id="wca_<?php echo $fld_id?>"><?php echo $title;?></label></td>
				<td align="center"><input type="text" maxlength="3" id="point_<?php echo $i;?>" name="point_<?php echo $i;?>" value="<?php echo $points;?>" style="width:30%" onkeyup="ChkValidChar(this.id);"/></td>
				<td align="center">
					<input type="checkbox" id="grade_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if($grade==1){echo 'checked="checked"';}?> value="" />
				Graded</td>
			</tr>
			<?php 
			$i++;
		}
	}
	?>
	</table>
	
	<script type="text/javascript" language="javascript">
		$(function(){
			var tabindex = 1;
			$('input,select').each(function() {
				if (this.type != "hidden") {
					var $input = $(this);
					$input.attr("tabindex", tabindex);
					tabindex++;
				}
			});
		});

		//Function to enter only numbers in textbox
		$("input[id^=point_]").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {					
				return false;
			}
		});
		
		//Function to set the max & min values for the textbox
		String.prototype.startsWith = function (str) {
			return (this.indexOf(str) === 0);
		}
		function ChkValidChar(id) {			
			var txtbx = document.getElementById(id).value;
			var nexttxtbx = 100;			
			if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
			{
				document.getElementById(id).value = "";				
			}
		}
	</script>
	<div align="center">
		<input type="button" value="Save" onclick="fn_savewcagrade(<?php echo $classid.",".$moduleid.",".$scheduleid.",".$scheduletype; ?>)" class="darkButton" />
	</div>
	<?php
}
	
if($oper == "saveindassesment" and $oper != '')
{		
	try{				
		$classid = isset($_REQUEST['classid']) ? $_REQUEST['classid'] : '0';
		$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '0';
		$sname = isset($_REQUEST['sname']) ? $ObjDB->EscapeStrAll($_REQUEST['sname']) : '0';
		$startdate = isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : '0';
		$enddate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '0';
		$scheduletype = isset($_REQUEST['scheduletype']) ? $_REQUEST['scheduletype'] : '0';
		$students = isset($_REQUEST['students']) ? $_REQUEST['students'] : '0';
		$unstudents = isset($_REQUEST['unstudents']) ? $_REQUEST['unstudents'] : '0';
		$studenttype = isset($_REQUEST['studenttype']) ? $_REQUEST['studenttype'] : '0';
		$numberofcopies = isset($_REQUEST['numberofcopies']) ? $_REQUEST['numberofcopies'] : '0';
		$numberofrotations = isset($_REQUEST['numberofrotations']) ? $_REQUEST['numberofrotations'] : '0';
		$rotationlength = isset($_REQUEST['rotationlength']) ? $_REQUEST['rotationlength'] : '0';
		$licenseid = isset($_REQUEST['licenseid']) ? $_REQUEST['licenseid'] : '0';
		$modules = isset($_REQUEST['modules']) ? $_REQUEST['modules'] : '0';
		$moduletype = isset($_REQUEST['moduletype']) ? $_REQUEST['moduletype'] : '0';		
		$pagetitle = isset($_REQUEST['pagetitle']) ? $_REQUEST['pagetitle'] : '';
		$points = isset($_REQUEST['points']) ? $_REQUEST['points'] : '';
		$grades = isset($_REQUEST['grades']) ? $_REQUEST['grades'] : '';
		$students = explode(',',$students);
		$unstudents = explode(',',$unstudents);	
		
		$validate_sid=true;
		$validate_sname=true;
		$validate_classid=true;
		$validate_scheduletype=true;
		$validate_startdate=true;
		$validate_enddate=true;
		$validate_licenseid=true;			
		if($sid!=0) 
			$validate_sid=validate_datatype($sid,'int');
		$validate_sname=validate_datas($sname,'lettersonly');
		$validate_classid=validate_datatype($classid,'int');
		$validate_licenseid=validate_datatype($licenseid,'int');
		$validate_scheduletype=validate_datatype($scheduletype,'int');
		$validate_startdate=validate_datas($startdate,'dateformat');
		$validate_enddate=validate_datas($enddate,'dateformat');
		
		if($validate_sid and $validate_sname and $validate_classid and $validate_scheduletype and $validate_startdate and $validate_licenseid and $validate_enddate){
			if($moduletype==1){
				$days = $ObjDB->SelectSingleValueInt("SELECT fld_days FROM itc_module_master WHERE fld_id='".$modules."'");
			}
			else if($moduletype==7){
				$days = $ObjDB->SelectSingleValueInt("SELECT fld_days FROM itc_module_master WHERE fld_id='".$modules."'");
			}
			else{
				$days = $ObjDB->SelectSingleValueInt("SELECT fld_days FROM itc_mathmodule_master WHERE fld_id='".$modules."'");
			}				
			if($studenttype==1){
				/*---------checing the license for student----------------------*/				
				$count=0;
				$qry = $ObjDB->QueryObject("SELECT fld_student_id 
											FROM itc_class_student_mapping 
											WHERE fld_class_id='".$classid."' AND fld_flag='1'");
				if($qry->num_rows>0){
					$students=array();
					while($res=$qry->fetch_assoc())
					{
						extract($res);
						$students[]=$fld_student_id;
						$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
															  FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
															  WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' 
															  	AND b.fld_delstatus='0'");
						if($check==0)
						{
							$count++;
						}
					}
				}
			}
			else{
				$count=0;
				$add=0;			
				for($i=0;$i<sizeof($students);$i++)
				{
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
														  FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
														  WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");
					if($check==0)
					{
						$count++;
					}
				}				
				for($i=0;$i<sizeof($unstudents);$i++)
				{					
					$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
															FROM itc_license_assign_student 
															WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' AND fld_flag='1'");
					if($check>0)
					{
						
						$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_rotation_schedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_schedule_mastertemp
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_dyad_schedule_studentmapping AS a LEFT JOIN itc_class_dyad_schedulemaster
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_triad_schedule_studentmapping AS a LEFT JOIN itc_class_triad_schedulemaster
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																		UNION ALL 
																		SELECT COUNT(a.fld_id) AS cnt 
																		FROM itc_class_indassesment_student_mapping AS a LEFT JOIN itc_class_indassesment_master
																		AS b ON a.fld_schedule_id=b.fld_id 
																		WHERE a.fld_student_id='".$unstudents[$i]."' 
																		AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
																		) AS o");
						
						$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
										 SET fld_flag='0' 
										 WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
						if($studentcount==0){
							$add++;
							$ObjDB->NonQuery("UPDATE itc_license_assign_student 
											 SET fld_flag='0' 
											 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."'");
						}
					}
				}
			}
			
			$remainusersqry = $ObjDB->QueryObject("SELECT fld_remain_users AS remainusers, fld_no_of_users AS totalusers 
															FROM itc_license_track 
															WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' 
															AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");		
			extract($remainusersqry->fetch_assoc());
				
			$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																 FROM itc_license_assign_student 
																 WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
			$totalremain = $remainusers-$count;
			if($totalusers>=($assignedstudents+$count)){
				$flag=1;
			}		
			else{	
				$flag=0;
			}
			
			if($flag==1){ //if student user availale for license
				if($sid!=0){				
					$oldmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_class_indassesment_master WHERE fld_id='".$sid."'");				
					if($oldmoduleid!=$modules){
						if($moduletype==1)
						{
							$ObjDB->NonQuery("DELETE FROM itc_module_play_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							$ObjDB->NonQuery("DELETE FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							$ObjDB->NonQuery("DELETE FROM itc_module_variable_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							$ObjDB->NonQuery("DELETE FROM itc_module_answer_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
						}
						if($moduletype==2)
						{
							$ObjDB->NonQuery("DELETE FROM itc_module_play_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							$ObjDB->NonQuery("DELETE FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							$ObjDB->NonQuery("DELETE FROM itc_module_variable_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							$ObjDB->NonQuery("DELETE FROM itc_module_answer_track WHERE fld_schedule_id='".$sid."' AND fld_schedule_type='5'");
							
							$qry=$ObjDB->NonQuery("SELECT fld_id FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$scheduleid."' AND fld_test_type='5'");
							if($qry->num_rows>0)
							{
								while($row=$qry->fetch_assoc())
								{
									extract($row);
									$ObjDB->NonQuery("DELETE FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$fld_id."'");
								}
							}
							
							$ObjDB->NonQuery("DELETE FROM itc_assignment_sigmath_master WHERE fld_schedule_id='".$scheduleid."' AND fld_test_type='5'");
						}
					}
					$ObjDB->NonQuery("UPDATE itc_class_indassesment_master 
									SET fld_schedule_name='".$sname."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."' ,
										fld_enddate='".date("Y-m-d",strtotime($enddate))."', fld_module_id='".$modules."', fld_moduletype='".$moduletype."',
										fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
									WHERE fld_id='".$sid."'");
				}
				else{
					
					$sid = $ObjDB->NonQueryWithMaxValue("INSERT into itc_class_indassesment_master (fld_class_id,fld_license_id,fld_schedule_name,fld_module_id, fld_moduletype, 	
																fld_scheduletype, fld_student_type,fld_startdate,fld_enddate,fld_created_date,fld_createdby) 
														 VALUES('".$classid."','".$licenseid."','".$sname."','".$modules."','".$moduletype."','".$scheduletype."','".$studenttype."',
																'".date("Y-m-d",strtotime($startdate))."','".date("Y-m-d",strtotime($enddate))."','".date("Y-m-d H:i:s")."','".$uid."')");					
				}
				
				$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
								 SET fld_flag='0' 
								 WHERE fld_schedule_id='".$sid."'");
				
				for($i=0;$i<sizeof($students);$i++){
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_class_indassesment_student_mapping 
														WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_class_indassesment_student_mapping(fld_schedule_id, fld_student_id,fld_flag) 
										 VALUES ('".$sid."', '".$students[$i]."','1')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_class_indassesment_student_mapping 
										SET fld_flag='1' 
										WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
					
					//tracing student
					$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
					if($cnt==0)
					{
						$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag) 
										 VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE itc_license_assign_student 
										 SET fld_flag='1' 
										 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
					}
				}
				$ObjDB->NonQuery("UPDATE itc_class_indassesment_master 
								 SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_license_id='".$licenseid."' 
								 WHERE fld_id='".$sid."'");
				
				if($points!='')
				{
					$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_schedule_type='".$scheduletype."' AND fld_created_by='".$uid."'");
					$pagetitle = explode('~',$pagetitle);
					$points = explode('~',$points);
					$grades = explode(',',$grades);
					$r=2;
					for($i=0;$i<sizeof($points);$i++)
					{
						if($pagetitle[$i]=='Attendance')
						{
							$type=1;
							$session = 0;
							$newtitle = $pagetitle[$i];
						}
						else if($pagetitle[$i]=='Participation')
						{
							$type=2;
							$session = 0;
							$newtitle = $pagetitle[$i];
						}
						else if($pagetitle[$i]<>'Module Guide' and substr($pagetitle[$i], 0, 3)<>'RCA' and $pagetitle[$i]<>'Post Test')
						{
							$type=3;
							$session = 0;
							$newtitle = $pagetitle[$i];
						}
							
						if($pagetitle[$i]=='Module Guide')
						{
							$session=0;
							$type=0;
							$newtitle = $pagetitle[$i];
						}
						else if($pagetitle[$i]=='Post Test')
						{
							$session=6;
							$type=0;
							$newtitle = $pagetitle[$i];
						}
						else if(substr($pagetitle[$i], 0, 3)=='RCA')
						{
							$ses = $r; $ses--;
							$session=$ses;
							$type=0;
							$newtitle = "RCA ".$r;
							$r++;
						}
						
						$wcagradeid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_module_wca_grade WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_schedule_type='".$scheduletype."' AND fld_module_id='".$modules."' AND fld_session_id='".$session."' AND fld_page_title='".$newtitle."' AND fld_created_by='".$uid."' AND fld_type='".$type."'");
						
						if($wcagradeid!='')
							$ObjDB->NonQuery("UPDATE itc_module_wca_grade SET fld_flag='1', fld_grade='".$grades[$i]."', fld_points='".$points[$i]."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$wcagradeid."'");
						else
							$ObjDB->NonQuery("INSERT INTO itc_module_wca_grade (fld_type, fld_schedule_type, fld_schedule_id, fld_class_id, fld_module_id, fld_session_id, fld_page_title, fld_grade, fld_points, fld_flag, fld_created_by, fld_created_date) VALUES('".$type."', '".$scheduletype."', '".$sid."', '".$classid."', '".$modules."', '".$session."', '".$newtitle."', '".$grades[$i]."', '".$points[$i]."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
						
						
						if($i<6)	
							$qrycount = $ObjDB->QueryObject("SELECT fld_id AS fieldid, fld_teacher_points_earned AS teachpoint, fld_points_earned AS earnedpoints, fld_points_possible AS posible FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type IN (5,6) AND fld_module_id='".$modules."' AND fld_session_id='".$session."' AND fld_type='0'");
						else
							$qrycount = $ObjDB->QueryObject("SELECT fld_id AS fieldid, fld_teacher_points_earned AS teachpoint, fld_points_earned AS earnedpoints, fld_points_possible AS posible FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_schedule_type IN (5,6) AND fld_module_id='".$modules."' AND fld_type='".$type."'");
							
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
								$ObjDB->NonQuery("UPDATE itc_module_points_master SET fld_grade='".$grades[$i]."', fld_points_possible='".$newpossible."', fld_points_earned='".$newearned."', fld_teacher_points_earned='".$newteacher."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$fieldid."'");
							}
						}
					}
				}
				echo "success~".$sid;
				send_notification($licenseid,$schoolid,$indid);			
			}
			else{
				echo "fail";
			}
		}
		else{
			echo "invalid";
		}
	}
	catch(Exception $e){
		echo "invalid";
	}
}
	
if($oper == "classlock" and $oper != '')
{		
	$classid = isset($method['classid']) ? $method['classid'] : '0';
	$flag = isset($method['flag']) ? $method['flag'] : '0';
	$ObjDB->NonQuery("UPDATE itc_class_master SET fld_lock='".$flag."' WHERE fld_id='".$classid."'");
}

if($oper == "createstudentform" and $oper != '')
{	?>
<div class='row'>
    <div class='twelve columns '>                
        <div class='eleven columns centered insideForm'>
            <form id="studentform" name="studentform">	
                <div class="row">
                    <div class='six columns'>
                        First Name<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="fname" name="fname" placeholder='First Name' type='text' onkeyup="fn_generateuname()" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class='six columns'>
                        Last Name<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="lname" name="lname" placeholder='Last Name' type='text' onkeyup="fn_generateuname();fn_checkusername()" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class='six columns'>
                        Username<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="uname" name="uname" placeholder='User Name' type='text' onblur="$(this).valid();" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class='six columns'>
                        Password<span class="fldreq">*</span>
                        <dl class='field row'>
                            <dt class='text'>
                                 <input  id="password" name="password" placeholder='Password' type='text' value="<?php echo generatePassword();?>" />
                            </dt>                                        
                        </dl>
                    </div>
                </div>                
                <div style="padding-left: 1%;padding-top: 2%;">                        	
                    <input type="button" value="Save" onclick="fn_createstudent(0)" class="darkButton" />
                    <input type="button" value="Save & Continue" onclick="fn_createstudent(1)" class="darkButton" />
                    <input type="button" value="Cancel" onclick="$.fancybox.close()" class="darkButton" />
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">
	function fn_generateuname()
	{		
		$('#uname').val($('#fname').val().toLowerCase().substring(0,1)+$('#lname').val().toLowerCase());		
	}
	function fn_checkusername()
	{
		$('#save').attr('disabled','disabled');
		$('#savec').attr('disabled','disabled');		
		var dataparam = "oper=checkstdname&uname="+$('#uname').val();	
		$.ajax({
			type: "POST",
			url: 'users/individuals/users-individuals-student_newstudentdb.php',
			data: dataparam,
			success: function(data)
			{
				if(trim(data)=='false')
					$('#uname').val(Math.floor(Math.random() * 8) + 1+$('#fname').val().toLowerCase().substring(0,1)+$('#lname').val().toLowerCase());
					$('#save').removeAttr('disabled');
					$('#savec').removeAttr('disabled');	
			}
		});
	}	
	$(function(){
		$("#studentform").validate({
			ignore: "",
				errorElement: "dd",
				errorPlacement: function(error, element) {
					$(element).parents('dl').addClass('error');
					error.appendTo($(element).parents('dl'));
					error.addClass('msg'); 	
			},
			rules: { 
				uname: { required: true, chkusername: true },									
				password: { required: true },
				fname: { required: true },
				lname: { required: true }	
			}, 
			messages: { 
				uname: { required: "please enter the User name", chkusername: "Student username already exists" },           
				password:{  required: "Please enter the password" },
				fname:{ required: "Please enter the first name"},
				lname:{ required: "Please enter the last name"},									
			},
			highlight: function(element, errorClass, validClass) {
				$(element).parent('dl').addClass(errorClass);
				$(element).addClass(errorClass).removeClass(validClass);
			},
			unhighlight: function(element, errorClass, validClass) {
				if($(element).attr('class') == 'error'){
						$(element).parents('dl').removeClass(errorClass);
						$(element).removeClass(errorClass).addClass(validClass);
				}
			},
			onkeyup: false,
			onblur: true
		});
	});	
</script>                            
	<?php 	
}

if($oper == "savestudent" and $oper != '')
{	
	$fname = isset($method['fname']) ? $ObjDB->EscapeStrAll($method['fname']) : '';
	$lname = isset($method['lname']) ? $ObjDB->EscapeStrAll($method['lname']) : '';
	$uname = isset($method['uname']) ? $ObjDB->EscapeStrAll($method['uname']) : '';
	$password = isset($method['password']) ? $method['password'] : '';
	$uguid = gen_uuid();
	$userid = $ObjDB->NonQueryWithMaxValue ("INSERT INTO itc_user_master(fld_uuid, fld_username, fld_password, fld_profile_id,fld_role_id, fld_fname, fld_lname, fld_district_id, 
														fld_school_id, fld_activestatus, fld_user_id, fld_created_by, fld_created_date)
													VALUES('".$uguid."', '".$uname."','".fnEncrypt($password,$encryptkey)."','10','5','".$fname."','".$lname."','".$districtid."',
														'".$schoolid."','1','".$indid."','".$uid."','".date("Y-m-d H:i:s")."')");
	echo "success~";?>
	<div class="draglinkleft" id="list3_<?php echo $userid; ?>" >
        <div class="dragItemLable" id="<?php echo $userid; ?>"><?php echo stripcslashes($fname." ".$lname); ?></div>
        <div class="clickable" id="clck_<?php echo $userid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $userid; ?>);"></div>
    </div> 
 <?php                                                    
}

if($oper == "changeeventdate" and $oper != '')
{	
	$curdate=date("Y-m-d");
	$type = isset($method['type']) ? $method['type'] : '';
	$sid = isset($method['sid']) ? $method['sid'] : '';
	$date = isset($method['date']) ? $method['date'] : '';
	$rotation = isset($method['rotation']) ? $method['rotation'] : '';
	$edate = isset($method['enddate']) ? $method['enddate'] : '';
	$stageid = isset($method['stageid']) ? $method['stageid'] : '';
	
	if($type=="Sigmath")
	{
		$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_start_date='".$date."',fld_end_date='".$edate."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
	else if($type=="assesment")
	{
		$ObjDB->NonQuery("UPDATE itc_class_indassesment_master SET fld_startdate='".$date."',fld_enddate='".$edate."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
	}
	else if($type=="rotation")
	{
		
			$qry=$ObjDB->NonQuery("SELECT fld_numberofrotations AS rot,fld_rotationlength AS length FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$sid."' AND fld_delstatus='0'");
			
			$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_rotation_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
			
			if($rotation==$rotval)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_startdate='".$date."' WHERE fld_id='".$sid."' AND fld_delstatus='0'");
			} 
			
			$row=$qry->fetch_assoc();
			extract($row);
			
			
			for($i=$rotation;$i<=$rot+1;$i++)
			{
				if($i==$rotation)
				{
					$startdate=$date;
					$enddate=$edate;
				}
				else
				{
					$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
					$enddate=date("Y-m-d",strtotime($startdate. "+".$length." weekdays"));
				}
				
				
				
				$ObjDB->NonQuery("UPDATE itc_class_rotation_schedulegriddet 
								 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."' 
								 WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."'");
			}
			
			$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_schedulegriddet WHERE fld_schedule_id='".$sid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mastertemp SET fld_enddate='".$rotenddate."' WHERE fld_id='".$sid."'");
		
	}
	else if($type=="dyad")
	{		
		$qry=$ObjDB->NonQuery("SELECT fld_numberofrotation 
							  FROM itc_class_dyad_schedule_insstagemap 
							  WHERE fld_id<='".$stageid."' AND fld_flag='1' AND fld_schedule_id='".$sid."' AND fld_numberofrotation<>'0'");
		$count='';
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			
			$count=$count+$fld_numberofrotation;
		}
		 
		for($i=$rotation;$i<=$count;$i++)
		{
			if($i==$rotation)
			{
				$startdate=$date;
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}			
			$ObjDB->NonQuery("UPDATE itc_class_dyad_schedulegriddet 
							 SET fld_startdate='".$startdate."',fld_enddate='".$enddate."' 
							 WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
		}
		
	}
	else if($type=="triad")
	{
		$qry=$ObjDB->NonQuery("SELECT fld_numberofrotation 
							  FROM itc_class_triad_schedule_insstagemap 
							  WHERE fld_id<='".$stageid."' AND fld_flag='1' AND fld_schedule_id='".$sid."' AND fld_numberofrotation<>'0'");
		$count='';
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			
			$count=$count+$fld_numberofrotation;
		}
		 
		for($i=$rotation;$i<=$count;$i++)
		{
			if($i==$rotation)
			{
				$startdate=$date;
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+6 weekdays"));
			}
			$ObjDB->NonQuery("UPDATE itc_class_triad_schedulegriddet 
							SET fld_startdate='".$startdate."',fld_enddate='".$enddate."' 
							WHERE fld_schedule_id='".$sid."' AND fld_flag='1' AND fld_rotation='".$i."' AND fld_stageid='".$stageid."'");
		}
		
	}
}

if($oper=="checkrotdate" and $oper!="")
{
	$sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
	$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
	$rotation = isset($_REQUEST['rotation']) ? $_REQUEST['rotation'] : '';
	$edate = isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : '';
	
	$rotval=$ObjDB->SelectSingleValueInt("SELECT MIN(fld_rotation) FROM itc_class_rotation_schedulegriddet WHERE fld_schedule_id='".$sid."' AND fld_flag='1'");
		
		$prevrotation=$rotation-1;
		
		$prerotstartdate=$ObjDB->SelectSingleValue("select fld_startdate from itc_class_rotation_schedulegriddet where fld_schedule_id='".$sid."' and fld_rotation='".$prevrotation."' and fld_flag='1'");
		
		$condition="false";
		
		if($rotation==$rotval)
		{
			$condition="true";
		}
		else if(strtotime($date)>=strtotime($prerotstartdate))
		{
			$condition="true";
		}
		
		if($condition=="true")
		{
			echo "success";
		}
		else
		{
			$rotation=$rotation-1;
			$prevrotation=$prevrotation-1;
			
			echo "rotation".$rotation. " cannot begin before rotation".$prevrotation;
		}
}

	@include("footer.php");