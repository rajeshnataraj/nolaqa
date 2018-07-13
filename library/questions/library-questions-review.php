<?php 
	@include("sessioncheck.php");
	
	$id = isset($_POST['id']) ? $_POST['id'] : '0';
	
	$id = explode(",",$id);
	
	$unitname='';
	$lessonname='';
	$questiontype='';
	$filename='';
	$testtype='';
	
	$qrydetails = $ObjDB->QueryObject("SELECT c.`fld_unit_name` AS unitname, CONCAT(d.`fld_ipl_name`,' ',e.`fld_version`) AS lessonname, b.`fld_question_type` AS questiontype, 											a.`fld_file_name` AS filename, a.`fld_question_type_id` AS testtype 
										FROM `itc_question_details` a 
										LEFT JOIN `itc_question_type` b ON a.`fld_question_type_id`=b.`fld_id` 
										LEFT JOIN `itc_unit_master` c ON a.`fld_unit_id`=c.`fld_id` 
										LEFT JOIN `itc_ipl_master` d ON a.`fld_lesson_id`=d.`fld_id` 
										LEFT JOIN `itc_ipl_version_track` e ON d.`fld_id`=e.`fld_ipl_id`
										WHERE a.`fld_id`='".$id[0]."' AND a.`fld_delstatus`='0' AND b.`fld_delstatus`='0' AND c.`fld_delstatus`='0' 
										AND d.`fld_delstatus`='0' AND d.`fld_access`='1' AND e.`fld_zip_type`='1' 
										AND e.`fld_delstatus`='0'");
	if($qrydetails->num_rows > 0){
		$rowdetails = $qrydetails->fetch_assoc();
		extract($rowdetails);
	}
?>
<script language="javascript">

	$('#quesdetails').removeClass("active-first");
	$('#newques').removeClass("active-mid");
	$('#review').parents().removeClass("dim");
	$('#review').addClass("active-last");
	
</script>
<section data-type='#library-questions' id='library-questions-review'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="darkTitle">Review Your New Question</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row rowspacer'>        
        	<div class="twelve columns formBase">
            	<div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <div class='row'>
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Unit:</span>
                                <div class="wizardReportData"><?php echo $unitname; ?></div>
                          	</div>                      	
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Lesson:</span>
                                <div class="wizardReportData"><?php echo $lessonname; ?></div>
                          	</div>
                            <div class='four columns'>
                            	<span class="wizardReportDesc">Question Type:</span>
                                <div class="wizardReportData"><?php echo $questiontype; ?></div>
                          	</div>
                      	 </div>
                        <?php if($testtype != 1 and $filename != ''){?>
                        <div class='row rowspacer'>
                              <div class='twelve columns'>
                            	<span class="wizardReportDesc">Question Review:</span>
                                <div class="wizardReportData"><?php echo $filename; ?>
                                <input type="button" id="btnlibrary-questions-rempreview" value="Preview" style="margin-left:10px;" class="mainBtn darkButton" name="<?php echo $id[0]?>" align="right"/></div>
                                </div>
                      	 </div>
                        <?php } ?>
                        
                        <div class="row rowspacer">
                            <div class='twelve columns'>
                               <div class='row'>
                                 <div class='eleven columns' style="min-height:300px;">
                                    <span class="wizardReportDesc">Question Preview:</span>
                                    <div id="loadImg"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif"/></div>
                                    <iframe src="library/questions/library-questions-reviewiframe.php?id=<?php echo $id[0]; ?>" width="100%" height="10px" style="border:#F00;overflow:hidden" id="ifr_question3" onload="$('#loadImg').remove();autoResize('ifr_question3',1);"></iframe>
                                 </div>
                               </div>
                            </div>
                        </div>   
                        
                        <div class='row rowspacer'>
                            <div class='eight columns'>
                                <p class='btn secondary eight columns' style="float:left; margin-left:280px;">
                                    <a onclick="fn_savetest(<?php echo $id[0];?>)">Save Question</a>
                                </p>
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