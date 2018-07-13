<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	
	$id = explode(",",$id);
	$qrytestdetails = $ObjDB->QueryObject("SELECT `fld_test_name` AS testname, `fld_test_des` AS testdes, `fld_time_limit` AS testtime, fld_score AS score, 
	                                             `fld_max_attempts` AS testattempts, fld_question_type as qusttype FROM `itc_test_master` 
										 WHERE fld_id='".$id[0]."' AND `fld_delstatus`='0'");
		$rowtestdetails = $qrytestdetails->fetch_assoc();
		extract($rowtestdetails);
		
	
?>
<section data-type='#test-testassign' id='test-testassign-testreviewmain'>
	<script language="javascript">
        $('#newtest').removeClass("active-first");
        $('#testquestion').removeClass("active-mid");
        $('#testreview').parents().removeClass("dim");
        $('#testreview').addClass("active-last");
    </script>

	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Review your Assessment</p>
            	<p class="dialogSubTitleLight">Review your assessment details below.</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <div class="row">
                    <div class="four columns">
	                    <div class="row">
                            <div class="wizardReportDesc">Assessment Name:</div>
                            <div class="wizardReportData"><?php echo $testname;?></div>
                        </div>
                        <div class="row rowspacer">
	                        <div class="wizardReportDesc" style="width:235px;">Assessment Description:</div>
    	                    <div class="wizardReportData"><?php if(strlen($testdes)>60){ $temptestdes = substr($testdes,0,60)."..."; } else { $temptestdes =$testdes;} echo $temptestdes;?></div>
                        </div>
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Time Limit:</div>
                            <div class="wizardReportData"><?php echo $testtime;?></div>
                        </div>
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Assessment Score:</div>
                            <div class="wizardReportData"><?php echo $score;?></div>
                        </div>
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Questions:</div>
                            <div class="wizardReportData"><?php
                                $countquestion=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_questionassign WHERE fld_test_id='".$id[0]."' AND fld_delstatus='0'");
                                echo $countquestion;?>
                            </div>    
                        </div>
                         <?php if($qusttype !=2){ ?>
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Score Per Question:</div>
                            <div class="wizardReportData"><?php
                               if($countquestion !=0){
                                $questionweight=$score/$countquestion; 
                                echo round($questionweight,2);
								}
								else{
									echo "0";
								}
                            ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Number of Attempts:</div>
                            <div class="wizardReportData"><?php echo $testattempts;?></div>
                       	</div>
                    </div>
                    <div class="eight columns">
                    	<div class="row">
                            <div class="wizardReportDesc">Grading Scale:</div>
                            <div class="wizardReportData">
                            <?php
                                $qryscaledetails = $ObjDB->QueryObject("SELECT `fld_grade` AS grade, `fld_lower_bound` AS lowerb, `fld_upper_bound` AS upperb 
								                                       FROM   `itc_test_grading_scale_mapping` WHERE `fld_test_id`='".$id[0]."' "); 
                                     
                                if($qryscaledetails->num_rows!=0){ 
                                    while($row=$qryscaledetails->fetch_assoc()){
                                        extract($row);
                            ?>
                                        <div class="wizardReportData"><?php echo $grade.": ".$lowerb."+";?></div>
                            <?php
                                    }
                                }
                            ?>
                            </div>
                       	</div>
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Question List:</div>
                            <?php 
                                $qryqusdetails = $ObjDB->QueryObject("SELECT a.fld_question_id AS qid, b.fld_question AS qusname 
								                                     FROM `itc_test_questionassign` AS a, `itc_question_details` AS b 
																	 WHERE a.fld_question_id=b.fld_id AND a.fld_test_id='".$id[0]."' 
																	 AND a.fld_delstatus='0' ORDER BY a.`fld_order_by` ASC"); 
                                if($qryqusdetails->num_rows>0){
                                    $j=1;
                                    while($rowqusdetails = $qryqusdetails->fetch_assoc())
                                    {
                                        extract($rowqusdetails);
                                        if(strlen($qusname)>50){ $tempqusname = substr(strip_tags($qusname),0,50)."..."; } else { $tempqusname = strip_tags($qusname);}
                                        ?>
                                        <div class="wizardReportData" onclick="removesections('#test-testassign-testreviewmain');fn_showquestion(<?php echo $id[0];?>,<?php echo $qid;?>);" style="cursor:pointer;"><?php echo  $j.".".$tempqusname;?></div>
                                    <?php
                                        $j++;
                                    }
                                }
                                else
                                {
                            ?>
                                    <div class="wizardReportData">No Questions</div>
                            <?php
                                }
                            ?>
                            </div>
                            <div class="row rowspacer">
                            	<input type="hidden" id="hidqfilename" name="hidqfilename" value="<?php echo "assquestion_".time(); ?>" />
                            	<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="Print Questions" onClick="fn_downloadquestion(<?php echo $id[0];?>,1)" /> 
								<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right; margin-right: 20px;" value="Answer Key" onClick="fn_downloadanswer(<?php echo $id[0];?>,2)" />
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