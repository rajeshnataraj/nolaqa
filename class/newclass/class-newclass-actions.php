<?php 
@include("sessioncheck.php");

$classid = isset($method['id']) ? $method['id'] : '';

$tempclassid = $classid;
$assingnmentid = '';
$flag=0;
$classname = '';
$qry = $ObjDB->QueryObject("SELECT fld_lock AS flag, fld_class_name AS classname 
							FROM itc_class_master 
							WHERE fld_id='".$classid."'");
if($qry->num_rows>0)
extract($qry->fetch_assoc());

$rubriccount = 0;
//print_r($rows);
$temprubrics = dbSelect("itc_class_expmis_rubricmaster", array("fld_delstatus" => 0, "fld_class_id" => $tempclassid));

foreach ($temprubrics as $temprubric){
    if(count($temprubric) > 0) {
        if((int)$temprubric["fld_schedule_type"] == 18){
            $misindass = dbSelect("itc_class_indasmission_master", array("fld_id" => $temprubric["fld_schedule_id"], "fld_class_id" => $tempclassid,  "fld_delstatus" => 0, "fld_lock" => 0, "fld_flag" => 1));
            if(count($misindass[0]) > 1){
                $rubriccount++;
            }
        }
        else{
            $expindass = dbSelect("itc_class_indasexpedition_master", array("fld_id" => $temprubric["fld_schedule_id"], "fld_class_id" => $tempclassid, "fld_delstatus" => 0, "fld_lock" => 0, "fld_flag" => 1));
            if(count($expindass[0]) > 1){
                $rubriccount++;
            }
            elseif((int)$temprubric["fld_schedule_type"] == 17 || (int)$temprubric["fld_schedule_type"] == 19){
                $rubriccount++;
            }
        }
    }
}

$lockclscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_lockclassautomation WHERE fld_class_id='".$classid."' AND fld_delstatus='0'");

?>
<section data-type='#class-newclass' id='class-newclass-actions'>
    <script language="javascript">
   		$.getScript("reports/gradebook/reports-gradebook.js");
    </script>
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
       			<p class="dialogTitle"><?php echo $classname;?></p>
        		<p class="dialogSubTitleLight">&nbsp;</p>
      		</div>
    	</div>
        
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-classdetails' name='<?php echo $classid;?>,1'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View / Edit<br />Details</div>
            </a>
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-addpeople' name='<?php echo $classid;?>,1'>
                <div class='icon-synergy-users'></div>
                <div class='onBtn'>Manage<br>People</div>
            </a>
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-resetpasswords' name='0,<?php echo $classid;?>,1'>
                <div class='icon-synergy-tools'></div>
                <div class='onBtn'>Reset Student Passwords</div>
            </a>
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-scheduledetails' name='<?php echo $classid;?>,1'>
                <div class='icon-synergy-tablet'></div>
                <div class='onBtn'>View / Edit <br />Schedule</div>
            </a>
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-calendar' name='<?php echo $classid;?>,1'>
                <div class='icon-synergy-calendar'></div>
                <div class='onBtn'>View Class <br />Calendar</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradebook' name='0,<?php echo $classid;?>,1'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Grade Book</div>
            </a>
            <?php if($rubriccount > 0){
                ?>

                <a class='skip btn mainBtn' href='#reports' id='btnreports-rubric-teacher' name='0,<?php echo $classid;?>,1'>
                    <div class='icon-synergy-tests'></div>
                    <div class='onBtn'>Grading Rubric</div>
                </a>
    <?php
            }
            ?>
            <a class='skip btn main' href='#class-class' onclick="fn_deleteclass(<?php echo $classid;?>)">
                <div class='icon-synergy-close-dark'></div>
                <div class='onBtn'>Delete <br />Class</div>
            </a>
            <!-- lock class automation start -->
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-lockclassautomation' name='<?php echo $classid;?>'>
                <div id='locked' class='<?php if($flag == '0'){echo "icon-synergy-unlocked";} else { echo "icon-synergy-locked";}?>'></div>
                <div class='onBtn'><?php if($flag==1) echo "Lock Class"; else echo "Unlock Class"; ?></div>
            </a>
            <!-- lock class automation end -->

            <?php
            //The class id is given in the variable $classid;

            $get_class_schedules_query = "SELECT 
                                                                a.fld_id AS sid, 
                                                                b.fld_expedition_id AS expid,
                                                                a.fld_schedule_name AS sname,
                                                                17 AS stype, 
                                                                'Expedition Schedule' AS typename,
                                                                '' as wcalock, 
                                                                a.fld_class_id AS classid,
                                                                a.fld_startdate AS startdate, 
                                                                a.fld_enddate AS enddate, 
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_rotation_expschedule_mastertemp AS a 
                                                                LEFT JOIN itc_class_rotation_expschedulegriddet AS b ON b.fld_schedule_id=a.fld_id 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=b.fld_expedition_id 
                                                            WHERE 
                                                                a.fld_class_id='$classid' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid
													    UNION ALL 
                                                            SELECT 
                                                                a.fld_id AS sid, 
                                                                a.fld_exp_id AS expid,
                                                                a.fld_schedule_name AS sname,
                                                                15 AS stype,
                                                                'Whole Class Assignment - Expedition' AS typename, 
                                                                a.fld_lock as wcalock,  
                                                                a.fld_class_id AS classid, 
                                                                a.fld_startdate AS startdate, 
                                                                a.fld_enddate AS enddate, 
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_indasexpedition_master AS a 
                                                                LEFT JOIN itc_class_exp_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=a.fld_exp_id 
                                                            WHERE 
                                                                a.fld_class_id='$classid' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid";
            $query_object = $ObjDB->QueryObject($get_class_schedules_query);

        if($query_object->num_rows > 0){
                ?>

            <!--Digital Logbook-->
            <a class='skip btn mainBtn' href='#class-newclass' id='btnclass-newclass-digitallogbook' name='<?php echo $classid;?>'>
                <div  class='icon-synergy-dashboard'></div>
                <div class='onBtn'>Digital Logbook</div>
            </a>
                <?php
            }
            ?>
			
        </div>
        <input type="hidden" id="hidflag" name="hidflag" value="1" />
        <input type="hidden" id="hidclassid" name="hidclassid" value="<?php echo $classid;?>" />
        <input type="hidden" value="1" id="classtypeval" />
	</div>
</section>
<?php
	@include("footer.php");