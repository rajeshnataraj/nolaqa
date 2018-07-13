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

             $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname FROM itc_class_master WHERE fld_district_id='".$districtid."' AND fld_school_id='".$schoolid."' AND fld_archive_class='0' AND fld_delstatus = '0' ORDER BY fld_class_name");
             while($res=$qry->fetch_assoc())
                {
                    extract($res);
                    ?>
                    <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick=" $('#showstart').show(); "><?php echo $classname; ?></a></li>
                    <?php  }   ?>   
            </ul>
        </div>
	</div>
<?php    
}

/*--- Load document dropdown Module---*/
if($oper=="showassignments" and $oper != " " )
{
   
 $classid = isset($method['classid']) ? $method['classid'] : '';
 $fld_start_date = isset($method['startdate']) ? $method['startdate'] : '';
 $fld_end_date = isset($method['enddate']) ? $method['enddate'] : '';
 
 $expsdate=explode("/",$fld_start_date);
 $expedate=explode("/",$fld_end_date);
 
 $startdate=$expsdate[2]."-".$expsdate[0]."-".$expsdate[1];
 $enddate=$expedate[2]."-".$expedate[0]."-".$expedate[1];
 
 $fld_start_date=$startdate;
 $fld_end_date=$enddate;
 
 if($fld_start_date!='')
 {
    $sqry = "AND ('".$fld_start_date."' BETWEEN b.fld_startdate AND b.fld_enddate OR '".$fld_end_date."' BETWEEN b.fld_startdate AND b.fld_enddate OR b.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR b.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
    $sqry1 = "AND ('".$fld_start_date."' BETWEEN e.fld_startdate AND e.fld_enddate OR '".$fld_end_date."' BETWEEN e.fld_startdate AND e.fld_enddate OR e.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR e.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
    $sqry2 = "AND ('".$fld_start_date."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$fld_end_date."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."' OR a.fld_enddate BETWEEN '".$fld_start_date."' AND '".$fld_end_date."')";
			}
 else
 {
    $sqry='';
    $sqry1='';
    $sqry2='';
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
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id, 1 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Module') AS modulename 
											FROM itc_class_rotation_schedulegriddet AS a 
											LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													AND b.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='1' ".$sqry.")
												UNION ALL	 
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id, 2 AS typename, CONCAT(b.fld_module_name,' ',c.fld_version,' / Dyad') AS modulename 
											FROM itc_class_dyad_schedulegriddet AS a 
											LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
											LEFT JOIN itc_class_dyad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                                                        LEFT JOIN itc_class_dyad_schedulemaster as e on a.fld_schedule_id = e.fld_id
											WHERE (a.fld_rotation='0') AND a.fld_class_id='".$classid."' AND a.fld_flag='1' AND d.fld_flag='1'
													AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0' ".$sqry1.")
												UNION ALL	
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id, 3 AS typename, CONCAT(b.fld_module_name,' ',c.fld_version,' / Triad') AS modulename 
											FROM itc_class_triad_schedulegriddet AS a 
											LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
											LEFT JOIN itc_class_triad_schedule_studentmapping AS d ON (d.fld_schedule_id=a.fld_schedule_id)
                                                                                        LEFT JOIN itc_class_triad_schedulemaster AS e ON a.fld_schedule_id = e.fld_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND d.fld_flag='1'
													AND b.fld_delstatus='0' AND c.fld_delstatus='0'  AND e.fld_delstatus='0' ".$sqry1.")
												UNION ALL		
											(SELECT a.fld_schedule_id AS scheduleid, a.fld_module_id, 4 AS typename, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' / MM') AS modulename 
											FROM itc_class_rotation_schedulegriddet AS a 
											LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON (a.fld_class_id=b.fld_class_id AND a.fld_schedule_id=b.fld_id) 
											LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id
											LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND b.fld_delstatus='0' 
													AND b.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND a.fld_type='2' ".$sqry.")
												UNION ALL	
											(SELECT a.fld_id AS scheduleid, a.fld_module_id, 5 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Ind Module') AS modulename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='1' AND c.fld_delstatus='0' AND d.fld_delstatus='0' ".$sqry2.")
												UNION ALL	
											(SELECT a.fld_id AS scheduleid, a.fld_module_id, 6 AS typename, CONCAT(c.fld_mathmodule_name,' ',d.fld_version,' / Ind MM') AS modulename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_module_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='2' AND c.fld_delstatus='0' AND d.fld_delstatus='0' ".$sqry2.")
												UNION ALL	
											(SELECT a.fld_id AS scheduleid, a.fld_module_id, 7 AS typename, CONCAT(c.fld_module_name,' ',d.fld_version,' / Ind Quest') AS modulename 
											FROM itc_class_indassesment_master AS a 
											LEFT JOIN itc_class_indassesment_student_mapping AS b ON a.fld_id=b.fld_schedule_id 
											LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
											LEFT JOIN itc_module_version_track AS d ON c.fld_id=d.fld_mod_id
											WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_flag='1' AND a.fld_delstatus='0' 
													AND a.fld_moduletype='7' AND c.fld_delstatus='0' AND d.fld_delstatus='0' ".$sqry2.")
										)AS w 
										GROUP BY w.typename, w.modulename ORDER BY w.modulename");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid.'~'.$fld_module_id.'~'.$typename;?>" onclick="fn_showstudentlist(<?php echo $classid.','.$fld_module_id.',2'?>);"><?php echo $modulename; ?></a></li>
						<?php
					}
				}
					?>    
                </ul>
            </div>
        </div>
		<?php
}

/*--- Load document dropdown Rotation---*/

if($oper=="showassignmentsrotate" and $oper != " " )
{
    
 $classid = isset($method['classid']) ? $method['classid'] : '';
 $fld_start_date = isset($method['stdate']) ? $method['stdate'] : '';
 $fld_end_date = isset($method['enddate']) ? $method['enddate'] : '';
 
 $expsdate=explode("/",$fld_start_date);
 $expedate=explode("/",$fld_end_date);
 
 $startdate=$expsdate[2]."-".$expsdate[0]."-".$expsdate[1];
 $enddate=$expedate[2]."-".$expedate[0]."-".$expedate[1];
 
 if($fld_start_date!='')
 {
    $sqry = "AND ('".$startdate."' BETWEEN a.fld_startdate AND a.fld_enddate OR '".$enddate."' BETWEEN a.fld_startdate AND a.fld_enddate OR a.fld_startdate BETWEEN '".$startdate."' AND '".$enddate."' OR a.fld_enddate BETWEEN '".$startdate."' AND '".$enddate."')";
 }
 else
 {
    $sqry='';
    
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
                                    
                    $qry = $ObjDB->QueryObject("SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,(CASE WHEN a.fld_scheduletype=2 
                                                                                                THEN 1 WHEN a.fld_scheduletype=6 THEN 4 END) AS stype, (CASE WHEN a.fld_scheduletype=2 
                                                                                                THEN 'Module Schedule' WHEN a.fld_scheduletype=6 THEN 'Math Module Schedule' END) AS typename
                                                                                        FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
                                                                                                ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' ".$sqry." 
                                                                                        GROUP BY sid
                                                UNION ALL                                       
                                                SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,2 AS stype,'Dyad' AS typename 
                                                                                                
                                                                                        FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' ".$sqry."
                                                                                        GROUP BY sid
                                                UNION ALL 

                                                SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,3 AS stype,'Triad' AS typename 
                                                                                        FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON b.fld_schedule_id=a.fld_id
                                                                                        WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1'  ".$sqry."
                                                                                        GROUP BY sid");
                    
                    
                    
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
                                            extract($row);
                                    ?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $sid.'~0~'.$stype;?>" onclick="fn_showmodulelist(<?php echo $sid.','.$stype;?>);"><?php echo $sname . " / ". $typename; ?></a></li>
						<?php
                                            }
					}
				
					?>    
                </ul>
            </div>
        </div>
		<?php
}

/* Module schedule display  */
if($oper == "showmodule" and $oper != '')
{
    $stype = isset($method['stype']) ? $method['stype'] : ''; 
    $schid = isset($method['schid']) ? $method['schid'] : '';
    $classid = isset($method['classid']) ? $method['classid'] : '';

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
                        
			$("#list11").sortable({
				connectWith: ".droptrue1",
				dropOnEmpty: true,
				items: "div[class='draglinkleft']",
				receive: function(event, ui) { 
					$("div[class=draglinkright]").each(function(){ 
						if($(this).parent().attr('id')=='list11'){
							fn_movealllistitems('list11','list12',$(this).children(":first").attr('id'));
							
						}
					});											
				}
			});
		
			$( "#list12" ).sortable({
				connectWith: ".droptrue1",
				dropOnEmpty: true,
				receive: function(event, ui) { 
					$("div[class=draglinkleft]").each(function(){ 
						if($(this).parent().attr('id')=='list12'){
							fn_movealllistitems('list11','list12',$(this).children(":first").attr('id'));
							
						}
					});								
				}
			});
		
		
	});										
</script>
    <div class='six columns'>
        <div class="dragndropcol">
        <?php 
                if($stype==1 OR $stype==4)
                {
                    $qrymodule=$ObjDB->QueryObject("SELECT fld_module_id as moduleid FROM itc_class_rotation_moduledet WHERE  fld_schedule_id='".$schid."' AND fld_flag=1");
                }
                else if($stype==2)
                {
                   $qrymodule=$ObjDB->QueryObject("SELECT fld_module_id as moduleid FROM itc_class_dyad_moduledet WHERE  fld_schedule_id='".$schid."' AND fld_flag=1"); 
                }
                 else if($stype==3)
                {
                   $qrymodule=$ObjDB->QueryObject("SELECT fld_module_id as moduleid FROM itc_class_triad_moduledet WHERE  fld_schedule_id='".$schid."' AND fld_flag=1"); 
                }
             
        ?>
            <div class="dragtitle">Available Modules</div>
            <div class="draglinkleftSearch" id="s_list11" >
               <dl class='field row'>
                    <dt class='text'>
                        <input placeholder='Search' type='text' id="list_11_search" name="list_11_search" onKeyUp="search_list(this,'#list11');" />
                    </dt>
                </dl>
            </div>
            <div class="dragWell" id="testrailvisible0" >
                <div id="list11" class="dragleftinner droptrue1">

                    <?php  
                    
                            if($qrymodule->num_rows > 0)
                            {
                                while($row = $qrymodule->fetch_assoc())
                                {
                                
                                 extract($row);
                    
                                if($stype==4)
                                {
                                    $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version)
				                            FROM itc_mathmodule_master AS a 
											LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
											WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                }
                                else
                                {
                                    $modulename=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version)
			                              FROM itc_module_master AS a 
										  LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id='".$moduleid."'    
										  WHERE a.fld_id='".$moduleid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                }
                         
                            ?>
                        <div class="draglinkleft" id="list11_<?php echo $moduleid; ?>" >
                            <div class="dragItemLable tooltip" id="<?php echo $moduleid; ?>"><?php echo $modulename;?></div>
                            <div class="clickable" id="clck_<?php echo $moduleid; ?>" onclick="fn_movealllistitems('list11','list12',<?php echo $moduleid; ?>);"></div>
                        </div> 
                    <?php 
                                }
                            }
                    ?>

                </div>
        </div>
        <div class="dragAllLink"  onclick="fn_movealllistitems('list11','list12',0,0);" style="cursor: pointer;cursor:hand;width: 152px;float: right;">Add all modules</div>
    </div>
</div>
    <div class='six columns'>
    <div class="dragndropcol">
        <div class="dragtitle">Selected Modules</div>
        <div class="draglinkleftSearch" id="s_list12" >
           <dl class='field row'>
                <dt class='text'>
                    <input placeholder='Search' type='text' id="list_12_search" name="list_12_search" onKeyUp="search_list(this,'#list12');" />
                </dt>
            </dl>
        </div>
         <div class="dragWell" id="testrailvisible1">
            <div id="list12" class="dragleftinner droptrue1">
            
        </div>
        </div>
        <div class="dragAllLink" onclick="fn_movealllistitems('list12','list11',0,0);"  style="cursor: pointer;cursor:hand;width: 180px;float: right;">Remove all modules</div>
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

			$('#testrailvisible2').slimscroll({
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
			$('#testrailvisible3').slimscroll({
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
                                    
                                 
$qry_mks =$ObjDB->QueryObject("SELECT w.* FROM (
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
                                        <div class="dragWell" id="testrailvisible2" >
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
                                         <div class="dragWell" id="testrailvisible3">
                                            <div id="list10" class="dragleftinner droptrue1">
                                            
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);"  style="cursor: pointer;cursor:hand;width: 180px;float: right;">remove all students</div>
                                    </div>
                                </div>                         

                                             <?php 
  	
}


//Show the student id list
if($oper == "showstudent2" and $oper != '')
{
    $type = isset($method['type']) ? $method['type'] : '';
    $classid = isset($method['classid']) ? $method['classid'] : '';
    $sid = isset($method['assignmentid']) ? $method['assignmentid'] : '';    

	
?>
<script type="text/javascript" language="javascript">

	$(function() {

			$('#testrailvisible4').slimscroll({
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
			$('#testrailvisible5').slimscroll({
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
                                    
                                 if($type==1 OR $type==4)
                                 {
                                            $qry_student =$ObjDB->QueryObject("SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname
                                         FROM itc_user_master AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
                                         WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY studentid");
                                 }
                                 else if($type==2)
                                 {
                                     $qry_student =$ObjDB->QueryObject("SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname
				 FROM itc_user_master AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY studentid");
                                 }
                                 else if($type==3)
                                 {
                                     $qry_student =$ObjDB->QueryObject("SELECT a.fld_id as studentid, CONCAT(a.fld_lname,' ',a.fld_fname) AS sname
				 FROM itc_user_master AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id 
				 WHERE b.fld_schedule_id='".$sid."' AND b.fld_flag='1' AND a.fld_delstatus='0' ORDER BY studentid");
                                 }

                                    ?>
                                        <div class="dragtitle">Students</div>
                                        <div class="draglinkleftSearch" id="s_list9" >
                                           <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this,'#list9');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible4" >
                                            <div id="list9" class="dragleftinner droptrue1">

                                         <?php      
                                               if($qry_student->num_rows > 0){

$i=0;
                                                while($row = $qry_student->fetch_assoc()){
                                                        extract($row);
                                                        

                                                    ?>
                                                    <div class="draglinkleft" id="list9_<?php echo $studentid; ?>" >
                                                        <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $sname;?>"><?php echo $studentid; ?></div>
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
                                         <div class="dragWell" id="testrailvisible5">
                                            <div id="list10" class="dragleftinner droptrue1">
                                            
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list10','list9',0,0);"  style="cursor: pointer;cursor:hand;width: 180px;float: right;">remove all students</div>
                                    </div>
                                </div>

<?php
  	
}
	@include("footer.php");
