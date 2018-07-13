<?php 
error_reporting(0);
@include("table.class.php");
@include("comm_func.php");

$method=$_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$answertypeid='';
?>
<link href='../../css/imports.css' rel='stylesheet' type="text/css" />
<link href='../../css/question.css' rel='stylesheet' type="text/css" />

<?php
	$questionids=  array() ;
	$qry1 = $ObjDB->QueryObject("SELECT fld_question_id AS quesid FROM itc_test_questionassign 
	                            WHERE fld_test_id='".$id."' AND fld_delstatus = '0'");
	
	if($qry1->num_rows>0){
		while($rowquestionid = $qry1->fetch_assoc())
		{
                        extract($rowquestionid);
			$questionids[]=$quesid;
			
                }
        }
        shuffle($questionids);
        for($k=0;$k<sizeof($questionids);$k++)
        {
            $m=$k+1;
            $questionid=$questionids[$k];
			$qryquesdetails = $ObjDB->QueryObject("SELECT fld_question as question, fld_answer_type as answertypeid 
			                                      FROM itc_question_details WHERE fld_id='".$questionid."'");
			
			
	
	if($qryquesdetails->num_rows>0){
		$rowquesdetails = $qryquesdetails->fetch_assoc();
		extract($rowquesdetails);
	}
	?>
     <table cellpadding="5" cellspacing="0" style="width:100%;height:100px;">
     				<tr>
						<td colspan="2">Question <?php echo $m;?>:&nbsp;</td>
					</tr>
					<tr>
						<td style="width:5%">&nbsp;</td><td style="font-weight:bold; width:95%"><?php echo $ObjDB->SelectSingleValue("select fld_question from itc_question_details where fld_id='".$questionid."'");?></td>
					</tr>
				
				<?php
				/*--- Multiple Choice id=1 ---*/
				if($answertypeid == 1)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
					                           WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
											   AND fld_attr_id='1' AND fld_flag='1'");
					$answerarray=array();
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray[$i]=$answer;
						$i++;
					}
				?>
					<?php if(isset($answerarray[0]) and $answerarray[0]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "A.	  ".strip_tags($answerarray[0]);?></td>
					</tr>
					<?php } if(isset($answerarray[0]) and $answerarray[1]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "B.   ".strip_tags($answerarray[1]);?></td>
					</tr>
					<?php } if(isset($answerarray[0]) and $answerarray[2]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "C.   ".strip_tags($answerarray[2]);?></td>
					</tr>
					<?php } if(isset($answerarray[0]) and $answerarray[3]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "D.   ".strip_tags($answerarray[3]);?></td>
					</tr>
					<?php } if(isset($answerarray[0]) and $answerarray[4]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "E.   ".strip_tags($answerarray[4]);?></td>
					</tr>
					<?php } if( isset($answerarray[0]) and $answerarray[5]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "F.   ".strip_tags($answerarray[5]);?></td>
					</tr>
					<?php } if(isset($answerarray[0]) and $answerarray[6]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "G.   ".strip_tags($answerarray[6]);?></td>
					</tr>
					<?php } if(isset($answerarray[0]) and $answerarray[7]!='') { ?>
					<tr>
						<td style="width:5%">&nbsp;</td><td><?php echo "H.   ".strip_tags($answerarray[7]);?></td>
					</tr>
					<?php }
				}
				
				/*--- Single Answer id=2 ---*/
				if($answertypeid == 2) 
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
					                          GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
											  GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
											  FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
											  AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
					
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$i++;
					}
					?>
					<tr>
						<td style="width:5%;">&nbsp;</td>
						<td style="width:7%;"><?php echo $prefix; ?></td>
						<td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
						<td style="width:20%"><?php echo $suffix; ?></td>
					</tr>
				<?php	
				}
				
				/*--- Match the following id=3 ---*/ 
				if($answertypeid == 3 ) 
				{				
					$qrypresuf = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
					                                GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
													FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													AND fld_answer<>'' AND fld_flag='1'");
					$prefixarray=array();
					$suffixarray=array();
					while($row = $qrypresuf->fetch_assoc())
					{
						extract($row);
						$prefixarray = explode("~",$prefix);
						$suffixarray = explode("~",$suffix);
					}			
					for($i=0;$i<sizeof($prefixarray);$i++){ 
					?>
						<tr>
							<td style="width:5%;">&nbsp;</td>
							<td style="width:5%;"><?php echo $prefixarray[$i]; ?></td>
							<td style="width:5%"><?php echo $suffixarray[$i]; ?></td>
						</tr>
					<?php 	
					}
				}		
				
				/*--- Custom answer type id=4 ---*/
				if($answertypeid == 4) // Custom Answer Type
				{
					$answer = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
					                                    AND fld_ans_type='".$answertypeid."' AND fld_attr_id='6' AND fld_flag='1'");			
					$answer = explode(',',$answer);	
					$values = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
					                                   AND fld_ans_type='".$answertypeid."' AND fld_attr_id='7' AND fld_flag='1'");
					$values = explode(',',$values);			
					 $j=0;
					 $anspattern = '';
					 for($i=0;$i<sizeof($answer);$i++){
						?>
						<tr>
						<?php
						if(isset($answer[$i]) and $answer[$i] == 5){
							?>
								<td style="width:5%;">&nbsp;</td>
								<td style="width:20%;"><?php echo '<div class="outer-label"><span id="lab_'.$values[$j].'">'.$values[$j].'</span></div>'; ?></td>
							<?php
							
						}
						else {
							?>
							<td style="width:5%;">&nbsp;</td>
							<td style="width:5%;"><?php echo $ObjDB->SelectSingleValue("SELECT fld_html_code 
																						FROM itc_question_answer_pattern_master 
																						WHERE fld_id='".$answer[$i]."'"); ?></td>
							<?php
													
						}
						?>
						</tr>
						<?php
						
						if(isset($answer[$i]) and ($answer[$i] == 5 or $answer[$i]==4 or $answer[$i]==20 or $answer[$i]==21 or $answer[$i]==22 or $answer[$i]==23 or $answer[$i]==24)){
							if($anspattern=='')
								$anspattern = $values[$j];
							else
								$anspattern = $anspattern.",".$values[$j];
							$j++;	
						}
						else if($answer[$i]==17){
							if($anspattern=='')
								$anspattern = $values[$j];
							else
								$anspattern = $anspattern.",".$values[$j];
							$j = $j + 2;
						}
						else if($answer[$i]==18){
							if($anspattern=='')
								$anspattern = $values[$j];
							else
								$anspattern = $anspattern.",".$values[$j];
							$j = $j + 3;
						}
					 }
				}  // Custom Answer Type if ends*/
			
				if($answertypeid == 4)
				{
					$answer = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_question_answer_mapping 
					                                   WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													    AND fld_attr_id='6' AND fld_flag='1'");			
					$answer = explode(',',$answer);	
					$values = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_question_answer_mapping 
					                                   WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													   AND fld_attr_id='7' AND fld_flag='1'");
								
					?>			
					<?php
					$j=0;
					for($i=0;$i<sizeof($answer);$i++){
						echo $ObjDB->SelectSingleValue("SELECT fld_html_code FROM itc_question_answer_pattern_master 
						                                WHERE fld_id='".$answer[$i]."'");
					}
				}
				
				/*--- Answer choice id=5 ---*/
				if($answertypeid == 5)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
					                            WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
												AND fld_flag='1' ORDER BY fld_boxid ASC ");
					$answerarray=array();
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray[$i]=$answer;
						$i++;
					}
					?>	
						<tr>
							<td style="width:5%;">&nbsp;</td>
							<td style="width:10%;"><?php echo $answerarray[0]; ?></td>
                            <td style="width:10%;"><?php echo $answerarray[1]; ?></td>
						</tr>
					<?php
				}
				
				/*--- Menu & Extrems id=6 ---*/
				if($answertypeid == 6)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
					                           WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
											   AND fld_flag='1'");
					$answerarray=array();
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray[$i]=$answer;
						$i++;
					}				
					?>
                    <tr>
                        <td style="width:10%;">&nbsp;</td>
                        <td style="width:10%;">Means</td>
                        <td style="width:15%;">&nbsp;</td>
                        <td style="width:15%;">Extremes</td>
                    </tr>
                    <tr>
                        <td style="width:5%;">&nbsp;</td>
                        <td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
                        <td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
                        <td style="width:5%;">&nbsp;</td>
                        <td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
                        <td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
                    </tr>
				<?php
				} 
			
				/*--- Single Range id=7 ---*/
				if($answertypeid == 7 ) // Single Range
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
					                          GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
											  GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
											  FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
											  AND fld_answer<>'' AND fld_flag='1'");
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
						<td style="width:5%;">&nbsp;</td>
						<td style="width:10%;"><?php echo $prefix; ?></td>
						<td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
						<td style="width:10%"><?php echo $suffix; ?></td>
					</tr>
					
					<?php	
				}
				
				/*--- Mulitple choice image id=8 ---*/
				if($answertypeid==8)
				{
					$qry = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
					                          WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='1' AND fld_flag='1'");
					$answerarray=array();
					$i=0;
					while($row=$qry->fetch_assoc())
					{
						extract($row);
						$answerarray[$i]=$answer;
						$i++;
					}
					$width1=10;
					$height1=10;
				
					$image1 = __CNTANSIMGPATH__.$answerarray[0];
					$image2 = __CNTANSIMGPATH__.$answerarray[1];
					$image3 = __CNTANSIMGPATH__.$answerarray[2];
					$image4 = __CNTANSIMGPATH__.$answerarray[3];
					$image5 = __CNTANSIMGPATH__.$answerarray[4];
					$image6 = __CNTANSIMGPATH__.$answerarray[5];
					$image7 = __CNTANSIMGPATH__.$answerarray[6];
					$image8 = __CNTANSIMGPATH__.$answerarray[7];
				
					if($answerarray[0]!='' && $answerarray[0]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>A :</b><img name="txtimageans1" id="txtimageans1" src="<?php echo $image1; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[1]!='' && $answerarray[1]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>B :</b><img name="txtimageans2" id="txtimageans2" src="<?php echo $image2; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[2]!='' && $answerarray[2]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>C :</b><img name="txtimageans3" id="txtimageans3" src="<?php echo $image3; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[3]!='' && $answerarray[3]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>D :</b><img name="txtimageans4" id="txtimageans4" src="<?php echo $image4; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[4]!='' && $answerarray[4]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>E :</b><img name="txtimageans5" id="txtimageans5" src="<?php echo $image5; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[5]!='' && $answerarray[5]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>F :</b><img name="txtimageans6" id="txtimageans6" src="<?php echo $image6; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[6]!='' && $answerarray[6]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>G :</b><img name="txtimageans7" id="txtimageans7" src="<?php echo $image7; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					}
					if($answerarray[7]!='' && $answerarray[7]!='no-image.png')
					{
						?>
						<tr>
							<td colspan="2"><b>H :</b><img name="txtimageans8" id="txtimageans8" src="<?php echo $image8; ?>" width="<?php echo $width1;?>" height="<?php echo $height1;?>"/></td>
						</tr>
						<?php
					} 
					?>
					<?php 
				}
				/*--- Single Multiple id=9 ---*/
				if($answertypeid == 9) // Single Multiple
				{
					$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
					while($row = $qry->fetch_assoc())
					{
						extract($row);
					}
					?>
					<tr>
						<td style="width:5%;">&nbsp;</td>
						<td style="width:10%;"><?php echo $prefix; ?></td>
						<td style="width:10%"><div style="width:100px; height:25px; border:1px solid #000;"></div></td>
						<td style="width:10%"><?php echo $suffix; ?></td>
					</tr>
				<?php    
				}	
				?>
				</table>
    <?php }


	@include("footer.php");

