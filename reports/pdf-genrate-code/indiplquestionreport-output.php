<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
?>
<style>
	.tdmiddle{
		border-top:1px solid #000; border-bottom:1px solid #000;
	}
</style>
<?php 
$classid=$id[1];
$studentid=$id[2];
$assignmentid=$id[3];
if($id[5]==0)
{
	$iplid=$id[4]; 
	
	$count1=$ObjDB->QueryObject("SELECT a.fld_track_id as trackid, a.fld_test_type AS ipltesttype, b.fld_lesson_id AS newiplid FROM itc_assignment_sigmath_answer_track AS a LEFT JOIN itc_assignment_sigmath_master AS b ON a.fld_track_id=b.fld_id WHERE b.fld_class_id='".$classid."' AND b.fld_schedule_id='".$assignmentid."' AND b.fld_test_type='1' AND b.fld_student_id='".$studentid."' AND b.fld_lesson_id='".$iplid."' GROUP BY a.fld_test_type");
}
else
{
	if($id[8]==5 || $id[8]==6)
		$testtype = 5;
	else if($id[8]==1 || $id[8]==2)
		$testtype = 2;
		
	$iplid=$id[4].",".$id[5].",".$id[6].",".$id[7];
	$count1=$ObjDB->QueryObject("SELECT a.fld_track_id as trackid, a.fld_test_type AS ipltesttype, b.fld_lesson_id AS newiplid FROM itc_assignment_sigmath_answer_track AS a LEFT JOIN itc_assignment_sigmath_master AS b ON a.fld_track_id=b.fld_id WHERE b.fld_class_id='".$classid."' AND b.fld_schedule_id='".$assignmentid."' AND b.fld_test_type='".$testtype."' AND b.fld_student_id='".$studentid."' AND b.fld_lesson_id IN (".$iplid.") GROUP BY b.fld_lesson_id, a.fld_test_type");
}


$alphabet = array('A','B','C','D','E','F','G','H');
$cnt = 1;
if($count1->num_rows>0)
{
	while($row=$count1->fetch_assoc())
	{
		extract($row);
		$loop = $ipltesttype;
		
		if($loop==1)
		{
			$typename = "- Diagnostic Test";
		}
		if($loop==2)
		{
			$typename = "- Mastery 1 Test";
		}
		if($loop==3)
		{
			$typename = "- Mastery 2 Test";
		}
		
		$iplname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.fld_ipl_name,' ',(SELECT fld_version FROM itc_ipl_version_track WHERE fld_ipl_id=b.fld_id AND fld_zip_type='1' AND fld_delstatus='0')) FROM itc_ipl_master AS b WHERE b.fld_id='".$newiplid."'");
				
		$anscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$trackid."' AND fld_test_type='".$loop."' AND fld_correct_answer='1'");
		$totalcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$trackid."' AND fld_test_type='".$loop."'");
		
		$qryques = $ObjDB->QueryObject("SELECT fld_question_id as questionid, fld_answer_type as answertypeid, fld_answer as stuanswer, fld_correct_answer as correctanswer FROM itc_assignment_sigmath_answer_track WHERE fld_track_id='".$trackid."' AND fld_test_type='".$loop."'");
		
		if($qryques->num_rows>0)
		{
			?>
            <table cellpadding="0" cellspacing="0">
                <tr style="font-weight:bold;"><td class="tdmiddle"><?php echo $iplname." ".$typename;?></td><td class="tdmiddle" align="right"><?php echo $anscount." out of ".$totalcount." are Correct";?></td></tr>
            </table>
            <?php
			$k=0;
			while($res=$qryques->fetch_assoc())
			{	
				extract($res);	
				?>
				<table cellpadding="5" cellspacing="0" style="width:100%">
					<tr>
						<td colspan="2" style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>">Question <?php $k++; echo $k;?>:&nbsp;<span style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><?php if($correctanswer==1){ echo "   (Correct)"; } else { echo "   (Incorrect)"; }?></span></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td>
						<td style="font-weight:bold; width:95%">
						<?php echo $ObjDB->SelectSingleValue("select fld_question from itc_question_details where fld_id='".$questionid."'");?>
					    </td>
					</tr>
				
				<?php
				/*--- Multiple Choice id=1 ---*/
				if($answertypeid == 1)
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
									
					$alphabet = array('A','B','C','D','E','F','G','H');
					$anscnt = 0;
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						$anschoices = explode("~",$choice);
						$correctans = explode("~",$correct);
					}
					
					for($i=0;$i<sizeof($anschoices);$i++){
					?>
					<tr>
						<td style="width:10%">&nbsp;</td><td style="width:90%"><?php echo $alphabet[$i].". ".strip_tags($anschoices[$i]);?></td>
					</tr>
					<?php
					} // end answer choice for
					
					$correctanswerchoice = '';
					for($i=0;$i<sizeof($correctans);$i++){
						if($correctanswerchoice == '') {
							$correctanswerchoice .= $alphabet[$correctans[$i]-1]; 
						}
						else {
							$correctanswerchoice .= ", ".$alphabet[$correctans[$i]-1]; 
						}
					}
					?>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo htmlentities($correctanswerchoice);?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer == 1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  
                                                    <?php if($stuanswer!='') {
                                                            $stuanswer = explode(",",$stuanswer);
                                                            for($j=0;$j<sizeof($stuanswer);$j++)
                                                            {
                                                                echo htmlentities($alphabet[$stuanswer[$j]-1])."   ".strip_tags($anschoices[$stuanswer[$j]-1]); 
                                                                echo "<br/>";
                                                            } 
                                                        }
                                                        else echo "No Answer";?>
                                                </td>
					</tr>
                    <?php
				}
				
				/*--- Single Answer id=2 ---*/
				if($answertypeid == 2)
				{
					$correctanswerchoice = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1' AND fld_attr_id = '1'");
					?>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo htmlentities($correctanswerchoice); ?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b> <?php if($stuanswer!='') echo htmlentities($stuanswer); else echo "No Answer";?></td>
					</tr>
					<?php	
				}
				
				/*--- Match the following id=3 ---*/ 
				if($answertypeid == 3 )
				{
					$qrypresuf = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
															GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
													 FROM itc_question_answer_mapping 
													 WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
					$prefixarray=array();
					$suffixarray=array();
		
					while($row = $qrypresuf->fetch_assoc())
					{
						extract($row);
						$prefixarray = explode("~",$prefix);
						$suffixarray = explode("~",$suffix);
					}
					
					?>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>
					<?php
					for($i=0;$i<sizeof($prefixarray);$i++){ 
					?>
						<input type="text" id="mulbox<?php echo $i;?>" value="<?php echo $prefixarray[$i]; ?>" style="height:30px;" readonly/>&nbsp; 
						<input type="text" id="ans<?php echo $i;?>" style="width:50px;height:30px;" value="<?php echo $suffixarray[$i]; ?>" readonly/><br /><br />
						<?php 	
					} ?></td>
					</tr>
					
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b> <?php if($stuanswer!=''){ $stuanswer=explode("~",$stuanswer); for($i=0;$i<sizeof($prefixarray);$i++) { echo htmlentities($stuanswer[$i]); if($i!=sizeof($prefixarray)-1) { echo ","; } } } else echo "No Answer";?></td>
					</tr>
					<?php
				}
				
				/*--- Custom answer type id=4 ---*/
				if($answertypeid == 4)
				{
					$answer = $ObjDB->SelectSingleValue("SELECT fld_answer 
														FROM itc_question_answer_mapping 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='6' AND fld_flag='1'");			
					$answer = explode(',',$answer);	
					$values = $ObjDB->SelectSingleValue("SELECT fld_answer 
														FROM itc_question_answer_mapping 
														WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='7' AND fld_flag='1'");
					?>		
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo htmlentities($values); ?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php echo htmlentities(str_replace('~',',',$stuanswer)); ?></td>
					</tr>
					<?php
				}
				
				/*--- Answer choice id=5 ---*/
				if($answertypeid == 5)
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice',
														GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
					$answerarray = array();
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray = explode("~",$choice);
					}
					
					?>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php if($correct == '1'){ echo htmlentities($answerarray[0]); }else { echo htmlentities($answerarray[1]);   }?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php if($stuanswer!='') echo htmlentities($answerarray[$stuanswer-1]); else echo "No Answer";?></td>
					</tr>
					<?php
				}
				
				/*--- Menu & Extrems id=6 ---*/
				if($answertypeid == 6)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_answer AS answer 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
					$answerarray=array();
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray[$i]=$answer;
						$i++;
					}
					?>
					<div class="row rowspacer">
						<div class="six columns">
							Correct: <br />
							Means: <?php echo $answerarray[0].", ".$answerarray[1]; ?><br />
							Extremes: <?php echo $answerarray[2].", ".$answerarray[3]; ?>
						</div>
						<div class="six columns"></div>
					</div>
					<tr>
						<td style="width:5%">&nbsp;</td><td><b>Correct Answer :</b></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td>&nbsp;<b>Means</b><?php echo $answerarray[0].", ".$answerarray[1]; ?><br /></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td>&nbsp;<b>Extremes</b><?php echo $answerarray[2].", ".$answerarray[3]; ?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>">&nbsp;<b>Means</b><?php if($stuanswer!='') echo htmlentities($stuanswer[0])." , ".htmlentities($stuanswer[1]); else echo "No Answer";?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>">&nbsp;<b>Extremes</b><?php if($stuanswer!='') echo htmlentities($stuanswer[2])." , ".htmlentities($stuanswer[3]); else echo "No Answer";?></td>
					</tr>
					<?php	
				}
				
				/*--- Single Range id=7 ---*/
				if($answertypeid == 7 )
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
														GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
														GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
					$answerarray=array();
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray=explode("~",$choice);
						$i++;
					}
					?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><b>Correct Answer :</b><?php echo htmlentities($answerarray[0])." To ".htmlentities($answerarray[1]);?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b><?php if($stuanswer!='') echo htmlentities($stuanswer); else echo "No Answer";?></td>
					</tr>
					<?php	
				}
				
				/*--- Mulitple choice image id=8 ---*/
				if($answertypeid==8)
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
														GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
						
					$alphabet = array('A','B','C','D','E','F','G','H');
					$anscnt = 0;
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						$anschoices = explode("~",$choice);
						$correctans = explode("~",$correct);
					}
					
					for($i=0;$i<sizeof($anschoices);$i++){
						$imgid = $i + 1;
						if($anschoices[$i]!='' && $anschoices[$i]!='no-image.png') {
						?>
							<tr>
								<td colspan="2"><b><?php echo $alphabet[$i]; ?> :</b>
									<img name="txtimageans<?php echo $imgid; ?>" id="txtimageans<?php echo $imgid; ?>" 
                                    src="<?php echo $domainame;?>thumb.php?src=<?php echo __CNTANSIMGPATH__.$anschoices[$i]; ?>&w=200&h=100&zc=2" />	
								</td>
							</tr>
							<?php
						}
					}
						
					$correctanswerchoice = '';
					for($i=0;$i<sizeof($correctans);$i++){
						if($correctanswerchoice == '') {
							$correctanswerchoice .= $alphabet[$correctans[$i]-1]; 
						}
						else {
							$correctanswerchoice .= ", ".$alphabet[$correctans[$i]-1]; 
						}
					}
					?>
					
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo $correctanswerchoice; ?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  
						<?php if($stuanswer!='') {
                                                        $stuanswer = explode(",",$stuanswer);
                                                        for($j=0;$j<sizeof($stuanswer);$j++)
                                                        {
                                                            echo htmlentities($alphabet[$stuanswer[$j]-1]); 
                                                            echo "<br/>";
                                                        } 
                                                    }  else echo "No Answer";?></td>
					</tr>
					<?php 
				}
				
				/*--- Single Multiple id=9 ---*/	
				if($answertypeid == 9)
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
														GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
														GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
					while($row = $qry->fetch_assoc())
					{
						extract($row);
					}
					?>	
					
					<tr>
						<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo $choice; ?></td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php if($stuanswer!='') echo $stuanswer; else echo "No Answer";?></td>
					</tr>
					<?php
				}
				
				/*--- Drag & Drop id=10 ---*/	
				if($answertypeid == 10 or $answertypeid == 12 or $answertypeid == 13 or $answertypeid == 14)
				{
					?>
					<tr>
						<td style="width:5%">Drag and Drop Questions cannot be show here</td>
					</tr>
					<?php 
				}
				
				?>
				</table>
				<?php
			}
			$cnt++;
			if($cnt<=$count1->num_rows && $qryques->num_rows>0) { ?>
                <div style="page-break-before: always;">&nbsp;</div> <?php 
            }
		}
	}
}
else
{
	if($id[5]==0)
	{
		?>
		<div style="color:#F00">The student has no test scores associated with this IPL Unit.</div>
		<?php
	}
	else if($id[8]==1 || $id[8]==2)
	{
		?>
		<div style="color:#F00">The student has no test scores associated with this Math Module.</div>
		<?php
	}
	else
	{
		?>
		<div style="color:#F00">The student has no test scores associated with this Individual Math Module.</div>
		<?php
	}
}