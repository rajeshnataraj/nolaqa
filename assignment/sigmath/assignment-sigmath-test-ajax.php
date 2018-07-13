<?php
@include("sessioncheck.php");	
$oper = isset($method['oper']) ? $method['oper'] : '';

/*----------diagnostic start-------------*/ 
if($oper == "diagnosticstart" and $oper != '')
{
	$sid = isset($method['sid']) ? $method['sid'] : '';
	$lessonid = isset($method['lessonid']) ? $method['lessonid'] : '';
	$mathtype = isset($method['testtype']) ? $method['testtype'] : '0';
	$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0';
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
	?>

    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns'>
                    <h3 class="blue">You are now ready to begin the Diagnostic Assessment for <?php echo $lessonname."."; ?> </h3>
                </div>
                <div class='six columns'>
                	<img src="img/start.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            
                 
    </div><div class='row rowspacer'>
                 <div class="right five columns">
                 	<p class='btn secondary five columns '>
                        <a onclick="fn_question(<?php echo $sid;?>,<?php echo $lessonid;?>,1,1,0,0,<?php echo $mathtype; ?>,<?php echo $moduleid; ?>);">Continue</a>
                    </p>                   	
                 </div>        
            </div>
         </div>   ~<?php echo $lessonname; ?>      
    
<?php 	
}

//show questions
if($oper=="questionview" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$testtype = (isset($method['testtype'])) ?  $method['testtype'] : '';
	$qorder = (isset($method['qorder'])) ?  $method['qorder'] : '';
	$anscount = (isset($method['anscount'])) ?  $method['anscount'] : '';	
	$mathtype = (isset($method['mathtype'])) ?  $method['mathtype'] : '';	
	$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0';
	$qordernew=0;
	
	$quesqry=$ObjDB->QueryObject("SELECT * FROM itc_diag_question_mapping WHERE fld_lesson_id='".$lessonid."' AND fld_access=1 AND fld_delstatus=0");
	if($quesqry->num_rows>0)
		extract($quesqry->fetch_assoc());	
	if($testtype==1)
	{
		if($qorder==1)
		{
			if($mathtype==2) //Rotational mathmodule schedule
				$classidqry = $ObjDB->QueryObject("SELECT fld_class_id AS classid, fld_license_id AS licenseid FROM itc_class_rotation_schedule_mastertemp WHERE fld_id='".$sid."'");
			else if($mathtype==5) //individual assignment math module schedule
				$classidqry = $ObjDB->QueryObject("SELECT fld_class_id AS classid, fld_license_id AS licenseid FROM itc_class_indassesment_master WHERE fld_id='".$sid."'");
			else	//sigmath schedule
				$classidqry = $ObjDB->QueryObject("SELECT fld_class_id AS classid, fld_license_id AS licenseid FROM itc_class_sigmath_master WHERE fld_id='".$sid."'");
			if($classidqry->num_rows>0)
				extract($classidqry->fetch_assoc());	
				
			$qrypoint=$ObjDB->QueryObject("SELECT fld_ipl_points AS points,fld_unit_id AS unitid FROM itc_ipl_master WHERE fld_id='".$lessonid."'");
			if($qrypoint->num_rows>0)			
				extract($qrypoint->fetch_assoc());
			
			if($mathtype!=10){									
				$chkassess = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														  FROM itc_assignment_sigmath_master 
														  WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_unit_id='".$unitid."' 
														  AND fld_lesson_id='".$lessonid."' AND fld_student_id='".$uid."' AND fld_delstatus='0' AND fld_module_id='".$moduleid."'");
				$fld_grade=0;
				if($chkassess==0){	
					$gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade 
													  FROM itc_class_sigmath_grade 
													  WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$lessonid."' AND fld_flag='1'");
					if($gradepoint->num_rows>0){
						extract($gradepoint->fetch_assoc());							
					}
					else
					{
						$fld_grade = '1';
						$points = 100;
					}		
					$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_master(fld_class_id,fld_schedule_id,fld_unit_id, fld_lesson_id, fld_student_id, 
															fld_type, fld_status, fld_points_possible, fld_test_type, fld_grade, fld_module_id, fld_created_by, fld_created_date) 
									 					  VALUES('".$classid."','".$sid."','".$unitid."','".$lessonid."','".$uid."','1','0','".$points."',
															'".$mathtype."','".$fld_grade."','".$moduleid."', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
				else{
					$maxid=$chkassess;
				}						
			}		
							
			$stcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track_student WHERE fld_student_id='".$uid."'AND fld_license_id='".$licenseid."'AND fld_flag='1'");
			
			if($stcount==0 and $mathtype!=10)
			{
				if($mathtype==2)
					$qryschedules = $ObjDB->QueryObject("SELECT fld_id 
														FROM itc_class_rotation_schedule_mastertemp 
														WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'  AND fld_moduletype='2'");
				else if($mathtype==5)
					$qryschedules = $ObjDB->QueryObject("SELECT fld_id 
														FROM itc_class_indassesment_master 
														WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'  AND fld_moduletype='2'");
				else
					$qryschedules = $ObjDB->QueryObject("SELECT fld_id 
														FROM itc_class_sigmath_master 
														WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'");				
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
				
				if($districtid!='0' and $schoolid!='0' and $indid=='0')
					$licenseiplcount = $ObjDB->SelectSingleValueInt("SELECT fld_ipl_count 
																	FROM itc_license_track 
																	WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' 
																	AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."' 
																	ORDER BY fld_id LIMIT 0,1");
				
				else
					$licenseiplcount = $ObjDB->SelectSingleValueInt("SELECT fld_ipl_count 
																	FROM itc_license_track 
																	WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' 
																	AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' 
																	AND fld_end_date>='".date("Y-m-d")."' 
																	ORDER BY fld_id LIMIT 0,1");
				
				$iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(DISTINCT(fld_lesson_id)) AS iplcount 
														 FROM itc_assignment_sigmath_master 
														 WHERE fld_schedule_id IN (".$schedules.") AND fld_student_id='".$uid."' AND fld_test_type='1' AND fld_delstatus='0'");
				
				if($iplcount > $licenseiplcount)
				{
					$qryrem = $ObjDB->QueryObject("SELECT fld_id, fld_remain_users 
												  FROM itc_license_track 
												  WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' AND fld_school_id='".$schoolid."' 
												  AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."'");
					
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
						$ObjDB->NonQuery("UPDATE itc_license_track 
										 SET fld_remain_users='".$remusers."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
										 WHERE fld_id='".$fld_id."'");						
						$ObjDB->NonQuery("INSERT INTO itc_license_track_student(fld_student_id, fld_license_id, fld_flag, fld_created_by, fld_created_date) VALUES('".$uid."', '".$licenseid."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
					}
				}
			}
						
			$qordernew=2;
			
			$input = array($fld_diag_ques1a,$fld_diag_ques1b);
			
			if($sessmasterprfid==10)
					$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
									 VALUES('".$maxid."','".$uid."','diagstart','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
		}
		else if($qorder==2)
		{
			$qordernew=3;
			$input = array($fld_diag_ques2a,$fld_diag_ques2b);
		}
		else if($qorder==3)
		{
			$qordernew=0;
			$input = array($fld_diag_ques3a,$fld_diag_ques3b);
		}
		
		/*-------------select random questions-------------*/			
		shuffle($input);
		foreach ($input as $number) {
			$quesid = $number;
			$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."'");
			if($chk==0){
				break;
			}	
		}	
	}
	

	if($testtype==2)
	{
		if($qorder==1)
		{				
			$input = array($fld_mast1_ques1a,$fld_mast1_ques1b);
			$qordernew=2;
		}
		else if($qorder==2)
		{	
			$input = array($fld_mast1_ques1a,$fld_mast1_ques1b);		
			$qordernew=3;
		}
		else if($qorder==3)
		{
			$input = array($fld_mast1_ques2a,$fld_mast1_ques2b);
			$qordernew=4;
		}
		else if($qorder==4)
		{
			$input = array($fld_mast1_ques2a,$fld_mast1_ques2b);
			$qordernew=5;
		}
		else if($qorder==5)
		{				
			$input = array($fld_mast1_ques3a,$fld_mast1_ques3b);
			$qordernew=6;
		}
		else if($qorder==6)
		{
			$input = array($fld_mast1_ques3a,$fld_mast1_ques3b);
			$qordernew=0;
		}
		/*-------------select random questions-------------*/
		shuffle($input);
		foreach ($input as $number) {
			$quesid = $number;
			$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."'");
			if($chk==0){
				break;
			}
		}	

	}

	if($testtype==3)
	{
		if($qorder==1)
		{
			$input = array($fld_mast2_ques1a,$fld_mast2_ques1b);
			$qordernew=2;
		}
		else if($qorder==2)
		{
			$input = array($fld_mast2_ques1a,$fld_mast2_ques1b);
			$qordernew=3;
		}
		else if($qorder==3)
		{
			$input = array($fld_mast2_ques2a,$fld_mast2_ques2b);
			$qordernew=4;
		}
		else if($qorder==4)
		{
			$input = array($fld_mast2_ques2a,$fld_mast2_ques2b);
			$qordernew=5;
		}
		else if($qorder==5)
		{
			$input = array($fld_mast2_ques3a,$fld_mast2_ques3b);
			$qordernew=6;
		}
		else if($qorder==6)
		{
			$input = array($fld_mast2_ques3a,$fld_mast2_ques3b);
			$qordernew=0;
		}
		shuffle($input);
		foreach ($input as $number) {
			$quesid = $number;
			$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."'");
			if($chk==0){
				break;
			}
		}	
	}

	$status = "fn_anscheck(".$sid.",".$lessonid.",".$testtype.",".$quesid.",".$anscount.",".$maxid.");";	
	?>
        <div id="divqframe"></div>

        <script language="javascript" type="text/javascript">
            $(document).ready(function() {
                            $('body').css('overflow','hidden');

                            var cssObjOuter = {		
                            'display' : 'block',		
                            'width' : $('body').width(),		
                            'height' : $(window).height()		
                            };		

                            var cssObjInner = {		
                            'display' : 'block',		
                            'width' : $('body').width(),		
                            'height' : $(window).height() - 89		
                            };
                           
                            var lrmargin = 20;
                            $('#divcustomlightbox').css(cssObjOuter);
                            $('#divlbcontent').css('background-image','none');
                            $('#divlbcontent').css(cssObjInner);		                            		

                            var iframeCss = {		
                            'width' :  ($('body').width()-lrmargin)+'px',
                            'border' : '1px solid #000',		
                            'margin' : (lrmargin/2)	
                            };
                           
                            $(document).scrollTop(0);

                            $.ajax({
                                    type: 'post',
                                    url: 'assignment/sigmath/assignment-sigmath-questioniframe.php',
                                    data: 'id=<?php echo $quesid;?>',
                                    success:function(data) {
                                            $('#divqframe').html(data);
                                            AMtranslated = false;
                                            translate();                                            
                                    }
                            });

                            $('#divqframe').slimscroll({
                                size: '10px',
                                height: ($('#divlbcontent').height()-lrmargin)+'px',
                                width: ($('body').width()-lrmargin)+'px',
                                alwaysVisible: true,
                                    scrollTo : '10px'
                            });

                            $('.slimScrollDiv').css({'border' : '1px solid #000', 'margin' : (lrmargin/2) });
                    });		                    
    </script>                                                        
	<div style="padding-right:30px;">
		<div class="right five columns">
			<p id="ctnbtn" class='btn secondary five columns' style="height: 2%;line-height: 30px;font-size: 20px;">
				<a onclick="<?php echo $status; ?>">Continue</a>
			</p>                   	
		 </div> 
	</div>	
        <input type="hidden" value="<?php echo $qordernew; ?>" id="qorder" />
	<input type="hidden" id="current_qorder" value="<?php echo $qorder; ?>" />
	<input type="hidden" id="maxid" value="<?php echo $maxid; ?>" />
	<input type="hidden" id="testtype" value="<?php echo $testtype; ?>" />
	<?php 	
}

//answer check	
if($oper=="answercheck" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';
	$anstype = (isset($method['anstype'])) ?  $method['anstype'] : '';
	$quesid = (isset($method['quesid'])) ?  $method['quesid'] : '';
	$anscount = (isset($method['anscount'])) ?  $method['anscount'] : '';
	$answer = (isset($method['answer'])) ?  $method['answer'] : '';
	$answer1 = (isset($method['answer'])) ?  $method['answer'] : '';
	$testtype = (isset($method['testtype'])) ?  $method['testtype'] : '';
	$cqorder = (isset($method['cqorder'])) ?  $method['cqorder'] : '';
	$show = 0;
	$count=0;
	if($cqorder==2 or $cqorder==4 or $cqorder==6 or $testtype==1){
		$show = 1;
	}
	$tempanscount=0;	
	if($anstype==1 or $anstype==8)
	{
		$answer=explode(",",$answer);		
		$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) 
									SEPARATOR '~') AS 'correct' 
									FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_answer<>'' AND fld_flag='1'");
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
			$show=1;
		}
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, fld_test_type,
														 fld_correct_answer, fld_show, fld_created_by, fld_created_date)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."', '".$uid."', '".date("Y-m-d H:i:s")."')");
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
			$count=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1' 
												AND LOWER(REPLACE(fld_answer, ' ', ''))='".strtolower(str_replace(' ', '', $answer))."'");
		}
		else{
			$count=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' 
												AND LOWER(REPLACE(fld_answer, ' ', ''))='".strtolower(str_replace(' ', '', $answer))."'");
		}
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, fld_test_type, 
													fld_correct_answer, fld_show, fld_created_by, fld_created_date) 
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."','".$uid."','".date("Y-m-d H:i:s")."')");
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
		$qry = $ObjDB->QueryObject("SELECT fld_answer AS canswer 
									FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='4' AND fld_flag='1' 
									ORDER BY fld_boxid ASC");
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
													fld_test_type, fld_correct_answer, fld_show, fld_created_by, fld_created_date) 
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."', '".$uid."', '".date("Y-m-d H:i:s")."')");	
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
		$maincount = 0;
		for($i=0;$i<sizeof($answerdesign);$i++){			
			if($answerdesign[$i]==5 or $answerdesign[$i]==4 or $answerdesign[$i]==17 or $answerdesign[$i]==18 or $answerdesign[$i]==20 or $answerdesign[$i]==21 or $answerdesign[$i]==22 or $answerdesign[$i]==23 or $answerdesign[$i]==24 ){								
				
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
													fld_test_type, fld_correct_answer, fld_show, fld_created_by, fld_created_date)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."', '".$uid."', '".date("Y-m-d H:i:s")."')");
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
		$qry = $ObjDB->QueryObject("SELECT fld_answer 
									FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1'");
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
													fld_test_type, fld_correct_answer, fld_show, fld_created_by, fld_created_date)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."', '".$uid."', '".date("Y-m-d H:i:s")."')");	
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
		$qry = $ObjDB->QueryObject("SELECT fld_answer 
									FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1' 
									ORDER BY fld_boxid ASC");
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
													fld_test_type, fld_correct_answer, fld_show, fld_created_by, fld_created_date)
												VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."', '".$uid."', '".date("Y-m-d H:i:s")."')");	
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
		$qry = $ObjDB->QueryObject("SELECT LCASE(fld_answer) AS fld_answer 
									FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1'");		
		$i=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			if(strtolower(str_replace(' ', '', $fld_answer))==str_replace(' ', '', strtolower($answer))){
				$count=1;				
			}
			if($count==1)
				break;
			$i++;
		}
		
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT fld_id 
											FROM itc_assignment_sigmath_answer_track 
											WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		if($count==1){$show=1;}
		if($chk==0 and $maxid!=0)
		{
			$chk = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_answer_track(fld_track_id, fld_student_id, fld_question_id, fld_answer_type, fld_answer, fld_test_type, 
													fld_correct_answer, fld_show, fld_created_by, fld_created_date)
												 VALUES('".$maxid."','".$uid."','".$quesid."','".$anstype."','".$answer1."','".$testtype."','".$count."','".$show."', '".$uid."', '".date("Y-m-d H:i:s")."')");	
		}
		else if($maxid!=0)
		{
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_answer_track 
							 SET fld_answer_type='".$anstype."', fld_correct_answer='".$count."', fld_answer='".$answer1."', fld_show='".$show."', 
							 fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
							 WHERE fld_track_id='".$maxid."' AND fld_question_id='".$quesid."' AND fld_test_type='".$testtype."'");
		}
		
	}
        
	if($anstype==10)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' 
									ORDER BY fld_boxid ASC");
		$stuanswer=explode("~",$answer);
		$answerarray=array();
		$i=0;
		$count=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			if($stuanswer[$i]==$fld_answer){
				$count=1;
			}
			else{
				$count=0;
			}
			$i++;
		}	
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
											and fld_student_id='".$uid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_created_by,fld_created_date)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$uid."','".$date."')");		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_by='".$uid."',fld_updated_date='".$date."'   
							where fld_test_id='".$testid."' and fld_question_id='".$quesid."'");
		}
	}
	
	if($anstype==11)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_boxid FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1'");
		$stuanswer=explode("~",$answer);
		$actual_anscount = $qry->num_rows;
		$tempanscount=0;
		$answerarray=array();
		$i=0;
		$count=0;		
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			if($stuanswer[$i]==$fld_boxid){
				$tempanscount++;
			}
			$i++;
		}
		if($actual_anscount==$tempanscount){
			$count=1;
		}
		else{
			$count=0;
		}
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												and fld_student_id='".$uid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, 
								fld_answer_type_id, fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_created_by,fld_created_date)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$uid."','".$date."')");		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',fld_updated_by='".$uid."',
								fld_updated_date='".$date."' 
							where fld_test_id='".$testid."' and fld_question_id='".$quesid."'");
		}
	}
	
	if($anstype==12)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_ball_color, fld_correct, fld_ano_correct FROM itc_question_drag_drop 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_flag='1' 
										AND (fld_correct<>'0' OR fld_ano_correct<>'0')");
		$stuanswer=explode(",",$answer);		
		array_count_values($stuanswer);
		$newans = array_count_values($stuanswer);
		$ballarray=array();
		$correctarray=array();
		$anocorrectarray=array();

		$count=0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			$ballarray[]=$fld_ball_color;
			$correctarray[]=$fld_correct;
			$anocorrectarray[]=$fld_ano_correct;
			
			if($fld_correct!=0)
			{
				$testarray[$fld_ball_color]=$fld_correct;
			}
			if($fld_ano_correct!=0)
			{
				 $testarray2[$fld_ball_color]=$fld_ano_correct;
			}
		}	
		
		$totalarray=array("color"=>$ballarray,"correctans"=>$correctarray,"ancorrectans"=>$anocorrectarray);
		
		$correctsize=sizeof($testarray);
		$anocorrectsize=sizeof($testarray2);
                 $chkcount = 0;
                $anchkcount = 0;
                
		if($correctsize==sizeof($newans))
		{
			$result = array_diff_assoc($testarray,$newans);
			if(sizeof($result)==0){
                            $chkcount=1;
                        }
		}
                
		if($anocorrectsize==sizeof($newans))
		{
			$result = array_diff_assoc($testarray2,$newans);
			if(sizeof($result)==0){
                            $anchkcount=1;
                        }
		}

		if($chkcount == 1 or $anchkcount == 1)
                {
                    $count=1;
                }
                
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' and fld_student_id='".$uid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_created_by,fld_created_date)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."',
								'".$timecount."','".$uid."','".$date."')");		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_by='".$uid."',fld_updated_date='".$date."' 
							where fld_test_id='".$testid."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."'");
		}
	}
	
	if($anstype==13)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' 
									ORDER BY fld_boxid ASC");
		$stuanswer=explode(",",$answer);
		$answerarray=array();
		$i=0;
		$count = 0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			$ans = str_replace('px','',$fld_answer);
			$answerarray[$i]=$ans;
			$i++;
		}			
		for($j=0;$j<sizeof($answerarray);$j++)
		{
			$newpoints=explode(",",$answerarray[$j]);
			for($k=0;$k<sizeof($stuanswer);$k++)
			{
				$stuans = str_replace('px','',$stuanswer[$k]);
				$newstupoints=explode(",",$stuans);
				$min = $newpoints[0]-3;
				$max = $newpoints[0]+3;
				$range = range($min, $max);
				
				$min1 = $newpoints[1]-3;
				$max1 = $newpoints[1]+3;
				$range1 = range($min1, $max1);
				if(in_array($newstupoints[0], $range) && in_array($newstupoints[1], $range1)){
					$count++;
				}
			}
		}		
		if($count==$qry->num_rows)
		{
			$show=1;
		}
		else
		{
			$show=0;
			$count=0;
		}
			
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												and fld_student_id='".$uid."'");
		
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_created_by,fld_created_date)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$uid."','".$date."')");		
		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',fld_updated_by='".$uid."',
								fld_updated_date='".$date."'  
							where fld_test_id='".$testid."' and fld_question_id='".$quesid."'");
		}
	}
	
	if($anstype==14)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' 
									ORDER BY fld_boxid ASC");
		$stuanswer=explode("~",$answer);
		$answerarray=array();
		$i=0;
		$count = 0;
		while($row=$qry->fetch_assoc())
		{
			extract($row);
			$min = $fld_answer-15;
			$max = $fld_answer+15;
			$range = range($min, $max);
			if(in_array($stuanswer[$i], $range)) {
				$count=1;
			}
			else {
				$count=0;
			}
			$i++;
		}		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												and fld_student_id='".$uid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_created_by,fld_created_date)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$uid."','".$date."')");		
		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_by='".$uid."',fld_updated_date='".$date."' 
							where fld_test_id='".$testid."' and fld_question_id='".$quesid."'");
		}
	}	
        
	echo $anscount+$count."~".$count."~".$quesid."~".$chk;
}

//diagnostic pass	
if($oper=="diagpass" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';	
        
        $gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade 
                                                FROM itc_class_sigmath_grade 
                                                WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$lessonid."' AND fld_flag='1'");
        if($gradepoint->num_rows>0){
              extract($gradepoint->fetch_assoc());							
        }
        else
        {
              $fld_grade = '1';
	$points=$ObjDB->SelectSingleValueInt("SELECT fld_ipl_points FROM itc_ipl_master WHERE fld_id='".$lessonid."'");	
        }	
	
	$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");	
	if($maxid!=0)
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type=1,fld_status=1,fld_points_earned='".$points."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");	
	$mathtype = $ObjDB->SelectSingleValueInt("SELECT fld_test_type FROM itc_assignment_sigmath_master WHERE fld_id='".$maxid."'");
	if($mathtype==1){		
		/*Getting next lesson*/
		$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
												 FROM itc_class_sigmath_lesson_mapping 
												 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
												 	WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
													AND fld_delstatus='0' ) 
												 ORDER BY fld_order LIMIT 0,1");
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
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                    <h3 class="blue">Diagnostic Test Completed. <br/> You answered all of the questions correctly.<?php if($maxid!=0){ ?><br />Click Continue to advance to the next lesson.<?php }?></h3> 
                </div>
                <div class='six columns'>
                	<img src="img/success.png" style="border:0px;margin-left: 15%;" />
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
   <?php
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
}

//diagnostic fail	
if($oper=="diagfail" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';
	if($maxid!=0)
	{
		$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
		$ObjDB->NonQuery("update itc_assignment_sigmath_master set fld_type=6, fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  where fld_id='".$maxid."'");	
		
		$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
	}
	else
	{
		$function = "closefullscreenlesson()";
	}
	
	?>
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                	<h3 class="blue">You did not master <?php echo $lessonname."."; ?><?php if($maxid!=0){ ?><br/> You are ready to begin the <?php echo $lessonname; ?> Lesson.<?php }?></h3>
                </div>
                <div class='six columns'>
                	<img src="img/fail.png" style="border:0px;margin-left: 15%;" />
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
   <?php 
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
}

//playing the lesson
if($oper == "playlesson" and $oper != '')
{ 
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';
	$orientationflag = (isset($method['orientationflag'])) ?  $method['orientationflag'] : '0';
	if($sessmasterprfid==10)
            
            if($maxid==0)
            {
                $classidqry = $ObjDB->QueryObject("SELECT fld_class_id AS classid, fld_license_id AS licenseid FROM itc_class_sigmath_master WHERE fld_id='".$sid."'");
			if($classidqry->num_rows>0)
				extract($classidqry->fetch_assoc());	
				
			$qrypoint=$ObjDB->QueryObject("SELECT fld_ipl_points AS points,fld_unit_id AS unitid FROM itc_ipl_master WHERE fld_id='".$lessonid."'");
			if($qrypoint->num_rows>0)			
				extract($qrypoint->fetch_assoc());
			
			if($mathtype!=10){									
				$chkassess = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														  FROM itc_assignment_sigmath_master 
														  WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_unit_id='".$unitid."' 
														  AND fld_lesson_id='".$lessonid."' AND fld_student_id='".$uid."' AND fld_delstatus='0' AND fld_module_id='".$moduleid."'");
				$fld_grade=0;
				if($chkassess==0){	
					$gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade 
													  FROM itc_class_sigmath_grade 
													  WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$lessonid."' AND fld_flag='1'");
					if($gradepoint->num_rows>0){
						extract($gradepoint->fetch_assoc());							
					}
					else
					{
						$fld_grade = '1';
						$points = 100;
					}		
					$maxid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_assignment_sigmath_master(fld_class_id,fld_schedule_id,fld_unit_id, fld_lesson_id, fld_student_id, 
															fld_type, fld_status, fld_points_possible, fld_test_type, fld_grade, fld_module_id, fld_created_by, fld_created_date) 
									 					  VALUES('".$classid."','".$sid."','".$unitid."','".$lessonid."','".$uid."','1','0','".$points."',
															'1','".$fld_grade."','0', '".$uid."', '".date("Y-m-d H:i:s")."')");
				}
				else{
					$maxid=$chkassess;
				}						
			}		
							
			$stcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track_student WHERE fld_student_id='".$uid."'AND fld_license_id='".$licenseid."'AND fld_flag='1'");
			
			if($stcount==0 and $mathtype!=10)
			{
				if($mathtype==2)
					$qryschedules = $ObjDB->QueryObject("SELECT fld_id 
														FROM itc_class_rotation_schedule_mastertemp 
														WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'  AND fld_moduletype='2'");
				else if($mathtype==5)
					$qryschedules = $ObjDB->QueryObject("SELECT fld_id 
														FROM itc_class_indassesment_master 
														WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'  AND fld_moduletype='2'");
				else
					$qryschedules = $ObjDB->QueryObject("SELECT fld_id 
														FROM itc_class_sigmath_master 
														WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_delstatus='0'");				
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
				
				if($districtid!='0' and $schoolid!='0' and $indid=='0')
					$licenseiplcount = $ObjDB->SelectSingleValueInt("SELECT fld_ipl_count 
																	FROM itc_license_track 
																	WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' 
																	AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."' 
																	ORDER BY fld_id LIMIT 0,1");
				
				else
					$licenseiplcount = $ObjDB->SelectSingleValueInt("SELECT fld_ipl_count 
																	FROM itc_license_track 
																	WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' 
																	AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' 
																	AND fld_end_date>='".date("Y-m-d")."' 
																	ORDER BY fld_id LIMIT 0,1");
				
				$iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(DISTINCT(fld_lesson_id)) AS iplcount 
														 FROM itc_assignment_sigmath_master 
														 WHERE fld_schedule_id IN (".$schedules.") AND fld_student_id='".$uid."' AND fld_test_type='1' AND fld_delstatus='0'");
				
				if($iplcount > $licenseiplcount)
				{
					$qryrem = $ObjDB->QueryObject("SELECT fld_id, fld_remain_users 
												  FROM itc_license_track 
												  WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' AND fld_school_id='".$schoolid."' 
												  AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."'");
					
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
						$ObjDB->NonQuery("UPDATE itc_license_track 
										 SET fld_remain_users='".$remusers."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' 
										 WHERE fld_id='".$fld_id."'");						
						$ObjDB->NonQuery("INSERT INTO itc_license_track_student(fld_student_id, fld_license_id, fld_flag, fld_created_by, fld_created_date) VALUES('".$uid."', '".$licenseid."', '1', '".$uid."', '".date("Y-m-d H:i:s")."')");
					}
				}
                        }
                
            }
            
        $ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) values('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");	
	$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type='2', fld_status=0, fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");
	$lessonpath = $ObjDB->SelectSingleValue("SELECT fld_zip_name FROM itc_ipl_version_track WHERE fld_ipl_id='".$lessonid."' AND fld_zip_type='1' AND fld_delstatus='0'");
	
	?>
    <script type="text/javascript">
		var interval = setInterval("fn_slidecheck(<?php echo $sid;?>,<?php echo $lessonid;?>,<?php echo $maxid;?>,<?php echo $orientationflag;?>)",5000);
	</script> 
    
    	<iframe src="<?php echo _CONTENTURL_."vscorm/rte1.php?SCOInstanceID=".$maxid."&lessonid=".$lessonid."&studentid=".$uid."&hostname=".$_SERVER['SERVER_NAME']; ?>" width="100%" height="100%;" style="border:none;margin:0 auto;background-color:#FFF"></iframe>        
        <input type="hidden" id="testtype" value="0" />                
       
       
    <?php     
}


//check if the lesson is completed or not
if($oper == "slidecheck" and $oper != '')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';	
	echo $ObjDB->SelectSingleValue("SELECT varValue FROM itc_assignment_lesson_scorm_track WHERE SCOInstanceID='".$maxid."' AND varName='cmi.core.lesson_status'");
}

//check if the remediation is completed or not
if($oper == "remslidecheck" and $oper != '')
{	
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';
	echo $ObjDB->SelectSingleValue("SELECT varValue FROM itc_assignment_rem_scorm_track WHERE SCOInstanceID='".$maxid."' AND varName='cmi.core.lesson_status'");
}

if($oper == "checklockstatus" and $oper != '')
{	
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';		
	echo $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_assignment_sigmath_master WHERE fld_id='".$maxid."' AND fld_type='4'");
}

//review the lesssons
if($oper == "review" and $oper != '')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';	
	
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");	
	$lessonpath = $ObjDB->SelectSingleValue("select fld_zip_name from itc_ipl_version_track where fld_ipl_id='".$lessonid."' and fld_zip_type='1' and fld_delstatus='0'");
	
	?>
    
    	<iframe src="<?php echo _CONTENTURL_."vscorm/rte1.php?SCOInstanceID=".$maxid."&lessonid=".$lessonid."&studentid=".$uid."&hostname=".$_SERVER['SERVER_NAME'];?>" width="100%" height="100%;" style="border:none;margin:0 auto;background-color:#FFF"></iframe>       
                
    <?php     
}

/*----------mastery1 start-------------*/ 
if($oper == "mastery1start" and $oper != '')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$maxid = isset($method['maxid']) ? $method['maxid'] : '0';
	$lessonid = isset($method['lessonid']) ? $method['lessonid'] : '';
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");	
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
	if($maxid!=0)
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
						 SET fld_type='3', fld_status=0, fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
						 WHERE fld_id='".$maxid."'");
	?>
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                	<h3 class="blue">You are now ready to begin the  <br/> Mastery Test 1 for <?php echo $lessonname;?>. </h3>
                </div>
                <div class='six columns'>
                	<img src="img/start.png" style="border:0px;margin-left: 15%;" />
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
    </div>
<?php 	
	echo "~".$lessonname;
}

//remediation play
if($oper == "remediation" and $oper != '')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$quesid = (isset($method['quesid'])) ? $method['quesid'] : '';
	$lessonid = (isset($method['lessonid'])) ? $method['lessonid'] : '';
	$testtype = (isset($method['testtype'])) ? $method['testtype'] : '';
	$qorder = (isset($method['qorder'])) ? $method['qorder'] : '';
	$anscount = (isset($method['anscount'])) ? $method['anscount'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';
	$ansmaxid = (isset($method['ansmaxid'])) ?  $method['ansmaxid'] : '0';
	$rfile=$ObjDB->SelectSingleValue("SELECT fld_file_name FROM itc_question_details WHERE fld_id='".$quesid."'");
	
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
	} ?>     
    
	<?php $tmp = explode('.',$rfile);
    if($tmp[1]=='zip' and $sessmasterprfid==10){
        $flag=1;
        $foldername= str_replace('.zip','',$rfile);
        
		?>
		<iframe src="<?php echo _CONTENTURL_."vscormrem/rte1.php?SCOInstanceID=".$ansmaxid."&foldername=".$foldername."&studentid=".$uid."&hostname=".$_SERVER['SERVER_NAME'];?>" width="100%" height="100%;" style="border:none;margin:0 auto;background-color:#FFF"></iframe>        
        <script type="text/javascript">
            var remslide = setInterval("fn_remslidecheck(<?php echo $ansmaxid;?>)",5000);
        </script> 
        <?php 
    }
	else if($tmp[1]=='zip' and $sessmasterprfid!=10){
        $flag=2;
        $foldername= str_replace('.zip','',$rfile);
       
		?>
		<iframe src="<?php echo _CONTENTURL_."vscormrem/rte1.php?SCOInstanceID=".$ansmaxid."&foldername=".$foldername."&studentid=".$uid;?>" width="100%" height="100%;" style="border:none;margin:0 auto;background-color:#FFF"></iframe>        
        <script type="text/javascript">            
        </script> 
        <?php 
    }
    else{ $flag=2;?>
        <object type="application/x-shockwave-flash" data="<?php echo _CONTENTURL_."question/remediations/".$rfile;?>" width="100%" height="100%">
             <param name="movie" value="<?php echo _CONTENTURL_;?>question/remediations/<?php echo $rfile;?>" /> 
             <embed src="<?php echo _CONTENTURL_;?>question/remediations/<?php echo $rfile;?>" width="100%" height="100%" name="uits" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
        </object><?php 
    }?>                          
    <div id="remcontinue" style="padding-right:30px; <?php if($flag==1){?>display:none;<?php }?>">
        <div class="right five columns">
            <p class='btn primary five columns' style="height: 2%;line-height: 30px;font-size: 20px;">
                <a onclick="<?php echo $status; ?>">Continue</a>
            </p>                   	
        </div>
    </div>       
     <input type="hidden" name="currentstep" id="currentstep" value="<?php echo $testtype;?>" />
     <input type="hidden" name="testtype" id="testtype" value="<?php echo $testtype;?>" />
     <input type="hidden" name="maxid" id="maxid" value="<?php echo $maxid;?>" />
     <input type="hidden" id="iplid" name="iplid" value="<?php echo $lessonid;?>" /> 
  
<?php 
}


//mastery1pass
if($oper=="mastery1pass" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';	
	$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");	
	if($maxid!=0)
	{
		$gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade 
                                                FROM itc_class_sigmath_grade 
                                                WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$lessonid."' AND fld_flag='1'");
                if($gradepoint->num_rows>0){
                      extract($gradepoint->fetch_assoc());							
                }
                else
                {
                      $fld_grade = '1';
		$points=$ObjDB->SelectSingleValueInt("SELECT fld_ipl_points FROM itc_ipl_master WHERE fld_id='".$lessonid."'");
                }
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type=3,fld_status=1,fld_points_earned='".$points."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");	
		$mathtype = $ObjDB->SelectSingleValueInt("SELECT fld_test_type FROM itc_assignment_sigmath_master WHERE fld_id='".$maxid."'");
	}
	if($mathtype==1){		
		/*Getting next lesson*/
		$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
												 FROM itc_class_sigmath_lesson_mapping 
												 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
												 	WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
												 	AND fld_delstatus='0' ) 
												 ORDER BY fld_order LIMIT 0,1");
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
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                   Mastery Test Completed. <br/> You have completed the lesson.<?php if($maxid!=0){?><br />Click Continue to advance to the next lesson. <?php }?> 
                </div>
                <div class='six columns'>
                	<img src="img/success.png" style="border:0px;margin-left: 15%;" />
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
   <?php
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
}

//mastery1fail
if($oper=="mastery1fail" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$maxid = isset($method['maxid']) ? $method['maxid'] : '0';
	$lessonid = isset($method['lessonid']) ? $method['lessonid'] : '';
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
	if($maxid!=0)
	{
		$mathtypeqry = $ObjDB->QueryObject("SELECT fld_test_type AS mathtype, fld_lockstatus AS chkstatus FROM itc_assignment_sigmath_master WHERE fld_id='".$maxid."'");
		extract($mathtypeqry->fetch_assoc());
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type='5', fld_status=0, fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_lockstatus='0' WHERE fld_id='".$maxid."'");	
	
		if($mathtype==2 or $mathtype==5){
			$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
			$name = "Continue";
		}
		else{
			$function = "fn_review(".$sid.",".$lessonid.",".$maxid.")";
			$name = "Review";
		}
	}
	else
	{
		$function = "closefullscreenlesson()";
		$name = "Continue";
	}
	?>
    
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                  You did not master the test for <?php echo $lessonname;?>. <br/><br/><?php if($maxid!=0 and $mathtype!=2 and $mathtype!=5) { ?>
                        You will not advance until the teacher grants <br/> access to move forward. <?php }?>
                </div>
                <div class='six columns'>
                	<img src="img/lock.png" style="border:0px;margin-left: 15%;" />
                </div>
            </div>
            <div class='row'>
                <div class="right five columns">
                 	<p class='btn primary five columns'>
                        <a onclick="<?php echo $function; ?>"><?php echo $name; ?></a>
                    </p>                   	
               	</div>        
            </div>
         </div>        
    </div>
    <input type="hidden" id="testtype" value="5" />
     <script type="text/javascript">
	 <?php if($maxid!=0 and $mathtype!=2 and $mathtype!=5) { ?>
		var lockstatus = setInterval("fn_checklockstatus(<?php echo $sid;?>,<?php echo $lessonid;?>,<?php echo $maxid;?>)",5000);
		<?php }?>
	</script> 
        <?php 
		if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
}

/*----------mastery2 start-------------*/ 
if($oper == "mastery2start" and $oper != '')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$maxid = isset($method['maxid']) ? $method['maxid'] : '0';
	$lessonid = isset($method['lessonid']) ? $method['lessonid'] : '';	
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
	if($maxid!=0)
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type='4', fld_status=0, fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");
	?>    
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                  You are now ready to begin the  <br/> Mastery Test 2 for <?php echo $lessonname;?>. 
                </div>
                <div class='six columns'>
                	<img src="img/start.png" style="border:0px;margin-left: 15%;" />
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
    </div>    
<?php 	
echo "~".$lessonname;
}

//mastery2pass
if($oper=="mastery2pass" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '0';	
	$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");	
	if($maxid!=0)
	{
		$gradepoint = $ObjDB->QueryObject("SELECT fld_points AS points,fld_grade 
                                                FROM itc_class_sigmath_grade 
                                                WHERE fld_schedule_id='".$sid."' AND fld_lesson_id='".$lessonid."' AND fld_flag='1'");
                if($gradepoint->num_rows>0){
                      extract($gradepoint->fetch_assoc());							
                }
                else
                {
                      $fld_grade = '1';
		$points=$ObjDB->SelectSingleValueInt("SELECT fld_ipl_points FROM itc_ipl_master WHERE fld_id='".$lessonid."'");
                }
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
						SET fld_type=4,fld_status=1,fld_points_earned='".$points."',fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' 
						WHERE fld_id='".$maxid."'");	
		
		$mathtype = $ObjDB->SelectSingleValueInt("SELECT fld_test_type FROM itc_assignment_sigmath_master WHERE fld_id='".$maxid."'");
		if($mathtype==1){			
			/*Getting next lesson*/
			$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
													 FROM itc_class_sigmath_lesson_mapping 
													 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
													 WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
													 	AND fld_delstatus='0' ) 
													 ORDER BY fld_order LIMIT 0,1");
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
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
                  <h3 class="blue">Mastery Test Completed. <br/> You have completed the lesson.<?php if($maxid!=0){?><br />Click Continue to advance to the next lesson.<?php }?></h3>
                </div>
                <div class='six columns'>
                	<img src="img/success.png" style="border:0px;margin-left: 15%;" />
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
   <?php
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
}

//mastery2fail
if($oper=="mastery2fail" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$maxid = isset($method['maxid']) ? $method['maxid'] : '0';
	$lessonid = isset($method['lessonid']) ? $method['lessonid'] : '';
	$lessonid1 = $lessonid;
	$ObjDB->NonQuery("UPDATE itc_class_sigmath_master SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_updatedby='".$uid."' WHERE fld_id='".$sid."'");	
	if($maxid!=0)
	{
		$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type='4', fld_status=2, fld_points_earned='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");
		
		$mathtype = $ObjDB->SelectSingleValueInt("SELECT fld_test_type FROM itc_assignment_sigmath_master WHERE fld_id='".$maxid."'");
		if($mathtype==1){			
			/*Getting next lesson*/
			$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
													 FROM itc_class_sigmath_lesson_mapping 
													 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
													 	WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
														AND fld_delstatus='0') 
													ORDER BY fld_order LIMIT 0,1");
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
	$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".$lessonid1."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
		?>
    <div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%; font-size:30px;">
                 You did not master the test for <?php echo $lessonname;?>.
                </div>
                <div class='six columns'>
                	<img src="img/fail.png" style="border:0px;margin-left: 15%;" />
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
   <?php 
   if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','".$sid."','".$lessonid."','".$uid."','".date("Y-m-d H:i:s")."')");
}

//unlock
if($oper=="unlock" and $oper!='')
{
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';
	$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master 
					 SET fld_type='4', fld_status=0, fld_unlocked_by='".$uid."', fld_unlocked_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  
					 WHERE fld_id='".$maxid."'");
}

if($oper=="classunlock" and $oper!='')
{
	$classid = (isset($method['classid'])) ?  $method['classid'] : '';
	$ObjDB->NonQuery("UPDATE itc_class_master SET fld_lock='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$classid."'");
}

//completed
if($oper=="completed" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$sname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_sigmath_master WHERE fld_id='".$sid."'");
?> 
	<div class='row' id="divcontent">
         <div class='twelve columns' style="height:100%;">
            <div class="row" style="height:100%;">
                <div class='six columns' style="height:100%;">
	                <h3 class="blue">You have completed all the lessons from the <?php echo $sname;?>  Schedule.<br/><br/>                 
                	<b>Congratulations.</b>
                	</h3>
                </div>   
                <div class='six columns'>
                	<img src="img/success.png" style="border:0px;margin-left:15%;" />
                </div>             
            </div>
            <div class='row'>
            	<div class="right five columns">
                 	<p class='btn secondary five columns'>
                        <a onclick="fn_loadschedule();">Finish</a>
                    </p>                   	
               	</div>       
            </div>
         </div>        
    </div>   
	<?php
}

//unload
if($oper=="unload" and $oper!='')
{
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';
	$testtype = (isset($method['testtype'])) ?  $method['testtype'] : '';	
	$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_type='".($testtype+5)."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");
	
	if($sessmasterprfid==10)
			$ObjDB->NonQuery("INSERT INTO itc_assignment_sigmath_track(fld_track_id,fld_student_id,fld_oper,fld_schedule_id,fld_lesson_id,fld_created_by,fld_created_date) 
							 VALUES('".$maxid."','".$uid."','".$oper."','0','0','".$uid."','".date("Y-m-d H:i:s")."')");
}

//nextlessonn for mathmodule
if($oper=="nextlesson" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonids = (isset($method['lessonids'])) ?  $method['lessonids'] : '';
	$mathtype = (isset($method['mathtype'])) ?  $method['mathtype'] : '';
	$lessonids = explode(',',$lessonids);	
	
	$lessonarray=array();
	$qryexistlesson = $ObjDB->QueryObject("SELECT fld_lesson_id 
										  FROM itc_assignment_sigmath_master 
										  WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='".$mathtype."' 
										  AND fld_delstatus='0'");
	if($qryexistlesson->num_rows>0){
		while($res = $qryexistlesson->fetch_assoc()){
			extract($res);
			$lessonarray[]=$fld_lesson_id;
		}
	}
	$remaininglesson = array_diff($lessonids,$lessonarray);
	
	if(current($remaininglesson)!=''){	
		$function = "fn_diagnosticstart(".$sid.",".current($remaininglesson).",".$mathtype.")";
		$lessonname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',b.fld_version) 
												FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
												WHERE a.fld_id='".current($remaininglesson)."' AND b.fld_zip_type='1' AND b.fld_delstatus='0'");
		echo $function."~".$lessonname;
	}
	else{
		echo "completed";
	}
}

//orientationcomplete
if($oper=="orientationcomplete" and $oper!='')
{
	$sid = (isset($method['sid'])) ?  $method['sid'] : '';
	$lessonid = (isset($method['lessonid'])) ?  $method['lessonid'] : '';
	$maxid = (isset($method['maxid'])) ?  $method['maxid'] : '';	
	
	$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_status=1, fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$maxid."'");	
	
	$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
											 FROM itc_class_sigmath_lesson_mapping 
											 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id FROM itc_assignment_sigmath_master 
											 	WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' AND fld_schedule_id='".$sid."' AND fld_test_type='1' 
												AND fld_delstatus='0') 
											 ORDER BY fld_order LIMIT 0,1");
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
	$messagealertdet=$ObjDB->QueryObject("SELECT fld_message as messagealert, fld_from as frommsg1 FROM itc_message_master WHERE fld_readstatus='0' AND fld_to='".$uid."' AND fld_alert='1' AND fld_delstatus='0'");
	$noofmsg = $messagealertdet->num_rows;
	if($messagealertdet->num_rows>0){
		$res =$messagealertdet->fetch_assoc();
		extract($res);
			
		$frommsg=$ObjDB->SelectSingleValueInt("SELECT fld_profile_id FROM itc_user_master WHERE fld_id='".$frommsg1."' AND fld_delstatus='0'");
	}
	$message=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_message_master WHERE fld_readstatus='0' AND fld_to='".$uid."' AND fld_delstatus='0'");
	
	$calendar=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_calendar_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' AND fld_startdate='".$today."'");
	
	$classlock=$ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
											FROM itc_class_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON b.fld_class_id=a.fld_id 
											WHERE (b.fld_teacher_id='".$uid."' OR a.fld_created_by='".$uid."') AND a.fld_lock=1 AND a.fld_delstatus='0' AND b.fld_flag='1'");
	
	$lockstatus = $ObjDB->QueryObject("SELECT a.fld_id 
									  FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_class_id=b.fld_class_id 
									  LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=a.fld_schedule_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id 
									  LEFT JOIN itc_user_master AS e ON e.fld_id=a.fld_student_id  LEFT JOIN itc_ipl_master AS f ON f.fld_id=a.fld_lesson_id 
									  WHERE a.fld_type=5 AND b.fld_teacher_id='".$uid."' AND b.fld_flag='1'  AND c.fld_delstatus='0' AND d.fld_delstatus='0' AND e.fld_delstatus='0' 
									  AND f.fld_delstatus='0' AND a.fld_lockstatus='0' AND a.fld_delstatus='0' AND a.fld_status='0'");
	$lock=0;
	if($lockstatus->num_rows>0){
		while($res =$lockstatus->fetch_assoc()){
			extract($res);
			$ObjDB->NonQuery("UPDATE itc_assignment_sigmath_master SET fld_lockstatus='1', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."'  WHERE fld_id='".$fld_id."'");
		}
		$lock=1;							
	}
	echo $lock."~".$message."~".$calendar."~".$classlock."~".$messagealert."~".$frommsg."~".$noofmsg;
        
      
}

//reloaddiv
if($oper=="reloaddiv" and $oper!='')
{
	if($uid1=='') 
	{
		$qrysigmath = $ObjDB->QueryObject("SELECT a.fld_id AS sid, a.fld_schedule_name AS sname, fn_shortname(a.fld_schedule_name,1) AS shortname, a.fld_end_date AS edate FROM itc_class_sigmath_master AS a LEFT JOIN itc_class_sigmath_student_mapping AS b ON a.fld_id=b.fld_sigmath_id LEFT JOIN itc_class_master AS c ON c.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_student_id='".$uid."' AND DATE(a.fld_start_date) <= DATE(NOW()) AND DATE(a.fld_end_date) >= DATE(NOW()) AND c.fld_delstatus='0' AND c.fld_lock='0' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')");  
		
		$qrydyadtriad = $ObjDB->QueryObject("SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Dyad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 2 AS schtype FROM `itc_class_dyad_schedulemaster` AS a LEFT JOIN `itc_class_dyad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate)<=DATE(NOW()) AND b.fld_startdate<='".date("Y-m-d")."' AND b.fld_enddate>='".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'		UNION    SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Triad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 3 AS schtype FROM `itc_class_triad_schedulemaster` AS a LEFT JOIN `itc_class_triad_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate)<=DATE(NOW()) AND b.fld_startdate<='".date("Y-m-d")."' AND b.fld_enddate>='".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1'"); 
		
		$qrytraditional = $ObjDB->QueryObject("SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation-1) AS modulename, 1 AS schtype FROM `itc_class_rotation_schedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate)<=DATE(NOW()) AND b.fld_startdate<='".date("Y-m-d")."' AND b.fld_enddate>='".date("Y-m-d")."' AND a.fld_moduletype='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')  AND b.fld_flag='1'      UNION        SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Math Module') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_mathmodule_name,' / Rotation ',b.fld_rotation-1) AS modulename, 4 AS schtype FROM `itc_class_rotation_schedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_schedulegriddet` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate)<=DATE(NOW()) AND b.fld_startdate<='".date("Y-m-d")."' AND b.fld_enddate>='".date("Y-m-d")."' AND a.fld_moduletype='2' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')  AND b.fld_flag='1'		UNION 		SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Module') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_module_name,' / Individual Module ') AS modulename, 5 AS schtype FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND b.fld_student_id='".$uid."' AND a.fld_startdate<='".date("Y-m-d")."' AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_enddate>='".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<=NOW() AND fld_end_date >=NOW())  AND b.fld_flag='1'				UNION 		SELECT a.fld_module_id, CONCAT(a.fld_schedule_name,' / Math Module') AS schedulename, a.fld_id AS scheduleid, a.fld_enddate, CONCAT(c.fld_mathmodule_name,' / Individual Math Module ') AS modulename, 6 AS schtype FROM `itc_class_indassesment_master` AS a LEFT JOIN `itc_class_indassesment_student_mapping` AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='2' AND b.fld_student_id='".$uid."' AND DATE(a.fld_startdate)<=DATE(NOW()) AND a.fld_startdate<='".date("Y-m-d")."' AND a.fld_enddate>='".date("Y-m-d")."' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1' ");
		
		$qrytest = $ObjDB->QueryObject("SELECT a.fld_max_attempts AS maxattempts, b.fld_max_attempts AS timeattempted, a.fld_test_name AS testname, b.fld_start_date AS fld_enddate, fn_shortname(a.fld_test_name,1) AS shortname, a.fld_id AS testid, a.fld_id, b.fld_id AS testmapid FROM itc_test_master AS a, itc_test_student_mapping AS b LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id WHERE a.fld_id=b.`fld_test_id` AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_student_id ='".$uid."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND DATE(b.fld_start_date)=DATE(NOW())");
		
		$qryactivities = $ObjDB->QueryObject("SELECT a.fld_activity_name AS activityname, a.fld_id AS activityid, b.fld_end_date AS fld_enddate, fn_shortname(a.fld_activity_name,1) AS shortname FROM itc_activity_master AS a, itc_activity_student_mapping AS b LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id WHERE a.fld_id=b.`fld_activity_id` AND c.fld_delstatus='0' AND c.fld_lock='0' AND b.fld_student_id ='".$uid."' AND b.fld_flag='1' AND a.fld_delstatus='0'  AND DATE(b.fld_start_date)=DATE(NOW())");
		
		$testcount = 0;
		if($qrytest->num_rows>0){                                           
			while($rowtest=$qrytest->fetch_assoc()){
				extract($rowtest);
				if($maxattempts>$timeattempted)
				{
					$testcount++;
					$schedulenames = '';
					$assnames = $testname;
					$duedates = '';
					$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-assignmentengine-gototest','assignment/assignmentengine/assignment-assignmentengine-gototest.php','id=".$testid."');";
				}
				else
				{
					$testcount;
				}
			}
		}
		
		$sigmathcount = $qrysigmath->num_rows;
		$sciencecount = $qrydyadtriad->num_rows;
		$traditionalcount = $qrytraditional->num_rows;
		$activitiescount = $qryactivities->num_rows;
	}
	else
	{
		$qrydyadtriad = $ObjDB->QueryObject("SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Dyad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 2 AS schtype FROM itc_class_dyad_schedulemaster AS a LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (SELECT fld_module_id FROM itc_class_dyad_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid."' AND fld_schedule_id=b.fld_schedule_id) = (SELECT fld_module_id FROM itc_class_dyad_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid1."' AND fld_schedule_id=b.fld_schedule_id) AND DATE(a.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW()) AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1' GROUP BY scheduleid 		UNION 		SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Triad') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation) AS modulename, 3 AS schtype FROM itc_class_triad_schedulemaster AS a LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND (SELECT fld_module_id FROM itc_class_triad_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid."' AND fld_schedule_id=b.fld_schedule_id) = (SELECT fld_module_id FROM itc_class_triad_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid1."' AND fld_schedule_id=b.fld_schedule_id) AND DATE(a.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW()) AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1' GROUP BY scheduleid");
		
		$qrytraditional = $ObjDB->QueryObject("SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Module') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_module_name,' / Rotation ',b.fld_rotation-1) AS modulename, 1 AS schtype FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id LEFT JOIN itc_class_master AS d ON d.fld_id=a.fld_class_id WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' AND a.fld_moduletype='1' AND (SELECT fld_module_id FROM itc_class_rotation_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid."' AND fld_schedule_id=b.fld_schedule_id) = (SELECT fld_module_id FROM itc_class_rotation_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid1."' AND fld_schedule_id=b.fld_schedule_id) AND DATE(a.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW()) AND a.fld_moduletype='1' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1' GROUP BY scheduleid      UNION      SELECT b.fld_module_id, CONCAT(a.fld_schedule_name,' / Math Module') AS schedulename, a.fld_id AS scheduleid, b.fld_enddate, CONCAT(c.fld_mathmodule_name, ' / Rotation ', b.fld_rotation - 1) AS modulename, 4 AS schtype FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id = b.fld_schedule_id LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id = c.fld_id LEFT JOIN itc_class_master AS d ON d.fld_id = a.fld_class_id WHERE a.fld_delstatus = '0' AND d.fld_delstatus = '0' AND d.fld_lock = '0' AND a.fld_moduletype = '2' AND (SELECT fld_module_id FROM itc_class_rotation_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid."' AND fld_schedule_id=b.fld_schedule_id) = (SELECT fld_module_id FROM itc_class_rotation_schedulegriddet WHERE fld_module_id=b.fld_module_id AND fld_rotation=b.fld_rotation AND fld_row_id=b.fld_row_id AND fld_student_id='".$uid1."' AND fld_schedule_id=b.fld_schedule_id) AND DATE(a.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_startdate) <= DATE(NOW()) AND DATE(b.fld_enddate) >= DATE(NOW()) AND a.fld_moduletype = '2' AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."') AND b.fld_flag='1' GROUP BY scheduleid");
		
		$sigmathcount = 0;
		$sciencecount = $qrydyadtriad->num_rows;
		$traditionalcount = $qrytraditional->num_rows;
		$testcount = 0;
		$activitiescount = 0;
	}
		
	$totalcount = $sigmathcount+$sciencecount+$traditionalcount+$testcount+$activitiescount;
	
	if($totalcount==1)
	{
		if($sigmathcount!=0){                                           
			while($rowsigmath=$qrysigmath->fetch_assoc()){
				extract($rowsigmath);
				
				$currentqry = $ObjDB->QueryObject("SELECT fld_id AS maxid, fld_lesson_id AS lessonid, fld_type AS type, fld_status AS status 
													FROM itc_assignment_sigmath_master 
													WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$uid."' AND fld_status=0 AND fld_delstatus='0'  
													ORDER BY fld_id DESC LIMIT 0,1");
				$flag=0;
				$completed=0;
				if($currentqry->num_rows>0){
					$current_res = $currentqry->fetch_assoc();
					extract($current_res);
					
					//check is the lesson is abailable or not 
					$chklesson = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
															  FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
															  WHERE a.fld_sigmath_id='".$sid."' AND a.fld_lesson_id='".$lessonid."' AND a.fld_flag='1' AND b.fld_access='1' 
															  AND b.fld_delstatus='0'");		
									
					if($chklesson>0){
						$flag=1;
						if($type==1){
							$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
						}
						else if($type==2){
							$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0' AND fld_id='".$lessonid."'");
							if($orientationid==0 || $orientationid=='')
								$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.")";
							else
								$function = "fn_lessonplay(".$sid.",".$lessonid.",".$maxid.",1)";
						}
						else if($type==3){
							$function = "fn_startmastery1(".$sid.",".$lessonid.",".$maxid.")";
						}
						else if($type==4){
							$function = "fn_startmastery2(".$sid.",".$lessonid.",".$maxid.")";
						}
						else if($type==6){
							$function = "fn_diagfinish(".$sid.",".$lessonid.",0,".$maxid.")";
						}
						else if($type==5 or $type==7){
							$function = "fn_mastery1finish(".$sid.",".$lessonid.",0,".$maxid.")";
						}
						else if($type==8){
							$function = "fn_mastery2finish(".$sid.",".$lessonid.",0,".$maxid.")";
						}
					}
				}
				
				if($flag!=1){
					//get the lesson with out previously attend
					$orientationid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_ipl_master WHERE fld_lesson_type='2' AND fld_delstatus='0'");
					$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
															 FROM itc_class_sigmath_lesson_mapping 
															 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND ".$orientationid." NOT IN(SELECT fld_lesson_id 
															 	FROM itc_assignment_sigmath_master WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' 
																AND fld_schedule_id='".$sid."' AND fld_test_type='1' AND fld_delstatus='0' ) AND fld_lesson_id='".$orientationid."'");
					
					$function = "fn_lessonplay(".$sid.",".$lessonid.",1,1)";	
					if($lessonid==0 || $lessonid=='')
					{
						$lessonid = $ObjDB->SelectSingleValueInt("SELECT fld_lesson_id 
																 FROM itc_class_sigmath_lesson_mapping 
																 WHERE fld_sigmath_id='".$sid."' AND fld_flag='1' AND fld_lesson_id NOT IN(SELECT fld_lesson_id 
																 FROM itc_assignment_sigmath_master WHERE (fld_status=1 OR fld_status=2) AND fld_student_id='".$uid."' 
																 AND fld_schedule_id='".$sid."' AND fld_test_type='1' AND fld_delstatus='0' ) ORDER BY fld_order LIMIT 0,1");
					
						if($lessonid==0){
							$completed=1;								
						}
						$function = "fn_diagnosticstart(".$sid.",".$lessonid.",1)";
					}
				}
				$schedulenames = $sname;
				$assnames = $ObjDB->SelectSingleValue("SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id='".$lessonid."'");
				$duedates = $edate;
				$callfunction = "removesections('#assignment'); showpageswithpostmethod('assignment-sigmath-test','assignment/sigmath/assignment-sigmath-test.php','id=".$sid."~".$lessonid."~".$function."');";
			}
		}
		
		else if($sciencecount!=0){                                           
			while($rowdyadtriad=$qrydyadtriad->fetch_assoc()){
				extract($rowdyadtriad);
				$schedulenames = $schedulename;
				$assnames = $modulename;
				$duedates = $fld_enddate;
				$callfunction = "removesections('#assignment'); fn_showsess('".$scheduleid."~".$fld_module_id."~".$schtype."',1);";
			}
		}
		
		else if($traditionalcount!=0){                                           
			while($rowtraditional=$qrytraditional->fetch_assoc()){
				extract($rowtraditional);
				$schedulenames = $schedulename;
				$assnames = $modulename;
				$duedates = $fld_enddate;
				$callfunction = "removesections('#assignment'); fn_showsess('".$scheduleid."~".$fld_module_id."~".$schtype."',2);";
			}
		}
		
		else if($activitiescount!=0){                                           
			while($rowactivities=$qryactivities->fetch_assoc()){
				extract($rowactivities);
				$schedulenames = '';
				$assnames = $activityname;
				$duedates = $fld_enddate;
				$callfunction = "removesections('#assignment'); showpageswithpostmethod('library-activities-viewactivity','library/activities/library-activities-viewactivity.php','id=".$activityid."')";
			}
		}
	}
	?>
    <div class="dashSchedule"><?php echo $schedulenames; ?></div>
    <div class="dashModule"><?php echo $assnames; ?></div>
    <div class="dashDuedate"><?php if($duedates!='') {?>Due Date: <?php echo date("m/d/Y",strtotime($duedates)); }?> </div>
    <div class="dashStart"><input type="button" class="btnstart" value="Start" onclick="removesections('#home');<?php echo $callfunction; ?>"/></div>
    <?php
}

// Alert message close(fld_alert is changed to 0)
if($oper == "alertclose" and $oper != '')
{
	$ObjDB->NonQuery("UPDATE itc_message_master SET fld_alert='0', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_to='".$uid."' AND fld_alert='1'");	
}

	@include("footer.php");
