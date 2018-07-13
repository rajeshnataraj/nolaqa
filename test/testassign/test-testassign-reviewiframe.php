<?php 
	@include("sessioncheck.php");
	$method=$_REQUEST;
	$questionid=isset($method['id']) ? $method['id'] : '0';
?>
<script language="javascript" type="text/javascript" src="../../jquery-ui/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript" src="../../jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/pdw/editor_plugin_src.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/asciimath/js/ASCIIMathMLwFallbackSmall.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/asciisvg/js/ASCIIsvg.js"></script>    
<script type="text/javascript">
document.domain = 'pitsco.com';
	var AScgiloc = '../../tiny_mce/php/svgimg.php';
	var AMTcgiloc = "../../cgi-bin/mathtex.cgi";
</script>
<link href='../../css/imports.css' rel='stylesheet' type="text/css" />
<link href='../../css/question.css' rel='stylesheet' type="text/css" />
<style type="text/css">
body {
	background-color:transparent;	
	height:100%;
	width:100%;
	overflow:hidden;
}
p {
	margin: 0;
	float: left;		
}
</style>
<?php 
	$qryquesdetails = $ObjDB->QueryObject("SELECT fld_question as question, fld_answer_type as answertypeid 
	                                       FROM itc_question_details WHERE fld_id='".$questionid."'");
	
	if($qryquesdetails->num_rows>0){
		$rowquesdetails = $qryquesdetails->fetch_assoc();
		extract($rowquesdetails);
	}
	?>
    <div class='row rowspacer'>
        <div class='twelve columns'>
            <?php echo $question; ?>
        </div>
    </div>    
    
    <div class='row rowspacer'>
        <div class='twelve columns'>
			<?php
            if($answertypeid == 1) // Multiple Choice 
            {
                $qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
				                           GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										   FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
										   AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
                
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
                <div class="row rowspacer">
                    <div class="one columns" style="width:15px;"><?php echo $alphabet[$i]; ?>.</div>
                    <div class="eleven columns" style="margin-left:1%;">
                        <?php echo $anschoices[$i];?>
                    </div>
                </div>
                <?php
                } // end answer choice for
                
                $correctanswer = '';
				
                for($i=0;$i<sizeof($correctans);$i++){
                    if($correctanswer == '') {
                        $correctanswer .= $alphabet[$correctans[$i]-1]; 
                    }
                    else {
                        $correctanswer .= ", ".$alphabet[$correctans[$i]-1]; 
                    }
                }
            	?>
                <div class="row rowspacer">
                   <div class="eleven columns">
                        Correct Answer: <strong><?php echo $correctanswer;?></strong>
                    </div>
                </div>
            <?php  
            } // Multiple Choice  if ends
			
			if($answertypeid == 2) // Single Answer 
			{
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
				                          GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
										  GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
										  FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
										  AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
				$i=0;
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					$i++;
				}
				?>
                <div class="row rowspacer">
                	<div class="eight columns">
                        <div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix; ?></span></div>
                        <div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="" readonly /></div>
                        <div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix; ?></span></div>
                	</div>
              	</div>
                <div class="row rowspacer">
                	<div class="eight columns">
                    	Correct: &nbsp; <?php echo $choice; ?>
               		</div>
                </div>
			<?php	
			} // Single Answer if ends
			
			if($answertypeid == 3 ) // Match the following
			{				
				$qrypresuf = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
				                                 GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
												 FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
												 AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
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
                	<div class="row">
	                	<div class='eight columns'>	
							<div class="outer-input-sym"><span class="ques-symbol" style="font-size: 20px;margin-right: 20px;"><?php echo $prefixarray[$i]; ?></span></div>
                    		<div class="outer-input-txt"><input type="text" class="ques-input qit-medium" id="ans<?php echo $i;?>" value="<?php echo $suffixarray[$i]; ?>" placeholder="Answer" readonly /></div>
                        </div>
					</div>
				<?php 	
				}
				echo '<div class="rowspacer"></div>';
			}	// Match the following if ends
			
			if($answertypeid == 4) // Custom Answer Type
			{
				$answer = $ObjDB->SelectSingleValue("SELECT fld_answer 
													FROM itc_question_answer_mapping 
													WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='6' AND fld_flag='1'");			
				$answer = explode(',',$answer);	
				$values = $ObjDB->SelectSingleValue("SELECT fld_answer 
													FROM itc_question_answer_mapping 
													WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='7' AND fld_flag='1'");
				$values = explode(',',$values);			
			?>
            	<div class="row rowspacer">
                	<div class="twelve columns">
					<?php     
                     $j=0;
                     $anspattern = '';
					 $tmparray = array();
					 $tmpans = array();
                     
					 for($i=0;$i<sizeof($answer);$i++){
						if($answer[$i] == 5){
                            echo '<div class="outer-label"><span id="lab_'.$values[$j].'">'.$values[$j].'</span></div>';
							$tmparray[]=$values[$j];
                        }
                        else {
                            echo $ObjDB->SelectSingleValue("SELECT fld_html_code 
															FROM itc_question_answer_pattern_master 
															WHERE fld_id='".$answer[$i]."'");								
						}                       
                        if($answer[$i] == 5 or $answer[$i]==4 or $answer[$i]==20 or $answer[$i]==21 or $answer[$i]==22 or $answer[$i]==23 or $answer[$i]==24){ 						
							if($answer[$i]!=5)
								$tmpans[] = $values[$j];
                            $j++;	
                        }
                        else if($answer[$i]==17){
							$tmpans[] = $values[$j];
							$tmpans[] = $values[$j+1];
							$j = $j + 2;
                        }
                        else if($answer[$i]==18){
							$tmpans[] = $values[$j];
							$tmpans[] = $values[$j+1];
							$tmpans[] = $values[$j+2];
							$j = $j + 3;
                        }
                     }
					 $result = array_values(array_diff($values, $tmparray));					 
					?>
					<script>					
						var i=0;
						var answer = <?php echo json_encode($tmpans);?>;										
						$("input[id='txt']").each(function(){
						   $(this).val(answer[i]);
						   i++;					   
						});
                                                
                                                var oldlen=4;
						$("input[type='text']").each(function(){
							var newlen = ($(this).val().length) + 1;
							if(oldlen < newlen){
								$('.dfrac-small').css({'width':newlen+'rem'});	
								oldlen = newlen;
							}
						});
					 </script>
                	</div>
                </div>
                <?php
			} // Custom Answer Type if ends
			
			if($answertypeid == 5) // Answer Choice
			{
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
				                           GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										   FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
										   AND fld_answer<>'' AND fld_flag='1'");
				$answerarray = array();
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					$answerarray = explode("~",$choice);
				}
				?>
                <div class="row rowspacer">
                	<table width="15%" cellpadding="0" cellspacing="0">
                        <tr height="70">
                            <td width="20%">
                                <input type="radio" disabled="disabled" id="rightans" name="yesorno" value="<?php echo $correct; ?>"  <?php if($correct == '1'){ echo "checked='checked'";}?> />
                           </td>
                           <td>
                                <label style="font-size:1.5em" for="rightans"><?php echo $answerarray[0]; ?></label>
                           </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <input type="radio" disabled="disabled" id="wrongans" name="yesorno" value="<?php echo $correct; ?>" <?php if($correct != '1'){ echo "checked='checked'";}?> />
                            </td>
                            <td>
                                <label style="font-size:1.5em" for="wrongans"><?php echo $answerarray[1]; ?></label>
                           </td>
                        </tr>
                    </table>
                </div>
			<?php
			} // Answer Choice if ends
			
			if($answertypeid == 6) // Means and Extremes  
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
                <div class="twelve columns">
                    <div class="means" align="center" style="width:50%; float:left" >
                        <b>Means</b><br /><br />
                        <input type="text" name="mean1" id="mean1" value="" class="mix-input" readonly/>&nbsp;
                        <input type="text" name="mean2" id="mean2" value=""class="mix-input"  readonly="readonly" />
                    </div>
                    <div class="means" align="center" style="width:50%;float:left">
                        <b>Extremes</b> <br /><br />
                        <input name="ext1" type="text" class="mix-input" id="ext1" value=""  readonly="readonly" />&nbsp;
                        <input type="text" name="ext2" id="ext2" value="" class="mix-input" readonly />
                    </div>
                </div>
                
                <div class="row rowspacer">
                	<div class="six columns">
                    	Correct: <br /><br />
                        Means: <?php echo $answerarray[0].", ".$answerarray[1]; ?><br />
                        Extremes: <?php echo $answerarray[2].", ".$answerarray[3]; ?>
                    </div>
                    <div class="six columns"></div>
                </div>
                
			<?php
			} // Means and Extremes if ends
			
			if($answertypeid == 7 ) // Single Range
			{
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
				                          GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
										  GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
										  FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
										  AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
				$answerarray=array();
				$i=0;
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					$answerarray=explode("~",$choice);
					$i++;
				}
				?>
                <div class="row rowspacer">
                	<div class="eight columns">
                        <div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix;?></span></div>
                        <div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="" readonly /></div>
                        <div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix;?></span></div>
                	</div>
              	</div>
                <div class="row rowspacer">
                	<div class="eight columns">
                    	Correct: &nbsp;&nbsp;<?php echo $answerarray[0]; ?>&nbsp;to&nbsp; <?php echo $answerarray[1]; ?>
               		</div>
                </div>
				<?php	
			}	// Single Range if ends
			
			if($answertypeid==8) // Multiple Image 
			{
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
				                          GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										   FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
										   AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
                
                $alphabet = array('A','B','C','D','E','F','G','H');
                $anscnt = 0;
                while($row = $qry->fetch_assoc())
                {
                    extract($row);
                    $anschoices = explode("~",$choice);
                    $correctans = explode("~",$correct);
                }
                
				echo '<div class="row rowspacer">';
				
                for($i=0;$i<sizeof($anschoices);$i++){
					$imgid = $i + 1;
					if($anschoices[$i]!='' && $anschoices[$i]!='no-image.png')
					{
						?>
						<div class="six columns" style="margin-left:1%;<?php if($i>1){ echo 'margin-top:30px;'; } ?>">
							<div style="width:15px;float: left;"><?php echo $alphabet[$i]; ?>.</div>
							<div style="width:95%;float: left;margin-left:1%;">
                                                            <?php //Get image width
                                                                list($width,$height) = getimagesize( __CNTANSIMGPATH__.$anschoices[$i]);							
                                                            ?>
                                                            <img name="txtimageans<?php echo $imgid; ?>" id="txtimageans<?php echo $imgid; ?>" src="../../thumb.php?src=<?php echo __CNTANSIMGPATH__.$anschoices[$i]; if($width > 400){?>&w=400&h=400&zc=2<?php }else{ echo "&w=".$width."&h=".$height."&zc=2"; } ?>" />						
							</div>
						</div>
						<?php
					}
                } // end answer choice for
				
                echo '</div>';
				                
                $correctanswer = '';
                for($i=0;$i<sizeof($correctans);$i++){
                    if($correctanswer == '') {
                        $correctanswer .= $alphabet[$correctans[$i]-1]; 
                    }
                    else {
                        $correctanswer .= ", ".$alphabet[$correctans[$i]-1]; 
                    }
                }
            ?>
                <div class="row rowspacer">
                   <div class="eleven columns">
                        Correct Answer: <strong><?php echo $correctanswer;?></strong>
                    </div>
                </div>
            <?php 
			} // Multiple Image if ends
			
			if($answertypeid == 9) // Single Multiple
			{
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
				                           GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
										   GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
										   FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
										   AND fld_answer<>'' AND fld_flag='1'");
				while($row = $qry->fetch_assoc())
				{
					extract($row);
				}
				?>
                <div class="row rowspacer">
                	<div class="eight columns">
                        <div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix; ?></span></div>
                        <div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="" readonly /></div>
                        <div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix; ?></span></div>
                	</div>
              	</div>
                <div class="row rowspacer">
                	<div class="eight columns">
                    	Correct: &nbsp; 
						<?php echo $choice; ?>
               		</div>
                </div>
            <?php    
			}	// Single Multiple if ends
			
			if($answertypeid == 10)
			{
				$dropqus=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='10' AND fld_flag='1'");
				$dropans=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='2' AND fld_flag='1'");
				
				$i=0;$j=0;
				while($qus=$dropqus->fetch_assoc())
				{
					extract($qus);
					$questionarray[$i]=$answer;
					$i++;
				}
				while($ans=$dropans->fetch_assoc())
				{
					extract($ans);
					$answerarray[$j]=$answer;
					$boxarray[$j]=$boxid;
					$j++;
				}
				?>
				<div class="eleven columns">   
                	<div class='row'>                
					<?php for ($i=0;$i<sizeof($questionarray);$i++){ ?>                        
							<span class="ques-symbol"><?php echo $questionarray[$i]; ?></span>                           
					<?php } ?> 
                    </div>
				</div>
                <div class="eleven columns"> 
                	<?php for($i=0;$i<sizeof($answerarray);$i++){ ?>
                		<input type="text" class="ques-input" id="ans<?php echo $i+1;?>" style="width:10%; margin:5px;" value="<?php echo $answerarray[$i]; ?>" placeholder="Answer" readonly />&nbsp;                        
                	<?php } ?> 
                </div>
                <?php
			} // Drag & Drop if ends
			
			if($answertypeid == 11) // Pull down
			{				            
				$pullqus=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                             WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='1' AND fld_flag='1'");
				$pullans=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                              WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='2' AND fld_flag='1'");
				$i=0;$j=0;
				while($qus=$pullqus->fetch_assoc())
				{
					extract($qus);
					$questionarray[$i]=$answer;
					$i++;
				}
				while($ans=$pullans->fetch_assoc())
				{
					extract($ans);
					$answerarray[$j]=$answer;
					$boxarray[$j]=$boxid;
					$j++;
				}?>
				<div class="eleven columns">                   
					<?php for ($i=0;$i<sizeof($questionarray);$i++){ ?>                        
							<div class='row rowspacer'>
								<div class="outer-input-sym" style="padding:1px; float:left">
									<span class="ques-symbol" style="font-size:16px;"><?php echo $questionarray[$i]; ?></span>
								</div>                       
								<input class="ques-input " style="width:25%" type="text" id="txtsingleanswer" name="txtsingleanswer" value="<?php echo $answerarray[$i]; ?>" readonly />
							</div>
					<?php } ?> 
				</div>
            	<?php 
			} 
			
			if($answertypeid == 12) // Drag & Drop - Type 2
			{
				$ansopt = $ObjDB->QueryObject("SELECT fld_ball_color, fld_inner_ball, fld_outer_ball, fld_correct, fld_ano_correct 
				                              FROM itc_question_drag_drop WHERE fld_quesid='".$questionid."' 
											  AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
				
				$ballcolor=array();
				$insideballcolor=array();
				$outsideballcolor=array();
				$innerball=array();
				$outerball=array();
				$correctball=array();
				$anocorrectball=array();
				$l=0;
				while($row=$ansopt->fetch_assoc())
				{
					extract($row);
					$ballcolor[$l]=$fld_ball_color;
					if($fld_inner_ball!=0)
						$insideballcolor[$l]=$fld_ball_color;
					if($fld_outer_ball!=0)
						$outsideballcolor[$l]=$fld_ball_color;
					$innerball[$l]=$fld_inner_ball;
					$outerball[$l]=$fld_outer_ball;
					$correctball[$l]=$fld_correct;
					$anocorrectball[$l]=$fld_ano_correct;
					$l++;
				}
				?>
                <style>
					.ballcontainer{
						width:300px;
						height:215px;
						border-left:5px solid #000;
						border-bottom:5px solid #000;
						border-right:5px solid #000;
						display:table-cell;
						vertical-align:bottom !important;
					}
					.ballsplitted
					{
						display: table-cell;
						height:215px;
						padding: 16px;
						vertical-align: baseline;
						width: 264px;
					}
					.ball-green{
						background:#060;
						border-radius:30px;
						width:30px;
						height:30px;
						float:left;
					}
					.ball-blue{
						background:#06F;
						border-radius:30px;
						width:30px;
						height:30px;
						float:left;
					}
					.ball-red{
						background:#F00;
						border-radius:30px;
						width:30px;
						height:30px;
						float:left;
					}
				</style>
                <div id="wrapper" style="height:280px">
                	<div class="ballcontainer">
                    	<?php
						for($k=0;$k<$l;$k++)
						{
							for($i=0;$i<$innerball[$k];$i++)
							{
								?>
                                <script>
									divelement='<div style="background:none repeat scroll 0 0 #<?php echo $insideballcolor[$k];?>;" class="ball-green"></div>';
									$('.ballcontainer').append(divelement);
								</script>
                                <?php
							}
						}?>
                    </div>
                    <div class="ballsplitted">
                    	<?php
						for($k=0;$k<$l;$k++)
						{
							for($i=0;$i<$outerball[$k];$i++)
							{
								?>
                                <script>
									divelement='<div style="background:none repeat scroll 0 0 #<?php echo $outsideballcolor[$k];?>;" class="ball-blue"></div>';
									$('.ballsplitted').append(divelement);
								</script>
                                <?php
							}
						}?>
                    </div>
                </div>
                
                <div class="row rowspacer">
                	<table class='table table-hover table-striped table-bordered'>
                        <thead class='tableHeadText'>
                            <tr style="text-align:center">
                                <th>colors</th>
                                <th>answer count</th>
                                <th>alternative answer count</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
                            for($k=0;$k<$l;$k++)
                            {
                                if($correctball[$k]!=0 || $anocorrectball[$l]!=0)
                                {
                                    ?>
                                    <tr id="tanswerrow1" style="text-align:center;">
                                    	<td><span><?php echo "#".$ballcolor[$k];?></span></td>
                                        <td><input class="mix-input qit-medium" type="text" style="width:75px;height:35px;" value="<?php echo $correctball[$k];?>" readonly/></td>
                                        <td><input class="mix-input qit-medium" type="text" style="width:75px;height:35px;" value="<?php echo $anocorrectball[$k];?>" readonly/></td>
                                    </tr>
									<?php
                                }
							}?>
                    	</tbody>
                    </table>
                </div>
                <script>
					(function(d){d.fn.shuffle=function(c){c=[];return this.each(function(){c.push(d(this).clone(true))}).each(function(a,b){d(b).replaceWith(c[a=Math.floor(Math.random()*c.length)]);c.splice(a,1)})};d.shuffle=function(a){return d(a).shuffle()}})(jQuery);
					$('.ball-green').shuffle();
					$('.ball-blue').shuffle();
				</script>
                <?php
			} // Drag & Drop - Type 2
			
			if($answertypeid == 13 )
			{
                            ?>
				<style type="text/css">
					.drag1 {
						background: #000;
						width: 10px;
						height: 10px;
						border-radius: 50%;
						z-index:100;
						cursor:pointer;
						float:left;
						margin:5px;
					}
				</style>
					  <?php
				$ansimage = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer FROM itc_question_answer_mapping
				                                     WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													 AND fld_attr_id='1' AND fld_flag='1'");
													 
				$qryimagepos = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                                    WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													AND fld_attr_id='2' AND fld_flag='1'");
				
				$imagepos=array();				
				$l=0;
				while($rowqryimagepos=$qryimagepos->fetch_assoc())
				{
					$l++;
					extract($rowqryimagepos);
					$imagepos[$l]=$answer;
				}
				for($k=1;$k<=$l;$k++)
				{
					$imageballposition[$k] = explode(',',$imagepos[$k]);
				}
				
				$ansimage1 = ($ansimage != '') ? "../../thumb.php?src=".__CNTANSIMGPATH__.$ansimage."&w=800&h=200&zc=3" : '';
				?>
				<div class="rowspacer">&nbsp;</div>
			   
				<div class="twelve columns rowspacer" >
					<div class="twelve columns" id="droppable" style="width:100%; pointer-events:none">
						<div id="balldraggable1" class="rowspacer drag1" title="Drag this Point" style="display:none"></div>
						<div id="balldraggable2" class="rowspacer drag1" title="Drag this Point" style="display:none"></div>
						<div id="balldraggable3" class="rowspacer drag1" title="Drag this Point" style="display:none"></div>
						<div id="balldraggable4" class="rowspacer drag1" title="Drag this Point" style="display:none"></div>
						<div id="balldraggable5" class="rowspacer drag1" title="Drag this Point" style="display:none"></div>
				
						<img name="txtimage" id="txtimage" src="<?php echo $ansimage1; ?>"/>   
					</div>
				</div>
			   
				<script language="javascript" type="text/javascript">
					/******This is for dragable script******/
					$('#balldraggable1,#balldraggable2,#balldraggable3,#balldraggable4,#balldraggable5').draggable({
						containment: '#txtimage',
						create: function( event, ui ) {
						$('#balldraggable1').css({'top':'<?php echo $imageballposition[1][0];?>','left':'<?php echo $imageballposition[1][1];?>' });
						$('#balldraggable2').css({'top':'<?php echo $imageballposition[2][0];?>','left':'<?php echo $imageballposition[2][1];?>' });
						$('#balldraggable3').css({'top':'<?php echo $imageballposition[3][0];?>','left':'<?php echo $imageballposition[3][1];?>' });
						$('#balldraggable4').css({'top':'<?php echo $imageballposition[4][0];?>','left':'<?php echo $imageballposition[4][1];?>' });
						$('#balldraggable5').css({'top':'<?php echo $imageballposition[5][0];?>','left':'<?php echo $imageballposition[5][1];?>' });
						<?php if($imagepos[1]!=''){?>$('#balldraggable1').css({'display':'block'});<?php }?>
						<?php if($imagepos[2]!=''){?>$('#balldraggable2').css({'display':'block'});<?php }?>
						<?php if($imagepos[3]!=''){?>$('#balldraggable3').css({'display':'block'});<?php }?>
						<?php if($imagepos[4]!=''){?>$('#balldraggable4').css({'display':'block'});<?php }?>
						<?php if($imagepos[5]!=''){?>$('#balldraggable5').css({'display':'block'});<?php }?>
						}
					});
					$('#droppable').droppable({
						accept: '.drag1',
						start: function(event, ui) { $(this).removeAttr("style"); },
						drop: function(event, ui) {
							ballpost=('' + ui.draggable.css('top') + ',' + ui.draggable.css('left'));
							var id = ui.draggable.attr('id').replace('balldraggable','');
							$('#hideimagedragpos'+id).val(ballpost);							
						}
					});
				</script>
				<?php 
			}  // Drag & Drop - Type 3
			
			if($answertypeid == 14)
			{
				$ansimage = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                                      WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													  AND fld_attr_id='1' AND fld_flag='1'");
				$imagepos = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                                       WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													   AND fld_attr_id='2' AND fld_flag='1'");
				?>
				<div class="six columns" >
					<div class="six columns" id="droppable" style="height:800px">
						<iframe id="iframegraphline" src="" height="800" width="800" style="pointer-events: none;"></iframe>
						<script>
							<?php if($imagepos!='') {?>
								$('#iframegraphline').attr('src','<?php echo $domainame;?>test/testassign/line.php?img=<?php echo $ansimage;?>&val=<?php echo $imagepos;?>');
								$("#hideimagename").val('<?php echo $ansimage;?>'); 
							<?php }?>
						</script>
					</div>
					<div id="debug" ></div>
				</div>
				<input type="hidden" id="hideimagename" name="hideimagename" value="<?php echo $ansimage; ?>" />
				<?php 
			}
                        
                        /************Custom Materices Code End Here Developed by Mohan M 30-7-2015************/
                        if($answertypeid == 16){
                            
                            $qry = $ObjDB->QueryObject("SELECT fld_boxid AS txtboxval, fld_answer AS aswer, fld_attr_id AS columnsval
                                                                          FROM itc_question_answer_mapping 
                                                                          WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
                            if($qry->num_rows > 0) 
                            {
                                while($row = $qry->fetch_assoc())
                                {
                                    extract($row); 
                                    $rowsval = explode("~",$columnsval);
                                }
                            }
                        if($rowsval[1]=='2'){ ?>
<div style="border-left:4px solid #b4b4b4; border-right:4px solid #b4b4b4; border-radius: 25px;  padding: 10px; width:149px;">
<?php }else if($rowsval[1]=='3'){ ?> 
<div style="border-left:4px solid #b4b4b4; border-right:4px solid #b4b4b4; border-radius: 25px;  padding: 10px; width:224px;">
<?php }else if($rowsval[1]=='4'){ ?> 
<div style="border-left:4px solid #b4b4b4; border-right:4px solid #b4b4b4; border-radius: 25px;  padding: 10px; width:301px;">
<?php }else if($rowsval[1]=='5'){ ?> 
<div style="border-left:4px solid #b4b4b4; border-right:4px solid #b4b4b4; border-radius: 25px;  padding: 10px; width:377px;">                
<?php } ?>
                            <br>
                            <?php
                            for($i=1;$i<=$rowsval[0];$i++)
                            {
                              
                                for($j=0;$j<$rowsval[1];$j++)
                                {
			?>
                                           <input id="txt_<?php echo $i."_".$j;?>" type='text' class="mix-input" size='2' maxlength="2">&nbsp;
                                    <?php
                                } ?>
                               <br> 
                                  <br> 
                               
                                <?php
                                
                            }
                            ?>
        </div>
                                    <?php
                               
                                
                        $qry = $ObjDB->QueryObject("SELECT fld_boxid AS txtboxval, fld_answer AS answer
                                                  FROM itc_question_answer_mapping 
                                                  WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
                        if($qry->num_rows > 0) 
                        {
                            while($row = $qry->fetch_assoc())
                            {
                                extract($row); ?>
                                <script>
                                    $('#<?php echo $txtboxval;?>').val('<?php echo $answer;?>');
                                </script> <?php
                            }
                        } 
                               
                                
                            
                            }
                        /************Custom Materices Code End Here Developed by Mohan M 30-7-2015************/
                            ?>
    </div>
    </div>
<?php
	@include("footer.php");    