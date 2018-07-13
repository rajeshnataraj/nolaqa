<?php 
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Student Dropdown ---*/
if($oper=="showschool" and $oper != " " )
{
	$districtid = isset($method['districtid']) ? $method['districtid'] : '';	
	?>
	School
	<div class="selectbox">
        <input type="hidden" name="schoolid" id="schoolid" value="">
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search School">
            <ul role="options" style="width:100%">
                <?php 

                $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname FROM itc_school_master WHERE fld_delstatus='0' AND fld_district_id='".$districtid."' ORDER BY fld_school_name");
                if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showclass('<?php echo $districtid;?>','<?php echo $schoolid;?>')"><?php echo $schoolname; ?></a></li>
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
$districtid = isset($method['districtid']) ? $method['districtid'] : '';
$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '';
    
?>
    Class
	<div class="selectbox">
        <input type="hidden" name="hidclassid" id="hidclassid" value=""/>
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search School">
            <ul role="options" style="width:100%">
                <?php

             $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname FROM itc_class_master WHERE fld_district_id='".$districtid."' AND fld_school_id='".$schoolid."' AND fld_delstatus='0' AND fld_archive_class='0' ORDER BY fld_class_name");
             while($res=$qry->fetch_assoc())
                {
                    extract($res);
                    ?>
                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showassignments(<?php echo $classid;?>)"><?php echo $classname; ?></a></li>
                    <?php  }   ?>   
            </ul>
        </div>
	</div>
<?php    
}
/*--- Load document dropdown ---*/
if($oper=="showassignments" and $oper != " " )
{
 $classid = isset($method['classid']) ? $method['classid'] : '';
 
 $docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle, a.fld_doc_guid AS docguid, b.fld_sub_title AS subjectname, b.fld_sub_year AS year, b.fld_sub_guid AS guid
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='".$stid."'");
		
		$stddocs = array();
		if($docqry->num_rows > 0){ 
			while($docrow = $docqry->fetch_assoc()){
				extract($docrow);
				$stddocs[$guid] = $documenttitle." | ". $subjectname." (".$year.")";	
			}
		}
                
                ?>
     Assignment
        <div class="selectbox">
            <input type="hidden" name="moduleid" id="moduleid" value=""/>
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span id="standards" class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Assignment">
                <ul role="options" style="width:100%">
                    <?php 
                    $qry = $ObjDB->QueryObject("SELECT w.* FROM (
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 1 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Module') AS modulename 
											FROM itc_class_rotation_schedulegriddet AS a 
											LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													AND b.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='1')
												UNION ALL	 
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 2 AS typename, CONCAT(b.fld_module_name,' ',c.fld_version,' / Dyad') AS modulename 
											FROM itc_class_dyad_schedulegriddet AS a 
											LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
											LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                                                        LEFT JOIN itc_class_dyad_schedulemaster as e on a.fld_schedule_id = e.fld_id
											WHERE (a.fld_rotation='0') AND a.fld_class_id='".$classid."' AND a.fld_flag='1' AND d.fld_flag='1'
													AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0')
												UNION ALL	
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 3 AS typename, CONCAT(b.fld_module_name,' ',c.fld_version,' / Triad') AS modulename 
											FROM itc_class_triad_schedulegriddet AS a 
											LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
											LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                                                        LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND d.fld_flag='1'
													AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0')
												UNION ALL		
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 4 AS typename, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' / MM') AS modulename 
											FROM itc_class_rotation_schedulegriddet AS a 
											LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													AND b.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='2')
												UNION ALL	
											(SELECT a.fld_id AS scheduleid, a.fld_module_id AS moduleid, 5 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Ind Module') AS modulename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
												UNION ALL	
											(SELECT a.fld_id AS scheduleid, a.fld_module_id AS moduleid, 6 AS typename, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' / Ind MM') AS modulename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
												UNION ALL	
											(SELECT a.fld_id AS scheduleid, a.fld_module_id AS moduleid, 7 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Ind Quest') AS modulename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='7' AND c.fld_delstatus='0' AND d.fld_delstatus='0')
                                                                                                        
                                                                                               UNION ALL
                                                                                        (SELECT a.fld_schedule_id AS scheduleid, a.fld_expedition_id AS moduleid, 19 AS typename,
                                                                                            CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition') AS modulename
                                                                                        FROM itc_class_rotation_expschedulegriddet AS a
                                                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
                                                                                        LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_expedition_id
                                                                                        LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
                                                                                            WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_delstatus = '0' AND b.fld_flag = '1'
                                                                                            AND c.fld_delstatus = '0' AND d.fld_delstatus = '0')          

                                                                                        UNION ALL
                                                                                        (SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 20 AS typename,
                                                                                            CONCAT(c.fld_exp_name, ' ', d.fld_version, ' / ', 'Expedition') AS modulename
                                                                                        FROM itc_class_rotation_modexpschedulegriddet AS a
                                                                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id = b.fld_id
                                                                                        LEFT JOIN itc_exp_master AS c ON c.fld_id = a.fld_module_id
                                                                                        LEFT JOIN itc_exp_version_track AS d ON d.fld_exp_id = c.fld_id
                                                                                            WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND b.fld_delstatus = '0' AND b.fld_flag = '1'
                                                                                            AND c.fld_delstatus = '0' AND d.fld_delstatus = '0' AND a.fld_type='2')  
                                                                                            
                                                                                         UNION ALL
                                                                                         
                                                                                        (SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id AS moduleid, 21 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Module') AS modulename 
											FROM itc_class_rotation_modexpschedulegriddet AS a 
											LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
										        AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='1')

										)AS w 
										GROUP BY w.typename, w.modulename ORDER BY w.modulename");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid.'~'.$moduleid.'~'.$typename;?>" onclick="fn_showstudentlist(<?php echo $classid.','.$moduleid.',2'?>); <?php if($typename==19 OR $typename==20){ ?> fn_dummyexpsch(1); <?php }else{ ?> fn_dummyexpsch(0); <?php } ?>"><?php echo $modulename; ?></a></li>
						<?php
					}
				}
					?>    
                </ul>
            </div>
        </div>
		<?php
}


//Show the student id list
if($oper == "showstudent" and $oper != '')
{
        $type = isset($method['type']) ? $method['type'] : '';
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$assignmentid = isset($method['assignmentid']) ? $method['assignmentid'] : '';

	
?>
<script type="text/javascript" language="javascript">

	$(function() {

			$('#testrailvisible0').slimscroll({
				width: '410px',
				height:'366px',
				size: '7px',
                                alwaysVisible: true,
				railVisible: true,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 1,
				color: '#d9d9d9',
				
			});
			$('#testrailvisible1').slimscroll({
				width: '410px',
				height:'366px',
				size: '7px',
                                alwaysVisible: true,
				railVisible: true,
				allowPageScroll: false,
				railColor: '#F4F4F4',
				opacity: 1,
				color: '#d9d9d9',
			});
			$("#list9").sortable({
				connectWith: ".droptrue1",
				dropOnEmpty: true,
				items: "div[class='draglinkleft']",
				receive: function(event, ui) { 
					$("div[class=draglinkright]").each(function(){ 
						if($(this).parent().attr('id')=='list9'){
							fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));
							fn_validatestudents();
						}
					});											
				}
			});
		
			$( "#list10" ).sortable({
				connectWith: ".droptrue1",
				dropOnEmpty: true,
				receive: function(event, ui) { 
					$("div[class=draglinkleft]").each(function(){ 
						if($(this).parent().attr('id')=='list10'){
							fn_movealllistitems('list9','list10',$(this).children(":first").attr('id'));

							fn_validatestudents();
						}
					});								
				}
			});
		
		
	});										
</script>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
                                    
                                 
$qry_mks =$ObjDB->QueryObject("SELECT w.* FROM(
											(SELECT CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, e.fld_id AS studentid
											FROM itc_class_rotation_schedulegriddet AS a 
											LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											LEFT JOIN itc_user_master as e ON e.fld_id=a.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													AND b.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='1'
													 AND e.fld_activestatus='1' AND e.fld_delstatus='0')
												UNION ALL	 
											(SELECT CONCAT(f.fld_fname,' ',f.fld_lname) AS studentname, f.fld_id AS studentid
											FROM itc_class_dyad_schedulegriddet AS a 
											LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
											LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                            LEFT JOIN itc_class_dyad_schedulemaster as e on a.fld_schedule_id = e.fld_id
                                            LEFT JOIN itc_user_master as f ON f.fld_id=a.fld_student_id
											WHERE (a.fld_rotation='0') AND a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND d.fld_flag='1'
													AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
												UNION ALL	
											(SELECT CONCAT(f.fld_fname,' ',f.fld_lname) AS studentname, f.fld_id AS studentid
											FROM itc_class_triad_schedulegriddet AS a 
											LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
											LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                            LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
                                            LEFT JOIN itc_user_master as f ON f.fld_id=d.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND d.fld_flag='1'
													AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
												UNION ALL		
											(SELECT CONCAT(f.fld_fname,' ',f.fld_lname) AS studentname, f.fld_id AS studentid
											FROM itc_class_rotation_schedulegriddet AS a 
											LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
											LEFT JOIN itc_user_master as f ON f.fld_id=a.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."'  AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													AND b.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='2' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
												UNION ALL	
											(SELECT CONCAT(f.fld_fname,' ',f.fld_lname) AS studentname, f.fld_id AS studentid
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											LEFT JOIN itc_user_master as f ON f.fld_id=b.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."'  AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
												UNION ALL	
											(SELECT CONCAT(f.fld_fname,' ',f.fld_lname) AS studentname, f.fld_id AS studentid
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
											LEFT JOIN itc_user_master as f ON f.fld_id = b.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
												UNION ALL	
											(SELECT CONCAT(f.fld_fname,' ',f.fld_lname) AS studentname, f.fld_id AS studentid
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											LEFT JOIN itc_user_master as f ON f.fld_id = b.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='7' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND f.fld_activestatus='1' AND f.fld_delstatus='0')
                                                                                            UNION ALL
                                                                                        (SELECT CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, e.fld_id AS studentid
                                                                                        FROM itc_class_rotation_expschedulegriddet AS a 
                                                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                                                        LEFT JOIN itc_exp_master AS c ON a.fld_expedition_id=c.fld_id
                                                                                        LEFT JOIN itc_exp_version_track AS d ON c.fld_id=d.fld_exp_id
                                                                                        LEFT JOIN itc_user_master as e ON e.fld_id=a.fld_student_id
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_expedition_id='".$assignmentid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                                                            AND b.fld_scheduletype='17' AND c.fld_delstatus='0' AND d.fld_delstatus='0' 
                                                                                             AND e.fld_activestatus='1' AND e.fld_delstatus='0')

                                                                                             UNION ALL
                                                                                        (SELECT CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, e.fld_id AS studentid
                                                                                        FROM itc_class_rotation_modexpschedulegriddet AS a 
                                                                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
                                                                                        LEFT JOIN itc_exp_master AS c ON a.fld_module_id=c.fld_id
                                                                                        LEFT JOIN itc_exp_version_track AS d ON c.fld_id=d.fld_exp_id
                                                                                        LEFT JOIN itc_user_master as e ON e.fld_id=a.fld_student_id
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND a.fld_type='2' AND b.fld_flag='1' AND b.fld_delstatus='0' 
                                                                                            AND c.fld_delstatus='0' AND d.fld_delstatus='0' 
                                                                                             AND e.fld_activestatus='1' AND e.fld_delstatus='0')
                                                                                             
                                                                                             UNION ALL
                                                                                             
                                                                                             (SELECT CONCAT(e.fld_fname,' ',e.fld_lname) AS studentname, e.fld_id AS studentid
											FROM itc_class_rotation_modexpschedulegriddet AS a 
											LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											LEFT JOIN itc_user_master as e ON e.fld_id=a.fld_student_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_module_id='".$assignmentid."' AND a.fld_flag='1' AND a.fld_type='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													 AND c.fld_delstatus='0' AND d.fld_delstatus='0'
													 AND e.fld_activestatus='1' AND e.fld_delstatus='0')

										)AS w 
										GROUP BY w.studentid");

                                    ?>
                                        <div class="dragtitle">Students</div>
                                        <div class="draglinkleftSearch" id="s_list9" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible0" >
                                            <div id="list9" class="dragleftinner droptrue1">

                                         <?php      
                                               if($qry_mks->num_rows > 0){

$i=0;
    while($qry_mks_details = $qry_mks->fetch_assoc()){
            extract($qry_mks_details);


                                                        ?>
                                                    <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                                        <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $studentname;?>"><?php echo $studentid; ?></div>
                                                        <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>);fn_validatestudents();"></div>
                                                    </div> 
                                            <?php 
                                                    }
                                                }
                                            ?>

                                            </div>
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list9','list10',0,0);" style="cursor: pointer;cursor:hand;width: 152px;float: right;">add all students</div>
                                    </div>
                                </div>
                            <div class='six columns'>
                                    <div class="dragndropcol">
                                        <div class="dragtitle">Selected Students</div>
                                        <div class="draglinkleftSearch" id="s_list10" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_10_search" name="list_10_search" onKeyUp="search_list(this,'#list10');" />
                                                </dt>
                                            </dl>
                                        </div>
                                         <div class="dragWell" id="testrailvisible1">
                                            <div id="list10" class="dragleftinner droptrue1">
                                             <?php 
                                          if($qry_mks->num_rows > 0){
$i=0;
    while($qry_mks_details = $qry_mks->fetch_assoc()){
            extract($qry_mks_details);
                                                        
                                                    ?>
                                                            <div class="draglinkright" id="list10_<?php echo $studentid; ?>">
                                                                <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $studentname;?>"><?php echo $studentid; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list9','list10',<?php echo $studentid; ?>);fn_validatestudents();"></div>
                                                            </div>
                                            <?php   }
                                                }
                                             
                                            ?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);"  style="cursor: pointer;cursor:hand;width: 180px;float: right;">remove all students</div>
                                    </div>
                                </div>                       

<?php
  	
}



	@include("footer.php");
