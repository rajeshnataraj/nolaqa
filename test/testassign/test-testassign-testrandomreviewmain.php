<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	
	$id = explode(",",$id);
	
	$qrytestdetails = $ObjDB->QueryObject("SELECT `fld_test_name` AS testname, `fld_test_des` AS testdes, `fld_time_limit` AS testtime, fld_score AS score, 
	                                     `fld_max_attempts` AS testattempts FROM `itc_test_master` WHERE  fld_id='".$id[0]."' AND `fld_delstatus`='0'");
		$rowtestdetails = $qrytestdetails->fetch_assoc();
		extract($rowtestdetails);
		
	
?>
<section data-type='#test-testassign' id='test-testassign-testrandomreviewmain'>
	<script language="javascript">
        $('#newtest').removeClass("active-first");
        $('#testquestion').removeClass("active-mid");
        $('#testreview').parents().removeClass("dim");
        $('#testreview').addClass("active-last");
    </script>
    
	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Review Your Assessment</p>
            	<p class="dialogSubTitleLight">Review your random assessment details below, then click Save Assessment to complete this wizard process.</p>
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
                    </div>
                        <div class="four columns">
                        <div class="row">
                            <div class="wizardReportDesc">Grading Scale:</div>
                            <div class="wizardReportData"><?php
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
                        </div>
                            <div class="four columns">
                                <div class="row">
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
                        
                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Number of Attempts:</div>
                            <div class="wizardReportData"><?php echo $testattempts;?></div>
                       	</div>
                    </div>
                    <br>
            <?php  echo "Please&nbspnotice&nbsp that the questions listed below won't be sorted when presented to the students in the 
                       assessment. Questions are presented to the students randomly so each and every student will have a 
                       different assessment.";
                      ?>
                    <br>
                  <div class="row rowspacer">
                      <div class="wizardReportDesc">Questions List:</div>
                      <?php $qrytagsdetails = $ObjDB->QueryObject("select a.fld_tag_id as mtagids, a.fld_id as qtagid
                                                                    from itc_test_random_questionassign as a, itc_test_questionassign as b 
                                                                    where a.fld_rtest_id ='".$id[0]."' and a.fld_id=b.fld_tag_id and a.fld_delstatus='0' group by a.fld_tag_id ORDER BY b.fld_order_bytags ASC");
                            if($qrytagsdetails->num_rows>0){
                                while($rowtagdetails = $qrytagsdetails->fetch_assoc()){
                                     extract($rowtagdetails);
                                     
                                     $tagids = explode(',',$mtagids);
                                     for($z=0;$z<count($tagids);$z++)
                                        {
                                        $tagidss = explode('_',$tagids[$z]);	
                                        if($tagidss[1]=='testengine'){
                                                        $tquery= "Assessment questions";

                                                }
                                                else if($tagidss[1] =='lesson'){
                                                    $tquery= $ObjDB->SelectSingleValue("SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id='".$tagidss[0]."' and fld_delstatus ='0'");

                                                }
                                                
                                                else if($tagidss[1] =='diagnostic'){
                                                        $tquery= "Diagnostic Test";
                                                   }
                                                   else if($tagidss[1] =='mastery1'){
                                                        $tquery= "Mastery Test1";
                                                   }
                                                   else if($tagidss[1] =='mastery2'){
                                                        $tquery= "Mastery Test2";
                                                   }

                                                else{
                                                    if($tagidss[0] == 61){
                                                        $tquery= "MAEP"; 
                                                     }
                                                     else{
                                                        $tquery= $ObjDB->SelectSingleValue("SELECT fld_tag_name as ty FROM itc_main_tag_master WHERE fld_id='".$tagidss[0]."' and fld_delstatus ='0'");
                                                     }
                                                }
                                                ?>
                                                <div class="textboxlist-bit textboxlist-bit-box textboxlist-bit-box-deletable"> <?php echo $tquery; ?></div>
                                                <?php 
                                        }//for ends
                                        echo "<br/><br/>";  
                                                $qryqdetails = $ObjDB->QueryObject("SELECT a.fld_question_id AS qid, b.fld_question AS qusname 
                                                                                FROM `itc_test_questionassign` AS a, `itc_question_details` AS b 
                                                                                WHERE a.fld_question_id=b.fld_id AND a.fld_test_id='".$id[0]."' AND a.fld_tag_id='".$qtagid."' AND a.fld_delstatus='0' 
                                                                                ORDER BY a.`fld_order_by` ASC");
                                                if($qryqdetails->num_rows>0){
                                                    $j=1;
                                                    while($rowqdetails = $qryqdetails->fetch_assoc()){
                                                        extract($rowqdetails);
                                                       if(strlen($qusname)>50){ $tempqusname = substr(strip_tags($qusname),0,50)."..."; } else { $tempqusname =strip_tags($qusname);}
                                                        ?>
                                                            <div class="wizardReportData" onclick="fn_showquestionrandom(<?php echo $id[0];?>,<?php echo $qid;?>);" style="width:550px;cursor:pointer;"><?php echo  $j.".".trim(str_replace("&nbsp;", "",$tempqusname));?></div>
                                                        <?php
                                                        $j++;
                                                    }
                                                    
                                                }
                                                else{
                                                    ?><div class="wizardReportData">No Questions</div> <?php
                                                }
                                        echo "<br/>";   
 
                                       
                                }//while ends
                                
                            } //if ends
                      ?>
                      </div>
                    </div>
                </div>
                
                <div class='row rowspacer'>
                    <div class='four columns'>
                    </div>
                    <div class='four columns'>
                    </div>
                    <div class='four columns'>
                        <input type="hidden" id="hidqfilename" name="hidqfilename" value="<?php echo "assquestion_".time(); ?>" />
                        <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:left; margin-bottom: 40px;" value="Print Questions" onClick="fn_downloadquestionrandom(<?php echo $id[0];?>)" /> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");