<?php
@include('../includes/table.class.php');
@include('../includes/comm_func.php');	
$uid = isset($_POST['uid']) ? $_POST['uid'] : '';
$sessmasterprfid = 10;
//$oper = isset($_POST['oper']) ? $_POST['oper'] : '';
$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';
$ipadflag = isset($_REQUEST['ipadflag']) ? $_REQUEST['ipadflag'] : '';
$url = $domainame;
?>
<?php 
/*----------diagnostic start-------------*/ 
if($oper == "diagnosticstart" and $oper != '')
{
	$sid = isset($_POST['sid']) ? $_POST['sid'] : '';
	$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
	$mathtype = isset($_POST['testtype']) ? $_POST['testtype'] : '0';
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");
	?>
    <div class='row' style="height:75%;margin-top:5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns'>
                    <h3 class="blue">You are now ready to begin the Diagnostic Assessment for <?php echo $lessonname."."; ?> </h3>
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/start.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            
            <div class='row rowspacer'>
                 <div class="right five columns">
                 	<p class='btn secondary five columns '>
                        <a onclick="fn_question(<?php echo $sid;?>,<?php echo $lessonid;?>,1,1,0,0,<?php echo $mathtype; ?>);">Continue</a>
                    </p>                   	
                 </div>        
            </div>
         </div>
         <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
         <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />    
    </div>~<?php echo $lessonname; ?>      
    
<?php 	
}

//show questions
if($oper=="questionview" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$testtype = (isset($_POST['testtype'])) ?  $_POST['testtype'] : '';
	$qorder = (isset($_POST['qorder'])) ?  $_POST['qorder'] : '';
	$anscount = (isset($_POST['anscount'])) ?  $_POST['anscount'] : '';	
	$mathtype = (isset($_POST['mathtype'])) ?  $_POST['mathtype'] : '';	
	$qordernew=0;
	
		$quesqry=$ObjDB->QueryObject("select * from itc_diag_question_mapping where fld_lesson_id='".$lessonid."' and fld_access=1 and fld_delstatus=0");
		$questionids=$quesqry->fetch_object();
		
		if($testtype==1)
		{
			if($qorder==1)
			{
				if($mathtype==2)
					$classid = $ObjDB->SelectSingleValueInt("select fld_class_id from itc_class_rotation_schedule_mastertemp where fld_id='".$sid."'");
				else if($mathtype==5)
					$classid = $ObjDB->SelectSingleValueInt("select fld_class_id from itc_class_indassesment_master where fld_id='".$sid."'");
				else	
					$classid = $ObjDB->SelectSingleValueInt("select fld_class_id from itc_class_sigmath_master where fld_id='".$sid."'");
				$qrypoint=$ObjDB->QueryObject("select fld_ipl_points as points,fld_unit_id as unitid from itc_ipl_master where fld_id='".$lessonid."'");
				$res = $qrypoint->fetch_assoc();
				extract($res);
				
				if($mathtype!=10){										
					$chkassess = $ObjDB->SelectSingleValueInt("select fld_id from itc_assignment_sigmath_master where fld_class_id='".$classid."' and fld_schedule_id='".$sid."' and fld_unit_id='".$unitid."' and fld_lesson_id='".$lessonid."' and fld_student_id='".$uid."' and fld_delstatus='0'");
					$fld_grade=0;
					if($chkassess==0){	
						$gradepoint = $ObjDB->QueryObject("select fld_points as points,fld_grade from itc_class_sigmath_grade where fld_schedule_id='".$sid."' and fld_lesson_id='".$lessonid."' and fld_flag='1'");
						if($gradepoint->num_rows>0){
							extract($gradepoint->fetch_assoc());							
						}
						else
						{
							$fld_grade = '1';
							$points = 100;
						}		
						$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_master(fld_class_id,fld_schedule_id,fld_unit_id, fld_lesson_id, fld_student_id, fld_created_date, fld_type, fld_status, fld_points_possible, fld_test_type, fld_grade) values('".$classid."','".$sid."','".$unitid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."','1','0','".$points."','".$mathtype."','".$fld_grade."')");
						$maxid=$ObjDB->SelectSingleValueInt("select MAX(fld_id) from itc_assignment_sigmath_master");						
					}
					else{
						$maxid=$chkassess;
					}						
				}
				if($mathtype==2)
					$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$sid."' AND fld_flag='1' AND fld_delstatus='0' AND fld_moduletype='2'");
				else if($mathtype==5)
					$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_indassesment_master WHERE fld_id='".$sid."' AND fld_flag='1' AND fld_delstatus='0'");
				else
					$licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_sigmath_master WHERE fld_id='".$sid."' AND fld_flag='1' AND fld_delstatus='0'");
								
				$stcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track_student WHERE fld_student_id='".$uid."'AND fld_license_id='".$licenseid."'AND fld_flag='1'");
				
				if($stcount==0 && $mathtype!=10)
				{
					if($mathtype==2)
						$qryschedules = $ObjDB->QueryObject("SELECT fld_id FROM itc_class_rotation_schedule_mastertemp WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'  AND fld_moduletype='2'");
					else if($mathtype==5)
						$qryschedules = $ObjDB->QueryObject("SELECT fld_id FROM itc_class_indassesment_master WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'  AND fld_moduletype='2'");
					else
						$qryschedules = $ObjDB->QueryObject("SELECT fld_id FROM itc_class_sigmath_master WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'");
					
					//$qryschedules = $ObjDB->QueryObject("SELECT fld_id FROM itc_class_sigmath_master WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'");
					if($qryschedules->num_rows > 0){
						$schedules='';
						while($resqryschedules=$qryschedules->fetch_assoc()){
							extract($resqryschedules);
							if($schedules=='')
								$schedules = $fld_id;
							else
								$schedules = $schedules.','.$fld_id;
						}
					}		
					
					if($districtid!='0' && $schoolid!='0' && $indid=='0')
						$licenseiplcount = $ObjDB->SelectSingleValueInt("SELECT fld_ipl_count FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."' ORDER BY fld_id LIMIT 0,1");
					
					else
						$licenseiplcount = $ObjDB->SelectSingleValueInt("SELECT fld_ipl_count FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."' ORDER BY fld_id LIMIT 0,1");
					
					$iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(DISTINCT(fld_lesson_id)) AS iplcount FROM itc_assignment_sigmath_master WHERE fld_schedule_id IN (".$schedules.") AND fld_student_id='".$uid."' AND fld_test_type='1' AND fld_delstatus='0'");
					
					if($iplcount > $licenseiplcount)
					{
						$qryrem = $ObjDB->QueryObject("SELECT fld_id, fld_remain_users FROM itc_license_track WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."'");
						
						if($qryrem->num_rows > 0){
							$resqryrem = $qryrem->fetch_assoc();
							extract($resqryrem);
							if($fld_remain_users==0){?>
                            	<script>
									alert("Your license limt has been exceed. Please contact your teacher or school admin.");
									window.location="index.php";
								</script>
                            <?php 
								exit();
							}
							$remusers = $fld_remain_users - 1;
							$ObjDB->NonQuery("UPDATE itc_license_track SET fld_remain_users='".$remusers."' WHERE fld_id='".$fld_id."'");
							
							$ObjDB->NonQuery("INSERT INTO itc_license_track_student(fld_student_id, fld_license_id, fld_flag,fld_created_by,fld_created_date) values('".$uid."', '".$licenseid."', '1','".$uid."','".date("Y-m-d H:i:s")."')");
						}
					}
				}
							
				$qordernew=2;
				
				$input = array($questionids->fld_diag_ques1a,$questionids->fld_diag_ques1b);
				if($sessmasterprfid==10)
					$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','diagstart','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
			}
			else if($qorder==2)
			{
				$qordernew=3;
				$input = array($questionids->fld_diag_ques2a,$questionids->fld_diag_ques2b);
			}
			else if($qorder==3)
			{
				$qordernew=0;
				$input = array($questionids->fld_diag_ques3a,$questionids->fld_diag_ques3b);
			}
			
			/*-------------select random questions-------------*/			
			shuffle($input);
			foreach ($input as $number) {
				$quesid = $number;
				$chk = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_assignment_sigmath_answer_track where fld_track_id='".$maxid."' and fld_question_id='".$quesid."'");
				if($chk==0){
					break;
				}	
			}	
			
		}
		

		if($testtype==2)
		{
			if($qorder==1)
			{				
				$input = array($questionids->fld_mast1_ques1a,$questionids->fld_mast1_ques1b);
				$qordernew=2;
			}
			else if($qorder==2)
			{	
				$input = array($questionids->fld_mast1_ques1a,$questionids->fld_mast1_ques1b);		
				$qordernew=3;
			}
			else if($qorder==3)
			{
				$input = array($questionids->fld_mast1_ques2a,$questionids->fld_mast1_ques2b);
				$qordernew=4;
			}
			else if($qorder==4)
			{
				$input = array($questionids->fld_mast1_ques2a,$questionids->fld_mast1_ques2b);
				$qordernew=5;
			}
			else if($qorder==5)
			{				
				$input = array($questionids->fld_mast1_ques3a,$questionids->fld_mast1_ques3b);
				$qordernew=6;
			}
			else if($qorder==6)
			{
				$input = array($questionids->fld_mast1_ques3a,$questionids->fld_mast1_ques3b);
				$qordernew=0;
			}
			/*-------------select random questions-------------*/
			shuffle($input);
			foreach ($input as $number) {
				$quesid = $number;
				$chk = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_assignment_sigmath_answer_track where fld_track_id='".$maxid."' and fld_question_id='".$quesid."'");
				if($chk==0){
					break;
				}
			}	

		}

		if($testtype==3)
		{
			if($qorder==1)
			{
				$input = array($questionids->fld_mast2_ques1a,$questionids->fld_mast2_ques1b);
				$qordernew=2;
			}
			else if($qorder==2)
			{
				$input = array($questionids->fld_mast2_ques1a,$questionids->fld_mast2_ques1b);
				$qordernew=3;
			}
			else if($qorder==3)
			{
				$input = array($questionids->fld_mast2_ques2a,$questionids->fld_mast2_ques2b);
				$qordernew=4;
			}
			else if($qorder==4)
			{
				$input = array($questionids->fld_mast2_ques2a,$questionids->fld_mast2_ques2b);
				$qordernew=5;
			}
			else if($qorder==5)
			{
				$input = array($questionids->fld_mast2_ques3a,$questionids->fld_mast2_ques3b);
				$qordernew=6;
			}
			else if($qorder==6)
			{
				$input = array($questionids->fld_mast2_ques3a,$questionids->fld_mast2_ques3b);
				$qordernew=0;
			}
			shuffle($input);
			foreach ($input as $number) {
				$quesid = $number;
				$chk = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_assignment_sigmath_answer_track where fld_track_id='".$maxid."' and fld_question_id='".$quesid."'");
				if($chk==0){
					break;
				}
			}	
		}

		$status = "fn_anscheck(".$sid.",".$lessonid.",".$testtype.",".$quesid.",".$anscount.",".$maxid.");";	
		?>
        <iframe id="ifrm" src="<?php echo $url; ?>api/assignment-sigmath-questioniframe.php?id=<?php echo $quesid;?>" width="100%" height="100%" frameborder="0" style="overflow:auto;-webkit-overflow-scrolling: touch;" ></iframe>
        <div style="padding-right:30px;">
            <div class="right five columns">
                <p id="ctnbtn" class='btn secondary five columns' style="height: 5%;line-height: 30px;font-size: 20px;margin: 0;">
                    <a onclick="<?php echo $status; ?>">Continue</a>
                </p>                   	
             </div> 
        </div>
        <!--<div class='row' style=" height: 85%; margin-top: 2%;">
        	 <div class='twelve columns' style="height:100%;">
             	<div class="row" style="height:100%;">
                	<div class='twelve columns' style="height:100%;">
             			
                    </div>
            
                
                
             </div>        
        </div>  -->
        <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
        <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  /> 
        <input type="hidden" value="<?php echo $qordernew; ?>" id="qorder" />
        <input type="hidden" id="current_qorder" value="<?php echo $qorder; ?>" />
        <input type="hidden" id="maxid" value="<?php echo $maxid; ?>" />
        <input type="hidden" id="testtype" value="<?php echo $testtype; ?>" />
		<?php 	
}

//answer check	
if($oper=="answercheck" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';
	$anstype = (isset($_POST['anstype'])) ?  $_POST['anstype'] : '';
	$quesid = (isset($_POST['quesid'])) ?  $_POST['quesid'] : '';
	$anscount = (isset($_POST['anscount'])) ?  $_POST['anscount'] : '';
	$answer = (isset($_POST['answer'])) ?  $_POST['answer'] : '';
	$answer1 = (isset($_POST['answer'])) ?  $_POST['answer'] : '';
	$testtype = (isset($_POST['testtype'])) ?  $_POST['testtype'] : '';
	$cqorder = (isset($_POST['cqorder'])) ?  $_POST['cqorder'] : '';
	$show = 0;
	$count=0;
	if($cqorder==2 or $cqorder==4 or $cqorder==6 or $testtype==1){
		$show = 1;
	}
	$tempanscount=0;	
	if($anstype==1 or $anstype==8)
	{
		$answer=explode(",",$answer);		
		$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' FROM itc_question_answer_mapping WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_answer<>'' AND fld_flag='1'");
		$row = $qry->fetch_assoc();
		extract($row);
		$actualanswer = explode('~',$correct);
		if(sizeof($answer)==sizeof($actualanswer)){ //check the student answered count and actual correct answer count			
			for($i=0;$i<sizeof($actualanswer);$i++){
				if(in_array($answer[$i],$actualanswer)){
					$tempanscount++;
				}
			}
		}
		if($tempanscount==sizeof($actualanswer)){
			$count=1;
		}
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, fld_test_type,
														 fld_correct_answer, fld_show)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."', 
							 	fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
		
	}
	
	
	if($anstype==2 or $anstype==5)
	{
		if($anstype==2){
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_question_answer_mapping where fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1' AND LOWER(REPLACE(fld_answer, ' ', ''))='".strtolower(str_replace(' ', '', $answer))."'");
		}
		else{
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_question_answer_mapping where fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' AND LOWER(REPLACE(fld_answer, ' ', ''))='".strtolower(str_replace(' ', '', $answer))."'");
		}
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, fld_test_type, 
													fld_correct_answer, fld_show) 
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."',
							 fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
		
	}
	
	
	if($anstype==3)
	{
		$tempanscount=0;
		$answer=explode("~",$answer);
		$qry = $ObjDB->QueryObject("SELECT fld_answer AS canswer FROM itc_question_answer_mapping WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='4' AND fld_flag='1' ORDER BY fld_boxid ASC");
		$actual_anscount = $qry->num_rows;
		$answerarray=array();
		$i=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			if($canswer==$answer[$i]){
				$tempanscount++;
			}
			$i++;
		}
		if($actual_anscount==$tempanscount){
			$count=1;
		}
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, 
													fld_test_type, fld_correct_answer, fld_show) 
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");	
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."', 
							 fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
	}
	
	if($anstype==4)
	{
		$tempanscount=0;
		$answer=explode("~",str_replace(' ', '', $answer));
		$order = $ObjDB->SelectSingleValueInt("SELECT fld_answer 
											FROM itc_question_answer_mapping 
											WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='9' AND fld_flag='1'");
		$answerdesign = $ObjDB->SelectSingleValue("SELECT fld_answer 
												  FROM itc_question_answer_mapping 
												  WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='6' AND fld_flag='1'");			
		$answerdesign = explode(',',$answerdesign);	
		$values = $ObjDB->SelectSingleValue("SELECT fld_answer 
											FROM itc_question_answer_mapping 
											WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='7' AND fld_flag='1'");
		$values = explode(',',str_replace(' ', '', $values));	
		$k=0;
		$j=0;
		$tmpcount = 0;
		$tmparray = array();
		//$order =1;
		$maincount = 0;
		for($i=0;$i<sizeof($answerdesign);$i++){			
			if($answerdesign[$i]==5 or $answerdesign[$i]==4 or $answerdesign[$i]==17 or $answerdesign[$i]==18 or $answerdesign[$i]==20 or $answerdesign[$i]==21 or $answerdesign[$i]==22 or $answerdesign[$i]==23 or $answerdesign[$i]==24 ){				
				//if($i!=0) $j++;
				
				if($answerdesign[$i]!=5){							
					if($answerdesign[$i]==17)	
					{
						for($m=0;$m<2;$m++)
						{
							$tmparray[]=$values[$j];
							if(strtolower(str_replace(' ', '', $values[$j]))==strtolower(str_replace(' ', '', $answer[$k]))){
								$tempanscount++;								
							}
							$k++;
								$j++;
								$tmpcount++;
						}
					}
					else if($answerdesign[$i]==18)	
					{
						for($m=0;$m<3;$m++)
						{
							$tmparray[]=$values[$j];
							if(strtolower(str_replace(' ', '', $values[$j]))==strtolower(str_replace(' ', '', $answer[$k]))){
								$tempanscount++;
								
							}
							$k++;
								$j++;
								$tmpcount++;
						}
					}
					else if(strtolower(str_replace(' ', '', $values[$j]))==strtolower(str_replace(' ', '', $answer[$k]))){
						$tmparray[]=$values[$j];
						$tempanscount++;
						$k++;
						$j++;
						$tmpcount++;
					}
					else{
						$tmparray[]=$values[$j];
						$k++;
						$j++;
						$tmpcount++;
					}
				}
				else
					$j++;
				$maincount++;
			}			
		}		
		if($tmpcount==$tempanscount and $order==1) {
			$count=1;
		}
		else if($order==0) {
			//print_r($tmparray);
			$result=array_diff(array_filter(array_map('strtolower', $tmparray)),array_filter(array_map('strtolower', $answer)));
			if(empty($result) and sizeof(array_filter($tmparray))==sizeof(array_filter($answer)))
			$count=1;
		}			
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, 
													fld_test_type, fld_correct_answer, fld_show)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."',
							 fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
	}
	
	if($anstype==6)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1'");
		$answerarray=array();
		$i=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			$answerarray[$i]=$fld_answer;
			$i++;
		}	
		
		$tempanscount=0;
		$answer=explode("~",$answer);
		
		if($answerarray[0]==$answer[0])
		{
			if($answerarray[1]==$answer[1])
			{
				if($answerarray[2]==$answer[2])
				{
					if($answerarray[3]==$answer[3])
					{
						$count=1;
					}
				}
				else
				{
					if($answerarray[2]==$answer[3])
					{
						if($answerarray[3]==$answer[2])
						{
							$count=1;
						}
					}
				}
			}
		}
		else if($answerarray[0]==$answer[1])
		{
			if($answerarray[1]==$answer[0])
			{
				if($answerarray[2]==$answer[2])
				{
					if($answerarray[3]==$answer[3])
					{
						$count=1;
					}
				}
				else
				{
					if($answerarray[2]==$answer[3])
					{
						if($answerarray[3]==$answer[2])
						{
							$count=1;
						}
					}
				}
			}
		}		
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, 
													fld_test_type, fld_correct_answer, fld_show)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");	
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."',
							 	fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
	}
	
	if($anstype==7)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1' ORDER BY fld_boxid ASC");
		$answerarray=array();
		$i=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			$answerarray[$i]=$fld_answer;
			$i++;
		}	
		
		if(str_replace(' ', '', $answer)>=str_replace(' ', '', $answerarray[0]) and str_replace(' ', '', $answer)<=str_replace(' ', '', $answerarray[1])){
			$count=1;
		}
		
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, 
													fld_test_type, fld_correct_answer, fld_show)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");	
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."',
							 	fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
		
	}
	
	if($anstype==9)
	{
		$qry = $ObjDB->QueryObject("SELECT LCASE(fld_answer) as fld_answer FROM itc_question_answer_mapping WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1'");		
		$i=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			if(strtolower(str_replace(' ', '', $fld_answer))==str_replace(' ', '', strtolower($answer))){
				$count=1;
				break;
			}
			$i++;
		}
		
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, fld_test_type, 
													fld_correct_answer, fld_show)
												 VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."')");	
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."', 
							 fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
		
	}
		
	echo $anscount+$count."~".$count."~".$quesid."~".$chk;
}

//diagnostic pass	
if($oper=="diagpass" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';	
	$points=$ObjDB->SelectSingleValueInt("select fld_ipl_points from itc_ipl_master where fld_id='".$lessonid."'");
	$ObjDB->NonQuery("update itc_class_sigmath_master set fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$sid."'");	
	if($maxid!=0)
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_status=1,fld_points_earned='".$points."' where fld_id='".$maxid."'");	
	$mathtype = $ObjDB->SelectSingleValueInt("select fld_test_type from itc_assignment_sigmath_master where fld_id='".$maxid."'");
	if($mathtype==1){
		/*Getting next lesson*/
		$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') ORDER BY fld_order LIMIT 0,1");
		if($lessonid==0){		
			$function = "fn_completed(".$sid.")";								
		}
		else{
			$function = "fn_diagnosticstart(".$sid.",".$lessonid.",".$mathtype.")";
		}
	}
	else if($maxid==0)
	{
		$function = "closefullscreenlesson()";
	}
	else{
		$function = "fn_mathmodulenextlesson(".$sid.",".$lessonid.",".$mathtype.")";
	}
	?>
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                    <h3 class="blue">Diagnostic Test Completed <br/> You answered all of the questions correctly.<?php if($maxid!=0){ ?><br />Click Continue to advance to the next lesson.<?php }?></h3> 
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/success.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="<?php echo $function; ?>">Continue</a>
                    </p>                   	
               	</div>       
            </div>
         </div>        
    </div>  
    <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />      
   <?php
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
}

//diagnostic fail	
if($oper=="diagfail" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';
	if($maxid!=0)
	{
		$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type=6 where fld_id='".$maxid."'");	
		
		$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
	}
	else
	{
		$function = "closefullscreenlesson()";
	}
	
	?>
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                	<h3 class="blue">You did not master <?php echo $lessonname."."; ?><?php if($maxid!=0){ ?><br/> You are ready to begin the <?php echo $lessonname; ?> Lesson.<?php }?></h3>
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/fail.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
                 <div class="right five columns">
                 	<p class='btn primary five columns'>
                        <a onclick="<?php echo $function; ?>">Continue</a>
                    </p>                   	
               	</div>               
            </div>
         </div>        
    </div>  
    <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />        
   <?php 
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
}

//playing the lesson
if($oper == "playlesson" and $oper != '')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';
	$orientationflag = (isset($_POST['orientationflag'])) ?  $_POST['orientationflag'] : '0';
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'"); 	
	

	$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='2', fld_status=0 where fld_id='".$maxid."'");
	?>
    <input type="hidden" id="lessonid" value="<?php echo $lessonid; ?>" />
        <input type="hidden" id="maxid" value="<?php echo $maxid; ?>" />
        <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>" /> 
    <?php
}


//check if the lesson is completed or not
if($oper == "slidecheck" and $oper != '')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';		
	if($lessonid == 79)
	{
		$tmpstatus=$ObjDB->SelectSingleValue("select varValue from itc_assignment_lesson_scorm_track where SCOInstanceID='".$maxid."' and varName='cmi.suspend_data'");
		$status=explode("|",$tmpstatus);
		if($status[1]== "lastviewedslide=17")
		{
			echo "completed";
		}
	}		
	else
	{
		echo $ObjDB->SelectSingleValue("select varValue from itc_assignment_lesson_scorm_track where SCOInstanceID='".$maxid."' and varName='cmi.core.lesson_status'");
	}
}

//check if the remediation is completed or not
if($oper == "remslidecheck" and $oper != '')
{	
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';
	echo $ObjDB->SelectSingleValue("select varValue from itc_assignment_rem_scorm_track where SCOInstanceID='".$maxid."' and varName='cmi.core.lesson_status'");
}

if($oper == "checklockstatus" and $oper != '')
{	
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';		
	echo $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_assignment_sigmath_master where fld_id='".$maxid."' and fld_type='4'");
}

//review the lesssons
if($oper == "review" and $oper != '')
{
	$sid = (isset($_REQUEST['sid'])) ?  $_REQUEST['sid'] : '';
	$lessonid = (isset($_REQUEST['lessonid'])) ?  $_REQUEST['lessonid'] : '';
	$maxid = (isset($_REQUEST['maxid'])) ?  $_REQUEST['maxid'] : '';
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	$lessonpath = $ObjDB->SelectSingleValue("select fld_zip_name from itc_ipl_version_track where fld_ipl_id='".$lessonid."' and fld_zip_type='1' and fld_delstatus='0'");
	$foldername= str_replace('.zip','',$lessonpath);
	$dir = "../uploaddir/s3/webipl/".$foldername."/";
	$files1 = scandir($dir);
	if($ipadflag==2)
	$finallessonpath= "../uploaddir/s3/webipl/".$foldername."/".$files1[2]."/index_lms.html";
	else
	$finallessonpath= "../uploaddir/s3/webipl/".$foldername."/".$files1[2]."/index_lms_html5.html";
	?>
    	
    	<iframe src="<?php echo $url."vscorm/rte1.php?SCOInstanceID=".$maxid."&lessonid=".$lessonid."&studentid=".$uid."&lessonpath=".urlencode($finallessonpath)?>" width="100%" height="100%;" style="border:none;margin:0 auto;"></iframe>
	
            
            <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
   			<input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />  
     <?php 
}

/*----------mastery1 start-------------*/ 
if($oper == "mastery1start" and $oper != '')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$maxid = isset($_POST['maxid']) ? $_POST['maxid'] : '0';
	$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	if($maxid!=0)
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='3', fld_status=0 where fld_id='".$maxid."'");
	?>
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                	<h3 class="blue">You are now ready to begin the  <br/> Mastery Test 1 for <?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");?>. </h3>
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/start.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="fn_question(<?php echo $sid;?>,<?php echo $lessonid;?>,2,1,0,<?php echo $maxid; ?>);">Continue</a>
                    </p>                   	
               	</div>
            </div>
         </div> 
         <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    	 <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />         
    </div> ~<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'"); ?>
<?php 	
}

//remediation play
if($oper == "remediation" and $oper != '')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$quesid = (isset($_POST['quesid'])) ? $_POST['quesid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ? $_POST['lessonid'] : '';
	$testtype = (isset($_POST['testtype'])) ? $_POST['testtype'] : '';
	$qorder = (isset($_POST['qorder'])) ? $_POST['qorder'] : '';
	$anscount = (isset($_POST['anscount'])) ? $_POST['anscount'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';
	$ansmaxid = (isset($_POST['ansmaxid'])) ?  $_POST['ansmaxid'] : '0';
	$rfile=$ObjDB->SelectSingleValue("select fld_file_name from itc_question_details where fld_id='".$quesid."'");
	
	if($qorder==0){
		if($testtype==2)
		{
			$status="fn_mastery1finish(".$sid.",".$lessonid.",".$anscount.",".$maxid.");";		
		}
		else
		{
			$status="fn_mastery2finish(".$sid.",".$lessonid.",".$anscount.",".$maxid.");";	
		}
	}
	else
	{
		$status= "fn_question(".$sid.",".$lessonid.",".$testtype.",".$qorder.",".$anscount.",".$maxid.");";	
	} 		
	echo $status."~".$rfile;
}


//mastery1pass
if($oper=="mastery1pass" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';

	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';	
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	$ObjDB->NonQuery("update itc_class_sigmath_master set fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$sid."'");	
	if($maxid!=0)
	{
		$points=$ObjDB->SelectSingleValueInt("select fld_ipl_points from itc_ipl_master where fld_id='".$lessonid."'");
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type=3,fld_status=1,fld_points_earned='".$points."' where fld_id='".$maxid."'");	
		$mathtype = $ObjDB->SelectSingleValueInt("select fld_test_type from itc_assignment_sigmath_master where fld_id='".$maxid."'");
	}
	if($mathtype==1){
		/*Getting next lesson*/
		$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') ORDER BY fld_order  LIMIT 0,1");
		if($lessonid==0){		
			$function = "fn_completed(".$sid.")";								
		}
		else{
			$function = "fn_diagnosticstart(".$sid.",".$lessonid.",".$mathtype.")";
		}
	}
	else if($maxid==0)
	{
		$function = "closefullscreenlesson()";
	}
	else{
		$function = "fn_mathmodulenextlesson(".$sid.",".$lessonid.",".$mathtype.")";
	}
	
	?>
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                   Mastery Test Completed <br/> You have completed the lesson.<?php if($maxid!=0){?><br />Click Continue to advance to the next lesson. <?php }?> 
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/success.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="<?php echo $function; ?>">Continue</a>
                    </p>                   	
               	</div>      
            </div>
         </div>
         <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    	 <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />         
    </div>~<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'"); ?>
   <?php
}

//mastery1fail
if($oper=="mastery1fail" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$maxid = isset($_POST['maxid']) ? $_POST['maxid'] : '0';
	$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");

	if($maxid!=0)
	{
		$mathtype = $ObjDB->SelectSingleValueInt("select fld_test_type from itc_assignment_sigmath_master where fld_id='".$maxid."'");
		$chkstatus = $ObjDB->SelectSingleValueInt("select fld_lockstatus from itc_assignment_sigmath_master where fld_id='".$maxid."'");		
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='5', fld_status=0, fld_updated_date='".date("Y-m-d H:i:s")."', fld_lockstatus='0' where fld_id='".$maxid."'");		
	
		if($mathtype==2){
			$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
			$name = "Continue";
		}
		else{
			$tempver = explode(" ", $lessonname);
			$versionno =  end($tempver);

			if($versionno == '1.0.0'){  
				$function = "javascript:void(0);";
			}
			else {
				$function = "fn_review(".$sid.",".$lessonid.",".$maxid.")";
			}
			
			$name = "Review";
		}
	}
	else
	{
		$function = "closefullscreenlesson()";
		$name = "Continue";
	}
	?>
    
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                  You did not master the test for <?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");?>. <br/><br/><?php if($maxid!=0) { ?>
                        You will not advance until the teacher grants <br/> access to move forward. <?php }?>
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/lock.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
                <div class="right five columns">
                 	<p class='btn primary five columns'>
                        <a onclick="<?php echo $function; ?>" ><?php echo $name; ?></a>
                    </p>                   	
               	</div>        
            </div>
         </div>        
    </div>
    <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    	 <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  /> 
         <input type="hidden" id="testtype" value="5" />
    <script type="text/javascript">
		var lockstatus = setInterval("fn_checklockstatus(<?php echo $sid;?>,<?php echo $lessonid;?>,<?php echo $maxid;?>)",5000);
	</script> ~<?php echo $lessonname; ?>
        <?php 
}

/*----------mastery2 start-------------*/ 
if($oper == "mastery2start" and $oper != '')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$maxid = isset($_POST['maxid']) ? $_POST['maxid'] : '0';
	$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	if($maxid!=0)
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='4', fld_status=0 where fld_id='".$maxid."'");
	?>    
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                  You are now ready to begin the  <br/> Mastery Test 2 for <?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'");?>. 
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/success.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
                <div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="fn_question(<?php echo $sid;?>,<?php echo $lessonid;?>,3,1,0,<?php echo $maxid; ?>);">Continue</a>
                    </p>                   	
               	</div>       
            </div>
         </div> 
         <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    	 <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />        
    </div>  ~<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'"); ?>  
<?php 	
}

//mastery2pass
if($oper=="mastery2pass" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '0';	
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	$ObjDB->NonQuery("update itc_class_sigmath_master set fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$sid."'");	
	if($maxid!=0)
	{
		$points=$ObjDB->SelectSingleValueInt("select fld_ipl_points from itc_ipl_master where fld_id='".$lessonid."'");
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type=4,fld_status=1,fld_points_earned='".$points."' where fld_id='".$maxid."'");	
		
		$mathtype = $ObjDB->SelectSingleValueInt("select fld_test_type from itc_assignment_sigmath_master where fld_id='".$maxid."'");
		if($mathtype==1){
			/*Getting next lesson*/
			$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') ORDER BY fld_order LIMIT 0,1");
			if($lessonid==0){		
				$function = "fn_completed(".$sid.")";								
			}
			else{
				$function = "fn_diagnosticstart(".$sid.",".$lessonid.",".$mathtype.")";
			}
		}
		else{
			$function = "fn_mathmodulenextlesson(".$sid.",".$lessonid.",".$mathtype.")";
		}	
	}
	else
	{
		$function = "closefullscreenlesson()";
	}
	?>
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                  <h3 class="blue">Mastery Test Completed. <br/> You have completed the lesson.<?php if($maxid!=0){?><br />Click Continue to advance to the next lesson.<?php }?></h3>
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/success.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="<?php echo $function; ?>">Continue</a>
                    </p>                   	
               	</div>       
            </div>
         </div> 
         <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    	 <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />        
    </div>~<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid."'"); ?>
   <?php
}

//mastery2fail
if($oper=="mastery2fail" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$maxid = isset($_POST['maxid']) ? $_POST['maxid'] : '0';
	$lessonid = isset($_POST['lessonid']) ? $_POST['lessonid'] : '';
	$lessonid1 = $lessonid;
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".date("Y-m-d H:i:s")."')");
	$ObjDB->NonQuery("update itc_class_sigmath_master set fld_updated_date='".date("Y-m-d H:i:s")."' where fld_id='".$sid."'");	
	if($maxid!=0)
	{
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='4', fld_status=2, fld_points_earned='0' where fld_id='".$maxid."'");
		
		$mathtype = $ObjDB->SelectSingleValueInt("select fld_test_type from itc_assignment_sigmath_master where fld_id='".$maxid."'");
		if($mathtype==1){
			/*Getting next lesson*/
			$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') ORDER BY fld_order LIMIT 0,1");
			if($lessonid==0){		
				$function = "fn_completed(".$sid.")";								
			}
			else{
				$function = "fn_diagnosticstart(".$sid.",".$lessonid.",".$mathtype.")";
			}		
		}
		else{
			$function = "fn_mathmodulenextlesson(".$sid.",".$lessonid.",".$mathtype.")";
		}	
	}
	else
	{
		$function = "closefullscreenlesson()";
	}
	
		?>
    <div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                 You did not master the test for <?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid1."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid1."'");?>.
                </div>
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/fail.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn primary five columns'>
                        <a onclick="<?php echo $function; ?>">Continue</a>
                    </p>                   	
               	</div>        
            </div>
         </div>  
         <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    	 <input type="hidden" id="lid" value="<?php echo $lessonid; ?>"  />       
    </div> ~<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid1."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".$lessonid1."'"); ?> 
   <?php 
}

//unlock
if($oper=="unlock" and $oper!='')
{
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';
	$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='4', fld_status=0, fld_unlocked_by='".$uid."', fld_unlocked_date='".date("Y-m-d H:i:s")."' where fld_id='".$maxid."'");
}

//completed
if($oper=="completed" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
?> 
	<div class='row' style="height: 75%;   margin-top: 5%;">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
	                <h3 class="blue">You have completed all the lessons from the <?php echo $ObjDB->SelectSingleValue("SELECT fld_schedule_name from itc_class_sigmath_master where fld_id='".$sid."'"); ?>  Schedule.<br/><br/>                 
                	<b>Congratulations.</b>
                	</h3>
                </div>   
                <div class='six columns'>
                	<img src="<?php echo $url; ?>img/success.png" style="border:0px;margin-left:15%;" />
                </div>             
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="fn_closescreen();">Finish</a>
                    </p>                   	
               	</div>       
            </div>
         </div>        
    </div>  
    <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"  />   
    <input type="hidden" id="lid" value="0"  />  
	<?php
}

//unload
if($oper=="unload" and $oper!='')
{
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';
	$testtype = (isset($_POST['testtype'])) ?  $_POST['testtype'] : '';	
	$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type='".($testtype+5)."' where fld_id='".$maxid."'");
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_date) values('".$maxid."','".$uid."','".$oper."','0','0','".date("Y-m-d H:i:s")."')");
}

//nextlessonn for mathmodule
if($oper=="nextlesson" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonids = (isset($_POST['lessonids'])) ?  $_POST['lessonids'] : '';
	$mathtype = (isset($_POST['mathtype'])) ?  $_POST['mathtype'] : '';
	$lessonids = explode(',',$lessonids);	
	
	$lessonarray=array();
	$qryexistlesson = $ObjDB->QueryObject("select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='".$mathtype."'");
	if($qryexistlesson->num_rows>0){
		while($res = $qryexistlesson->fetch_assoc()){
			extract($res);
			$lessonarray[]=$fld_lesson_id;
		}
	}
	$remaininglesson = array_diff($lessonids,$lessonarray);
	
	if(current($remaininglesson)!=''){	
		$function = "fn_diagnosticstart(".$sid.",".current($remaininglesson).",".$mathtype.")";
		$lessonname = $ObjDB->SelectSingleValue("select CONCAT(fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id='".current($remaininglesson)."' AND fld_zip_type='1' AND fld_delstatus='0')) from itc_ipl_master where fld_id='".current($remaininglesson)."'");
		echo $function."~".$lessonname;
	}
	else{
		echo "completed";
	}
}

//orientationcomplete
if($oper=="orientationcomplete" and $oper!='')
{
	$sid = (isset($_POST['sid'])) ?  $_POST['sid'] : '';
	$lessonid = (isset($_POST['lessonid'])) ?  $_POST['lessonid'] : '';
	$maxid = (isset($_POST['maxid'])) ?  $_POST['maxid'] : '';	
	
	$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_status=1 where fld_id='".$maxid."'");	
	
	$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id FROM itc_class_sigmath_lesson_mapping WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(select fld_lesson_id from itc_assignment_sigmath_master where (fld_status=1 or fld_status=2) and fld_student_id='".$uid."' and fld_schedule_id='".$sid."' and fld_test_type='1') LIMIT 0,1");
	if($lessonid==0){		
		$function = "fn_completed(".$sid.")";								
	}
	else{
		$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
	}
	echo $function;
}

if($oper=="lockstatus" and $oper!=''){		
	$today=date('Y-m-d');
	$message=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_message_master WHERE fld_readstatus='0' AND fld_to='".$uid."' AND fld_delstatus='0'");
	$calendar=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_calendar_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' AND fld_startdate='".$today."'");
	$lockstatus = $ObjDB->QueryObject("SELECT a.fld_id FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_class_id=b.fld_class_id LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=a.fld_schedule_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id LEFT JOIN itc_user_master AS e ON e.fld_id=a.fld_student_id  LEFT JOIN itc_ipl_master AS f ON f.fld_id=a.fld_lesson_id WHERE a.fld_type=5 AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1'  AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' AND f.fld_delstatus='0' AND a.fld_lockstatus='0'");
	$lock=0;
	if($lockstatus->num_rows>0){
		while($res =$lockstatus->fetch_assoc()){
			extract($res);
			$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_lockstatus='1' where fld_id='".$fld_id."'");
		}
		$lock=1;							
	}
	echo $lock."~".$message."~".$calendar;	
}
@include("footer.php");

?>