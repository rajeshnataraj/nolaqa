<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	
	$qryclassdetails = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_start_date AS startdate, fld_end_date AS enddate, fld_period AS classperiod 
											FROM itc_class_master  
											WHERE fld_id='".$id."'");
	
	if($qryclassdetails->num_rows>0){
		$rowclassdetails = $qryclassdetails->fetch_assoc();
		extract($rowclassdetails);
	}
?>
<script language="javascript">
	$('#classdetails').removeClass("active-first");
	$('#schedule').removeClass("active-mid");
	$('#people').removeClass("active-mid");
	$('#review').parents().removeClass("dim");
	$('#review').addClass("active-last");
</script>

<section data-type='#class-newclass' id='class-newclass-review'>
<div class="container">
    <div class="row">
        <div class="twelve columns">
        	<p class="dialogTitle">Review Your Class</p>
            <p class="dialogSubTitleLight">Review your class details. Then click Finish when finished.</h1>
        </div>
      </div>  
        <div class="row rowspacer">
        	<div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                     	<div class='row'>
                            <div class='three columns'>
                                <div class="row">
                                    <span class="wizardReportDesc">class name:</span>
                                    <div class="wizardReportData"><?php echo $classname;?></div>
                                </div>
                                <div class="row rowspacer">
                                    <span class="wizardReportDesc">class period:</span>
                                    <div class="wizardReportData"><?php echo $classperiod;?></div>
                                </div>                
                                <div class="row rowspacer">
                                    <span class="wizardReportDesc">start date:</span>
                                    <div class="wizardReportData"><?php echo date("m-d-Y",strtotime($startdate));?></div>
                                </div>
                                <div class="row rowspacer">
                                    <span class="wizardReportDesc">end date:</span>
                                    <div class="wizardReportData"><?php echo date("m-d-Y",strtotime($enddate)); ?></div>
                                </div>                 
                            </div>
                            <div class='three columns'>
                                <div class="row"> <!-- List of teacher taking the class -->
                                    <span class="wizardReportDesc">teachers:</span>
                                    <?php 
                                        $qryteacherdetails = $ObjDB->QueryObject("SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS teachername 
																					FROM itc_user_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_id=b.fld_teacher_id 
																					WHERE b.fld_class_id='".$id."' AND b.fld_flag='1' AND a.fld_delstatus='0'"); 
                                        if($qryteacherdetails->num_rows>0){
                                            while($rowteacherdetails = $qryteacherdetails->fetch_assoc()){
                                                extract($rowteacherdetails);
                                    ?>
                                            <div class="wizardReportData"><?php echo $teachername;?></div>
                                    <?php
                                            }
                                        }
                                        else {
                                    ?>
                                        <div class="wizardReportData">No Teachers...</div>
									<?php
                                    	}
                                    ?>
                                </div>
                                <div class="row rowspacer"><!-- List of students attending the class -->
                                    <span class="wizardReportDesc">students:</span>
									<?php 
                                        $qrystudentdetails = $ObjDB->QueryObject("SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname 
																				 FROM itc_user_master AS a LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
																				 WHERE b.fld_class_id='".$id."' AND b.fld_flag='1' AND a.fld_delstatus='0'"); 
                                        if($qrystudentdetails->num_rows>0){
                                            while($rowstudentdetails = $qrystudentdetails->fetch_assoc()){
                                                extract($rowstudentdetails);
                                    ?>
                                            <div class="wizardReportData"><?php echo $studentname;?></div>
                                    <?php
                                            }
                                        }
                                        else{
                                    ?>
                                            <div class="wizardReportData">No Students</div><?php
                                        }
                                    ?>	
                                </div>      
                            </div>
                            <div class='five columns'>
                                <div class="row">
                                    <div class="wizardReportDesc">schedules:</div> <!-- List of schedules in that class -->
                                    <?php 
                                        $qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS sname, fld_start_date AS startdate, fld_end_date AS enddate 
																	FROM itc_class_sigmath_master WHERE fld_class_id='".$id."' AND fld_delstatus='0' 
																	UNION 
																	
																	SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate 
																	FROM itc_class_rotation_schedule_mastertemp WHERE fld_class_id='".$id."' AND fld_delstatus='0' 
																	UNION 
																	
																	SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate 
																	FROM itc_class_dyad_schedulemaster WHERE fld_class_id='".$id."' AND fld_delstatus='0' 
																	UNION 
																	
																	SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate 
																	FROM itc_class_triad_schedulemaster WHERE fld_class_id='".$id."' AND fld_delstatus='0'
																	UNION
																	
																	SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate 
																	FROM itc_class_indassesment_master WHERE fld_class_id='".$id."' AND fld_delstatus='0'
                                                                                                                                        UNION
                                                                                                                                        
                                                                                                                                        SELECT fld_schedule_name AS sname, fld_startdate AS startdate, fld_enddate AS enddate 
																	FROM itc_class_indasexpedition_master WHERE fld_class_id='".$id."' AND fld_delstatus='0'");
                                              
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc()){
                                                extract($row);
                                    ?>
                                            <div class="wizardReportData"><?php echo $sname;?><br />
                                            <?php echo date("m-d-Y",strtotime($startdate))." - ".date("m-d-Y",strtotime($enddate));?></div>
                                            <br /><br />
                                    <?php
                                            }
                                        }
                                        else{
                                    ?>
                                            <div class="wizardReportData">No Schedules...</div><?php
                                        }
                                    ?>           
                                </div>
                            </div>
                        </div>  
                        <div class='row spacer' style="padding-top:20px;">
                            <div class='row'>
                                <p class='btn secondary four columns' style="margin-left:31%;">
                                    <a onclick="fn_finishclass(<?php echo $id;?>);">Finish</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	
</section>
<?php
	@include("footer.php");