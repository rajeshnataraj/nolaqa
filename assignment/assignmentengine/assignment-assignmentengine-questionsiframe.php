<?php 
	@include("sessioncheck.php");
	$method=$_REQUEST;
	$questionid=isset($method['id']) ? $method['id'] : '0';
	$ids = explode('_',$questionid);
	$questionid='';
	$quesorder='';
	$answertypeid='';
	$question='';
	if(isset($ids[0]))
	$questionid =$ids[0];
	if(isset($ids[1]))
	$quesorder =$ids[1];
	if(isset($ids[2]))
	$pause =$ids[2];
	if(isset($ids[3]))
	$studentclassid =$ids[3];
	if(isset($ids[4]))
	$testid =$ids[4];
?>
<script language="javascript" type="text/javascript" src="../../jquery-ui/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript" src="../../jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/pdw/editor_plugin_src.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/asciimath/js/ASCIIMathMLwFallbackLarge.js"></script>
<script language="javascript" type="text/javascript" src="../../tiny_mce/plugins/asciisvg/js/ASCIIsvg.js"></script> 
<script language="javascript" type="text/javascript" src="../../js/bootstrap-formhelpers-selectbox.js"></script>    
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
	min-width:0px;
	height:80%;
	width:100%;
}
p {
	margin: 0;
	float: left;	
	width: 100%;
	font-size:2rem !important;
}
p, .one.columns {
	font-size: 2rem;
}
.checkbox {
	margin-top: 10px;
}
.drag1 {
	background: #000;width: 10px;height:10px;border-radius:50%;z-index:100;cursor:pointer;float:left;margin:5px;
}
#droppable {
	width:600px;height:200px;
}
</style> <?php
if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
?>
<style>
.one.columns, .one, .columns, .column{
    float: left!important;
    margin-left:6px!important;
}
</style>
<?php
}
	
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
			/*--- Multiple Choise ---*/
			if($answertypeid == 1) // Multiple Choice 
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
				
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR '~') AS 'choice', 
				                           GROUP_CONCAT(IF(fld_attr_id = '2', fld_answer, NULL) SEPARATOR '~') AS 'correct' 
										   FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
										   AND fld_answer<>'' AND fld_flag='1'");
				
				$alphabet = array('A','B','C','D','E','F','G','H');
				$anscnt = 0;
				while($row = $qry->fetch_assoc())
				{
					extract($row);
					$anschoices = explode("~",$choice);
					$correctans = explode("~",$correct);
				}
				?>
				<div id="c_b">
					<?php 
					for($i=0;$i<sizeof($anschoices);$i++){
					?>
					<div class="row rowspacer"> 
					`	<div class="one columns" style="width:15px; float: left!important;"> 
                    		<input class="checkbox" type="checkbox" name="mulchoice" id="mulchoice<?php echo ($i+1);?>" value="<?php echo ($i+1);?>" <?php if($canswer == $i+1){?> checked="checked" <?php } ?> />
						</div>     	
						<div class="one columns" style="width:30px; float: left!important;"><label for="mulchoice<?php echo ($i+1);?>"><?php echo $alphabet[$i]; ?>.</label></div>
						<div class="eleven columns" style="margin-left:1%; float: left!important;">
							<label for="mulchoice<?php echo ($i+1);?>"><?php echo trim($anschoices[$i]);?> </label>
						</div>
					</div>
					<?php
					} // end answer choice for	
					?>
				</div>
				<?php 	
				
			} // Multiple Choice  if ends	
		
			/*--- Single Answer id=2 ---*/
			if($answertypeid == 2) // Single Answer 
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
													
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
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
						<div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="<?php echo $canswer;?>" /></div>
						<div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix; ?></span></div>
					</div>
				</div>		
			<?php	
			} // Single Answer if ends	
			
			/*--- Match the following id=3 ---*/ 
			if($answertypeid == 3 ) // Match the following
			{	
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}			
				$qrypresuf = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
				                                 GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' FROM itc_question_answer_mapping 
												 WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
				$prefixarray=array();
				$suffixarray=array();
				
				if($pause == 1){
					$prefixarray1 = explode("~",$canswer);
				}
		
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
							<div class="outer-input-txt"><input type="text" class="ques-input qit-medium" id="ans<?php echo $i;?>" value="<?php echo $prefixarray1[$i]; ?>" placeholder="Answer" /></div>
						</div>
					</div>
				<?php 	
				}
				$count = $i;
				echo '<div class="rowspacer"></div>';
			}	// Match the following if ends	
			
			
			/*--- Custom answer type id=4 ---*/
			if($answertypeid == 4)
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
				$answer = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_question_answer_mapping 
				                                    WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													AND fld_attr_id='6' AND fld_flag='1'");			
				$answer = explode(',',$answer);	
				$values = $ObjDB->SelectSingleValue("SELECT fld_answer FROM itc_question_answer_mapping 
				                                    WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													AND fld_attr_id='7' AND fld_flag='1'");
				$values = explode(',',$values);	
				
				$j=0;
				$count=0;
				 $anspattern = '';
				 for($i=0;$i<sizeof($answer);$i++){
					if($answer[$i] == 5){
						echo '<div class="outer-label"><span id="lab_'.$values[$j].'">'.$values[$j].'</span></div>';
					}
					else {
						echo $ObjDB->SelectSingleValue("SELECT fld_html_code FROM itc_question_answer_pattern_master 
						                               WHERE fld_id='".$answer[$i]."'");								
					}					
					if($answer[$i] == 5 or $answer[$i]==4 or $answer[$i]==20 or $answer[$i]==21 or $answer[$i]==22 or $answer[$i]==23 or $answer[$i]==24){
						$j++;	
						if($answer[$i]!=5)
						$count++;
					}
					else if($answer[$i]==17){
						$j = $j + 2;
						$count = $count + 2;
					}
					else if($answer[$i]==18){
						$j = $j + 3;
						$count = $count + 3;
					}
				 }
				 $tarray = explode('~',$canswer);				 
				?> 
					
				<script>	
					var j=1;
					var tmparray =<?php echo json_encode($tarray); ?>;
					$('input#txt').each(function(){							
						$(this).attr('id','txt_'+j);						
						if(tmparray[j-1]!='' && tmparray[j-1]!=undefined)
						$('#txt_'+j).val(tmparray[j-1]);					
						j++;
					});
					
				 </script>
			<?php 
			}
			
			/*--- Answer choice id=5 ---*/
			if($answertypeid == 5)
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
				$qry = $ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                           WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
										   AND fld_flag='1' AND fld_attr_id='1' ORDER BY fld_boxid ASC ");
				$answerarray=array();
				$i=0;
				while($row=$qry->fetch_assoc())
				{
					extract($row);
					$answerarray[$i]=$answer;
					$i++;
				}
				?>	
				<div id="c_b">
					<table width="15%" cellpadding="0" cellspacing="0">
						<tr height="70">
							<td width="20%">
								<input type="radio" id="rightans" name="yesorno" value="1" <?php if($canswer == 1){?> checked="checked" <?php } ?> />
						   </td>
						   <td>
								<label style="font-size:1.5em" for="rightans"><?php echo $answerarray[0]; ?></label>
						   </td>
						</tr>
						<tr>
							<td width="20%">
								<input type="radio" id="wrongans" name="yesorno" value="2" <?php if($canswer == 2){?> checked="checked" <?php } ?> />
							</td>
							<td>
								<label style="font-size:1.5em" for="wrongans"><?php echo $answerarray[1]; ?></label>
						   </td>
						</tr>
					</table>
				</div>
			<?php
			}
			
			/*--- Menu & Extrems id=6 ---*/
			if($answertypeid == 6)
			{	
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
					$ans = explode("~",$canswer);
				}		
				?>
				<div class="row rowspacer">
					<div class="six columns" align="center">
						<b>Means</b><br />
						<input type="text" name="mean1" id="mean1" value="<?php echo $ans[0];?>" class="mix-input" />&nbsp;
						<input type="text" name="mean2" id="mean2" value="<?php echo $ans[1];?>" class="mix-input" />
					</div>
					<div class="six columns" align="center">
						<b>Extremes</b> <br />
						<input name="ext1" type="text" class="mix-input" id="ext1" value="<?php echo $ans[2];?>" />&nbsp;
						<input type="text" name="ext2" class="mix-input" id="ext2" value="<?php echo $ans[3];?>" />
					</div>
				</div>		
				<?php	
			}
			
			/*--- Single Range id=7 ---*/
			
			if($answertypeid == 7 ) // Single Range
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
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
				<div class="row rowspacer">
					<div class="eight columns">
						<div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix;?></span></div>
						<div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="<?php echo $canswer;?>" /></div>
						<div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix;?></span></div>
					</div>
				</div>
				<?php	
			}	// Single Range if ends	
			
			/*--- Mulitple choice image id=8 ---*/
			if($answertypeid==8) // Multiple Image 
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
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
				
				echo '<div class="row rowspacer"> <div id="c_b">';		
				for($i=0;$i<sizeof($anschoices);$i++){
					$imgid = $i + 1;
					
					if($anschoices[$i] != 'no-image.png' && $anschoices[$i] != '') {
				?>
					<div class="six columns" style="margin-left:1%;<?php if($i>1){ echo 'margin-top:30px;'; } ?>">
						<div class="one columns">            		
							<input class="checkbox" type="checkbox" name="mulchoice" id="mulchoice<?php echo ($i+1);?>" value="<?php echo ($i+1);?>" <?php if($canswer == $i+1){?> checked="checked" <?php } ?> />  
						</div> 
						<div class="one columns" style="width:15px;float:left;"><?php echo $alphabet[$i]; ?>.</div>
						<div style="float:left;margin-left:5%;margin-top:2%;width:83%;">
						<?php //Get image width
							list($width,$height) = getimagesize(_CONTENTURL_."question/ansimg/".$anschoices[$i]);							
						?>
                        	<img name="txtimageans<?php echo $imgid; ?>" id="txtimageans<?php echo $imgid; ?>" src="../../thumb.php?src=<?php echo _CONTENTURL_."question/ansimg/".$anschoices[$i]; if($width > 400){?>&w=400&h=400&zc=2<?php } else{ echo "&w=".$width."&h=".$height."&zc=2"; } ?>"/>
						</div>
					</div>
				<?php
					}
				} // end answer choice for
				
				echo '</div></div>';		
			} // Multiple Image if ends	
		
			/*--- Single Multiple id=9 ---*/
			
			if($answertypeid == 9) // Single Multiple
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
				$qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
				                            GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
											GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
											FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' 
											AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
				while($row = $qry->fetch_assoc())
				{
					extract($row);
				}
				?>
				<div class="row rowspacer">
					<div class="eight columns">
						<div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix; ?></span></div>
						<div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="<?php  echo $canswer;?>" /></div>
						<div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix; ?></span></div>
					</div>
				</div>
			<?php    
			}	// Single Multiple if ends	
			
			if($answertypeid == 10) // Drag & Drop
			{
				if($pause == 1){
					$ObjDB->NonQuery("UPDATE `itc_test_student_answer_track` SET fld_answer=' ', fld_correct_answer=0, fld_show=0, fld_time_track='00:00:00' 
                  					WHERE fld_student_id='".$uid."' AND fld_test_id='".$testid."' AND fld_question_id='".$questionid."'");
				}
				
				$dropqus=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                              WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='10' AND fld_flag='1'");
				$dropans=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                              WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='2' AND fld_flag='1'");
				
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
				$count=$j;
				?>
				<div class="eleven columns">   
					<div class='row'>                
					<?php 
					for($i=0;$i<sizeof($questionarray);$i++)
					{ 
						?>                        
						<span class="ques-symbol drag" id="option_<?php echo $i; ?>" style="cursor:pointer"><?php echo $questionarray[$i]; ?></span> 
						<script>
							$("#option_<?php echo $i; ?>").draggable({
								containment: 'document',
								revert: true,
								start: function() {
									dragvalue = $(this).html();
								}
							});         
						</script>                 
						<?php 
					} ?> 
					</div>
				</div>
				<div class="eleven columns"> 
					<?php 
					for($i=0;$i<sizeof($answerarray);$i++)
					{ 
						?>
						<input type="text" class="ques-input" id="ans<?php echo $i+1;?>" style="width:10%; margin:5px;" value="" placeholder="Answer" readonly />&nbsp;           
						<script>
							$("#ans<?php echo $i+1;?>").droppable({
								accept: '.drag',
								drop: function()
								{
									$('#ans<?php echo $i+1;?>').val(dragvalue);
								}
							});
						</script>             
						<?php 
					} ?> 
				</div>
				<?php
			} 
			
			if($answertypeid == 11 )
			{
				$pullqus=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                             WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='1' AND fld_flag='1'");
			$pullans=$ObjDB->QueryObject("SELECT fld_answer AS answer, fld_boxid as boxid FROM itc_question_answer_mapping 
			                             WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='2' AND fld_flag='1'");
 			$ansopt=$ObjDB->QueryObject("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
			                             WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."'AND fld_attr_id='10' AND fld_flag='1'");
			
			$i=0;$j=0;$k=0;
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
				}
				while($opt=$ansopt->fetch_assoc())
				{
					extract($opt);
					$optionarray[$k]=$answer;				
					$k++;
				}
				$count=$i;
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
					$ans = explode("~",$canswer);
				}
				?>
				<div class="eleven columns">                   
					<?php for ($i=0;$i<sizeof($questionarray);$i++){ ?>
                    	<?php $pauseans = $ObjDB->SelectSingleValue("SELECT fld_answer FROM`itc_question_answer_mapping` WHERE fld_quesid='".$questionid."' AND fld_attr_id=2 AND fld_ans_type='".$answertypeid."' AND fld_boxid='".$ans[$i]."'"); ?>                        
							<div class='row'>
								<div class="outer-input-sym" style="padding:1px; float:left">
									<span class="ques-symbol"><?php echo $questionarray[$i]; ?></span>
								</div>                       
								<div class="five columns">
                                    <div class="selectbox" >
                                        <input type="hidden" name="pullans<?php echo $i+1;?>" id="pullans<?php echo $i+1;?>" value="<?php echo $ans[$i];?>" >
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#"><span class="selectbox-option input-medium" style="width: 95%;" data-option="" id="clearsubject"><?php if($pause == 0 or $pauseans == ''){ echo "Select Answer";} else {echo $pauseans;}?></span> <b class="caret"></b> </a> 
                                        <div class="selectbox-options">
                                            <ul role="options" id="option<?php echo $i;?>"> 
                                                <?php for($o=0;$o<sizeof($optionarray);$o++){?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $o+1; ?>"><?php echo $optionarray[$o]; ?></a></li>
                                                <?php  }	?>    
                                            
                                            </ul>
                                        </div>
                                    </div>
                                </div>
							</div>
					<?php } ?> 
				</div>
		<?php }
			$ans2 =0;
			if($answertypeid == 12) // Drag & Drop - Type 2
			{
				if($pause == 1){
					$ObjDB->NonQuery("UPDATE `itc_test_student_answer_track` SET fld_answer=' ', fld_correct_answer=0, fld_show=0, fld_time_track='00:00:00' 
                  					WHERE fld_student_id='".$uid."' AND fld_test_id='".$testid."' AND fld_question_id='".$questionid."'");
				}
				
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
								<div style="background:none repeat scroll 0 0 #<?php echo $insideballcolor[$k];?>; pointer-events:none" class="ball-green"></div>
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
								<div style="background:none repeat scroll 0 0 #<?php echo $outsideballcolor[$k];?>; cursor:all-scroll" class="ball-blue"></div>
								<?php
							}
						}?>
					</div>
				</div>
				<input type="hidden" id="ballans" name="ballans" value="" />
				<script>
					(function(d){d.fn.shuffle=function(c){c=[];return this.each(function(){c.push(d(this).clone(true))}).each(function(a,b){d(b).replaceWith(c[a=Math.floor(Math.random()*c.length)]);c.splice(a,1)})};d.shuffle=function(a){return d(a).shuffle()}})(jQuery);
					$('.ball-green').shuffle();
					$('.ball-blue').shuffle();
					
					function rearrangeballs()
					{
						var emtpdiv='<div class="empty" style="width: 30px; height: 30px; float: left;pointer-events:none"></div>';
						var totaldivmodulescnt =$('.ballcontainer > div[class]').length%10;
						var adddivcnt = 10-totaldivmodulescnt;
						ul = $('div.ballcontainer div:first-child'); // your parent ul element
						if(adddivcnt!=0)
						{
							for(i=0;i<adddivcnt;i++)
							{
								ul.before(emtpdiv);
							}
						}
					}
					rearrangeballs();
					
					$(function() {
						$(".ballcontainer").sortable({
							connectWith: ".ballsplitted",
							revert: true,
							over: function( event, ui ) {
							},
							receive: function(event, ui) {
								var clsitem= ui.item.attr('class');
								var itemstyle=ui.item.attr('style');
								var backgroun=ui.item.css('backgroundColor');
								$('.empty').remove();
								ui.item.removeAttr('style');
								ui.item.remove();
								$('div.ballcontainer :first-child').before('<div id="<?php echo $uniqu= uniqid(); ?>" style="'+itemstyle+'" class="'+clsitem+'"></div>');
								rearrangeballs();
								$('#<?php echo $uniqu; ?>').animate({ backgroundColor: "yellow" }, '100');
								$('#<?php echo $uniqu; ?>').animate({ backgroundColor: backgroun }, '200');
								getcontainerballs();
							}
						});
						$(".ballsplitted").sortable({connectWith: ".ballcontainer",revert: true,
							receive: function(event, ui) {
								var clsitem= ui.item.removeAttr('id');
								rearrangeballs();
								getcontainerballs();
							}
						});	
						function rgb2hex(rgb){
							rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
							return "" +
							("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
							("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
							("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
						}
								
						function getcontainerballs()
						{
							var ansvalue=[];
							$('.ballcontainer .ball-blue').each(function(){
								value = rgb2hex($(this).css('background-color'));
								ansvalue.push(value);
							});
							$('#ballans').val('');
							$('#ballans').val(ansvalue);
						}
					});
				</script>
				<?php
			}
			
			if($answertypeid == 13) // Drag & Drop
			{
				if($pause == 1){
					$ObjDB->NonQuery("UPDATE `itc_test_student_answer_track` SET fld_answer=' ', fld_correct_answer=0, fld_show=0, fld_time_track='00:00:00' 
                  					WHERE fld_student_id='".$uid."' AND fld_test_id='".$testid."' AND fld_question_id='".$questionid."'");
				}
								
				$ansimage = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                                      WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='1' AND fld_flag='1'");
				$pointcount = $ObjDB->SelectSingleValue("SELECT COUNT(fld_answer) FROM itc_question_answer_mapping 
				                                       WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='2' AND fld_flag='1'");
				;
				
				$ansimage1 = ($ansimage != '') ? "../../thumb.php?src=".__CNTANSIMGPATH__.$ansimage."&w=800&h=200&zc=3" :'';
				?>
                                <div class="twelve columns" id="droppable">
						<?php if($pointcount > 0) {?>
                                    <div id="balldraggable1" class="rowspacer drag1" title="Drag this Point" style="left:0px;"></div>
						<?php } if($pointcount > 1) {?>
                                    <div id="balldraggable2" class="rowspacer drag1" title="Drag this Point" style="left:10px;"></div>
						<?php } if($pointcount > 2) {?>
                                    <div id="balldraggable3" class="rowspacer drag1" title="Drag this Point" style="left:20px;"></div>
						<?php } if($pointcount > 3) {?>
                                    <div id="balldraggable4" class="rowspacer drag1" title="Drag this Point" style="left:30px;"></div>
						<?php } if($pointcount > 4) {?>
                                    <div id="balldraggable5" class="rowspacer drag1" title="Drag this Point" style="left:40px;"></div>
						<?php }?>
						<img name="txtimage" id="txtimage" src="<?php echo $ansimage1; ?>"/>   
					</div>
				<input type="hidden" id="hidepointcount" name="hidepointcount" value="<?php echo $pointcount;?>" />
				<input type="hidden" id="hideimagedragpos1" name="hideimagedragpos1" value="" />
				<input type="hidden" id="hideimagedragpos2" name="hideimagedragpos2" value="" />
				<input type="hidden" id="hideimagedragpos3" name="hideimagedragpos3" value="" />
				<input type="hidden" id="hideimagedragpos4" name="hideimagedragpos4" value="" />
				<input type="hidden" id="hideimagedragpos5" name="hideimagedragpos5" value="" />
				
				<script language="javascript" type="text/javascript">
					$('#balldraggable1,#balldraggable2,#balldraggable3,#balldraggable4,#balldraggable5').draggable({
						containment: '#txtimage',
					});
					$('#droppable').droppable({
						accept: '.drag1',
						start: function(event, ui) { $(this).removeAttr("style"); },
						drop: function(event, ui) {
							var pointcount = $('#hidepointcount').val();
							var id = ui.draggable.attr('id').replace('balldraggable','');
							var newp = 0;
                                                        if(id >= 2){
                                                            newp = (id - 1) * 20;
							}
                                                        var newleft = parseInt(ui.draggable.css('left').replace('px',''))+newp;
                                                        console.log(id+ " : " +newleft);
                                                        ballpost=('' + ui.draggable.css('top') + ',' + ui.draggable.css('left'));
							
							$('#hideimagedragpos'+id).val(ballpost);
						}
					});
				</script>
				<?php
			}
			
			if($answertypeid == 14)
			{
				if($pause == 1){
					$ObjDB->NonQuery("UPDATE `itc_test_student_answer_track` SET fld_answer=' ', fld_correct_answer=0, fld_show=0, fld_time_track='00:00:00' 
                  					WHERE fld_student_id='".$uid."' AND fld_test_id='".$testid."' AND fld_question_id='".$questionid."'");
				}
				  
				$ansimage = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer FROM itc_question_answer_mapping 
				                                      WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' 
													  AND fld_attr_id='1' AND fld_flag='1'");
				
				?>
				<div class="six columns" >
					<div class="six columns" id="droppable" style="height:800px">
						<iframe id="iframegraphline" src="" height="800" width="800"></iframe>
						<script>
							$('#iframegraphline').attr('src','line.php?img=<?php echo $ansimage;?>');
							$("#hideimagename").val('<?php echo $ansimage;?>'); 
							
							var iframe1 = document.getElementById('iframegraphline');
							var innerDoc1 = iframe1.contentDocument || iframe1.contentWindow.document;
						</script>
					</div>
				</div>
				<input type="hidden" id="hideimagename" name="hideimagename" value="<?php echo $ansimage; ?>" />
				<?php 
			}
                        /*--- Single Answer id=2 ---*/
			if($answertypeid == 15) // Single Answer 
			{
				if($pause == 1){
					$canswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
												FROM itc_question_details AS a 
												LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
												LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
												WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
												AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
				}
		?>
                                <style>textarea {
                                resize: none;
                                }</style>
				<div class="row">
					<div class="eight columns">
                                            <div class="outer-input-txt"><textarea autofocus style="width:729px;height:403px;text-align: left;" class="ques-input qit-medium"  id="txtopenresponse" maxlength="1000" name="txtopenresponse"  onkeypress="if (this.value.length==this.getAttribute('maxlength')) alert('Max character reached')"><?php echo $canswer;?></textarea></div>
                                            <p>Maximum allowed 1000 character's olny</p>
					</div>
                                    
				</div>		
			<?php	
			} // Single Answer if ends	
                        
                        
                        /************Custom Materices Code Start Here Developed by Mohan M 30-7-2015************/  
                        if($answertypeid == 16) 
                        {
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
		?>
                                
                                <input type="hidden" id="rowval" name="rowval" value="<?php echo $rowsval[0]; ?>" />
                                <input type="hidden" id="columnval" name="columnval" value="<?php echo $rowsval[1]; ?>" />
                                <?php
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
                                            { ?>
                                                <input id="txt_<?php echo $i."_".$j;?>" type='text' class="mix-input" size='2' >&nbsp;  <?php
                                            } ?>
                                            <br><br> 
                                            <?php
                                        }
                                        
                                       $tottxtbox= $rowsval[0]*$rowsval[1];
                                    ?>
                                </div>
                                <?php
                                if($pause == 1)
                                {
                                    $stuanswer = $ObjDB->SelectSingleValue("SELECT b.fld_answer AS canswer  
                                                                                FROM itc_question_details AS a 
                                                                                LEFT JOIN itc_test_student_answer_track AS b ON b.fld_question_id=a.fld_id
                                                                                LEFT JOIN itc_test_pause AS c ON c.fld_test_id= b.fld_test_id AND c.fld_student_id=b.fld_student_id 
                                                                                WHERE a.fld_id='".$questionid."' AND c.fld_test_id='".$testid."' 
                                                                                AND c.fld_class_id='".$studentclassid."' AND c.fld_student_id='".$uid."'");
                                   
                                     
                                    $sans=explode(",", $stuanswer, $tottxtbox);
                                    $stufillans=array_chunk($sans,$rowsval[1]);

                                    for($i=0;$i<sizeof($stufillans);$i++)
                                    {
                                        for($j=0;$j<sizeof($stufillans[$i]);$j++){
                                                ?>
                                            <script>
                                               $('#<?php echo "txt_".($i+1)."_".($j);?>').val('<?php echo $stufillans[$i][$j];?>');
                                            </script> <?php
                                        }
                                    }

                               } ///pause code end here
                        }
                          /************Custom Materices Code End Here Developed by Mohan M 30-7-2015************/
                ?>
                <script language="JavaScript">
                        function updateTextArea() {
                                var allVals = [];
                                $('#c_b :checked').each(function() {					
                                        allVals.push($(this).val());					
                                });
                                $('#answer').val(allVals)
                        }
                        $(function() {					
                                $('#c_b input').click(updateTextArea);
                                updateTextArea();					


                        });	

                        jQuery(document).ready(function($) {

                        }); //end if ready(fn)
		</script>
                <?php
                $teachid = $ObjDB->SelectSingleValue("SELECT fld_created_by FROM itc_user_master WHERE fld_id='".$uid."' AND fld_delstatus='0'");
                $clssid = $ObjDB->SelectSingleValue("SELECT fld_class_id FROM itc_test_student_mapping WHERE fld_test_id='".$testid."' AND fld_student_id='".$uid."' AND fld_created_by='".$teachid."'");
                $widflag = $ObjDB->SelectSingleValue("SELECT fld_flag FROM widgets_turnoff_student WHERE fld_schedule_id='".$scheduleid."' AND fld_class_id='".$clssid."' AND fld_created_by = '".$teachid."' AND fld_student_id='".$uid."'"); 
		if($widflag != '1'){
               
                   $contentid = 4;
             
             $widcontflag = $ObjDB->SelectSingleValue("SELECT fld_flag FROM widgets_turnoff_content WHERE fld_content_id='".$contentid."' AND fld_created_by = '".$teachid."'");
             if($widcontflag!='1'){
		?>
                <link href='<?= CONTENT_EXP_URL ?>/emaps-master/partials/widgets/tooltip.css' rel='stylesheet' type="text/css" />
		<script language="JavaScript">
		
                $(function() {

                   $( ".modalDialog" ).draggable();
                 });
		</script>
            <style>
                   
               .modalDialog {
                    position: fixed;
                    font-family: Arial, Helvetica, sans-serif;
                    top:50px;
                    opacity:0;
                    -webkit-user-drag: element;
                    -webkit-transition: opacity 400ms ease-in;
                    -moz-transition: opacity 400ms ease-in;
                    transition: opacity 400ms ease-in;
                    pointer-events: none;
                    
            }
            .modalDialog:target {
                    opacity:1;
                    pointer-events: auto;
            }

            .modalDialog > div {
                    width: 226px;
                    position: relative;
                    margin-top:20px;
                    

            }
            .closenew {
                    background: #606061;
                    color: #FFFFFF;
                    line-height: 25px;
                    position: absolute;
                    text-align: center;
                    top: -17px;
                    width: 20px;
                    background:red;
                    text-decoration: none;
                    font-weight: bold;
                    -webkit-border-radius: 12px;
                    -moz-border-radius: 12px;
                   
                    -moz-box-shadow: 1px 1px 3px #000;
                    -webkit-box-shadow: 1px 1px 3px #000;
                    box-shadow: 1px 1px 3px #000;
            }

            .closenew:hover { background: #00d9ff; }

            ul{
                      margin:0 !important;; 
                      padding: 0 !important;

                    }

          #wmenu { 
                    margin-top:360px;
                float: left;
                        line-height: 25px; 
                        left: 200px;
                        font-weight:normal; 
                        font-variant: small-caps; 

            }
                    #wmenu a { 
                display: block;
                text-decoration: none;
                        color: #fff;
                    }
                
                    #wmenu ul li ul li {
                        width: 150px; 
                        color: #49708a;  
                        padding-top: 3px; 
                        padding-bottom:3px; 
                        padding-left: 3px; 
                        padding-right: 3px; 
                        background: rgba(36, 72, 95, 1);
            }
		    #wmenu a:hover { background: #a3cae4;}
                    #wmenu ul li ul li a { 
                        font: 12px arial; 
                        font-weight:normal; 
                        padding:5px;}
                    #wmenu ul li {
                        width: 156px; 
                        background: #49708a;}

                    #wmenu li{ 
                        position:relative; 
                        float:left;
            }
                    #wmenu ul li ul, #wmenu:hover ul li ul, #wmenu:hover ul li:hover ul li ul{ 
                        display:none;
                        list-style-type:none; 
                        width: 140px;}
                    #wmenu:hover ul, #wmenu:hover ul li:hover ul, #wmenu:hover ul li:hover ul li:hover ul { 
                        display:block;}

                    #wmenu:hover ul li:hover ul { 
                        position: absolute;
                        margin-top: 1px;
                        font: 11px;
                        bottom:100%;
            }
                
            a#tooltip2 span {
                margin-top: -455px;
            }
            </style>  
    <input type="hidden" id="hiddquestionid_<?php echo $questionid; ?>" value="<?php echo $questionid; ?>" />    
    <input type="hidden" id="answertypeid_<?php echo $questionid; ?>" value="<?php echo $answertypeid; ?>" />
    <input type="hidden" id="boxcount" value="<?php echo $count; ?>" />
    <input type="hidden" id="hidqorderquesid_<?php echo $quesorder;?>" value="<?php echo $questionid; ?>" />
    <input type="hidden" name="answer" id="answer"/>
    </div>
</div>
                                            
<?php $widintflag = $ObjDB->SelectSingleValue("SELECT COUNT(fld_id) 
                                                                FROM itc_widgets_menu WHERE 
                                                                fld_id NOT IN (SELECT fld_widget_id FROM widgets_turnoff_individual WHERE fld_flag = '1' AND fld_created_by = '".$teachid."') AND fld_delstatus = '0'");
                   
           if($widintflag!=0){   
    ?>
        <div id="openModal1" class="modalDialog" draggable="true" style="height:335px;width:315px;background: gray;top: 10px;left:220px;">
            <div id="calbgrd2" class="dragclass">
                            <a href="#close" title="Close" class="closenew">X</a>

                            <iframe width="315" height="340" src="<?= CONTENT_EXP_URL ?>/emaps-master/partials/widgets/calculator/calculator.html" style="border:none;">

                            <p>Your browser does not support iframes.</p>
                            </iframe>

            </div>       
            </div>
            <div id="openModal2" class="modalDialog" draggable="true" style="height:675px;width:655px;background: gray;top: 10px;left:220px;">
            <div id="calbgrd2" class="dragclass">
                            <a href="#close" title="Close" class="closenew">X</a>

                            <iframe width="650" height="650" src="<?= CONTENT_EXP_URL ?>/emaps-master/partials/widgets/graphers/graphic.html" style="border:1 px; background-color:grey;">

                            <p>Your browser does not support iframes.</p>
                            </iframe>

                    </div>	
            </div>
            
            <div id="openModal4" class="modalDialog" draggable="true"  style="height:471px;width:501px;background: gray;top: 10px;left:220px;">
            <div id="calbgrd3">
                            <a href="#close" title="Close" class="closenew">X</a>

                            <iframe width="500" height="450" src="<?= CONTENT_EXP_URL ?>/emaps-master/partials/widgets/equation_function/scicalc.html" style="border: none; top: 5px;">

                            <p>Your browser does not support iframes.</p>
                            </iframe>

                    </div>	
            </div>
            <div id="wmenu">

                <div style="z-index: 999999;position: absolute; bottom:0px;">


                    <ul>
                        <li><center><a href="#">Widgets</a></center>
                            <ul><?php 
                            $widids = array();
                            $qry = $ObjDB->QueryObject("SELECT fld_id AS widgetid 
                                                        FROM itc_widgets_menu WHERE 
                                                        fld_id NOT IN (SELECT fld_widget_id FROM widgets_turnoff_individual WHERE fld_flag = '1' AND fld_created_by = '".$teachid."') AND fld_delstatus = '0'");
                            if($qry->num_rows > 0){													
                                while($rows = $qry->fetch_assoc()){
                                    extract($rows);
                        
                           if($widgetid == "1"){
                            ?>
                                <li style="margin-bottom:0px;">  <span style="float:left;width:90%;"> <a class="arrow-left" href="#openModal1">Calculator</a> </span> <span style="float:right;width:10%;"> <a id="tooltip1" style="padding:5px 0px; text-align:center">?<span style="font-family: Times New Roman, Times, serif;">                                        
                                        <strong style="text-align:center; font-size:18px;">Calculator</strong><br />
                                        <strong>Movement</strong><br />
                                        * &nbsp;You may drag the Calculator widget anywhere on the screen.<br />
                                        * &nbsp;To close the Calculator widget, click the red X in the upper-left corner.<br />
                                        <strong>Buttons</strong><br />
                                        * &nbsp;To use the Calculator widget, press the number and operator keys on your keyboard.<br /> &nbsp;&nbsp;You may also click the buttons on the widget.<br />
                                        * &nbsp;To close the Calculator widget, click the red X in the upper-left corner.<br />
                                        * &nbsp;Click CE to clear your calculation.<br />
                                        * &nbsp;Click the left-facing arrow to backspace and remove one digit at a time.<br />
                                        * &nbsp;Click the plus/minus button to change the number from positive to negative or<br /> &nbsp;&nbsp;from negative to positive.
                                </span>    
                           </a> </span></li><?php } 
                           if($widgetid == "2"){?>
                                        <li style="margin-bottom:0px;"><span style="float:left;width:90%;"> <a href="#openModal2">Graphing Calculator</a> </span> <span style="float:right;width:10%; font-family: Times New Roman, Times, serif;"> <a id="tooltip2" style="padding:5px 0px ; text-align:center">?<span>
                                        <strong style="text-align:center; font-size:18px;">Graphing Calculator</strong><br />
                                        <strong>Movement</strong><br />
                                        * &nbsp;To zoom in or out on the graph, click the + or - magnifying glass and then click <br />on the area of the graph on which you want to zoom.<br />

                                        * &nbsp;You may drag the Graphing Calculator widget anywhere on the screen.<br />
                                        * &nbsp;You may drag the graph to see a specific area of the graph. <br />
                                        * &nbsp;Click the double right-facing arrows to hide the equations and settings and enlarge the <br />&nbsp;&nbsp;&nbsp;graph area. Click the double left-facing arrows to make the equations and settings reappear. <br />
                                        * &nbsp;To close the Graphing Calculator widget, click the red X in the upper-left corner.<br />
                                        <strong>Settings</strong><br />

                                        * &nbsp;Click the settings icon to adjust the mode, gridlines, or precision of the graph.<br />  &nbsp;&nbsp;  - <strong>Mode:</strong> The available modes are DEG (degrees), RAD (radians), and GRAD (gradians).<br /> &nbsp;&nbsp;&nbsp;Be sure DEG is selected.<br />
                                        &nbsp;&nbsp;  - <strong>Gridlines:</strong> Less means fewer grids; the exact number depends on how far in or out the <br />&nbsp;&nbsp;&nbsp;graph is zoomed. Normal means more gridlines.<br />
                                        &nbsp;&nbsp;  - <strong>Precision:</strong> The available precisions are Low, Medium, High, and Ultra. <br />&nbsp;&nbsp;&nbsp;Low is the lowest resolution and should be used if you have many or complicated equations. <br />&nbsp;&nbsp;&nbsp;It takes less time to render. Ultra is the highest resolution. You will use this most often.<br />

                                     <strong>Equations</strong><br />

                                        * &nbsp;To graph an equation, use the numbers and operators on your keyboard <br />&nbsp;&nbsp;to type the equation in the y= field. <br />  &nbsp;&nbsp;&nbsp; <strong>Note:</strong> You can create exponents by using the caret. For example, 2x^2 is 2x2.<br />
                                        * &nbsp;You cannot customize the line color of the resulting graph.<br />  
                                        * &nbsp;To graph more than one equation, click the blue + button. You may add as many equations<br /> &nbsp;&nbsp;as necessary. Each new equation will be assigned a different color.<br />
                                        * &nbsp;You cannot remove an equation box after it's been added. If you do not wish to graph the<br /> &nbsp;&nbsp;equation, you must clear the equation typed in that field.<br />
                                        * &nbsp;After you have entered your equation(s), click the blue Evaluate button. <br />&nbsp;&nbsp;The graphs will appear on the coordinate plane to the left.<br />
                                       </span>
                                            </a> </span></li><?php } 
                                
                                if($widgetid == "4"){?>
                                        <li style="margin-bottom:0px;"> <span style="float:left;width:90%;"> <a href="#openModal4">Scientific Calculator</a> </span> <span style="float:right;width:10%; font-family: Times New Roman, Times, serif;"> <a id="tooltip4" style="padding:5px 0px ; text-align:center">?<span>
                                        <strong style="text-align:center; font-size:18px;">Scientific Calculator</strong><br />
                                        <strong>Movement</strong><br />
                                        * &nbsp;You may drag the Scientific Calculator widget anywhere on he screen.<br />
                                        * &nbsp;To close the Scientific Calculator widget, click the red X in the upper-left corner.<br />
                                        <strong>Buttons</strong><br />
                                        * &nbsp;To use the Scientific Calculator widget, you must click the buttons on the widget.<br />&nbsp;&nbsp;You cannot press the number and operator keys on your keyboard.<br />
                                        * Red Buttons:<br />
                                        &nbsp;&nbsp;  - Click the left-facing arrow to backspace and remove one digit at a time.<br />
                                        &nbsp;&nbsp;  - Click CE to clear your last entry.<br />
                                        &nbsp;&nbsp;  - Click AC to clear everything you've entered and start over.<br />
                                        * Arrows:<br />
                                        &nbsp;&nbsp;  - You can use these to calculate equations with more than one part. For example, for (5 + 2)(4 + 3), click 5, +, 2, =, <br />&nbsp;&nbsp;&nbsp;and then the down arrow. Then, click 4, +, 3, and =. Next, click *, the up<br />&nbsp;&nbsp;&nbsp; arrow, and then =. You will get the correct answer of 49. <br />
                                        * Functions<br />
                                        &nbsp;&nbsp;  - To use the function buttons, click a number and then the function button. <br />&nbsp;&nbsp;&nbsp;You do not need to click =. For example, to calculate 32, <br />&nbsp;&nbsp;&nbsp;click 3 and then x2. You will get the correct answer of 9.<br />
                                        &nbsp;&nbsp;  - To access more functions, click the large green drop-down arrow in the <br />&nbsp;&nbsp;&nbsp;upper-right area of the Scientific Calculator.<br />

                                        * &nbsp;Click the +/- button to change the number from positive to negative or from <br />&nbsp;&nbsp;negative to positive.<br />

                                <strong>Note:</strong> NaN means "not a number." This appears if you click an incorrect sequence<br /> &nbsp;&nbsp;of buttons.<br />


                                </span>
                                        </a> </span> </li><?php }
                                             }
                            }
                                ?>
                                    
                            </ul>
                        </li>
                    </ul> 
                </div>     
                
            </div>
<?php
           }// Widget Ind ends
   } // Condent based check ends
}// Student based check ends
	@include("footer.php");