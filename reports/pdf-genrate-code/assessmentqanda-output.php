<?php 
/*
created by: vijayalakshmi PHP Programmer
created on: 24/12/2014
pdf format for assessment question and answer to all types

*/
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

$studentid=$id[1];
$assignmentid=$id[0];

$classid = $ObjDB->SelectSingleValue("SELECT fld_class_id FROM itc_test_student_mapping WHERE fld_student_id='".$studentid."' AND fld_test_id='".$assignmentid."' AND fld_flag='1'"); 

	$alphabet = array('A','B','C','D','E','F','G','H');

$qryques = $ObjDB->QueryObject("SELECT fld_question_id AS questionid, fld_answer_type_id AS answertypeid, fld_answer AS stuanswer, fld_correct_answer AS correctanswer 
								FROM itc_test_student_answer_track 
								WHERE fld_test_id='".$assignmentid."' AND fld_student_id='".$studentid."' AND fld_delstatus='0'");
		
if($qryques->num_rows>0)
{
	$count = 1;
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
				<td style="width:5%">&nbsp;</td><td style="font-weight:bold; width:95%"><?php echo $ObjDB->SelectSingleValue("select fld_question from itc_question_details where fld_id='".$questionid."'");?></td>
			</tr>
		
		<?php
		/*--- Multiple Choice id=1 ---*/
		if($answertypeid == 1) // Multiple Choice 
		{
			$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
											GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");									
			
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
				<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo $correctanswerchoice;?></td>
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
				<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo $correctanswerchoice; ?></td>
			</tr>
			<tr>
				<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php if($stuanswer!='') echo htmlentities($stuanswer); else echo "No Answer";?></td>
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
                <td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php echo str_replace('~',',',$stuanswer); ?></td>
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
				<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php if($correct == '1'){ echo $answerarray[0]; }else { echo $answerarray[1];  }?></td>
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
				<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b><?php if($stuanswer!='') echo htmlentities($stuanswer); else echo "No Answer"; ?></td>
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
                
			$anscnt = 0;
			while($row = $qry->fetch_assoc())
			{
				extract($row);
				$anschoices = explode("~",$choice);
				$correctans = explode("~",$correct);
			}
			
			for($i=0;$i<sizeof($anschoices);$i++){
				$imgid = $i + 1;
				if($anschoices[$i]!='' && $anschoices[$i]!='no-image.png') {?>
					<tr>
                        <td colspan="2"><b><?php echo $alphabet[$i]; ?> :</b>
                        	<img name="txtimageans<?php echo $imgid; ?>" id="txtimageans<?php echo $imgid; ?>" 
                            src="<?php echo $domainame;?>thumb.php?src=<?php echo __CNTANSIMGPATH__.$anschoices[$i]; ?>&w=200&h=100&zc=3"  />	
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
				<td style="width:5%">&nbsp;</td><td style="width:95%"><b>Correct Answer :</b>  <?php echo htmlentities($choice); ?></td>
			</tr>
			<tr>
				<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php if($stuanswer!='') echo htmlentities($stuanswer); else echo "No Answer";?></td>
			</tr>
			<?php
		}
                 /*--- Single Answer id=2 ---*/
		if($answertypeid == 15)
		{
			$correctanswerchoice = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer 
										FROM itc_test_student_answer_track 
										WHERE fld_question_id='".$questionid."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='".$answertypeid."' AND  fld_delstatus='0'");
			?>
			<tr>
				<td style="width:5%">&nbsp;</td><td style="color:<?php if($correctanswer==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answered :</b>  <?php if($correctanswerchoice!='') echo htmlentities($correctanswerchoice); else echo "No Answer";?></td>
			</tr>
			<?php	
		}
		
		/*--- Drag & Drop id=10 ---*/	
		if($answertypeid == 10 or $answertypeid == 12 or $answertypeid == 13 or $answertypeid == 14)
		{
			?>
            
            <tr>
            <td colspan="2">Drag and Drop Questions cannot be show here</td>
				
			</tr>
			<?php 
		}
		
		?>
		</table>
		<?php
		
		$count++;
	}
	if($count<$qryques->num_rows) { ?>
		<div style="page-break-before: always;">&nbsp;</div> <?php 
	}
}
else
{
	?>
    <div style="color:#F00">The student has no scores associated with this Test.</div>
    <?php
}


