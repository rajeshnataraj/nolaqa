<?php
	@include("sessioncheck.php");
	
	$coursesid = isset($method['id']) ? $method['id'] : '0';	
	$coursename=$ObjDB->SelectSingleValue("SELECT fld_course_name from itc_course_master 
	                                     WHERE fld_id='".$coursesid."' AND fld_delstatus='0'"); // get the course name using course id
	
?>
<section data-type='2home' id='library-courses-viewcourses'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $coursename;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <div class='row'>
                            <div class='eight columns'>              
                                 <span class="wizardReportDesc">Lesson :</span>
                                 <?php 
								if($sessmasterprfid==2 or $sessmasterprfid==3){
									$qry="SELECT fld_pd_name AS lessonname 
									                                  FROM itc_pd_master 
																	  WHERE fld_delstatus='0' AND fld_course_id='".$coursesid."'";
								}
								else{
									$qry="SELECT c.fld_pd_name AS lessonname 
									                                  FROM itc_license_pd_mapping AS a 
																	  LEFT JOIN itc_license_track AS b ON a.fld_license_id = b.fld_license_id 
																	  RIGHT JOIN itc_pd_master AS c ON a.fld_pd_id=c.fld_id 
																	  WHERE b.fld_district_id='".$districtid."' AND b.fld_school_id='".$schoolid."' 
																	        AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' 
																			AND '".date("Y-m-d")."' BETWEEN b.fld_start_date AND b.fld_end_date 
																			AND a.fld_active='1' AND c.fld_course_id='".$coursesid."' 
																			AND c.fld_delstatus='0' GROUP BY a.fld_pd_id";									
								}
								
								$qry_lesson = $ObjDB->QueryObject($qry);
								
								if($qry_lesson->num_rows > 0){
									
									while($res_lesson=$qry_lesson->fetch_assoc()){ 
									   
									   extract($res_lesson);
								?>
							         <div class='wizardReportData' title="<?php echo $lessonname;?>"><?php echo $lessonname;?></div>
								<?php 
									}
									
								}else{
								?>
									<div class='wizardReportData'>No Lesson(s)</div>
								<?php
								}
								?>
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