<?php 
	/*
		Created By - Muthukumar. D
		Page - library-diagmastery-review
		Description:
			Show the Diagmastery details & Questions.
		History:
	*/
	
	@include("sessioncheck.php");

	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(",",$id);
	
	/*--- Variable deceleration-----*/
	$unitname='';
	$lessonname='';
	$lessonweight='';
	$qrydetails = $ObjDB->QueryObject("SELECT a.fld_lesson_weight AS lessonweight, a.fld_diag_ques1a AS diagques1, 			
										a.fld_diag_ques1b AS diagques2, a.fld_diag_ques2a AS diagques3, 
										a.fld_diag_ques2b AS diagques4, a.fld_diag_ques3a AS diagques5, 
										a.fld_diag_ques3b AS diagques6, a.fld_mast1_ques1a AS mast1ques1, 
										a.fld_mast1_ques1b AS mast1ques2, a.fld_mast1_ques2a AS mast1ques3, 
										a.fld_mast1_ques2b AS mast1ques4, a.fld_mast1_ques3a AS mast1ques5, 
										a.fld_mast1_ques3b AS mast1ques6, a.fld_mast2_ques1a AS mast2ques1, 
										a.fld_mast2_ques1b AS mast2ques2, a.fld_mast2_ques2a AS mast2ques3, 
										a.fld_mast2_ques2b AS mast2ques4, a.fld_mast2_ques3a AS mast2ques5, 
										a.fld_mast2_ques3b AS mast2ques6, d.fld_unit_name AS unitname, 
										CONCAT(e.fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track 
									WHERE fld_ipl_id=e.fld_id AND fld_zip_type='1' AND fld_delstatus='0')) AS lessonname 
									FROM itc_diag_question_mapping AS a LEFT JOIN itc_unit_master AS d ON 
										a.fld_unit_id=d.fld_id LEFT JOIN itc_ipl_master AS e ON 
										a.fld_lesson_id=e.fld_id 
									WHERE a.fld_id='".$id[0]."' AND a.fld_delstatus='0' AND d.fld_delstatus='0' 
										AND e.fld_delstatus='0' AND e.fld_access='1' AND d.fld_activestatus='0'");
	
	if($qrydetails->num_rows>0){
		$rowdetails = $qrydetails->fetch_assoc();
		extract($rowdetails);
	}
	
	$quesnum = array('1a. ','1b. ','2a. ','2b. ','3a. ','3b. ');
?>
<section data-type='#library-diagmastery' id='library-diagmastery-review'>
	<script language="javascript">
        $('#testdetails').removeClass("active-first");
        $('#diagques').removeClass("active-mid");
        $('#mas1ques').removeClass("active-mid");
        $('#mas2ques').removeClass("active-mid");
        $('#review').parents().removeClass("dim");
        $('#review').addClass("active-last");
    </script>

	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $lessonname." Summary"; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row rowspacer'>
	        <div class='twelve columns formBase'>
    	    	<div class='row'>
			       	<div class='eleven columns centered insideForm' style="min-height:300px;">
                    	<div class='row'>                            
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Unit:</span>
                                <div class="wizardReportData"><?php echo $unitname; ?></div>
                          	</div>                      	
                            <div class='four columns'>
                            	<span class="wizardReportDesc">IPL:</span>
                                <div class="wizardReportData"><?php echo $lessonname; ?></div>
                          	</div>
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Weight:</span>
                                <div class="wizardReportData"><?php echo $lessonweight; ?></div>
                          	</div>
                            <div class='four columns'></div>
                      	</div>
                        
                        <div class='row rowspacer'>
                            <div class='six columns'>
                            	<div class="wizardReportDesc">Diagnostic Questions:</div>
                            	<?php
									$diagqcnt = 0;
									for($i=1;$i<=6;$i++){
										$diagqvalue = $ObjDB->SelectSingleValue("SELECT fld_question 
																				FROM itc_question_details 
																				WHERE fld_id='".${"diagques" . $i}."'");
										if($diagqvalue != ''){
								?>
                                <div id="ques_<?php echo ${"diagques" . $i} ?>" class="row" onclick="removesections('#library-diagmastery-review'); fn_showques(2,<?php echo ${"diagques" . $i} ?>,2)" style="cursor:pointer;word-wrap:break-word;">
                                    <div class="one columns"><?php echo  $quesnum[$i-1]; ?></div>
                                    <div class="eleven columns"><?php echo strip_tags($diagqvalue);?></div>
                                </div>
                                <?php
											$diagqcnt = 1;
										}
									}
									if($diagqcnt==0){
										echo "No Questions";	
									}
								?>
                          	</div>
                            <div class='six columns'>
								<div class="wizardReportDesc">Mastery1 Questions:</div>
                                <?php
									$m1qcnt = 0;
									for($j=1;$j<=6;$j++){
										$m1qvalue = $ObjDB->SelectSingleValue("SELECT fld_question 
																			FROM itc_question_details 
																			WHERE fld_id='".${"mast1ques".$j}."'");					
										if($m1qvalue != ''){
								?>
                                		<div id="ques_<?php echo ${"mast1ques" . $j} ?>" class="row" onclick="removesections('#library-diagmastery-review'); fn_showques(2,<?php echo ${"mast1ques" . $j} ?>,2)" style="cursor:pointer;word-wrap:break-word;">
                                            <div class="one columns"><?php echo  $quesnum[$j-1]; ?></div>
                                            <div class="eleven columns"><?php echo strip_tags($m1qvalue);?></div>
                                        </div>
                                <?php
											$m1qcnt = 1;
										}
									}
									
									if($m1qcnt==0){
										echo "No Questions";	
									}
									
								?>
                          	</div>
                      	</div>
                        
                        <div class='row rowspacer'>
                            <div class='six columns'>
                            	<div class="wizardReportDesc">Mastery2 Questions:</div>
                            	<?php
									$m2qcnt = 0;
									for($i=1;$i<=6;$i++){
										$m2qvalue = $ObjDB->SelectSingleValue("SELECT fld_question 
																				FROM itc_question_details 
																				WHERE fld_id='".${"mast2ques" . $i}."'");
										if($m2qvalue != ''){	
								?>
                                			<div id="ques_<?php echo ${"mast2ques" . $i} ?>" class="row" onclick="removesections('#library-diagmastery-review'); fn_showques(2,<?php echo ${"mast2ques" . $i} ?>,2)" style="cursor:pointer;word-wrap:break-word;">
                                                <div class="one columns"><?php echo  $quesnum[$i-1]; ?></div>
                                                <div class="eleven columns"><?php echo strip_tags($m2qvalue);?></div>
                                            </div>
                                <?php
											$m2qcnt = 1;
										}
									}
									
									if($m2qcnt==0){
										echo "No Questions";
									}
								?>
                          	</div>
                            <div class='six columns'></div>
                      	</div>
                   	</div>
              	</div>
           	</div>
      	</div>       
    </div>
</section>
<?php
	@include("footer.php");