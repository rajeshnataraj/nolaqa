<?php 
	@include("sessioncheck.php");
	ini_set('display_errors', 1);
	error_reporting(E_ALL ^ E_NOTICE);
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Load Units Listbox ---*/
	if($oper=="loadcontent" and $oper != " ")
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';
		$sflag = isset($method['flag']) ? $method['flag'] : '0';
			
		?>
		<script type="text/javascript" language="javascript">
			$(function() {
				$('#testrailvisible3').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
					railVisible: true,
                                        alwaysVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1
					
				});
				$('#testrailvisible4').slimscroll({
					width: '410px',
					height:'366px',
					size: '7px',
					railVisible: true,
                                        alwaysVisible: true,
					allowPageScroll: false,
					railColor: '#F4F4F4',
					opacity: 1,
					color: '#d9d9d9',
                                         wheelStep: 1
				});
				$("#list3").sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					items: "div[class='draglinkleft']",
					receive: function(event, ui) { 
						$("div[class=draglinkright]").each(function(){ 
							if($(this).parent().attr('id')=='list3'){
								fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
							}
						});						
					}
				});
			
				$( "#list4" ).sortable({
					connectWith: ".droptrue3",
					dropOnEmpty: true,
					receive: function(event, ui) { 
						$("div[class=draglinkleft]").each(function(){ 
							if($(this).parent().attr('id')=='list4'){
								fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
							}
						});						
					}
				});			
				
			});  
        </script>
        
        <!--Start of unit drag and drop list id3&4 and testrailvisible3 &4 and droptrue3-->
        <div id="units"></div>
        <div class='row'>
                <div class='six columns'>
                    <div class="dragndropcol">
                    	<div class="dragtitle">Units available</div>
                            <div class="draglinkleftSearch" id="s_list3" >
                               <dl class='field row'>
                                    <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                                    </dt>
                                </dl>
                            </div>
                            <div class="dragWell" id="testrailvisible3" >
                                <div id="list3" class="dragleftinner droptrue3">
                                   
                                 <?php 						
								$qryunit= $ObjDB->QueryObject("SELECT a.fld_id as unitid, a.fld_unit_name as unitname 
															  FROM itc_unit_master AS a LEFT JOIN itc_license_cul_mapping AS b ON a.fld_id = b.fld_unit_id 
															  WHERE b.fld_license_id='".$licenseid."' AND b.fld_active='1' AND a.fld_id 
															  	NOT IN (SELECT fld_unit_id FROM itc_class_sigmath_unit_mapping WHERE fld_sigmath_id='".$sid."' 
																AND fld_flag='1' AND fld_license_id='".$licenseid."') 
															  GROUP BY a.fld_id ORDER BY unitname");								
													
								if($qryunit->num_rows > 0){
									while($rowsqryunit = $qryunit->fetch_assoc()){
										extract($rowsqryunit);
                            ?>
                                <div class="draglinkleft" id="list3_<?php echo $unitid; ?>" >
                                	<div class="dragItemLable" id="<?php echo $unitid; ?>"><?php echo $unitname; ?></div>
                                	<div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $unitid; ?>,'0','<?php echo $licenseid; ?>');"></div>
                                </div> 
                                <?php }
                                }?>
                                </div>
                            </div>
                    	<div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,0,'<?php echo $licenseid; ?>');">add all units</div>
                    </div>
                </div>
                <div class='six columns'>
                    <div class="dragndropcol">
                    	<div class="dragtitle">Units in your schedule</div>
                        <div class="draglinkleftSearch" id="s_list4" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this,'#list4');" />
                                </dt>
                            </dl>
                        </div>
                        <div class="dragWell" id="testrailvisible4">
                            <div id="list4" class="dragleftinner droptrue3">
								 <?php 
									$qryunitselect= $ObjDB->QueryObject("SELECT a.fld_id AS unitid, a.fld_unit_name AS unitname, COUNT(c.fld_unit_id) AS chkunit
																		FROM itc_unit_master AS a LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_unit_id 
																		LEFT JOIN itc_assignment_sigmath_master AS c ON c.fld_schedule_id=b.fld_sigmath_id AND c.fld_unit_id=b.fld_unit_id
																		WHERE b.fld_sigmath_id='".$sid."' AND b.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
																		GROUP BY a.fld_id 
																		ORDER BY b.fld_order");									
									if($sflag==1){
										$sid=0;
									}
									if($qryunitselect->num_rows > 0){
										while($rowsqryunitselect = $qryunitselect->fetch_assoc()){
											extract($rowsqryunitselect);											
                            ?>
                            <div class="draglinkright <?php if($chkunit!=0 and $sid!=0){?>dim<?php }?>" id="list4_<?php echo $unitid; ?>">
                                <div class="dragItemLable" id="<?php echo $unitid; ?>"><?php echo $unitname; ?></div>
                                <div class="clickable" id="clck_<?php echo $unitid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $unitid; ?>,'0','<?php echo $licenseid; ?>');"></div>
                            </div>
                            <?php }
                            }?>
                            </div>
                        </div>
                    <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0,0,'<?php echo $licenseid; ?>');">remove all units</div>
                    </div>
                </div>
            </div>
            <div class="row" align="right" style="padding-top:20px;">
            	<input type="button" value="Orderipl" onclick="fn_orderipls(<?php echo $sid.",".$licenseid; ?>)" class="darkButton" />
            </div>
        <!--End of unit drag and drop-->
       
		<?php 
	}	
	
	if($oper == "saveschedule" and $oper != '')
	{
		try{		
			$classid = isset($method['classid']) ? $method['classid'] : '0';
			$extids = isset($_POST['extids']) ? $_POST['extids'] : '';
			$sid = isset($method['sid']) ? $method['sid'] : '0';
			$sname = isset($method['sname']) ? $ObjDB->EscapeStrAll($method['sname']) : '0';
			$startdate = isset($method['startdate']) ? $method['startdate'] : '0';
			$enddate = isset($method['enddate']) ? $method['enddate'] : '0';
			$stype = isset($method['stype']) ? $method['stype'] : '0';
			$students = isset($method['students']) ? $method['students'] : '0';
			$unstudents = isset($method['unstudents']) ? $method['unstudents'] : '0';
			$studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
			$classid = isset($method['classid']) ? $method['classid'] : '0';
			$list3 = isset($method['list3']) ? $method['list3'] : '0';
			$list4 = isset($method['list4']) ? $method['list4'] : '0';
			$list5 = isset($method['list5']) ? $method['list5'] : '0';
			$list6 = isset($method['list6']) ? $method['list6'] : '0';
			$list7 = isset($method['list7']) ? $method['list7'] : '0';
			$list8 = isset($method['list8']) ? $method['list8'] : '0';
			$gradeflag = isset($method['gradeflag']) ? $method['gradeflag'] : '0';
			$gradepoint = isset($method['gradepoint']) ? $method['gradepoint'] : '0';
                        //Math Connection start
                        $mgradeflag = isset($method['mgradeflag']) ? $method['mgradeflag'] : '0';
                        $miplflag = isset($method['miplflag']) ? $method['miplflag'] : '0'; 
                             $iplflag = isset($method['iplflag']) ? $method['iplflag'] : '0'; //new line
			$mgradepoint = isset($method['mgradepoint']) ? $method['mgradepoint'] : '0';
                        $munitid = isset($method['munitid']) ? $method['munitid'] : '0';
                        $mlessid = isset($method['mlessid']) ? $method['mlessid'] : '0';
			//Math Connection End   
                        
                        //diagnostic test
                        $unitdiagflag=isset($method['unitdiagflag']) ? $method['unitdiagflag'] : '0';
                        $lessondiagflag=isset($method['lessondiagflag']) ? $method['lessondiagflag'] : '0';
                        //diagnostic test end
                        
			$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
			$students = explode(',',$students);
			$unstudents = explode(',',$unstudents);
			$list3 = explode(',',$list3);
			$list4 = explode(',',$list4);
			$list5 = explode(',',$list5);
			$list6 = explode(',',$list6);
			$list7 = explode(',',$list7);
			$list8 = explode(',',$list8);
			$extid=explode(",",$extids);
			$gradeflag = explode(',',$gradeflag);
			$gradepoint = explode(',',$gradepoint);			
                             //Math Connection start
                        $mgradeflag = explode(',',$mgradeflag);
                        $miplflag = explode(',',$miplflag); 
                             $iplflag = explode(',',$iplflag); //new line
			$mgradepoint = explode(',',$mgradepoint);
                        $munitid = explode(',',$munitid);
                        $mlessid = explode(',',$mlessid);	
			//Math Connection End   
                        
                        $unitdiagflag=explode(',',$unitdiagflag);
                        $lessondiagflag=explode(',',$lessondiagflag);
                        
			/**validation for the parameters and these below functions are validate to return true or false***/
			$validate_sid=true;
			$validate_sname=true;
			$validate_classid=true;
			$validate_stype=true;
			$validate_startdate=true;
			$validate_licenseid=true;			
			if($sid!=0) 
				$validate_sid=validate_datatype($sid,'int');
			$validate_sname=validate_datas($sname,'lettersonly');
			$validate_classid=validate_datatype($classid,'int');
			$validate_licenseid=validate_datatype($licenseid,'int');
			$validate_stype=validate_datatype($stype,'int');
			$validate_startdate=validate_datas($startdate,'dateformat');
			
			if($validate_sid and $validate_sname and $validate_classid and $validate_stype and $validate_startdate and $validate_licenseid){
				if($studenttype==1){
					/*---------checing the license for student----------------------*/	
					$students=array();			
					$count=0;
					$add=0;		
					$qry = $ObjDB->QueryObject("SELECT fld_student_id 
												FROM itc_class_student_mapping 
												WHERE fld_class_id='".$classid."' AND fld_flag='1'");
					if($qry->num_rows>0){
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
					for($i=0;$i<sizeof($students);$i++)
					{
						$check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
																FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' 
																	AND b.fld_delstatus='0'");				
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
							$studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt 
																			FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_master
																			AS b ON a.fld_sigmath_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
																			AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_sigmath_id<>'".$sid."'
																			UNION ALL 
																			SELECT COUNT(a.fld_id) AS cnt 
																			FROM itc_class_rotation_schedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_schedule_mastertemp
																			AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
																			AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
																			UNION ALL 
                                                                                                                                                        SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                                                        FROM itc_class_rotation_expschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_expschedule_mastertemp
                                                                                                                                                        AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                                                        UNION ALL 
                                                                                                                                                        SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                                                        FROM itc_class_rotation_mission_student_mappingtemp AS a LEFT JOIN itc_class_rotation_mission_mastertemp
                                                                                                                                                        AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                                                                        UNION ALL 
                                                                                                                                                        SELECT COUNT(a.fld_id) AS cnt 
                                                                                                                                                        FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_modexpschedule_mastertemp
                                                                                                                                                        AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																			UNION ALL 
																			SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a
																			LEFT JOIN itc_class_dyad_schedulemaster
																			AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
																			AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																			UNION ALL 
																			SELECT COUNT(a.fld_id) AS cnt 
																			FROM itc_class_triad_schedule_studentmapping AS a LEFT JOIN itc_class_triad_schedulemaster
																			AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
																			AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																			UNION ALL 
																			SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
																			LEFT JOIN itc_class_indassesment_master
																			AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
																			AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
																			UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
								LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
								LEFT JOIN itc_class_mission_schedule_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");
							
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_student_mapping 
											 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
											 WHERE fld_sigmath_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
							if($studentcount==0){
								$add++;
								$ObjDB->NonQuery("UPDATE itc_license_assign_student 
												 SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
												 WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' ");
							}
						}
					}	
				}
				$remainusersqry = $ObjDB->QueryObject("SELECT fld_remain_users AS remainusers, fld_no_of_users AS totusers 
															FROM itc_license_track 
															WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' 
															AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");		
				extract($remainusersqry->fetch_assoc());
				
				$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																 FROM itc_license_assign_student 
																 WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");		
				
				$totalremain = $remainusers-$count;
				if($totusers>=($assignedstudents+$count)){
					$flag=1;
				}		
				else{	
					$flag=0;
				}
				
				if($flag==1){ //if student user availale for license
					if($sid!=0){
						$ObjDB->NonQuery("UPDATE itc_class_sigmath_master 
										 SET fld_schedule_name='".$sname."',fld_start_date='".date('Y-m-d',strtotime($startdate))."',fld_end_date='".date('Y-m-d',strtotime($enddate))."', 
											 fld_student_type='".$studenttype."',fld_updatedby='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' 
										 WHERE fld_id='".$sid."'");
						$ObjDB->NonQuery("UPDATE itc_class_master 
										 SET fld_updated_date='".date("Y-m-d H:i:s")."' 
										 WHERE fld_id='".$classid."'");
					}
					else{
						
						$sid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_sigmath_master (fld_class_id,fld_schedule_name,fld_start_date,fld_end_date,fld_created_date,fld_createdby) 
															VALUES('".$classid."','".$sname."','".date('Y-m-d',strtotime($startdate))."','".date('Y-m-d',strtotime($enddate))."',
																'".date("Y-m-d H:i:s")."','".$uid."')");					
						$ObjDB->NonQuery("UPDATE itc_class_master 
										 SET fld_updated_date='".date("Y-m-d H:i:s")."' 
										 WHERE fld_id='".$classid."'");			
					}
					
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_student_mapping 
									 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
									 WHERE fld_sigmath_id='".$sid."'");
					for($i=0;$i<sizeof($students);$i++){
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_class_sigmath_student_mapping 
															WHERE fld_sigmath_id='".$sid."' AND fld_student_id='".$students[$i]."'");				
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_student_mapping(fld_sigmath_id, fld_student_id,fld_license_id, fld_flag,fld_createddate,fld_createdby) 
																					VALUES ('".$sid."', '".$students[$i]."', '".$licenseid."','1','".date('Y-m-d H:i:s')."','".$uid."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_student_mapping 
											 SET fld_flag='1',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
											 WHERE fld_sigmath_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
						}
						
						//tracing student
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_license_assign_student 
															WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) 
																			VALUES ('".$schoolid."','".$licenseid."','".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");									
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_license_assign_student 
											SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."'
											WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
						}
					}		
					
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_unit_mapping 
										 SET fld_flag='0',fld_order='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
										 WHERE fld_sigmath_id='".$sid."' AND fld_license_id='".$licenseid."'");			
					for($i=0;$i<sizeof($list4);$i++)
					{
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_class_sigmath_unit_mapping 
															WHERE fld_sigmath_id='".$sid."' AND fld_unit_id='".$list4[$i]."' AND fld_license_id='".$licenseid."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_unit_mapping(fld_sigmath_id, fld_unit_id, fld_license_id, fld_flag,fld_order,fld_createddate,fld_createdby) 
																				VALUES ('".$sid."', '".$list4[$i]."','".$licenseid."', '1','".$i."','".date('Y-m-d H:i:s')."','".$uid."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_unit_mapping 
											 SET fld_flag='1',fld_order='".$i."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
											 WHERE fld_sigmath_id='".$sid."' AND fld_unit_id='".$list4[$i]."' AND fld_id='".$cnt."' AND fld_license_id='".$licenseid."'");
						}
					}
					
					
                                        $ObjDB->NonQuery("UPDATE itc_class_sigmath_unitdiagnostictest_mapping 
										 SET fld_diagnostic_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
										 WHERE fld_sigmath_id='".$sid."' AND fld_license_id='".$licenseid."'");			
					for($i=0;$i<sizeof($unitdiagflag);$i++)
					{
                                                $unitflag=explode("~",$unitdiagflag[$i]);
                                                
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_class_sigmath_unitdiagnostictest_mapping 
															WHERE fld_sigmath_id='".$sid."' AND fld_unit_id='".$unitflag[0]."' AND fld_license_id='".$licenseid."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_unitdiagnostictest_mapping(fld_sigmath_id, fld_unit_id, fld_license_id,fld_diagnostic_flag,fld_createddate,fld_createdby) 
																				VALUES ('".$sid."', '".$unitflag[0]."','".$licenseid."','".$unitflag[1]."','".date('Y-m-d H:i:s')."','".$uid."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_unitdiagnostictest_mapping 
											 SET fld_diagnostic_flag='".$unitflag[1]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
											 WHERE fld_sigmath_id='".$sid."' AND fld_unit_id='".$unitflag[0]."' AND fld_id='".$cnt."' AND fld_license_id='".$licenseid."'");
						}
					}
					
					
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_lesson_mapping 
									 SET fld_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
									 WHERE fld_sigmath_id='".$sid."'  AND fld_license_id='".$licenseid."'");
					
					$lessondays=0;	
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_lesson_mapping 
									 SET fld_order='0' 
									 WHERE fld_sigmath_id='".$sid."'");			
					for($i=0;$i<sizeof($list6);$i++)//new line 419,424
					{
						$lessondays = $lessondays+$ObjDB->SelectSingleValueInt("SELECT fld_ipl_days 
																				FROM itc_ipl_master 
																				WHERE fld_id='".$list6[$i]."'");
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_class_sigmath_lesson_mapping 
															WHERE fld_sigmath_id='".$sid."' AND fld_lesson_id='".$list6[$i]."' AND fld_license_id='".$licenseid."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_lesson_mapping(fld_sigmath_id, fld_lesson_id, fld_license_id, fld_flag,fld_order,fld_createddate,fld_createdby) 
                                                                            VALUES ('".$sid."', '".$list6[$i]."', '".$licenseid."', '".$gradeflag[$i]."','".$i."','".date('Y-m-d H:i:s')."','".$uid."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_lesson_mapping 
                                                                                SET fld_flag='".$gradeflag[$i]."',fld_order='".$i."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
											 WHERE fld_sigmath_id='".$sid."' AND fld_lesson_id='".$list6[$i]."' AND fld_id='".$cnt."' AND fld_license_id='".$licenseid."'");
						}
					}
					
                                        
                                        $ObjDB->NonQuery("UPDATE itc_class_sigmath_lessondiagnostictest_mapping 
										 SET fld_diagnostic_flag='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
										 WHERE fld_sigmath_id='".$sid."' AND fld_license_id='".$licenseid."'");			
					for($i=0;$i<sizeof($lessondiagflag);$i++)
					{
                                                $lessonflag=explode("~",$lessondiagflag[$i]);
                                                
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_class_sigmath_lessondiagnostictest_mapping  
															WHERE fld_sigmath_id='".$sid."' AND fld_lesson_id='".$lessonflag[0]."' AND fld_license_id='".$licenseid."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_lessondiagnostictest_mapping (fld_sigmath_id, fld_lesson_id, fld_license_id,fld_diagnostic_flag,fld_createddate,fld_createdby) 
																				VALUES ('".$sid."', '".$lessonflag[0]."','".$licenseid."', '".$lessonflag[1]."','".date('Y-m-d H:i:s')."','".$uid."')");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_lessondiagnostictest_mapping 
											 SET fld_diagnostic_flag='".$lessonflag[1]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
											 WHERE fld_sigmath_id='".$sid."' AND fld_lesson_id='".$lessonflag[0]."' AND fld_id='".$cnt."' AND fld_license_id='".$licenseid."'");
						}
					}
					
					//update lesson grade points
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_grade 
									 SET fld_flag='0' 
									 WHERE fld_schedule_id='".$sid."'");
					$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
									 SET fld_grade='0' 
									 WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' AND fld_test_type='1'");							
					for($i=0;$i<sizeof($gradeflag);$i++)
					{				
						$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
															FROM itc_class_sigmath_grade 
															WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$list6[$i]."' AND fld_class_id='".$classid."'");
						if($cnt==0)
						{
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_grade(fld_class_id,fld_schedule_id,fld_lesson_id,fld_grade,fld_points,fld_flag,fld_created_date,fld_created_by) 
                                                                                VALUES ('".$classid."','".$sid."', '".$list6[$i]."', '".$gradeflag[$i]."','".$gradepoint[$i]."','".$iplflag[$i]."','".date("Y-m-d H:i:s")."','".$uid."')");	
							$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
											SET fld_grade='".$gradeflag[$i]."', fld_points_earned=(CASE WHEN fld_points_earned!=0 AND fld_status!='0' THEN 
												fld_points_earned + (".$gradepoint[$i]."-fld_points_earned) ELSE 0 END), fld_points_possible='".$gradepoint[$i]."' 
											WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' AND fld_lesson_id='".$list6[$i]."'");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_grade 
											 SET fld_flag='".$iplflag[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$gradeflag[$i]."', fld_points='".$gradepoint[$i]."', 
												fld_updated_by='".$uid."' 
											 WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$list6[$i]."' AND fld_id='".$cnt."' AND fld_class_id='".$classid."'");
							$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
											 SET fld_grade='".$gradeflag[$i]."', fld_points_earned=(CASE WHEN fld_points_earned!=0 AND fld_status!='0' THEN fld_points_earned + 
												(".$gradepoint[$i]."-fld_points_earned) ELSE 0 END), fld_points_possible='".$gradepoint[$i]."' 
											 WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' AND fld_lesson_id='".$list6[$i]."'");
						}
					}
                                        
                                    //Math Connection for checked
                                         $ObjDB->NonQuery("UPDATE itc_class_sigmath_grademapping 
									 SET fld_flag='0' 
									 WHERE fld_schedule_id='".$sid."'");
                                        for($i=0;$i<sizeof($mgradeflag);$i++)
					{  
                                           	$cnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id)
                                                                                            FROM itc_class_sigmath_grademapping 
                                                                                            WHERE fld_schedule_id='".$sid."' AND fld_unit_id='".$mlessid[$i]."' AND fld_class_id='".$classid."'");
						if($cnt==0)
						{
                                                    
							$ObjDB->NonQuery("INSERT INTO itc_class_sigmath_grademapping(fld_class_id,fld_schedule_id,fld_unit_id,fld_mgrade,fld_mpoints,fld_flag,fld_created_date,fld_created_by) 
                                                                        VALUES ('".$classid."','".$sid."', '".$mlessid[$i]."', '".$mgradeflag[$i]."','".$mgradepoint[$i]."','".$miplflag[$i]."','".date("Y-m-d H:i:s")."','".$uid."')");	
                                                        $ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
                                                                                SET fld_grade='".$mgradeflag[$i]."', fld_points_earned=(CASE WHEN fld_points_earned!=0 AND fld_status!='0' THEN fld_points_earned + 
                                                                                (".$mgradepoint[$i]."-fld_points_earned) ELSE 0 END), fld_points_possible='".$mgradepoint[$i]."' 
                                                                                WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' AND fld_lesson_id='0'");
						}
						else
						{
							$ObjDB->NonQuery("UPDATE itc_class_sigmath_grademapping 
                                                                            SET fld_flag='".$miplflag[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_mgrade='".$mgradeflag[$i]."', fld_mpoints='".$mgradepoint[$i]."', fld_updated_by='".$uid."' 
                                                                            WHERE fld_schedule_id='".$sid."' AND fld_unit_id='".$mlessid[$i]."' AND fld_class_id='".$classid."'");
                                                        $ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
                                                                            SET fld_grade='".$mgradeflag[$i]."', fld_points_earned=(CASE WHEN fld_points_earned!=0 AND fld_status!='0' THEN fld_points_earned + 
                                                                            (".$mgradepoint[$i]."-fld_points_earned) ELSE 0 END), fld_points_possible='".$mgradepoint[$i]."' 
                                                                            WHERE fld_schedule_id='".$sid."' AND fld_delstatus='0' AND fld_lesson_id='0'");

                                                }
					}
                                    //Math Connection
                                        
                                        
					//update enddate
					$enddate=date("Y-m-d",strtotime($startdate. "+".($lessondays-1)." weekdays"));
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_master 
									 SET fld_end_date='".date("Y-m-d",strtotime($enddate))."' 
									 WHERE fld_id='".$sid."'");			
					
					$ObjDB->NonQuery("UPDATE itc_class_sigmath_master 
									 SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_license_id='".$licenseid."', fld_student_type='".$studenttype."' 
									 WHERE fld_id='".$sid."'");
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
	
	//load schedule type base on license
	if($oper == "loadscheduletemplate" and $oper != '')
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';	
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		
		if($sid==0){
			//get count for license's content
			$qry = $ObjDB->QueryObject("SELECT COUNT(fld_id) AS cnt, 1 AS typ 
										FROM itc_license_cul_mapping 
										WHERE fld_license_id='".$licenseid."' AND fld_active='1' 	
									  UNION ALL 
										  SELECT COUNT(fld_id) AS cnt, 2 AS typ 
										  FROM itc_license_mod_mapping 
										  WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='1' 
									  UNION ALL 
										  SELECT COUNT(fld_id) AS cnt, 3 AS typ 
										  FROM itc_license_mod_mapping 
										  WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='2'
									  UNION ALL 
											SELECT COUNT(fld_id) AS cnt, 7 AS typ 
											FROM itc_license_mod_mapping 
											WHERE fld_license_id='".$licenseid."' AND fld_active='1' AND fld_type='7'
									  UNION ALL 
										  SELECT COUNT(fld_id) AS cnt, 15 AS typ 
										  FROM itc_license_exp_mapping 
										  WHERE fld_license_id='".$licenseid."' AND fld_flag='1'
                                                                           UNION ALL 
										  SELECT COUNT(fld_id) AS cnt, 17 AS typ 
										  FROM itc_license_exp_mapping 
										  WHERE fld_license_id='".$licenseid."' AND fld_flag='1'
                                                                           UNION ALL 
										  SELECT COUNT(fld_id) AS cnt, 18 AS typ 
										  FROM itc_license_mission_mapping 
										  WHERE fld_license_id='".$licenseid."' AND fld_flag='1'           
                                                                                      
                                                                          UNION ALL
                                                                                   
SELECT count(a.fld_id) AS cnt, 16 AS typ FROM itc_license_pd_mapping AS a 
    LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id
    LEFT JOIN itc_user_master AS c ON c.fld_school_id = b.fld_school_id
    WHERE b.fld_school_id='".$schoolid."' AND b.fld_district_id='".$districtid."' AND b.fld_user_id='".$indid."' 
    AND b.fld_delstatus='0' AND a.fld_active='1' AND c.fld_id='".$uid."'
    AND b.fld_license_id='".$licenseid."' "
                                
                                );
			if($qry->num_rows>0){
				while($res = $qry->fetch_assoc()){
					extract($res);
					if($typ==1){  //ipl
						$checkipl=$cnt;
					}
					else if($typ==2){ //module
						$checkmodule=$cnt;
					}
					else if($typ==3){ //math module
						$checkmathmodule=$cnt;
					}
					else if($typ==7){ //quest
						$checkquest=$cnt;
					}
					else if($typ==15){ //expedition
						$checkexp=$cnt;
					}
                                        else if($typ==17){ //expedition
						$checkexpschedule=$cnt;
					}
                                        else if($typ==18){ //mission
						 $checkmission=$cnt;
					}
                                        else if($typ==16){ //pd
                                            
                                            if($sessmasterprfid!='8' and $sessmasterprfid!='9' and $sessmasterprfid!='10' or $sessmasterprfid==2 or $sessmasterprfid==3)
                                            {
						$checkpd=$cnt;
				}
                                            else
                                            {
                                                $flag=$ObjDB->SelectSingleValueInt("SELECT fld_flag FROM itc_user_master WHERE fld_id='".$uid."'");

                                                if($flag==1 and $cnt!=0)
                                                {
                                                    $checkpd=$flag;
			}
			}
                                        }
				}
			}
			
                        $type = "Schedule template";
			$scheduletype ='';
			if($checkipl>0 and $checkmodule>0 and $checkmathmodule>0 and $checkquest>0 and $checkexp>0 and $checkpd>0 and $checkexpschedule>0 and $checkmission>0){
				$flag='all';
			}
			else if($checkipl>0 and $checkmodule>0)
			{
				$flag='iplmod';
			}
			else if($checkipl>0 and $checkmathmodule>0)
			{
				$flag='iplmath';
			}
			else if($checkmodule>0 and $checkmathmodule>0)
			{
				$flag='modmath';
			}
                        else if($checkipl>0 and $checkexp>0)
			{
				$flag='iplexp';
			}
                        else if($checkipl>0 and $checkexpschedule>0)
			{
				$flag='iplexpschedule';
			}
                        else if($checkipl>0 and $checkmission>0)
			{
				 $flag='iplmissionschedule';
			}
                        else if($checkipl>0 and $checkpd>0)
			{
				$flag='iplpd';
			}
			else if($checkipl>0)
			{
				$flag='ipl';
				$type = "IPL Series";
				$scheduletype =1;
				?>
				<script>
					fn_sigmathloadcontent(<?php echo $licenseid.",".$sid;?>);
				</script>
				<?php 
			}
			else if($checkmodule>0)
			{
				$flag='mod';			
			}
			else if($checkquest>0)
			{
				$flag='quest';	
			}	
			else if($checkexp>0)
			{
				$flag='exp';			
			}		
                        else if($checkexp>0)
			{
				$flag='expschedule';			
			}
                         else if($checkmission>0)
			{
				$flag='mission';			
			}
                        else if($checkpd>0)
			{
				$flag='pd';			
		}
		}
		else{
			if($scheduletype==1){				
				$type='IPL Series';
				?>
				<script>
					fn_sigmathloadcontent(<?php echo $licenseid.",".$sid;?>);
				</script>
				<?php 
			}
			if($scheduletype==2){
				$type='Module Schedule';
			?>
				<script>
					fn_rotloadcontent(<?php echo $licenseid.",".$sid.",1";?>);
				</script>
				<?php
			}
			if($scheduletype==6){
				$type='Math Module Schedule';
			?>
				<script>
					fn_rotloadcontent(<?php echo $licenseid.",".$sid.",2";?>);
				</script>
				<?php
			}
			if($scheduletype==3)
			{
			$type='Dyad Schedule ';
			?>
            <script>
            	fn_dyadstage(<?php echo $sid;?>,'ins',0);
			</script>
            <?php
			}
			if($scheduletype==4)
			{
			$type='Triad Schedule';
			?>
            	 <script>
            	fn_triadstage(<?php echo $sid;?>,'ins',0);
			</script>
			<?php
			}
			if($scheduletype==5)
			{
			$type='Whole Class Assignment - Modules';
			?>
            	<script>
            	fn_indassesment(<?php echo $licenseid.",".$sid;?>);
			</script>
			<?php
			}
			if($scheduletype==15)
			{
			$type='Whole Class Assignment - Expedition';
			?>
            	<script>
            	fn_indassesmentexpedition(<?php echo $licenseid.",".$sid;?>);
			</script>
			<?php
			}
                        if($scheduletype==18)
			{
                         $type='Whole Class Assignment - mission';
			?>
                        <script>
                        fn_missionassesment(<?php echo $licenseid.",".$sid;?>);
			</script>
			<?php
			}
                    if($scheduletype==16){				
                            $type='PD schedule';
                            ?>
                            <script>
                                    fn_pdloadcontent(<?php echo $licenseid.",".$sid;?>);
                            </script>
                            <?php 
		}
                    
                    if($scheduletype==17){				
                            $type='Expedition Schedule';
                            ?>
                            <script>
                                    fn_exploadcontent(<?php echo $licenseid.",".$sid.",1";?>);
                            </script>
                            <?php 
		}
                
                       if($scheduletype==19){				
                            $type='Module/Expedition Schedule';
                            ?>
                            <script>
                                    fn_modexploadcontent(<?php echo $licenseid.",".$sid.",1";?>);
                            </script>
                            <?php 
		}
                
                    if($scheduletype==20){				
                            $type='Mission Schedule';
                            ?>
                            <script>
                                    fn_missionloadcontent(<?php echo $licenseid.",".$sid.",1";?>);
                            </script>
                            <?php 
		}
		}
		?>
        	<div class='six columns'>
                    <input type="hidden" id="scrollhid1" value="0" />
            	Select schedule type<span class="fldreq">*</span><style>.cur {cursor: default;} </style>  
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="scheduletype" id="scheduletype" value="<?php echo $scheduletype; ?>" onchange="$(this).valid(); fn_loadmodule()" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject"><?php echo $type;?></span>
                                <b class="caret1"></b>
                            </a>
                            <?php
                                if($sid==0 and $flag!='ipl')
                                {
                                ?>
                                    <div class="selectbox-options">
                                        <ul role="options">
                                        <?php //Mohan M changed the name for alphabetical order
                                            if($checkmodule>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="3" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#moduletemplate,#expeditiontemplate,#modexpeditiontemplate,#missionid,#missionschtemplate').hide();$('#dyadtemplate').show();">Dyad Schedule</a></li>
                                                    <?php 
                                            } 
                                            if($checkexp>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="17" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#modexpeditiontemplate,#missionid,#missionschtemplate').hide();$('#expeditiontemplate').show();$('#instructionstages').html('');">Expedition Schedule</a></li>
                                                    <?php 
                                            } 
                                            if($checkipl>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="1" onclick="$('#stemplate').show();$('#pdtemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#modexpeditiontemplate,#missionid,#missionschtemplate').hide();$('#instructionstages').html('');">IPL Series</a></li>
                                                    <?php 
                                            }
                                            if($checkmathmodule>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="6" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#modexpeditiontemplate,#missionid,#missionschtemplate').hide();">Math Module Schedule</a></li>
                                                    <?php 
                                            }
                                            if($checkmission>0)
                                            {
                                                    ?>
                                                    <li><a tabindex="-1" href="#" data-option="20" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#modexpeditiontemplate,#missionid').hide();$('#missionschtemplate').show();$('#instructionstages').html('');">Mission Schedule</a></li>
                                                    <?php 
                                            }
                                            if($checkmodule>0)
                                            { ?>
												<li><a tabindex="-1" href="#" data-option="2" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#expeditiontemplate,#modexpeditiontemplate,#missionid,#missionschtemplate').hide();$('#moduletemplate').show();$('#instructionstages').html('');">Module Schedule</a></li>
                                                    <?php
                                            }
											if($checkmodule>0 AND $checkexp>0)
											{
												?>
												<li><a tabindex="-1" href="#" data-option="19" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#missionschtemplate').hide();$('#modexpeditiontemplate').show();$('#instructionstages').html('');">Module/Expedition Schedule</a></li>
												<?php
											}
                                            if($checkpd>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="16" onclick="$('#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#modexpeditiontemplate,#missionid,#missionschtemplate').hide();$('#instructionstages').html('');">PD Schedule</a></li>
                                                    <?php 
                                            }
                                            if($checkmodule>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="4" onclick="$('#pdtemplate,#stemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#missionid,#missionschtemplate').hide();$('#triadtemplate').show();">Triad Schedule</a></li>
                                                    <?php
                                            } 
                                            if($checkmathmodule>0 || $checkmodule>0 || $checkquest>0)
                                            { 	?>
												<li><a tabindex="-1" href="#" data-option="5" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#missionid,#missionschtemplate').hide();$('#instructionstages').html('');">Whole Class Assignment - Modules</a></li>
                                                    <?php 
                                            }
                                            if($checkexp>0)
                                            { ?>
												<li><a tabindex="-1" href="#" data-option="15" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#missionid,#moduletemplate,#expeditiontemplate,#missionschtemplate').hide();$('#instructionstages').html('');">Whole Class Assignment - Expedition</a></li>
                                                    <?php 
                                            }
                                            if($checkmission>0)
                                            {
                                                    ?>
												<li><a tabindex="-1" href="#" data-option="18" onclick="$('#pdtemplate,#stemplate,#triadtemplate,#dyadtemplate,#moduletemplate,#expeditiontemplate,#modexpeditiontemplate,#missionschtemplate').hide();$('#missionid').show();$('#instructionstages').html('');">Whole Class Assignment - Mission</a></li>
                                                    <?php 
                                            }
                                            ?>
                                        </ul>
                                    </div>
                            <?php
                                }
                                ?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
            
            <div class='six columns' id="stemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="stemplateid" id="stemplateid" value="" onchange="fn_sigmathloadcontent(<?php echo $licenseid.",".$sid;?>,1);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
            
	    <!-- module schedule template-->

            <div class='six columns' id="moduletemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="moduletemplateid" id="moduletemplateid" value="" onchange="fn_rotloadcontent(<?php echo $licenseid.",".$sid.",0";?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 
                           	
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_moduletype='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
  <!-- module schedule template-->
            
            <div class='six columns' id="dyadtemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="dyadtemplateid" id="dyadtemplateid" value="" onchange="fn_dyadstage(<?php echo $sid;?>,'ins',1);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
															  FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
															  WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
            
            <div class='six columns' id="triadtemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="triadtemplateid" id="triadtemplateid" value="" onchange="fn_triadstage(<?php echo $sid;?>,'ins',1);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
  
           <!-- PD template -->
           <div class='six columns' id="pdtemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="pdtemplateid" id="pdtemplateid" value="" onchange="fn_pdloadcontent(<?php echo $licenseid.",".$sid;?>,1);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
        <?php 
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_pdschedule_master AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
           
           
           <!-- Expedition schedule template-->

            <div class='six columns' id="expeditiontemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="expschtemplateid" id="expschtemplateid" value="" onchange="fn_exploadcontent(<?php echo $licenseid.",".$sid.",0";?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 

                           	
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_rotation_expschedule_mastertemp AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
  <!-- Expedition schedule template-->
  
           <!-- Mission schedule template-->

            <div class='six columns' id="missionschtemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="misschtemplateid" id="misschtemplateid" value="" onchange="fn_missionloadcontent(<?php echo $licenseid.",".$sid.",0";?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 

                           	
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_rotation_mission_mastertemp AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
  <!-- Mission schedule template-->
  
           <!-- ModuleExpedition schedule template-->

            <div class='six columns' id="modexpeditiontemplate" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="modexpschtemplateid" id="modexpschtemplateid" value="" onchange="fn_modexploadcontent(<?php echo $licenseid.",".$sid.",0";?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
                           	<?php 

                           	
								$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
																FROM itc_class_rotation_modexpschedule_mastertemp AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
																WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
  <!-- ModuleExpedition schedule template-->
           <!-- Mission schedule template-->

            <div class='six columns' id="missionid" style="display:none;">
            	Select schedule template
                <dl class='field row'>   
                    <dt class='dropdown'>   
                        <div class="selectbox">
                            <input type="hidden" name="missionscheduleid" id="missionscheduleid" value="" onchange="fn_missionassesment(<?php echo $licenseid.",".$sid;?>);" />
                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" id="clearsubject">Select schedule template</span>
                                <b class="caret1"></b>
                            </a>
        <?php 
                                
                           			$qrysch = $ObjDB->QueryObject("SELECT a.fld_id,a.fld_schedule_name 
											FROM itc_class_indasmission_master AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
											WHERE a.fld_license_id='".$licenseid."' AND b.fld_created_by='".$uid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
                                if($qrysch->num_rows>0){ ?>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search schedule">
                                        <ul role="options">
                                        <?php                                         
                                            while($res=$qrysch->fetch_assoc()){
                                                extract($res);?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_schedule_name; ?></a></li>
                                            <?php 
                                        }?>      
                                        </ul>
                                    </div>
                       <?php }?>
                        </div>
                    </dt>                                         
                </dl>
            </div>
  <!-- Mission schedule template-->
        <?php 
	}
	
	if($oper == "showremainingusers" and $oper != '')
	{
		$trackid = isset($method['trackid']) ? $method['trackid'] : '0';
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$fld_remain_users = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_id='".$trackid."'");	
		$assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_license_id) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' 
															AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' ");					
			echo "  <br />Available student seats: ".$fld_remain_users."  <br />Assigned student seats: ".$assignedstudents;
		
	}
	
	//change the ipl order based on unit name
	if($oper == "loadorderipl" and $oper != '')
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';	
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';	
		$sflag = isset($method['flag']) ? $method['flag'] : '0';
		$unitids = isset($method['unitids']) ? $method['unitids'] : '0';
		$unitid = explode(',',$unitids);
                $m=1;
                $fld_mgrade=1;$mflag=1;
                //Please remove the option of a Math Connection from these three units 1.Orientation,2.Calculators I,3.Graphing Calculators
                $cal=1;
                $graphcal=20;
                $orient=42;
                
		echo "Organize your IPLs";?>                
  
            <SCRIPT language="javascript">
                
            $(function(){

                // add multiple select / deselect functionality
                 $(".selectallunit").click(function () {
                      var id=this.id;
                      var unitid=id.split('_');
                   
                      if($('#'+id).is(':checked'))
                      {
                         
                        $('.unitselect_'+unitid[1]).attr('checked', this.checked);
                      }
                      else
                      {
                         
                         $('.unitselect_'+unitid[1]).removeAttr("checked");
                      }
                      
                });

                // if all checkbox are selected, check the selectall checkbox
                // and viceversa
                $(".case").click(function(){
                    var clsdet=this.id;
                    var unitid=clsdet.split('_');
                    
                    if($(".unitselect_"+unitid[2]).length == $(".unitselect_"+unitid[2]+":checked").length) {
                        $("#unitsel_"+unitid[2]).attr("checked", "checked");
                    } else {
                        $("#unitsel_"+unitid[2]).removeAttr("checked");
                    }

                });
            });
            
            </SCRIPT>
            
            
        <div class='row'>  
        	<div class='span10 offset1'>                                      
                <table class='table table-hover table-striped table-bordered' id="selectipl">
				<?php if($unitid[0] != '') { ?>
                <table class='table table-hover table-striped table-bordered scrolls' id="selectipl">
				<?php } ?>
                    <thead class='tableHeadText'>
                        <tr>
							<!--Scroll bar using codeing Changed by chandru start line -->
                            <th style="width:306px;">IPL Unit</th>
                            <th style="width:233px;">Tools</th>
                            <th style="width:233px;">Grade</th>  
                            <th style="width:207px;">Diagnostic Tests</th>
                           
							<!--Scroll bar using codeing Changed by chandru end line -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    
                    if($unitid[0] != '') {
						for($i=0;$i<sizeof($unitid);$i++)
						{
                                                    $unitdiagflag=$ObjDB->SelectSingleValueInt("SELECT fld_diagnostic_flag FROM itc_class_sigmath_unitdiagnostictest_mapping where fld_sigmath_id='".$sid."' AND fld_unit_id='".$unitid[$i]."'");
                                                    ?>
                        	<tr onclick="fn_showhidelesson(<?php echo $unitid[$i];?>)" name="0" id="unit_<?php echo $unitid[$i];?>">
                                <td colspan="3" style="font-weight:bold;"><?php echo $ObjDB->SelectSingleValue("SELECT fld_unit_name FROM itc_unit_master WHERE fld_id='".$unitid[$i]."'");?></td>
                                <td><input type="checkbox" <?php if($unitdiagflag==1 or $sid==0){ echo 'checked="checked"';}?> class="selectallunit" id="<?php echo "unitsel_".$unitid[$i];?>" value="<?php echo $unitid[$i];?>"></td>
                            </tr>
                        <?php 	
							if($sid==0)
								$extqry = '1 AS fld_flag';
							else
								$extqry = "(CASE WHEN a.fld_id = (SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_lesson_id=a.fld_id AND fld_sigmath_id='".$sid."' 
											AND fld_flag='1') THEN 1 END) AS fld_flag";
								$lessqry = $ObjDB->QueryObject("SELECT w.* FROM (SELECT a.fld_id AS lessonid, a.fld_ipl_points AS points, CONCAT(a.fld_ipl_name,' ',c.fld_version) 
																AS lessonname, d.fld_order AS orders, e.fld_access AS diagflag, ".$extqry." 
																FROM itc_ipl_master AS a LEFT JOIN itc_license_cul_mapping AS b ON a.fld_id = b.fld_lesson_id 
																LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=a.fld_id 
																LEFT JOIN itc_class_sigmath_lesson_mapping AS d ON d.fld_lesson_id=a.fld_id AND d.fld_sigmath_id='".$sid."' 
																AND d.fld_flag='1'
																LEFT JOIN itc_diag_question_mapping AS e ON e.fld_lesson_id=a.fld_id
																WHERE b.fld_license_id='".$licenseid."' AND a.fld_unit_id ='".$unitid[$i]."' AND b.fld_active='1' 
																AND a.fld_delstatus='0' AND c.fld_zip_type='1' AND c.fld_delstatus='0' AND e.fld_delstatus='0'
																GROUP BY a.fld_id) AS w 
																ORDER BY CASE WHEN w.orders IS NULL THEN 99999 END, w.orders ");	
							
							// for select existing schedules	
							$fld_grade=0;
							$checksch = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_sigmath_grade WHERE fld_schedule_id='".$sid."'");
							if($checksch==0)
								$fld_grade=1;					
							while($res=$lessqry->fetch_assoc()){ 
								extract($res);
								if($sid!=0){																		
                                   $gradeqry = $ObjDB->QueryObject("SELECT fld_grade, fld_points as points,fld_flag FROM itc_class_sigmath_grade WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$lessonid."'  LIMIT 0,1");//AND fld_flag=1 //new line
									if($gradeqry->num_rows>0)
									extract($gradeqry->fetch_assoc());
                                                                        
                                                                        $lessondiagflag=$ObjDB->SelectSingleValueInt("SELECT fld_diagnostic_flag FROM itc_class_sigmath_lessondiagnostictest_mapping where fld_sigmath_id='".$sid."' AND fld_lesson_id='".$lessonid."'");
								}
								?>
                            	<tr id="<?php echo $unitid[$i];?>">
                                    <td style="padding-left:100px;"><?php echo $lessonname; ?></td>                            
                                    <td>
                                        <div id="up_<?php echo $i+1;?>" class="synbtn-promote <?php if($diagflag=="" or $diagflag==0){?>dim<?php }?>" style="float:left"></div>
                                        <div id="down_<?php echo $i+1;?>" class="synbtn-demote <?php if($diagflag=="" or $diagflag==0){?>dim<?php }?>" style="float:left"></div>
                                            <input type="checkbox" class="ipl less_<?php echo $lessonid;?>" <?php if($fld_flag==1 and ($diagflag!='' and $diagflag!=0)){ echo 'checked="checked"';}?> id="ipl_<?php echo $lessonid;?>" value="<?php echo $lessonid; ?>"   <?php if($diagflag=='' or $diagflag==0){?> class="dim" <?php } ?>/><!-- new line -->
                                    </td>
                                    <td>                                    	
                                   		<input type="checkbox" <?php if($fld_grade==1 or $sid==0){ echo 'checked="checked"';}?> id="grade_<?php echo $lessonid;?>" value="<?php echo $lessonid; ?>" />                                       
                                        <input type="text" id="gradevalue_<?php echo $lessonid; ?>" name="<?php echo $lessonid;?>" onkeyup="ChkValidChar(this.id);" value="<?php echo $points; ?>" style="width:20%" />
                                    </td>
                                    <td><input type="checkbox" class="case unitselect_<?php echo $unitid[$i]; ?>" <?php if($lessondiagflag==1 or $sid==0){ echo 'checked="checked"';}?> id="diagtest_<?php echo $lessonid."_".$unitid[$i];?>" value="<?php echo $lessonid."~".$unitid[$i]; ?>" /></td>
                                </tr>
							<?php 	
                                                      
							}
                                                        
                    //Math Connections
                                     $points1=100;                   
                                    if($sid!=0){																		
                                            $fld_flag=0;
                                            $points1=100;
                                            $fld_mgrade==1;
                                            $mathgradeqry = $ObjDB->QueryObject("SELECT  fld_mgrade, fld_mpoints as points1,fld_flag FROM itc_class_sigmath_grademapping WHERE fld_unit_id='$unitid[$i]' AND fld_schedule_id='".$sid."'  LIMIT 0,1");
                                            if($mathgradeqry->num_rows>0)
                                            extract($mathgradeqry->fetch_assoc());
                                            }
                                        if($unitid[$i]!=$cal && $unitid[$i]!=$graphcal && $unitid[$i]!=$orient  )//Please remove the option of a Math Connection from these three units.1.Orientation,2.Calculators I,3.Graphing Calculators
                                        {
                                      ?>
                            <tr id="<?php echo $unitid[$i]; ?>">
                                <td style="padding-left:100px;"><?php echo "Math Connection";?></td>                            
                                <td>
                                    <div id="mup_<?php echo $i+1;?>" class="synbtn-promote dim" style="float:left"></div>
                                    <div id="mdown_<?php echo $i+1;?>" class="synbtn-demote dim" style="float:left"></div>
                                    <input type="checkbox" class="mipl mat_<?php echo $unitid[$i];?>" <?php if($fld_flag==1){ echo 'checked="checked"';}?> id="mipl_<?php echo $unitid[$i];?>" value="<?php echo $unitid[$i]; ?>" <?php if($diagflag=='' or $diagflag==0){?> class="dim" <?php } ?>  />
                                        <input type="hidden" id="unit_<?php echo $unitid[$i]; ?>" value="" /> 
                                </td>
                                <td>                                    	
                                    <input type="checkbox" <?php if($fld_mgrade==1 or $sid==0){ echo 'checked="checked"';}?> id="mgrade_<?php echo $unitid[$i];?>" value="<?php echo $unitid[$i]; ?>" />                                       
                                    <input type="text" id="mgradevalue_<?php echo $unitid[$i]; ?>" name="<?php echo $unitid[$i];?>" onkeyup="ChkValidChar(this.id);" value="<?php echo $points1; ?>" style="width:20%" />
                                </td>
                                <td></td>
                            </tr>
                                            <?php  //new line                         
                                        } //new line     
                                            ?>
                            <script>								
								$(document).ready(function(){
									loads<?php echo $i+1;?>();
									  
									$("#up_<?php echo $i+1;?>,#down_<?php echo $i+1;?>").click(function(){
										var row = $(this).parents("tr:first");  
										
										if ($(this).is("#up_<?php echo $i+1;?>") ) {
											var row1 =$(this).parents("tr:first").attr('id');
											var row2 =$(this).parents("tr:first").attr('id');
											$(this).parents("tr:first").attr('id',row2);
											$(this).parents("tr:first").attr('id',row1);
											var td1 =$(this).parents("tr:first").children('td').html();
											var td2 =$(this).parents("tr:first").children('td').html();
											$(this).parents("tr:first").children('td:first').html(td2);
											$(this).parents("tr:first").children('td:first').html(td1);
											row.insertBefore(row.prev());
										} else {
											var row1 =$(this).parents("tr:first").attr('id');
											var row2 =$(this).parents("tr:first").attr('id');
											$(this).parents("tr:first").attr('id',row2);
											$(this).parents("tr:first").attr('id',row1);
											var td1 =$(this).parents("tr:first").children('td').html();
											var td2 =$(this).parents("tr:first").children('td').html();
											$(this).parents("tr:first").children('td:first').html(td2);
											$(this).parents("tr:first").children('td:first').html(td1);						
											row.insertAfter(row.next());
										} 
										               
										loads<?php echo $i+1;?>();	
									});
									function loads<?php echo $i+1;?>()
									{					
										$('div#up_<?php echo $i+1;?>').each(function(index, element){
											 if(index==0){
												$(this).addClass('dim');
											 }
											 else {
												$(this).removeClass('dim');
											 }
										 });
										
										var total = $('div#down_<?php echo $i+1;?>').length;	 
										$('div#down_<?php echo $i+1;?>').each(function(index, element){
											if(index==total-1){
												$(this).addClass('dim');
											}
											else {
												$(this).removeClass('dim');
											}
										});	 
									}
								});
							</script>
                            <?php 
					$m++;	}
						}
 else {?>
     <tr>
         <td colspan="4"></td>
     </tr><?php
 }
					?>
                    </tbody>
                </table>  
                <script type="text/javascript" language="javascript">					
					//Function to enter only numbers in textbox
					$("input[id^=gradevalue_]").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}						
					});
					
					//Function to set the max & min values for the textbox
					String.prototype.startsWith = function (str) {
						return (this.indexOf(str) === 0);
					}
					function ChkValidChar(id) {						
						var txtbx = $('#'+id).val();
						var nexttxtbx = 100;						
						if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
						{
							$('#'+id).val('');
							$('#grade_'+$('#'+id).attr('name')).removeAttr('checked');
						}
						else if(txtbx==''){							
							$('#grade_'+$('#'+id).attr('name')).removeAttr('checked');
						}
						else{
							$('#grade_'+$('#'+id).attr('name')).attr('checked','checked');
						}
					}
                                        //math Connection
                                        function ChkValidChar(id) {
						var txtbx = $('#'+id).val();
						var nexttxtbx = 100;
                                                if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
                                                    {
                                                           $('#'+id).val('');
                                                           $('#mgrade_'+$('#'+id).attr('name')).removeAttr('checked');
                                                           $('#mipl_'+$('#'+id).attr('name')).removeAttr('checked');
                                                    }
                                                    else if(txtbx==''){							
                                                            $('#mgrade_'+$('#'+id).attr('name')).removeAttr('checked');
                                                            $('#mipl_'+$('#'+id).attr('name')).removeAttr('checked');
                                                     }
                                                    else{
                                                            $('#mgrade_'+$('#'+id).attr('name')).attr('checked','checked');
                                                            $('#mipl_'+$('#'+id).attr('name')).attr('checked','checked');
                                                    }
                                           }
                                           
                                    //When a teacher unchecks the associated IPL Unit Math Connection, the related Grade check box should automatically uncheck.  
                                    //IPL
                                        $(".ipl").click(function(e){  
                                          var id=this.id;
                                          //alert(id);
                                          var ipl1 = id.split("_");
                                          //alert(ipl1[1]);
                                          if($('#ipl_'+ipl1[1]).is(':checked')){
                                              var tmpgrade=1;
                                          }
                                          else{
                                              var tmpgrade=0;
                                          }                                          
                                          if(tmpgrade == 0){
                                              $('#grade_'+ipl1[1]).removeAttr('checked');
                                          }
                                          else{                                             
                                              $('#grade_'+ipl1[1]).attr('checked','checked');
                                          }
                                        });

                                        //Math Connection
                                    $(".mipl").click(function(e){  
                                          var id=this.id;                                          
                                          var milp1 = id.split("_");                                          
                                          if($('#mipl_'+milp1[1]).is(':checked')){
                                              var tmpgrade=1;
                                          }
                                          else{
                                              var tmpgrade=0;
                                          }                                           
                                          
                                          if(tmpgrade == 0){
                                              $('#mgrade_'+milp1[1]).removeAttr('checked');
                                          }
                                          else{                                            
                                              $('#mgrade_'+milp1[1]).attr('checked','checked');
                                          }
                                    });
				</script>

            </div>            
        </div>
         <?php 
			if($sflag==1){
				$sid=0;
			}
		?>     
        <div class="row rowspacer">
            <div class="tRight">
                <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Save Schedule" onClick="fn_saveschedule(<?php echo $sid; ?>)" />
            </div>
        </div>
        <script>
			function fn_showhidelesson(uid){
				var flag = $('#unit_'+uid).attr('name');
				if(flag==0)
					$('#unit_'+uid).attr('name',1);
				else
					$('#unit_'+uid).attr('name',0);
				$("tr[id^="+uid+"_]").each(function()
				{
					if(flag==0)
						$(this).hide();
					else
						$(this).show();
				});
			}			
		</script>
		<!-- scroll bar using code created by chandru start line -->
		<style>
		table.scrolls tbody,
		table.scrolls thead 
		{ 
			display: block;
		}
		table.scrolls tbody 
		{
			height: 300px;
			overflow-y: auto;
			overflow-x: hidden;
		}
		.table-striped tbody > tr:nth-child(2n+1) > td, .table-striped tbody > tr:nth-child(2n+1) > th 
		{
			background-color: #f9f9f9;
			width: 250px;
		}
		</style>
		<!-- scroll bar using code created by chandru end line -->
        <?php 
	}
	
	@include("footer.php");