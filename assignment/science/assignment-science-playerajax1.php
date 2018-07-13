<?php
	@include("sessioncheck.php");
	
	$oper = (isset($method['oper'])) ? $method['oper'] : '';
	$date = date("Y-m-d H:i:s");
	
	if($oper=="showscore" and $oper != " " )
	{
		$sectionid = isset($method['sectionid']) ? $method['sectionid'] : '';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';
		$testerid = isset($method['testerid']) ? $method['testerid'] : '';
		$testerid1 = isset($method['testerid1']) ? $method['testerid1'] : '';
		
		$score = $score1 = '';
		$eligible = $eligible1 = 1; 
		$pageid = $pageid1 = 0;
		
		$qryscore = $ObjDB->QueryObject("SELECT fld_lock, (CASE WHEN fld_lock='1' THEN fld_teacher_points_earned WHEN fld_lock='0' THEN fld_points_earned END) AS points 
										FROM itc_module_points_master 
										WHERE fld_session_id='".$sectionid."' AND fld_student_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
											AND fld_type='0' AND fld_preassment_id='0' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
	
		if($qryscore->num_rows>0){
			$rowscore=$qryscore->fetch_assoc();
			extract($rowscore);
			
			if($points != '') {	
				$eligible = 0;
				$score = $points;
			}
		}
		
		if($score != ''){
			$pageid = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_page_id) 
													FROM itc_module_play_track 
													WHERE fld_section_id='".$sectionid."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' 
														AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
		}
		
		if($testerid1 != ''){
			$qryscore1 = $ObjDB->QueryObject("SELECT fld_lock AS fld_lock1, (CASE WHEN fld_lock='1' THEN fld_teacher_points_earned WHEN fld_lock='0' THEN fld_points_earned END) AS points1 
												FROM itc_module_points_master 
												WHERE fld_session_id='".$sectionid."' AND fld_student_id='".$testerid1."' AND fld_module_id='".$moduleid."' AND fld_delstatus='0' 
													AND fld_schedule_id='".$scheduleid."' AND fld_type='0' AND fld_preassment_id='0' AND fld_schedule_type='".$scheduletype."'");
			
			if($qryscore1->num_rows>0){
				$rowscore1=$qryscore1->fetch_assoc();
				extract($rowscore1);
				
				if($points1 != '') {	
					$eligible1 = 0;
					$score1 = $points1;
				}
			}
			
			if($score1 != ''){
				$pageid1 = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_page_id) 
														FROM itc_module_play_track 
														WHERE fld_section_id='".$sectionid."' AND fld_tester_id='".$testerid1."' AND fld_module_id='".$moduleid."' 
															AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
			}
		}

		$result = array("score" => $score, "score1" => $score1, "eligible" => $eligible, "eligible1" => $eligible1, "pageid" =>$pageid ,"pageid1" => $pageid1);
		echo json_encode($result);
	}

	if($oper=="answertrack" and $oper != "") {
		
		$tmpqanswer = json_decode($method['q'], true);
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0'; 
		$sessionid = isset($method['sessionid']) ? $method['sessionid'] : ''; 
		$pageid = isset($method['pageid']) ? $method['pageid'] : '';
		
		for($i=0;$i<sizeof($tmpqanswer);$i++){
			$qanswer = $tmpqanswer[$i];
			
			$answerid1 = 0; 
			$ansoption1 = ''; 
			$anstext1 = '';
			$answerid = $qanswer['answers'][0]['answer_id']; 
			$ansoption = $qanswer['answers'][0]['number']; 
			$anstext = $ObjDB->EscapeStr($qanswer['answers'][0]['text']); 
			
			if(sizeof($qanswer['answers']) > 1) {
				$answerid1 = $qanswer['answers'][1]['answer_id']; 
				$ansoption1 = $qanswer['answers'][1]['number']; 
				$anstext1 = $ObjDB->EscapeStr($qanswer['answers'][1]['text']);
			}
			
			$assid = $qanswer['assessment_id'];
			$correct = ($qanswer['correct'] != '')? $qanswer['correct'] : 0;
			$attempts = $qanswer['attempts'];
			$questionid = $qanswer['question_id'];
			$questiontext = $ObjDB->EscapeStr($qanswer['question_text']);
			$testerid = $qanswer['tester_id'];
			$earned = $qanswer['points_earned'];
			$possible = $qanswer['points_possible'];
	
			if($scheduletype==4 or $scheduletype==6)
			{
				$tempmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id 
															FROM itc_mathmodule_master 
															WHERE fld_id='".$moduleid."'");
			}
			else{
				$tempmoduleid = $moduleid;
			}
			$ObjDB->NonQuery("UPDATE itc_module_answer_track 
							SET fld_delstatus='1', fld_deleted_date='".$date."' 
							WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sessionid."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' 
								AND fld_schedule_id='".$scheduleid."' AND fld_question_id='".$questionid."' AND fld_schedule_type='".$scheduletype."'");
			
			$ObjDB->NonQuery("INSERT INTO itc_module_answer_track (fld_tester_id, fld_module_id, fld_schedule_id, fld_session_id, fld_page_id, fld_assessment_id, fld_question_id, 
								fld_question_text, fld_answer_id, fld_answer_option, fld_answer_option1, fld_answer_text, fld_answer_text1, fld_correct, fld_attempts, fld_points_possible, 
								fld_points_earned, fld_created_by, fld_created_date, fld_schedule_type) 
							VALUES ('".$testerid."', '".$moduleid."', '".$scheduleid."', '".$sessionid."', '".$pageid."', '".$assid."', '".$questionid."', 
								'".$questiontext."', '".$answerid."', '".$ansoption."', '".$ansoption1."', '".$anstext."', '".$anstext1."', '".$correct."', '".$attempts."', 
								'".$possible."', '".$earned."', '".$uid."', '".$date."', '".$scheduletype."')");
			
			$pointsearned = $ObjDB->SelectSingleValueInt("SELECT SUM(fld_points_earned) 
														FROM itc_module_answer_track 
														WHERE fld_page_id='".$pageid."' AND fld_session_id='".$sessionid."' AND fld_tester_id='".$testerid."' AND fld_delstatus='0' 
															AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."'");
	
			if($scheduletype<5) //5
				$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible, fld_grade AS grade 
											FROM itc_module_grade 
											WHERE fld_session_id='".$sessionid."' AND fld_flag='1' AND fld_module_id='".$moduleid."'");
			else if($scheduletype==7)
				$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible, fld_grade AS grade 
											FROM itc_module_quest_details 
											WHERE fld_section_id='".$sessionid."' AND fld_flag='1' AND fld_module_id='".$moduleid."' 
												AND fld_page_id='".$pageid."'");
			else
				$qry = $ObjDB->QueryObject("SELECT fld_points AS pointspossible, fld_grade AS grade 
											FROM itc_module_wca_grade 
											WHERE fld_session_id='".$sessionid."' AND fld_flag='1' AND fld_module_id='".$moduleid."' 
												AND fld_schedule_id='".$scheduleid."' AND fld_type='0'");
												//AND fld_page_title='".$pagetitle."' 
			
			if($qry->num_rows > 0) { 
				$row = $qry->fetch_assoc();
				extract($row);
				
				$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_module_points_master 
													WHERE fld_session_id='".$sessionid."' AND fld_student_id='".$testerid."' AND fld_module_id='".$moduleid."' 
														AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' AND fld_type='0'");
				
				if($cnt!=0)
				{
					$ObjDB->NonQuery("UPDATE itc_module_points_master 
									SET fld_points_earned='".$pointsearned."', fld_points_possible='".$pointspossible."', fld_updated_date='".$date."', fld_updated_by='".$uid."' 
									WHERE fld_session_id='".$sessionid."' AND fld_student_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
										AND fld_schedule_type='".$scheduletype."' AND fld_id='".$cnt."'");
				}
				else
				{
					$ObjDB->NonQuery("INSERT INTO itc_module_points_master (fld_student_id, fld_module_id, fld_schedule_id, fld_session_id, fld_points_possible, fld_points_earned, 
										fld_grade, fld_schedule_type, fld_created_by, fld_created_date) 
									VALUES ('".$testerid."', '".$moduleid."', '".$scheduleid."', '".$sessionid."', '".$pointspossible."', '".$pointsearned."', 
										'".$grade."', '".$scheduletype."', '".$uid."', '".$date."')");
				}
			}
		}		
	}
	
	if($oper=="variabletrack" and $oper != "") {
		
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0'; 
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0'; 
		$sessionid = isset($method['sessionid']) ? $method['sessionid'] : ''; 
		$pageid = isset($method['pageid']) ? $method['pageid'] : ''; 
		$key = isset($method['key']) ? $ObjDB->EscapeStr($method['key']) : ''; 
		$answer = isset($method['answer']) ? $ObjDB->EscapeStr($method['answer']) : ''; 
		$testerid = isset($method['testerid']) ? $method['testerid'] : ''; 
		$testerid1 = isset($method['testerid1']) ? $method['testerid1'] : ''; 
		$edit = isset($method['edit']) ? $method['edit'] : 0; 
	
		if($edit==0)
		{
			$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_module_variable_track 
													WHERE fld_key='".$key."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_session_id='".$sessionid."' AND fld_page_id='".$pageid."' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
			
			if($count == 0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_module_variable_track (fld_tester_id, fld_module_id, fld_session_id, fld_page_id, fld_key, fld_key_value, fld_created_by, 
									fld_created_date, fld_schedule_id, fld_schedule_type) 
								VALUES ('".$testerid."', '".$moduleid."', '".$sessionid."', '".$pageid."', '".$key."', '".$answer."', '".$uid."', 
									'".$date."', '".$scheduleid."', '".$scheduletype."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_module_variable_track 
								SET fld_tester_id='".$testerid."', fld_module_id='".$moduleid."', fld_schedule_id='".$scheduleid."', fld_session_id='".$sessionid."', 
									fld_page_id='".$pageid."', fld_key='".$key."', fld_key_value='".$answer."', fld_schedule_type='".$scheduletype."', 
									fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid."'  
								WHERE fld_id='".$count."' ");
			}
			
			if($uid1 != '' and $uid1 != '0')
			{
				$count = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM itc_module_variable_track 
														WHERE fld_key='".$key."' AND fld_tester_id='".$testerid1."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
															AND fld_session_id='".$sessionid."' AND fld_page_id='".$pageid."' AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
			
				if($count == 0)
				{
					$ObjDB->NonQuery("INSERT INTO itc_module_variable_track (fld_tester_id, fld_module_id, fld_session_id, fld_page_id, fld_key, fld_key_value, fld_created_by, 
										fld_created_date, fld_schedule_id, fld_schedule_type) 
									VALUES ('".$testerid1."', '".$moduleid."', '".$sessionid."', '".$pageid."', '".$key."', '".$answer."', '".$uid1."', 
										'".$date."', '".$scheduleid."', '".$scheduletype."')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE itc_module_variable_track 
									SET fld_tester_id='".$testerid1."', fld_module_id='".$moduleid."', fld_schedule_id='".$scheduleid."', fld_session_id='".$sessionid."', 
										fld_page_id='".$pageid."', fld_key='".$key."', fld_key_value='".$answer."', fld_schedule_type='".$scheduletype."', 
										fld_updated_date='".date("Y-m-d H:i:s")."', fld_updated_by='".$uid1."' 
									WHERE fld_id='".$count."' ");
				}
			}
		}
		else {
			
			$keyvalue = $ObjDB->SelectSingleValue("SELECT fld_key_value 
													FROM itc_module_variable_track 
													WHERE fld_key='".$key."' AND fld_tester_id='".$testerid."' AND fld_module_id='".$moduleid."' AND fld_schedule_id='".$scheduleid."' 
														AND fld_schedule_type='".$scheduletype."' AND fld_delstatus='0'");
			
			echo $keyvalue;	
		}
	}
	
	if($oper=="savepagetrack" and $oper != "" ){
		$pages = json_decode($method['pages'], true);
		$sessionid = isset($method['sessionid']) ? $method['sessionid'] : '';
		$pageid = isset($method['pageid']) ? $method['pageid'] : '';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$pagename = explode("~", $pages[$pageid]);
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_module_play_track 
												WHERE fld_page_id='".$pageid."' AND fld_section_id='".$sessionid."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$uid."' 
													AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."'");
		if($count == 0){
			$ObjDB->NonQuery("INSERT INTO itc_module_play_track (fld_tester_id, fld_section_id, fld_module_id, fld_page_id, fld_page_name, fld_read_status, fld_created_by, 
								fld_created_date, fld_schedule_id, fld_schedule_type) 
							VALUES ('".$uid."', '".$sessionid."', '".$moduleid."', '".$pageid."', '".$ObjDB->EscapeStrAll($pagename[1])."', '1', '".$uid."', 
								'".$date."', '".$scheduleid."', '".$scheduletype."')");
		}
		
		if($uid1 != '')
		{
			$count1=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM itc_module_play_track 
												WHERE fld_page_id='".$pageid."' AND fld_section_id='".$sessionid."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$uid1."' 
													AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."'");
			if($count1==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_module_play_track (fld_tester_id, fld_section_id, fld_module_id, fld_page_id, fld_page_name, fld_read_status, fld_created_by, 
									fld_created_date, fld_schedule_id, fld_schedule_type) 
								VALUES ('".$uid1."', '".$sessionid."', '".$moduleid."', '".$pageid."', '".$ObjDB->EscapeStrAll($pagename[1])."', '1', '".$uid1."', 
									'".$date."', '".$scheduleid."', '".$scheduletype."')");
			}
		}
	}
	
	if($oper=="readpages" and $oper != "" ){
		$pagecount = isset($method['pagecount']) ? $method['pagecount'] : '';
		$sessionid = isset($method['sessionid']) ? $method['sessionid'] : '';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		
		$readstatus = array();
		
		for($i=0;$i<$pagecount;$i++){
			$readstatus[$i] = 0;
		}
			
		$pagesreadstatus = $ObjDB->QueryObject("SELECT GROUP_CONCAT(fld_page_id) AS pageids 
												FROM itc_module_play_track 
												WHERE fld_section_id='".$sessionid."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$uid."' AND fld_schedule_id='".$scheduleid."' 
													AND fld_schedule_type='".$scheduletype."' AND fld_read_status='1'");	
		if($pagesreadstatus->num_rows > 0){
			$rowpgst = $pagesreadstatus->fetch_assoc();
			extract($rowpgst);
			
			$tmppgid = explode(",", $pageids);
			
			for($i=0;$i<sizeof($tmppgid);$i++){
				$readstatus[$tmppgid[$i]] = 1;
			}
			
		}
		
		echo implode(",",$readstatus);				
	}
	
	if($oper=="showatten" and $oper != " " )
	{
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0'; 
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0'; 
		$sessionid = isset($method['sectionid']) ? $method['sectionid'] : ''; 
		$testerid = isset($method['testerid']) ? $method['testerid'] : ''; 
		$testerid1 = isset($method['testerid1']) ? $method['testerid1'] : ''; 
		
		$atten = '';
		$partic = '';
		$atten1 = '';
		$partic1 = '';
		
		$atten = $ObjDB->SelectSingleValue("SELECT fld_points_earned 
											FROM itc_module_points_master 
											WHERE fld_module_id='".$moduleid."'  AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
												AND fld_session_id='".$sessionid."' AND fld_type='1' AND fld_student_id='".$testerid."' AND fld_delstatus='0'");
		
		$partic = $ObjDB->SelectSingleValue("SELECT fld_points_earned 
											FROM itc_module_points_master 
											WHERE fld_module_id='".$moduleid."'  AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
												AND fld_session_id='".$sessionid."' AND fld_type='2' AND fld_student_id='".$testerid."' AND fld_delstatus='0'");
		
		if($testerid1 != '') {
			
			$atten1 = $ObjDB->SelectSingleValue("SELECT fld_points_earned 
												FROM itc_module_points_master 
												WHERE fld_module_id='".$moduleid."'  AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
													AND fld_session_id='".$sessionid."' AND fld_type='1' AND fld_student_id='".$testerid1."' AND fld_delstatus='0'");
		
			$partic1 = $ObjDB->SelectSingleValue("SELECT fld_points_earned 
												FROM itc_module_points_master 
												WHERE fld_module_id='".$moduleid."'  AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
													AND fld_session_id='".$sessionid."' AND fld_type='2' AND fld_student_id='".$testerid1."' AND fld_delstatus='0'");
		}
		
		$result = array("atten" => $atten, "partic" => $partic, "atten1" => $atten1, "partic1" => $partic1, "testerid1" => $testerid1);
		echo json_encode($result);
	}
	
	if($oper=="saveatten" and $oper != " " )
	{
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0'; 
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$moduleid = isset($method['moduleid']) ? $method['moduleid'] : '0'; 
		$sessionid = isset($method['sectionid']) ? $method['sectionid'] : ''; 
		$testerid = isset($method['testerid']) ? $method['testerid'] : ''; 
		$testerid1 = isset($method['testerid1']) ? $method['testerid1'] : ''; 
		
		$attpar = isset($method['attpar']) ? $method['attpar'] : ''; 
		$attpar=explode('~',$attpar);
		
		$attpar1 = isset($method['attpar1']) ? $method['attpar1'] : ''; 
		$attpar1=explode('~',$attpar1);
		
		$type = isset($method['type']) ? $method['type'] : ''; 
		$type=explode('~',$type);		
		for($i=0;$i<sizeof($attpar);$i++)
		{
			if($attpar[$i]!='')
			{
				$cnt = $ObjDB->SelectSingleValue("SELECT fld_id 
												FROM itc_module_points_master 
												WHERE fld_module_id='".$moduleid."'  AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."' 
													AND fld_session_id='".$sessionid."' AND fld_type='".$type[$i]."' AND fld_student_id='".$testerid."' AND fld_delstatus='0'");
				if($cnt=='')
				{
					$ObjDB->NonQuery("INSERT INTO `itc_module_points_master`(`fld_module_id`, `fld_schedule_id`, `fld_session_id`,  `fld_schedule_type`, `fld_type`, `fld_points_earned`, 
										`fld_points_possible`, `fld_student_id`, `fld_grade`) 
									VALUES ('".$moduleid."', '".$scheduleid."', '".$sessionid."', '".$scheduletype."', '".$type[$i]."', '".$attpar[$i]."', 
										'10', '".$testerid."', '1')");
				}
				else
				{
					$ObjDB->NonQuery("UPDATE `itc_module_points_master` 
									SET fld_points_earned='".$attpar[$i]."' 
									WHERE fld_id='".$cnt."'  ");
				}
			}
		}
		
		if($testerid1!='' && $testerid1!=0)
		{
			for($i=0;$i<sizeof($attpar1);$i++)
			{
				if($attpar1[$i]!='')
				{
					$cnt = $ObjDB->SelectSingleValue("SELECT fld_id 
													FROM itc_module_points_master 
													WHERE fld_module_id='".$moduleid."'  AND fld_schedule_id='".$scheduleid."' AND fld_schedule_type='".$scheduletype."'
														AND fld_session_id='".$sessionid."' AND fld_type='".$type[$i]."' AND fld_student_id='".$testerid1."' AND fld_delstatus='0'");
					if($cnt=='')
					{
						$ObjDB->NonQuery("INSERT INTO `itc_module_points_master`(`fld_module_id`, `fld_schedule_id`, `fld_session_id`,  `fld_schedule_type`, `fld_type`, 
											`fld_points_earned`, `fld_points_possible`, `fld_student_id`, `fld_grade`) 
										VALUES ('".$moduleid."', '".$scheduleid."', '".$sessionid."', '".$scheduletype."', '".$type[$i]."', 
											'".$attpar1[$i]."', '10', '".$testerid1."', '1')");
					}
					else
					{
						$ObjDB->NonQuery("UPDATE `itc_module_points_master` 
										SET fld_points_earned='".$attpar1[$i]."' 
										WHERE fld_id='".$cnt."'  ");
					}
				}
			}
		}
	}
	
	@include("footer.php");