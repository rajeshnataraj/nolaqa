<?php
	@include("sessioncheck.php");
	$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';
	$date=date("Y-m-d H:i:s");
//Close the test without pasue
if($oper=="cltestwopause" and $oper!='')
{
	$testid = (isset($_REQUEST['testid'])) ?  $_REQUEST['testid'] : '';
	
	$ObjDB->NonQuery("UPDATE `itc_test_student_mapping` SET `fld_test_pause`='0',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
                 	WHERE fld_student_id='".$uid."' and fld_test_id='".$testid."'");
}

//Pause test
if($oper=="pausetest" and $oper!='')
{
	$testid = (isset($_REQUEST['testid'])) ?  $_REQUEST['testid'] : '';
	$quesids = (isset($_POST['quesids'])) ?  $_POST['quesids'] : '';
	$timepause = (isset($_POST['timepause'])) ?  $_POST['timepause'] : '';
	$currectquesids = (isset($_POST['currectquesids'])) ?  $_POST['currectquesids'] : '';
	$classid = (isset($_POST['classid'])) ?  $_POST['classid'] : '';
	
	$count = $ObjDB->SelectSingleValueInt("SELECT `fld_max_attempts` 
											FROM `itc_test_student_mapping` 
											WHERE  fld_student_id='".$uid."' and fld_test_id='".$testid."'")-1;
										
	$ObjDB->NonQuery("UPDATE itc_test_student_mapping 
						SET fld_max_attempts='".$count."',fld_test_pause='1',fld_updated_by='".$uid."',fld_updated_date='".$date."' 
						WHERE fld_test_id='".$testid."' AND fld_student_id='".$uid."' AND fld_class_id='".$classid."'");						
					
	$ObjDB->NonQuery("UPDATE itc_test_pause 
						SET fld_delstatus='1',fld_updated_by='".$uid."',fld_updated_date='".$date."'  
						WHERE fld_test_id='".$testid."' AND fld_student_id='".$uid."' AND fld_class_id='".$classid."'");
												
	$ObjDB->NonQuery("INSERT INTO itc_test_pause(fld_test_id, fld_student_id,fld_class_id, fld_question_ids, fld_time,
						fld_pause_question,fld_created_by,fld_created_date)
					VALUES('".$testid."','".$uid."','".$classid."','".$quesids."','".$timepause."','".$currectquesids."','".$uid."','".$date."')");
		
		
	
}
//answer check	
if($oper=="answercheck" and $oper!='')
{
	$testid = (isset($_REQUEST['testid'])) ?  $_REQUEST['testid'] : '';
	$timecount = (isset($_POST['timecount'])) ?  $_POST['timecount'] : '';
	$anstype = (isset($_REQUEST['anstype'])) ?  $_REQUEST['anstype'] : '';
	$quesid = (isset($_REQUEST['quesid'])) ?  $_REQUEST['quesid'] : '';	
	$answer = (isset($_REQUEST['answer'])) ?  $_REQUEST['answer'] : '';
	$answer1 = (isset($_REQUEST['answer'])) ?  $_REQUEST['answer'] : '';	
	$cqorder = (isset($_REQUEST['cqorder'])) ?  $_REQUEST['cqorder'] : '';
        $classid = (isset($_POST['classid'])) ?  $_POST['classid'] : '';
        
	$show = 0;
	$count=0;
	
        $schid= isset($method['schid']) ? $method['schid'] : '';
        $schtype= isset($method['schtype']) ? $method['schtype'] : '';
	$maxstudatmpt = (isset($_REQUEST['maxstudatmpt'])) ?  $_REQUEST['maxstudatmpt'] : '';
           
        $qryquestiontagid = $ObjDB->QueryObject("SELECT fld_tag_id FROM itc_test_questionassign WHERE fld_question_id='".$quesid."' and fld_test_id='".$testid."' and fld_delstatus='0'");
        
        while($rowqryquestiontagid = $qryquestiontagid->fetch_assoc())
        {
            extract($rowqryquestiontagid);
            $chktagcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                    FROM itc_test_student_answer_track 
                                                    WHERE fld_question_id='".$quesid."' AND fld_delstatus='0' AND fld_test_id='".$testid."' 
                                                            AND fld_student_id='".$uid."' AND fld_tag_id='".$fld_tag_id."'");
            
            if($chktagcount == '0')
            {
                $questiontagid = $fld_tag_id;
                
            }
        }
	if($cqorder==2 or $cqorder==4 or $cqorder==6 or $testtype==1){
		$show = 1;
	}
	$tempanscount=0;	
        
        $stumapid = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_student_mapping 
                                                        WHERE fld_test_id='".$testid."' AND fld_student_id='".$uid."' AND fld_class_id='".$classid."'
                                                        AND fld_flag='1'");
        
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
		
		$chk = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
											FROM itc_test_student_answer_track 
                                                    WHERE fld_question_id='".$quesid."' AND fld_delstatus='0' AND fld_test_id='".$testid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'
                                                            AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' AND fld_attempts='".$maxstudatmpt."' AND fld_student_id='".$uid."'");
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer1."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer1."', fld_show='".$show."', fld_time_track='".$timecount."',fld_updated_by='".$uid."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."' ");
		}
	}
	
	
	if($anstype==2 or $anstype==5)
	{
		if($anstype==2)
		{
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_question_answer_mapping where fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='1' AND fld_flag='1' AND LOWER(REPLACE(fld_answer, ' ', ''))='".strtolower(str_replace(' ', '', $answer))."'");
		}
		else{
			$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) from itc_question_answer_mapping where fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' AND LOWER(REPLACE(fld_answer, ' ', ''))='".strtolower(str_replace(' ', '', $answer))."'");			
		}
			
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."' , fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."' ");
		}
	}
	
	
    if($anstype==15 )
	{
		
		$count=0;		
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."',fld_open_flag='0', fld_attempts='".$maxstudatmpt."'  
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		}
	}
	
	
		
	if($anstype==3)
	{
		$tempanscount=0;
		$answer1=$answer;
		$answer=explode("~",$answer);
		$qry = $ObjDB->QueryObject("SELECT fld_answer AS canswer FROM itc_question_answer_mapping 
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
		else{
			$count=0;
		}
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
                                                        AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($count==0){$show=0;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer1."','".$count."','".$show."',
								'".$timecount."','".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer1."', fld_show='".$show."', fld_time_track='".$timecount."', 
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."',fld_attempts='".$maxstudatmpt."' 
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
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
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show,fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer1."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");
				}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer1."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		}
	}
	
	
	if($anstype==6)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping 
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
		$answer1=$answer;
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
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer1."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer1."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."',fld_attempts='".$maxstudatmpt."' 
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		}
	}
	
	if($anstype==7)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping 
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
		
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."',
								'".$timecount."','".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."',fld_attempts='".$maxstudatmpt."' where fld_test_id='".$testid."' 
								and fld_question_id='".$quesid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		}
		
	}
	
		
	if($anstype==9)
	{
		$qry = $ObjDB->QueryObject("SELECT LCASE(fld_answer) as fld_answer FROM itc_question_answer_mapping 
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
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."',  fld_time_track='".$timecount."',
								fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."', fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
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
											AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_by='".$uid."',fld_updated_date='".$date."',fld_attempts='".$maxstudatmpt."' 
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
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
												AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, 
								fld_answer_type_id, fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',fld_updated_by='".$uid."',
								fld_updated_date='".$date."',fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
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
                
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."',
								'".$timecount."','".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
                        
                }
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_by='".$uid."',fld_updated_date='".$date."',fld_attempts='".$maxstudatmpt."' 
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		}
	}
	
	if($anstype==13)
	{
		$qry = $ObjDB->QueryObject("SELECT fld_answer FROM itc_question_answer_mapping 
									WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_attr_id='2' AND fld_flag='1' 
									ORDER BY fld_boxid ASC");
		$stuanswer=explode("~",$answer);
		$answerarray=array();
                $answerarray1=array();
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
												AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',fld_updated_by='".$uid."',
								fld_updated_date='".$date."',fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
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
		$row=$qry->fetch_assoc();
                extract($row);
                $corrans = explode(",", $fld_answer);

                for ($i=0; $i < sizeof($corrans); $i++) {
                    $min = $corrans[$i]-20;
                    $max = $corrans[$i]+20;
                    $range = range($min, $max);

                    if(in_array($stuanswer[$i], $range)) {
                        ${"count" . $i} = 1;
                    }
                    else {
                        ${"count" . $i} = 0;
                    }
                }

                if($count0 and $count1 and $count2 and $count3){
                    $count = 1;
                }
                else {
                    $count = 0;
                }
		
		$chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
											where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
												AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		if($count==1){$show=1;}
		if($chk==0)
		{
			$ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
								fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
							values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
								'".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		
		}
		else
		{
			$ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
								fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
								fld_updated_by='".$uid."',fld_updated_date='".$date."',fld_attempts='".$maxstudatmpt."'
							where fld_test_id='".$testid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_question_id='".$quesid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
		}
	}
	
        
    /************Custom Materices Code Start Here Developed by Mohan M 30-7-2015************/  
        if($anstype==16)
        {
            
            $qry = $ObjDB->QueryObject("SELECT fld_boxid AS txtboxval, fld_answer AS crctanswer, fld_attr_id AS columnsval
                                                                             FROM itc_question_answer_mapping 
                                                                             WHERE fld_quesid='".$quesid."' AND fld_ans_type='".$anstype."' AND fld_flag='1'");
            if($qry->num_rows > 0) 
            {
                $answerarray=array();
		$i=0;
               while($row = $qry->fetch_assoc())
               {
                   extract($row); 
                  
                    $answerarray[$i]=$crctanswer;
                    $i++;
                    
               }
            }
            
            $matdetailtemp = explode(',',$answer);
            
            for($r=0;$r<sizeof($answerarray);$r++)
            {
                if($answerarray[$r]==$matdetailtemp[$r]){
                    $count=1;
                }
                else{
                    $count=0;
                    break;
                }              
            }

            $chk = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_student_answer_track 
                                                            where fld_question_id='".$quesid."' and fld_delstatus='0' and fld_test_id='".$testid."' 
                                                                    AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_attempts='".$maxstudatmpt."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
            if($count==1){$show=1;}
            if($chk==0)
            {
                    $ObjDB->NonQuery("INSERT INTO itc_test_student_answer_track(fld_test_id, fld_student_id, fld_question_id, fld_tag_id, fld_answer_type_id, 
                                                            fld_answer, fld_correct_answer, fld_show, fld_time_track,fld_schedule_id,fld_schedule_type,fld_created_by,fld_created_date,fld_attempts, fld_class_id, fld_stumap_id)
                                                    values('".$testid."','".$uid."','".$quesid."','".$questiontagid."','".$anstype."','".$answer."','".$count."','".$show."','".$timecount."',
                                                            '".$schid."','".$schtype."','".$uid."','".$date."','".$maxstudatmpt."','".$classid."','".$stumapid."')");		}
            else
            {
                    $ObjDB->NonQuery("update itc_test_student_answer_track set fld_answer_type_id='".$anstype."', fld_correct_answer='".$count."', 
                                                            fld_answer='".$answer."', fld_show='".$show."', fld_time_track='".$timecount."',
                                                            fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."',fld_attempts='".$maxstudatmpt."' 
                                                    where fld_test_id='".$testid."' and fld_question_id='".$quesid."' AND fld_schedule_id='".$schid."' AND fld_schedule_type='".$schtype."' and fld_student_id='".$uid."' AND fld_class_id='".$classid."' AND fld_stumap_id='".$stumapid."'");
            }
        }
                   
   /************Custom Materices Code End Here Developed by Mohan M 30-7-2015************/  
        
        
	$licenseid = $ObjDB->SelectSingleValueInt("SELECT a.fld_license_id 
											FROM itc_license_assessment_mapping AS a 
											LEFT JOIN itc_license_track AS b ON a.fld_license_id=b.fld_license_id 
											LEFT JOIN itc_user_master AS c ON (b.fld_school_id=c.fld_school_id AND b.fld_user_id=c.fld_user_id) 
											WHERE c.fld_id='".$uid."' AND b.fld_delstatus='0' AND b.fld_start_date<=NOW() AND b.fld_end_date>=NOW() 
												AND a.fld_assessment_id='".$testid."' AND a.fld_access='1' AND a.fld_license_id 
											NOT IN (SELECT fld_license_id FROM itc_license_track_student 
											WHERE fld_student_id='".$uid."' AND fld_flag='1') LIMIT 0,1");
	
	if($licenseid!='' && $licenseid!='0')
	{
		$stcount = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_track_student 
												WHERE fld_student_id='".$uid."' AND fld_license_id='".$licenseid."' AND fld_flag='1'");
		
		if($stcount==0)
		{
			$qryrem = $ObjDB->QueryObject("SELECT fld_id, fld_remain_users FROM itc_license_track 
										WHERE fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_district_id='".$districtid."' 
											AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
											AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date>='".date("Y-m-d")."'");
			
			if($qryrem->num_rows > 0) {
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
				$ObjDB->NonQuery("UPDATE itc_license_track SET fld_remain_users='".$remusers."',fld_updated_by='".$uid."',
									fld_updated_date='".$date."' 
								WHERE fld_id='".$fld_id."'");
				
				$ObjDB->NonQuery("INSERT INTO itc_license_track_student(fld_student_id, fld_license_id, fld_flag,fld_created_by,fld_created_date) 
								values('".$uid."', '".$licenseid."', '1','".$uid."','".$date."')");
			}
		}
	}
	
	echo $anscount+$count."~".$count."~".$quesid;
}

if($oper=="savereportmark" and $oper!='')
{
	$testid = (isset($_REQUEST['testid'])) ?  $_REQUEST['testid'] : '';	
	
	$qrytestexp = $ObjDB->QueryObject("SELECT fld_expt AS expid, fld_total_question AS quescount FROM itc_test_master WHERE fld_id='".$testid."'");
	
	if($qrytestexp->num_rows > 0) {
		$resqrytestexp = $qrytestexp->fetch_assoc();
		extract($resqrytestexp);
		
		if($expid!=0)
		{
			$qryexp = $ObjDB->QueryObject("SELECT a.fld_id AS schid, b.fld_pointspossible AS exppossible, b.fld_id AS exptypeid FROM itc_class_indasexpedition_master AS a 
											LEFT JOIN itc_class_exp_grade AS b ON (a.fld_id=b.fld_schedule_id) 
											WHERE b.fld_exp_id='".$expid."' AND b.fld_flag='1' AND b.fld_exptype='3' AND a.fld_exp_id='".$expid."' 
												AND a.fld_flag='1' AND a.fld_delstatus='0'");
			
			if($qryexp->num_rows > 0) {
				while($resqryexp = $qryexp->fetch_assoc())
				{
					extract($resqryexp);
					
					$correctcount = $ObjDB->SelectSingleValueInt("SELECT count(*) from itc_test_student_answer_track WHERE fld_delstatus='0' AND fld_test_id='".$testid."' AND fld_student_id='".$uid."' AND fld_correct_answer='1'");
					
					$totalscore = $correctcount*($exppossible/$quescount);
					
					$exppointfieldid = $ObjDB->SelectSingleValueInt("SELECT fld_id from itc_exp_points_master WHERE fld_delstatus='0' AND fld_schedule_id='".$schid."' AND fld_student_id='".$uid."' AND fld_exptype='3' AND fld_res_id='".$exptypeid."' AND fld_exp_id='".$expid."'");
					
					if($exppointfieldid!=0)
						$ObjDB->NonQuery("UPDATE itc_exp_points_master SET fld_points_earned='".$totalscore."', 
											fld_points_possible='".$exppossible."', fld_updated_by='".$uid."', fld_updated_date='".date("Y-m-d")."' 
										WHERE fld_id='".$exppointfieldid."'");
					else
						$ObjDB->NonQuery("INSERT INTO itc_exp_points_master(fld_student_id, fld_schedule_id, fld_exp_id, fld_exptype, 
											fld_schedule_type, fld_res_id, fld_points_earned, fld_grade, fld_lock, fld_created_by, 
											fld_created_date, fld_points_possible) 
										values('".$uid."', '".$schid."', '".$expid."', '3', '15', '".$exptypeid."', '".$totalscore."', '1', '0',
											'".$uid."', '".date("Y-m-d")."', '".$exppossible."')");
				}	
			}
		}
	}	
}
	@include("footer.php");