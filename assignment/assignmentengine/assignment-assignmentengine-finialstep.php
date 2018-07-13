<?php
@include("sessioncheck.php");

$id= isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);	

$ObjDB->NonQuery("UPDATE `itc_test_student_mapping` SET `fld_test_pause`='0' 
                 WHERE fld_student_id='".$uid."' and fld_test_id='".$id[0]."'");
				 
$totalq = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM `itc_test_questionassign` AS a 
                                       LEFT JOIN itc_question_details AS b ON a.`fld_question_id`=b.`fld_id` 
									   WHERE a.fld_test_id='".$id[0]."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");


									   
$correctans = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_test_student_answer_track 
                                            WHERE fld_test_id='".$id[0]."' 
                                           AND fld_correct_answer='1' AND fld_student_id='".$uid."' and fld_schedule_id='".$id[2]."' AND fld_schedule_type='".$id[3]."' AND fld_delstatus='0'");
										   
$time = $ObjDB->SelectSingleValue("SELECT MIN(`fld_time_track`) FROM `itc_test_student_answer_track` 
                                   WHERE fld_test_id='".$id[0]."' AND fld_student_id='".$uid."'");

$opencount=$ObjDB->SelectSingleValue("SELECT count(fld_answer_type_id) FROM `itc_test_student_answer_track` 
                                   WHERE fld_test_id='".$id[0]."' AND fld_answer_type_id='15' AND fld_student_id='".$uid."' AND fld_delstatus='0'");
?>
<section data-type='2home' id='assignment-assignmentengine-finialstep'>
    <div class='container'>
        <div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle">Assignments and Grades</p>
          </div>
        </div>
        
        <div class='row rowspacer'>
          <div class='twelve columns formBase'>
            <div class='row' style="height:500px;">
                <div class='eleven columns centered insideForm'> <!--with in ".$time." mins -->
                    <div class='row buttons'>
                        <div class="wizardReportData" style="font-size:40px;">
                        <?php if($id[1] !="timex") { echo "You have completed the test.";}
                         else { echo "Your time is Exceeds"; }?>
                          <br/> You Answered <?php echo $correctans;?> out of <?php echo $totalq;?> Questions Correctly  
                          <?php if($opencount >= 1){ ?><br/><?php echo $opencount." Questions are open response which should be scored by your teacher"; } ?>
                          </div>
                        <div class='twelve columns'>
                            <div class='row'>
                                 <p class='btn' style="width:200px; height:40px; margin-top:250px; float:right">
                                    <a onclick="fn_closetest();" href="#">Close Test</a>
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
