<?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-gradereports-gradeajax.php
		
	History: updated By mohan kumar .v 
 * For select all students and order changed from class->student->assignmet to  class->assignmet->student
	

*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Schedule Dropdown ---*/
if($oper=="showschedule" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';
        $preposttype = isset($method['preposttype']) ? $method['preposttype'] : '0'; /*****updated by mohan m******/
	?>
    Schedule
	<div class="selectbox">
		<input type="hidden" name="scheduleid" id="scheduleid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Select Schedule">
			<ul role="options" style="width:100%">
				<?php 
                                if($type!=7)
                                {
                                    if($preposttype=='1')  /*****updated by mohan m******/
                                    {
                                        $qry = $ObjDB->QueryObject("SELECT w.* FROM (
												(SELECT CONCAT(a.fld_schedule_name,' / ',(CASE WHEN a.fld_moduletype='1' THEN 'Module' 
												WHEN a.fld_moduletype='2' THEN 'MM' END)) AS schedulename, a.fld_id AS scheduleid, 
												(CASE WHEN a.fld_moduletype='1' THEN '1' WHEN a.fld_moduletype='2' THEN '4' END) AS typename 
												FROM itc_class_rotation_schedule_mastertemp as a
												LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
												ON b.fld_schedule_id=a.fld_id
												WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' group by scheduleid) 		
											UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / Dyad') AS schedulename, fld_id AS scheduleid, 2 AS typename 
												FROM itc_class_dyad_schedulemaster 
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')		
											UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / Triad') AS schedulename, fld_id AS scheduleid, 3 AS typename
												FROM itc_class_triad_schedulemaster 
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
											UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / WCA') AS schedulename, fld_id AS scheduleid, (CASE WHEN fld_moduletype='1' THEN '5' WHEN fld_moduletype='2' THEN '6' WHEN fld_moduletype='7' THEN '7' END) AS typename 
												FROM itc_class_indassesment_master 
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' AND fld_moduletype<>'17')
											UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / Expedition') AS schedulename, fld_id AS scheduleid, 15 AS typename 
												FROM itc_class_indasexpedition_master
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
											
											UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / Mission') AS schedulename, fld_id AS scheduleid, 18 AS typename 
												FROM itc_class_indasmission_master
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
													 UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / Expedition') AS schedulename, fld_id AS scheduleid, 19 AS typename 
												FROM itc_class_rotation_expschedule_mastertemp 
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
												 UNION ALL		
												(SELECT CONCAT(fld_schedule_name,' / Mod And Exp') AS schedulename, fld_id AS scheduleid, 20 AS typename 
												FROM itc_class_rotation_modexpschedule_mastertemp 
												WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
										) AS w 
										 ORDER BY w.typename, w.schedulename");
                                    }  /*****updated by mohan m******/
                                    else
                                    {
                                        $qry = $ObjDB->QueryObject("SELECT w.* FROM (
											(SELECT CONCAT(a.fld_schedule_name,' / ',(CASE WHEN a.fld_moduletype='1' THEN 'Module' 
                                                                                        WHEN a.fld_moduletype='2' THEN 'MM' END)) AS schedulename, a.fld_id AS scheduleid, 
                                                                                        (CASE WHEN a.fld_moduletype='1' THEN '1' WHEN a.fld_moduletype='2' THEN '4' END) AS typename 
                                                                                        FROM itc_class_rotation_schedule_mastertemp as a
                                                                                        LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
                                                                                        ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' group by scheduleid) 		
												UNION ALL		
											(SELECT CONCAT(fld_schedule_name,' / Dyad') AS schedulename, fld_id AS scheduleid, 2 AS typename 
											FROM itc_class_dyad_schedulemaster 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')		
												UNION ALL		
											(SELECT CONCAT(fld_schedule_name,' / Triad') AS schedulename, fld_id AS scheduleid, 3 AS typename
											FROM itc_class_triad_schedulemaster 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1')
                                                                                             UNION ALL		
											(SELECT CONCAT(fld_schedule_name,' / WCA') AS schedulename, fld_id AS scheduleid, (CASE WHEN fld_moduletype='1' THEN '5' WHEN fld_moduletype='2' THEN '6' WHEN fld_moduletype='7' THEN '7' END) AS typename 
											FROM itc_class_indassesment_master 
											WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1' AND fld_moduletype<>'17')
											 UNION ALL	
											 (SELECT CONCAT(a.fld_schedule_name,' / Mod Exp Sch') AS schedulename, a.fld_id AS scheduleid, 20 AS typename 
												FROM itc_class_rotation_modexpschedule_mastertemp as a
												LEFT JOIN itc_class_rotation_modexpschedule_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
												WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_flag='1' group by scheduleid)
										) AS w 
										 ORDER BY w.typename, w.schedulename");
                                    }
				
                                }
                                else
                                {
                                    $qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-15') AS scheduleid,null AS expschid, 15 AS typename 
                                                                        FROM itc_class_indasexpedition_master
                                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
														   	UNION ALL	
                                                                SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-19') AS scheduleid, fld_id AS expschid,  19 AS typename 
                                                                        FROM itc_class_rotation_expschedule_mastertemp 
                                                                        WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
															 UNION ALL
                                                                        SELECT a.fld_schedule_name AS schedulename, CONCAT(a.fld_id,'-20') AS scheduleid, a.fld_id AS expschid ,20 AS typename 
																		FROM itc_class_rotation_modexpschedule_mastertemp as a
																		WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0'
																		 AND a.fld_flag='1' group by scheduleid
                                                               ");
                                  
                                }
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						if($type==1)
						{
							$function = "fn_load_rotation(".$scheduleid.",".$typename.")";
						}
						else if($type==2)
						{
							if($preposttype=='1') 
							{
								$function = "$('#viewreportdiv').show(); $('#schtype').val(".$typename.");";							   
							}
							else
							{
								$function = "$('#viewreportdiv').show(); $('#schtype').val(".$typename.");";
							}
							
						}
						else if($type==7)
						{
							if($typename=='15')
							{
								$function = "$('#showstart').show(); fn_hide(); $('#schtype').val(".$typename.");";
							}
							else if($typename=='20')
							{
								$function = "fn_expschload_rotation(".$expschid.",".$typename.")";
							}
							else
							{
								$function = "fn_expschload_rotation(".$expschid.",".$typename.")";
							}

						}

						if($preposttype=='1') 
						{ 	?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid;?>" onclick="<?php echo $function;?>"><?php echo $schedulename; ?></a></li>
							<?php   
						}
						else
						{ 	?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid;?>" <?php if($typename<=4){?>onclick="<?php echo $function;?>"<?php }else{  if($typename=='15'){?> onclick="javascript:$('#uniddiv').html('<input type=hidden name=typeids id=typeids value=<?php echo $typename;?>>'); $('#rotationdiv').hide();$('#viewreportdiv').show(); $('#expschedule').val(<?php echo $typename;?>);"<?php }else if($typename=='19'){ ?> onclick="<?php echo $function;?>" <?php }else if($typename=='20'){ ?> onclick="<?php echo $function;?>" <?php }else{ ?> onclick="javascript:$('#uniddiv').html('<input type=hidden name=typeids id=typeids value=<?php echo $typename;?>>');$('#viewreportdiv').show();"  <?php } } ?> ><?php echo $schedulename; ?></a></li> <!-- onclick="<?php //echo $function;?>" -->
							<?php  
						}
					}
				} ?>      
			</ul>
		</div>
        
	</div>
	<?php
}

/*--- Load Rotation Dropdown ---*/
if($oper=="showrotation" and $oper != " " )
{
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';
	if($type==1 || $type==4)
	{
		$query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
					FROM itc_class_rotation_schedulegriddet 
					WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
					GROUP BY fld_rotation 
					ORDER BY fld_rotation";	
	}
	else if($type==2)
	{
		$query = "SELECT fld_rotation AS rotation, fld_rotation AS realrotation 
					FROM itc_class_dyad_schedulegriddet 
					WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
					GROUP BY fld_rotation 
					ORDER BY fld_rotation";
	}
	else if($type==3)
	{
		$query = "SELECT fld_rotation AS rotation, fld_rotation AS realrotation 
					FROM itc_class_triad_schedulegriddet 
					WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
					GROUP BY fld_rotation 
					ORDER BY fld_rotation";
	}
	else if($type==20)
	{
		$query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
					FROM itc_class_rotation_modexpschedulegriddet 
					WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
					GROUP BY fld_rotation 
					ORDER BY fld_rotation";	
	}
		
	?>
    <input type="hidden" name="typeids" id="typeids" value="<?php echo $type;?>" />
    Rotations
	<div>
    	<table style="width:100%">
        	<tr>
				<?php 
                $qry = $ObjDB->QueryObject($query);
                if($qry->num_rows>0){
                    $i = 1;
                    $j=1;
                    while($row = $qry->fetch_assoc())
                    {
                        extract($row);
                        $count = ($i % 4);
						?>
                        <td style="width:25%">
                        	<form id="frmrep" name="frmrep">
                            	<div class="field">
                                    <label class="checkbox" for="check_<?php echo $realrotation;?>" onclick="fn_checkrotation(this.id,<?php echo $realrotation;?>)" id="0" style="width:110px">
                                        <input name="<?php echo $realrotation;?>" id="check_<?php echo $realrotation;?>" value="1" type="checkbox" style="display:none;"/>
                                        <span></span><?php if($realrotation==0) { echo "	Orientation"; } else { echo "	Rotation ".$realrotation; }?>
                                    </label>
                                </div>
                            </form>
						</td>
                        <?php
                        if($count == 0)
                        {
							?>
                            </tr>
                            <tr>
							<?php
                        }
                        $i++;
                    }
                }
				?>
			</tr>
        </table>	
	</div>
	<?php
}


/*--- Load Exp Schedule Rotation Dropdown ---*/
if($oper=="showexpschrotation" and $oper!= " " )
{
    $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
    $type = isset($method['type']) ? $method['type'] : '';
    if($type==19)
    {
            $query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                            FROM itc_class_rotation_expschedulegriddet 
                            WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                GROUP BY fld_rotation  ORDER BY fld_rotation";	
    }
	else if($type==20)
	{
		 $query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                            FROM itc_class_rotation_modexpschedulegriddet
                            WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                GROUP BY fld_rotation  ORDER BY fld_rotation";	
		
	}
    ?>
    <input type="hidden" name="expschtypeids" id="expschtypeids" value="<?php echo $type;?>" />
    Rotations
    <div>
    	<table style="width:100%">
        	<tr>
                <?php 
                $qry = $ObjDB->QueryObject($query);
                if($qry->num_rows>0)
                {
                    $i = 1;
                    $j=1;
                    while($row = $qry->fetch_assoc())
                    {
                        extract($row);
                        $count = ($i % 4);
                        ?>
                        <td style="width:25%">
                        	<form id="frmrep" name="frmrep">
                            	<div class="field">
                                    <label class="checkbox" for="expcheck_<?php echo $realrotation;?>" onclick="fn_checkexpschrotation(this.id,<?php echo $realrotation;?>)" id="0" style="width:110px">
                                        <input name="<?php echo $realrotation;?>" id="expcheck_<?php echo $realrotation;?>" value="1" type="checkbox" style="display:none;"/>
                                        <span></span><?php if($realrotation==0) { echo "	Orientation"; } else { echo "	Rotation ".$realrotation; }?>
                                    </label>
                                </div>
                            </form>
                        </td>
                        <?php
                        if($count == 0)
                        {
                            ?>
                            </tr>
                            <tr>
                            <?php
                        }
                        $i++;
                    }
                }
                ?>
                            </tr>
        </table>
    </div>
    <?php
}
/*--- Load Exp Schedule Rotation Dropdown ---*/

/*--- Load Assignment Dropdown ---*/
if($oper=="showassignment" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';	
	
	?>
    Assignment
	<div class="selectbox">
		<input type="hidden" name="assignmentid" id="assignmentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Assignment">
			<ul role="options" style="width:100%">
				<?php 
                                
                           
				$qry = $ObjDB->QueryObject("SELECT 
													w . *
													FROM
													((SELECT 
														a.fld_id AS schduleid,
															c.fld_unit_id AS assignmentid,
															d.fld_unit_name AS assignmentname,
															0 AS typename
													FROM
														itc_class_sigmath_master AS a
													LEFT JOIN itc_class_sigmath_unit_mapping AS c ON a.fld_id = c.fld_sigmath_id
													LEFT JOIN itc_unit_master AS d ON c.fld_unit_id = d.fld_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'
                                                                            ) 
                                                    UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Module') AS assignmentname,
															1 AS typename
													FROM
														itc_class_rotation_schedulegriddet AS a
													LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_module_master AS c ON c.fld_id = a.fld_module_id
													LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND a.fld_type = '1'
															AND c.fld_delstatus = '0'
                                                                                AND d.fld_delstatus = '0') 
                                                        UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(b.fld_module_name, ' ', c.fld_version, ' / ', 'Dyad') AS assignmentname,
															2 AS typename
													FROM
														itc_class_dyad_schedulegriddet AS a
													LEFT JOIN itc_module_master AS b ON a.fld_module_id = b.fld_id
													LEFT JOIN itc_module_version_track AS c ON b.fld_id = c.fld_mod_id
																		LEFT JOIN itc_class_dyad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
													WHERE
														a.fld_class_id = '".$classid."'
															AND a.fld_rotation = '0'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND c.fld_delstatus = '0'
                                                                                    AND e.fld_delstatus = '0') 
                                                            UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(b.fld_module_name, ' ', c.fld_version, ' / ', 'Triad') AS assignmentname,
															3 AS typename
													FROM
														itc_class_triad_schedulegriddet AS a
													LEFT JOIN itc_module_master AS b ON a.fld_module_id = b.fld_id
													LEFT JOIN itc_module_version_track AS c ON b.fld_id = c.fld_mod_id
																		LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
													WHERE
														a.fld_class_id = '".$classid."'
															AND a.fld_rotation = '0'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND c.fld_delstatus = '0'
                                                                                        AND e.fld_delstatus = '0') 
                                                            UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_mathmodule_name, ' ', d.fld_version, ' / ', 'MM') AS assignmentname,
															4 AS typename
													FROM
														itc_class_rotation_schedulegriddet AS a
													LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_mathmodule_master AS c ON c.fld_id = a.fld_module_id
													LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_module_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND b.fld_moduletype = '2'
															AND c.fld_delstatus = '0'
                                                                            AND d.fld_delstatus = '0')
                                                            UNION ALL (SELECT 
														a.fld_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Ind Module') AS assignmentname,
								5 AS typename  
													FROM
														itc_class_indassesment_master AS a

													LEFT JOIN itc_module_master AS c ON a.fld_module_id = c.fld_id
													LEFT JOIN itc_module_version_track AS d ON c.fld_id = d.fld_mod_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'

															AND a.fld_moduletype = '1'
															AND c.fld_delstatus = '0'
                                                                                AND d.fld_delstatus = '0')
                                                                UNION ALL (SELECT 
														a.fld_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_mathmodule_name, ' ', d.fld_version, ' / ', 'Ind MM') AS assignmentname,
								6 AS typename 
													FROM
														itc_class_indassesment_master AS a

													LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id = c.fld_id
													LEFT JOIN itc_module_version_track AS d ON c.fld_module_id = d.fld_mod_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'

															AND a.fld_moduletype = '2'
															AND c.fld_delstatus = '0'
                                                                                    AND d.fld_delstatus = '0') 
                                                                UNION ALL (SELECT 
														a.fld_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Ind Quest') AS assignmentname,
								7 AS typename 
													FROM
														itc_class_indassesment_master AS a

													LEFT JOIN itc_module_master AS c ON a.fld_module_id = c.fld_id
													LEFT JOIN itc_module_version_track AS d ON c.fld_id = d.fld_mod_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'

															AND a.fld_moduletype = '7'
															AND c.fld_delstatus = '0'
                                                                            AND d.fld_delstatus = '0') 
                                                                UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_contentname, ' / ',' Custom Content') AS assignmentname,
															8 AS typename
													FROM
														itc_class_rotation_schedulegriddet AS a
													LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_customcontent_master AS c ON c.fld_id = a.fld_module_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND a.fld_type = '8'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND b.fld_moduletype = '1'
                                                                            AND c.fld_delstatus = '0') 
                                                                UNION ALL (SELECT 
                                                                                    a.fld_id AS schduleid,
                                                                                    a.fld_exp_id AS assignmentid,
                                                                                    CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Ind Expedition') AS assignmentname,
                                                                                    15 AS typename 
                                                                            FROM
                                                                            itc_class_indasexpedition_master AS a
                                                                            LEFT JOIN itc_exp_master AS c ON a.fld_exp_id = c.fld_id
                                                                            LEFT JOIN itc_exp_version_track AS d ON c.fld_id = d.fld_exp_id
                                                                            WHERE
                                                                            a.fld_class_id = '".$classid."'
                                                                            AND a.fld_flag = '1'
                                                                            AND a.fld_delstatus = '0'
                                                                            AND c.fld_delstatus = '0'
                                                                            AND d.fld_delstatus = '0') 
                                                                UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_expedition_id AS assignmentid,
                                                                                    CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition Sch') AS assignmentname,
															19 AS typename
													FROM
														itc_class_rotation_expschedulegriddet AS a
													LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_expedition_id
													LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
													WHERE
														a.fld_class_id = '".$classid."'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0') 
										UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
														a.fld_module_id AS assignmentid,
														CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition') AS assignmentname,
														20 AS typename
													FROM
														itc_class_rotation_modexpschedulegriddet AS a
													LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_module_id
													LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
													WHERE
														a.fld_class_id = '".$classid."'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1' AND a.fld_type='2'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0' )
										UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
														a.fld_module_id AS assignmentid,
														CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Module') AS assignmentname,
														21 AS typename
													FROM
														itc_class_rotation_modexpschedulegriddet AS a
													LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_module_master AS c ON c.fld_id = a.fld_module_id
													LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1' 
															AND a.fld_type = '1'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0')
										UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
															CONCAT(c.fld_contentname, ' / ',' Custom Content') AS assignmentname,
															22 AS typename
													FROM
														itc_class_rotation_modexpschedulegriddet AS a
													LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
													LEFT JOIN itc_customcontent_master AS c ON c.fld_id = a.fld_module_id
													WHERE
														a.fld_class_id = '".$classid."'

															AND a.fld_flag = '1'
															AND a.fld_type = '8'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND c.fld_delstatus = '0')	
                                                                UNION ALL (SELECT 
                                                                                    a.fld_id AS schduleid,
                                                                                    a.fld_mis_id AS assignmentid,
                                                                                    CONCAT(c.fld_mis_name, ' ', d.fld_version, ' / ', 'Ind Mission') AS assignmentname,
                                                                                    18 AS typename 
                                                                            FROM
                                                                            itc_class_indasmission_master AS a
                                                                            LEFT JOIN itc_mission_master AS c ON a.fld_mis_id = c.fld_id
                                                                            LEFT JOIN itc_mission_version_track AS d ON c.fld_id = d.fld_mis_id
                                                                            WHERE
                                                                            a.fld_class_id = '".$classid."'
                                                                            AND a.fld_flag = '1'
                                                                            AND a.fld_delstatus = '0'
                                                                            AND c.fld_delstatus = '0'
                                                                            AND d.fld_delstatus = '0') 
                                                                UNION ALL (SELECT 
                                                                            a.fld_schedule_id AS schduleid,
                                                                                    a.fld_mission_id AS assignmentid,
                                                                                    CONCAT(c.fld_mis_name, ' ', d.fld_version, ' / ', 'Mission Sch') AS assignmentname,
                                                                                    23 AS typename
                                                                            FROM itc_class_rotation_mission_schedulegriddet AS a
                                                                            LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id = b.fld_id
                                                                            LEFT JOIN itc_mission_master AS c ON c.fld_id = a.fld_mission_id
                                                                            LEFT JOIN itc_mission_version_track AS d ON d.fld_mis_id = c.fld_id
                                                                            WHERE
                                                                            a.fld_class_id = '".$classid."'
                                                                            AND a.fld_flag = '1'
                                                                            AND b.fld_delstatus = '0'
                                                                            AND b.fld_flag = '1'
                                                                            AND c.fld_delstatus = '0'
                                                                            AND d.fld_delstatus = '0') 
																UNION ALL (SELECT a.fld_id AS scheduleid,b.fld_id AS assignment,
																			CONCAT(b.fld_contentname,' Ind Custom Content') AS assigmentname, 17 AS typename
																			FROM itc_class_indassesment_master AS a 
																			LEFT JOIN itc_customcontent_master AS b ON a.fld_module_id=b.fld_id 
																			WHERE a.fld_class_id='".$classid."' AND a.fld_flag=1 AND b.fld_delstatus='0' 
																			ORDER BY a.fld_startdate)	
															) AS w
													GROUP BY typename , assignmentname");

				if($qry->num_rows>0){
                                    
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $schduleid."~".$assignmentid."~".$typename;?>" onclick="$('#studentdiv').show(); fn_showstudent(2,<?php echo $classid;?>);"><?php echo $assignmentname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- Load Student Dropdown ---*/
if($oper=="showstudent" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';
	?>
    Student
	<div class="selectbox">
		<input type="hidden" name="studentid" id="studentid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Student">
			<ul role="options" style="width:100%">
				<?php if($type==1 or $type==3) {?>
            	<li><a tabindex="-1" href="#" data-option="0" onclick="<?php if($type==1) { ?>$('#showstart').show(); fn_hide();<?php } else if($type==3) { ?>$('#stupassdiv').show(); $('#viewreportdiv').show();<?php } ?>">All Students</a></li>
				<?php }
				$qry = $ObjDB->QueryObject("SELECT CONCAT(a.fld_lname, ' ', a.fld_fname) AS studentname, a.fld_id AS studentid 
											FROM itc_user_master AS a 
											LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
											WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
											AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
											ORDER BY studentname");
				if($qry->num_rows>0){
					//Select All Students                                   
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $studentid;?>" onclick="<?php if($type==1) {?>$('#showstart').show(); fn_hide();<?php } else if($type==3) {?>$('#stupassdiv').show(); $('#viewreportdiv').show();<?php } else if($type==2) {?> $('#stupassdiv').show(); $('#viewreportdiv').show();<?php }?>"><?php echo $studentname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}



/*--- District/Pitsco ---*/
if($oper=="showteachers" and $oper != " " )
{
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '';
	$individualid = isset($method['individualid']) ? $method['individualid'] : '';
	$val = isset($method['val']) ? $method['val'] : '';
	?>
	Teachers
	<div class="selectbox">
		<input type="hidden" name="teacherid" id="teacherid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Teacher</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Teacher">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT CONCAT(fld_fname,' ',fld_lname) AS teachername, fld_id AS teacherid 
											FROM itc_user_master 
											WHERE fld_activestatus='1' AND fld_delstatus='0' AND fld_profile_id IN (7,8,9) 
											AND fld_school_id='".$schoolid."' AND fld_user_id='".$individualid."' 
											ORDER BY fld_lname");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $teacherid;?>" onclick="fn_showclass(<?php echo $teacherid;?>,<?php echo $val;?>);"><?php echo $teachername; ?></a></li>
						<?php
						
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

if($oper=="showclass" and $oper != " " )
{
	$teacherid = isset($method['teacherid']) ? $method['teacherid'] : '';
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '0';
	$indid = isset($method['indid']) ? $method['indid'] : '0';
	$val = isset($method['val']) ? $method['val'] : '0';
	?>
	Class 
	<dl class='field row'>
		<div class="selectbox">
			<input type="hidden" name="classid" id="classid" value="">
			<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
				<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
				<b class="caret1"></b>
			</a>
			<div class="selectbox-options">
				<input type="text" class="selectbox-filter" placeholder="Search Class">
				<ul role="options" style="width:100%">
					<?php 
					$qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
												FROM itc_class_master 
												WHERE fld_delstatus='0' AND (fld_created_by='".$teacherid."' 
												OR fld_id IN (SELECT fld_class_id 
																FROM itc_class_teacher_mapping 
																WHERE fld_teacher_id='".$teacherid."' AND fld_flag='1')) 
												ORDER BY fld_class_name");
					if($qry->num_rows>0){
						while($row = $qry->fetch_assoc())
						{
							extract($row);
							if($val == 1)
								$function = "fn_load_schedule(1,".$classid.")";
							if($val == 2)
								$function = "$('#studentdiv').show(); fn_showstudent(1,".$classid.")";
							if($val == 3)
								$function = "fn_hide(); $('#showstart').show();";
							if($val == 4)
								$function = "$('#studentdiv').show(); fn_showstudent(2,".$classid.")";
							if($val == 5)
								$function = "$('#studentdiv').show(); fn_showstudent(3,".$classid.")";
							if($val == 6)
								$function = "fn_load_schedule(2,".$classid.",1)"; /****Updated by mohan m******/
                                                        if($val == 7)
								$function = "fn_load_schedule(7,".$classid.")";
							?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="<?php echo $function;?>"><?php echo $classname;?></a></li>
							<?php
						}
					}?>      
				</ul>
			</div>
		</div> 
	</dl>
	<?php
}

if($oper=="showstudentnew" and $oper != "")
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	?>
    Student
	<div class="selectbox">
		<input type="hidden" name="studentidnew" id="studentidnew" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Student">
			<ul role="options" style="width:100%">
				<!--<li><a tabindex="-1" href="#" data-option="0" onclick="fn_showscdulenew(<?php //echo $classid.",0";?>)">All Students</a></li>-->
				<?php 
				$qry = $ObjDB->QueryObject("SELECT CONCAT(a.fld_lname, ' ', a.fld_fname) AS studentname, a.fld_id AS studentid 
											FROM itc_user_master AS a 
											LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
											WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
											AND b.fld_class_id='".$classid."' AND b.fld_flag='1' 
											ORDER BY studentname");
				if($qry->num_rows>0){
					//Select All Students
                                     //Select All Students
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $studentid;?>" onclick="fn_showscdulenew(<?php echo $classid.",".$studentid;?>)"><?php echo $studentname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}
if($oper=="showscdulenew" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$studentid = isset($method['studentid']) ? $method['studentid'] : '';
	?> 
	 <script language="javascript" type="text/javascript">
    					$(function() {
							$('#testrailvisible15').slimscroll({
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
							$('#testrailvisible16').slimscroll({
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
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								items: "div[class='draglinkleft']",
								receive: function(event, ui) {
									$("div[class=draglinkright]").each(function(){ 
										if($(this).parent().attr('id')=='list3'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'rotational');
										}
									});
								}
							});
                        
							$( "#list4" ).sortable({
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								receive: function(event, ui) {
									$("div[class=draglinkleft]").each(function(){ 
										if($(this).parent().attr('id')=='list4'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'rotational');
										}
									});
								}
							});
                        });
                    
      		 </script>
            <div class='row rowspacer' >
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                   
                    $qryass = $ObjDB->QueryObject("SELECT 
											w . *
											FROM
											((SELECT 
													a.fld_id AS schduleid,
															c.fld_unit_id AS assignmentid,
																	fn_shortname(d.fld_unit_name, 1) as shortname,
															d.fld_unit_name AS assignmentname,
															0 AS typename
											FROM
													itc_class_sigmath_master AS a
															LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id
											LEFT JOIN itc_class_sigmath_unit_mapping AS c ON a.fld_id = c.fld_sigmath_id
											LEFT JOIN itc_unit_master AS d ON c.fld_unit_id = d.fld_id
											WHERE
													a.fld_class_id = '".$classid."'
																	AND b.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
																	AND a.fld_delstatus = '0') UNION ALL (SELECT 
													a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Module', ' / ', b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Module', ' / ', b.fld_schedule_name) AS assignmentname,
															1 AS typename
												FROM
														itc_class_rotation_schedulegriddet AS a
												LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
												LEFT JOIN itc_module_master AS c ON c.fld_id = a.fld_module_id
												LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_id
												WHERE
														a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
																AND a.fld_flag = '1'
																AND b.fld_delstatus = '0'
																AND b.fld_flag = '1'
																AND a.fld_type = '1'
																AND c.fld_delstatus = '0'
																	AND d.fld_delstatus = '0') UNION ALL (SELECT 
													a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(b.fld_module_name, ' ', c.fld_version, ' / ', 'Dyad',' / ',e.fld_schedule_name),2) AS shortname,
																	CONCAT(b.fld_module_name, ' ', c.fld_version, ' / ', 'Dyad',' / ',e.fld_schedule_name) AS assignmentname,
															2 AS typename
													FROM
															itc_class_dyad_schedulegriddet AS a
													LEFT JOIN itc_module_master AS b ON a.fld_module_id = b.fld_id
													LEFT JOIN itc_module_version_track AS c ON b.fld_id = c.fld_mod_id
																							LEFT JOIN itc_class_dyad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
													WHERE
															a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
																	AND a.fld_rotation = '0'
																	AND a.fld_flag = '1'
																	AND b.fld_delstatus = '0'
																	AND c.fld_delstatus = '0'
																	AND e.fld_delstatus = '0') UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
																a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(b.fld_module_name, ' ', c.fld_version, ' / ', 'Triad', ' / ', e.fld_schedule_name),2) AS shortname,
																	CONCAT(b.fld_module_name, ' ', c.fld_version, ' / ', 'Triad', ' / ', e.fld_schedule_name) AS assignmentname,
																		3 AS typename
														FROM
																itc_class_triad_schedulegriddet AS a
														LEFT JOIN itc_module_master AS b ON a.fld_module_id = b.fld_id
														LEFT JOIN itc_module_version_track AS c ON b.fld_id = c.fld_mod_id
																								LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
														WHERE
																a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
																		AND a.fld_rotation = '0'
																		AND a.fld_flag = '1'
																		AND b.fld_delstatus = '0'
																		AND c.fld_delstatus = '0'
																	AND e.fld_delstatus = '0') UNION ALL (SELECT 
														a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_mathmodule_name, ' ', d.fld_version, ' / ', 'MM', ' / ', b.fld_schedule_name),2) AS shortname, 
																	CONCAT(c.fld_mathmodule_name, ' ', d.fld_version, ' / ', 'MM', ' / ', b.fld_schedule_name) AS assignmentname,
																4 AS typename
														FROM
																itc_class_rotation_schedulegriddet AS a
														LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
														LEFT JOIN itc_mathmodule_master AS c ON c.fld_id = a.fld_module_id
														LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_module_id
														WHERE
														a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND b.fld_moduletype = '2'
															AND c.fld_delstatus = '0'
																	AND d.fld_delstatus = '0') UNION ALL (SELECT 
														a.fld_id AS schduleid,
																a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Ind Module',' / ',a.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Ind Module',' / ',a.fld_schedule_name) AS assignmentname,
																		5 AS typename  
														FROM
																itc_class_indassesment_master AS a
															LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
														LEFT JOIN itc_module_master AS c ON a.fld_module_id = c.fld_id
														LEFT JOIN itc_module_version_track AS d ON c.fld_id = d.fld_mod_id
														WHERE
														a.fld_class_id = '".$classid."'
																	AND b.fld_student_id = '".$studentid."'
																AND a.fld_flag = '1'
																AND a.fld_delstatus = '0'
																AND a.fld_moduletype = '1'
																AND c.fld_delstatus = '0'
																AND d.fld_delstatus = '0')
												UNION ALL (SELECT 
															a.fld_id AS schduleid,
																	a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_mathmodule_name, ' ', d.fld_version, ' / ', 'Ind MM',' / ',a.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_mathmodule_name, ' ', d.fld_version, ' / ', 'Ind MM',' / ',a.fld_schedule_name) AS assignmentname,
															6 AS typename 
															FROM
																	itc_class_indassesment_master AS a
															LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
															LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id = c.fld_id
															LEFT JOIN itc_module_version_track AS d ON c.fld_module_id = d.fld_mod_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND b.fld_student_id = '".$studentid."'
																	AND a.fld_flag = '1'
																	AND a.fld_delstatus = '0'

																	AND a.fld_moduletype = '2'
																	AND c.fld_delstatus = '0'
																	AND d.fld_delstatus = '0') 
												UNION ALL (SELECT 
																a.fld_id AS schduleid,
																		a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Ind Quest',' / ',a.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Ind Quest',' / ',a.fld_schedule_name) AS assignmentname,
																		7 AS typename 
															FROM
															itc_class_indassesment_master AS a
															LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id
															LEFT JOIN itc_module_master AS c ON a.fld_module_id = c.fld_id
															LEFT JOIN itc_module_version_track AS d ON c.fld_id = d.fld_mod_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND b.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'

															AND a.fld_moduletype = '7'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0') 
												UNION ALL (SELECT 
															a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_contentname, ' / ', ' Custom Content',' / ',b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_contentname, ' / ', ' Custom Content',' / ',b.fld_schedule_name) AS assignmentname,
															8 AS typename
															FROM
																	itc_class_rotation_schedulegriddet AS a
															LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
															LEFT JOIN itc_customcontent_master AS c ON c.fld_id = a.fld_module_id
															WHERE
																	a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND a.fld_type = '8'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND b.fld_moduletype = '1'
															AND c.fld_delstatus = '0') 
												UNION ALL (SELECT 
																	a.fld_id AS schduleid,
																	a.fld_exp_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Ind Expedition',' / ',a.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Ind Expedition',' / ',a.fld_schedule_name) AS assignmentname,
																	15 AS typename 
															FROM
															itc_class_indasexpedition_master AS a
															LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_schedule_id
															LEFT JOIN itc_exp_master AS c ON a.fld_exp_id = c.fld_id
															LEFT JOIN itc_exp_version_track AS d ON c.fld_id = d.fld_exp_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND b.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0') 
												UNION ALL (SELECT 
															a.fld_schedule_id AS schduleid,
																	a.fld_expedition_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition Sch',' / ',b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition Sch',' / ',b.fld_schedule_name) AS assignmentname,
																	19 AS typename
															FROM
																	itc_class_rotation_expschedulegriddet AS a
															LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
															LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_expedition_id
															LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0') 
												UNION ALL (SELECT 
																	a.fld_schedule_id AS schduleid,
																	a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition',' / ',b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition',' / ',b.fld_schedule_name) AS assignmentname,
																	20 AS typename
															FROM
																	itc_class_rotation_modexpschedulegriddet AS a
															LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
															LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_module_id
															LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
															WHERE
																	a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
																			AND a.fld_flag = '1'
																			AND b.fld_delstatus = '0'
																			AND b.fld_flag = '1' AND a.fld_type='2'
																			AND c.fld_delstatus = '0'
																			AND d.fld_delstatus = '0' )
												UNION ALL (SELECT 
															a.fld_schedule_id AS schduleid,
															a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Module',' / ',b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_module_name, ' ', d.fld_version, ' / ', 'Module',' / ',b.fld_schedule_name) AS assignmentname,
															21 AS typename
															FROM
																	itc_class_rotation_modexpschedulegriddet AS a
															LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
															LEFT JOIN itc_module_master AS c ON c.fld_id = a.fld_module_id
															LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id = c.fld_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1' 
															AND a.fld_type = '1'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0')
												UNION ALL (SELECT 
															a.fld_schedule_id AS schduleid,
																	a.fld_module_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_contentname, ' / ', ' Custom Content',' / ',b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_contentname, ' / ', ' Custom Content',' / ',b.fld_schedule_name) AS assignmentname,
																	22 AS typename
																		FROM
																	itc_class_rotation_modexpschedulegriddet AS a
															LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
															LEFT JOIN itc_customcontent_master AS c ON c.fld_id = a.fld_module_id
															WHERE
																	a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND a.fld_type = '8'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND c.fld_delstatus = '0')	
												UNION ALL (SELECT 
																	a.fld_id AS schduleid,
																	a.fld_mis_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_mis_name, ' ', d.fld_version, ' / ', 'Ind Mission',' / ',a.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_mis_name, ' ', d.fld_version, ' / ', 'Ind Mission',' / ',a.fld_schedule_name) AS assignmentname,
																	18 AS typename 
															FROM
															itc_class_indasmission_master AS a
															LEFT JOIN itc_class_mission_student_mapping AS b ON a.fld_id=b.fld_schedule_id
															LEFT JOIN itc_mission_master AS c ON a.fld_mis_id = c.fld_id
															LEFT JOIN itc_mission_version_track AS d ON c.fld_id = d.fld_mis_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND b.fld_student_id= '".$studentid."'
															AND a.fld_flag = '1'
															AND a.fld_delstatus = '0'
															AND c.fld_delstatus = '0'
															AND d.fld_delstatus = '0') 
												UNION ALL (SELECT 
															a.fld_schedule_id AS schduleid,
																	a.fld_mission_id AS assignmentid,
																	fn_shortname(CONCAT(c.fld_mis_name, ' ', d.fld_version, ' / ', 'Mission Sch',' / ',b.fld_schedule_name),2) AS shortname,
																	CONCAT(c.fld_mis_name, ' ', d.fld_version, ' / ', 'Mission Sch',' / ',b.fld_schedule_name) AS assignmentname,
																	23 AS typename
															FROM itc_class_rotation_mission_schedulegriddet AS a
															LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id = b.fld_id
															LEFT JOIN itc_mission_master AS c ON c.fld_id = a.fld_mission_id
															LEFT JOIN itc_mission_version_track AS d ON d.fld_mis_id = c.fld_id
															WHERE
															a.fld_class_id = '".$classid."'
																	AND a.fld_student_id = '".$studentid."'
															AND a.fld_flag = '1'
															AND b.fld_delstatus = '0'
															AND b.fld_flag = '1'
															AND c.fld_delstatus = '0'
																	AND d.fld_delstatus = '0') UNION ALL (SELECT 
																a.fld_id AS scheduleid,
																	b.fld_id AS assignment,
																	fn_shortname(CONCAT(b.fld_contentname, ' Ind Custom Content',' / ',a.fld_schedule_name),2) AS shortname,
																	CONCAT(b.fld_contentname, ' Ind Custom Content',' / ',a.fld_schedule_name) AS assigmentname,
																	17 AS typename
															FROM
																itc_class_indassesment_master AS a
															LEFT JOIN itc_customcontent_master AS b ON a.fld_module_id = b.fld_id
															LEFT JOIN itc_class_indassesment_student_mapping AS c ON a.fld_id=c.fld_schedule_id
															WHERE
																a.fld_class_id = '".$classid."'
																	AND c.fld_student_id = '".$studentid."'
																	AND a.fld_flag = 1
																	AND b.fld_delstatus = '0'
															ORDER BY a.fld_startdate)) AS w
											GROUP BY typename , assignmentname");
                   
                        ?>
                        <div class="dragtitle">Assignments available</div>
                        <div class="dragWell" id="testrailvisible15" >
                        <div class="draglinkleftSearch" id="s_list3" >
						   <dl class='field row'>
								<dt class='text'>
									<input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
								</dt>
							</dl>
						</div>
                            <div id="list3" class="dragleftinner droptrue1">
									<?php 
									if($qryass->num_rows > 0){													
									  while($rowsass = $qryass->fetch_assoc()){
										  extract($rowsass);
											?>
											<div class="draglinkleft" id="list3_<?php echo $schduleid."-".$assignmentid."-".$typename; ?>" title="<?php echo $assignmentname; ?>">
												<div class="dragItemLable" id="<?php echo $schduleid."-".$assignmentid."-".$typename; ?>"><?php echo $shortname; ?></div>
												<div class="clickable" id="clck_<?php echo $schduleid."-".$assignmentid."-".$typename; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $schduleid."-".$assignmentid."-".$typename;; ?>',0);"></div>
											</div> 
                                        <?php }
                                            }	?>
                            </div>
                        </div>
                        <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,0);">add all Assignments</div>
                    </div>
                </div>
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                               
                        ?>
                        <div class="dragtitle">Selected Assignments</div>
                        <div class="dragWell" id="testrailvisible16">
                            <div id="list4" class="dragleftinner droptrue1">
                                <?php 
								 if($qryass->num_rows > 0){													
								  while($rowsass = $qryass->fetch_assoc()){
									  extract($rowsass);
										?>
										<div class="draglinkright" id="list4_<?php echo $schduleid."-".$assignmentid."-".$typename; ?>" title="<?php echo $assignmentname; ?>">
											<div class="dragItemLable" id="<?php echo $schduleid."-".$assignmentid."-".$typename; ?>"><?php echo $shortname; ?></div>
											<div class="clickable" id="clck_<?php echo $schduleid."-".$assignmentid."-".$typename; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $schduleid."-".$assignmentid."-".$typename;; ?>',0);"></div>
										</div>
								 <?php }
									}	?>   
                            </div>
                        </div>
                        <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0,0);">remove all Assignments</div>
                    </div>
                </div>
            </div> 
	<?php
}
@include("footer.php");