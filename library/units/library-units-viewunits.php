<?php
	@include("sessioncheck.php");
	
	$unitsid = isset($method['id']) ? $method['id'] : '0';	
	$unitname=$ObjDB->SelectSingleValue("SELECT fld_unit_name from itc_unit_master 
	                                     WHERE fld_id='".$unitsid."' AND fld_delstatus='0'"); // get the unit name using unit id
	
?>
<section data-type='2home' id='library-units-viewunits'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $unitname;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <div class='row'>
                            <div class='eight columns'>              
                                 <span class="wizardReportDesc">IPL:</span>
                                 <?php 
								if($sessmasterprfid==2 or $sessmasterprfid==3){
									$qry="SELECT fld_ipl_name AS lessonname 
									                                  FROM itc_ipl_master 
																	  WHERE fld_delstatus='0' AND fld_unit_id='".$unitsid."'";
								}
								else{
									$qry="SELECT c.fld_ipl_name AS lessonname 
									                                  FROM itc_license_cul_mapping AS a 
																	  LEFT JOIN itc_license_track AS b ON a.fld_license_id = b.fld_license_id 
																	  RIGHT JOIN itc_ipl_master AS c ON a.fld_lesson_id=c.fld_id 
																	  WHERE b.fld_district_id='".$districtid."' AND b.fld_school_id='".$schoolid."' 
																	        AND b.fld_user_id='".$indid."' AND b.fld_delstatus='0' 
																			AND '".date("Y-m-d")."' BETWEEN b.fld_start_date AND b.fld_end_date 
																			AND a.fld_active='1' AND c.fld_unit_id='".$unitsid."' 
																			AND c.fld_delstatus='0' GROUP BY a.fld_lesson_id";									
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
									<div class='wizardReportData'>No IPL(s)</div>
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