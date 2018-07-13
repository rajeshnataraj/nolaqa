<?php 
	@include("table.class.php");
	$questionid=isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
?>
<script type="text/javascript">
	var AScgiloc = '../../tiny_mce/php/svgimg.php';
	var AMTcgiloc = "../../cgi-bin/mathtex.cgi";
</script>
<style type="text/css">
p {
	margin: 0;
	float: left;	
	width: 100%;
}
.hstyle {
    color: #49708a;
}
</style>
<link href='../../css/imports.css' rel='stylesheet' type="text/css" />
<link href='../../css/question-student.css' rel='stylesheet' type="text/css" />

<?php 

$qryquesdetails = $ObjDB->QueryObject("SELECT fld_question AS question, fld_answer_type AS answertypeid FROM itc_question_details WHERE fld_id='".$questionid."'");

if($qryquesdetails->num_rows>0){
	$rowquesdetails = $qryquesdetails->fetch_assoc();
	extract($rowquesdetails);
}
?>
<div id="qview" style="margin-bottom:20px; font-size: 3rem;">
<div class='row rowspacer'>
    <div class='twelve columns' id="qtestassign">
        <?php echo $question; ?>
    </div>
</div>
<script>
     $("#qtestassign").find("a").attr("style","");
     $("#qtestassign").find("p").attr("style","");
     $("#qtestassign").find("span").attr("style","");
     $("#qtestassign").find("div").attr("style","");
     $("#qtestassign").find("table").attr("style","");
     $("#qtestassign").find("strong").attr("style","");
     
     $("#qtestassign").find("li").attr("style","");
     $("#qtestassign").find("ul").attr("style","");
     $("#qtestassign").find("h1").attr("style","");
     $("#qtestassign").find("h2").attr("style","");
     $("#qtestassign").find("h3").attr("style","");
     $("#qtestassign").find("h4").attr("style","");
     $("#qtestassign").find("h5").attr("style","");
     $("#qtestassign").find("h6").attr("style","");
     
     $("#qtestassign").find("a").removeClass();
     $("#qtestassign").find("p").removeClass();
     $("#qtestassign").find("span").removeClass();
     $("#qtestassign").find("div").removeClass();
     $("#qtestassign").find("table").removeClass();
     $("#qtestassign").find("strong").removeClass();
     
     $("#qtestassign").find("li").removeClass();
     $("#qtestassign").find("ul").removeClass();
     $("#qtestassign").find("hi").removeClass();
     $("#qtestassign").find("h2").removeClass();
     $("#qtestassign").find("h3").removeClass();
     $("#qtestassign").find("h4").removeClass();
     $("#qtestassign").find("h5").removeClass();
     $("#qtestassign").find("h6").removeClass();
     
     $("#qtestassign").find("a").addClass("hstyle");
     $("#qtestassign").find("p").addClass("hstyle");
     $("#qtestassign").find("span").addClass("hstyle");
     $("#qtestassign").find("div").addClass("hstyle");
     $("#qtestassign").find("table").addClass("hstyle");
     $("#qtestassign").find("strong").addClass("hstyle");
     
     $("#qtestassign").find("li").addClass("hstyle");
     $("#qtestassign").find("ul").addClass("hstyle");
     $("#qtestassign").find("h1").addClass("hstyle");
     $("#qtestassign").find("h2").addClass("hstyle");
     $("#qtestassign").find("h3").addClass("hstyle");
     $("#qtestassign").find("h4").addClass("hstyle");
     $("#qtestassign").find("h5").addClass("hstyle");
     $("#qtestassign").find("h6").addClass("hstyle");     
     
   
</script>    
<div class='row rowspacer'>
    <div class='twelve columns'>
        <?php 
        /*--- Multiple Choise ---*/
        if($answertypeid == 1) // Multiple Choice 
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
            ?>
            <div id="c_b">
                <?php 
                for($i=0;$i<sizeof($anschoices);$i++){
                ?>
                <div class="row rowspacer"> 
                `	<div class="one columns" style="width:15px;"> 
                        <input type="checkbox" name="mulchoice" id="mulchoice<?php echo ($i+1);?>" value="<?php echo ($i+1);?>" />
                    </div>     	
                    <div class="one columns" style="width:30px;"><label for="mulchoice<?php echo ($i+1);?>"><?php echo $alphabet[$i]; ?>.</label></div>
                    <div class="eleven columns" style="margin-left:1%;">
                        <label for="mulchoice<?php echo ($i+1);?>"><?php echo preg_replace("/(\r\n){3,}/","\r\n\r\n",trim($anschoices[$i]));?></label>
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
            $qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
										GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
										GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
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
                    <div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="" /></div>
                    <div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix; ?></span></div>
                </div>
            </div>		
        <?php	
        } // Single Answer if ends	
        
        /*--- Match the following id=3 ---*/ 
        if($answertypeid == 3 ) // Match the following
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
            
            for($i=0;$i<sizeof($prefixarray);$i++){ 
            ?>
                <div class="row">
                    <div class='eight columns'>	
                        <div class="outer-input-sym"><span class="ques-symbol" style="font-size: 20px;margin-right: 20px;"><?php echo $prefixarray[$i]; ?></span></div>
                        <div class="outer-input-txt"><input type="text" class="ques-input qit-medium" id="ans<?php echo $i;?>" value="" placeholder="Answer" /></div>
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
            $answer = $ObjDB->SelectSingleValue("SELECT fld_answer 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='6' AND fld_flag='1'");			
            $answer = explode(',',$answer);	
            $values = $ObjDB->SelectSingleValue("SELECT fld_answer 
												FROM itc_question_answer_mapping 
												WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_attr_id='7' AND fld_flag='1'");
            $values = explode(',',$values);	
            
            $j=0;
            $count=0;
             $anspattern = '';
             for($i=0;$i<sizeof($answer);$i++){
                if($answer[$i] == 5){
                    echo '<div class="outer-label"><span id="lab_'.$values[$j].'">'.$values[$j].'</span></div>';
                }
                else {
                    echo $ObjDB->SelectSingleValue("SELECT fld_html_code FROM itc_question_answer_pattern_master WHERE fld_id='".$answer[$i]."'");								
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
            ?> 
                
            <script language="javascript" type="text/javascript">	
				var j=1;
                $('input#txt').each(function(){	                
                    $(this).attr('id','txt_'+j);					
                    j++;
                });
				
				$('input').autoGrowInput({
                    comfortZone: 10,
                    maxWidth: 200
                });
             </script>
        <?php
        }
        
        /*--- Answer choice id=5 ---*/
        if($answertypeid == 5)
        {
            $qry = $ObjDB->QueryObject("SELECT fld_answer AS answer 
										FROM itc_question_answer_mapping 
										WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1' AND fld_attr_id='1' 
										ORDER BY fld_boxid ASC ");
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
                            <input type="radio" id="rightans" name="yesorno" value="1" />
                       </td>
                       <td>
                            <label style="font-size:1.5em" for="rightans"><?php echo $answerarray[0]; ?></label>
                       </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <input type="radio" id="wrongans" name="yesorno" value="2" />
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
            ?>
            <div class="row rowspacer">
                <div class="six columns" align="center">
                    <b>Means</b><br />
                    <input type="text" name="mean1" id="mean1" value="" class="mix-input" />&nbsp;
                    <input type="text" name="mean2" id="mean2" value="" class="mix-input" />
                </div>
                <div class="six columns" align="center">
                    <b>Extremes</b> <br />
                    <input name="ext1" type="text" class="mix-input" id="ext1" value="" />&nbsp;
                    <input type="text" name="ext2" class="mix-input" id="ext2" value="" />
                </div>
            </div>		
            <?php	
        }
        
        /*--- Single Range id=7 ---*/
        
        if($answertypeid == 7 ) // Single Range
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
            <div class="row rowspacer">
                <div class="eight columns">
                    <div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix;?></span></div>
                    <div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="" /></div>
                    <div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix;?></span></div>
                </div>
            </div>
            <?php	
        }	// Single Range if ends	
        
        /*--- Mulitple choice image id=8 ---*/
        if($answertypeid==8) // Multiple Image 
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
            
            echo '<div class="row rowspacer"> <div id="c_b">';		
            for($i=0;$i<sizeof($anschoices);$i++){
                $imgid = $i + 1;
                
                if($anschoices[$i] != 'no-image.png'  && $anschoices[$i] != '') {
            ?>
                <div class="six columns" style="margin-left:1%;<?php if($i>1){ echo 'margin-top:30px;'; } ?>">
                    <div class="one columns">            		
                        <input type="checkbox" name="mulchoice" id="mulchoice<?php echo ($i+1);?>" value="<?php echo ($i+1);?>" />  
                    </div> 
                    <div class="one columns" style="width:15px;float:left;"><?php echo $alphabet[$i]; ?>.</div>
                    <div style="float:left;margin-left:5%;margin-top:2%;width:83%;">
                    <?php //Get image width
                        list($width,$height) = getimagesize( __CNTANSIMGPATH__.$anschoices[$i]);							
                    ?>
                        <img name="txtimageans<?php echo $imgid; ?>" id="txtimageans<?php echo $imgid; ?>" src="thumb.php?src=<?php echo  __CNTANSIMGPATH__.$anschoices[$i]; if($width > 400){?>&w=400&h=400&zc=2<?php }else{ echo "&w=".$width."&h=".$height."&zc=2"; } ?>" />					
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
            $qry = $ObjDB->QueryObject("SELECT GROUP_CONCAT(IF(fld_attr_id = '1', fld_answer, NULL) SEPARATOR ', ') AS 'choice', 
										GROUP_CONCAT(IF(fld_attr_id = '3', fld_answer, NULL) SEPARATOR '~') AS 'prefix', 
										GROUP_CONCAT(IF(fld_attr_id = '4', fld_answer, NULL) SEPARATOR '~') AS 'suffix' 
										FROM itc_question_answer_mapping WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_answer<>'' AND fld_flag='1'");
            while($row = $qry->fetch_assoc())
            {
                extract($row);
            }
            ?>
            <div class="row rowspacer">
                <div class="eight columns">
                    <div class="outer-input-sym"><span class="ques-symbol"><?php echo $prefix; ?></span></div>
                    <div class="outer-input-txt"><input class="ques-input qit-medium" type="text" id="txtsingleanswer" name="txtsingleanswer" value="" /></div>
                    <div class="outer-input-sym"><span class="ques-symbol"><?php echo $suffix; ?></span></div>
                </div>
            </div>
        <?php    
        }	// Single Multiple if ends	
        
        /*--- Drag & Drop id=10 ---*/	
        
        if($answertypeid == 10) // Drag & Drop
        {
            $filename = $ObjDB->SelectSingleValue("SELECT fld_answer AS answer 
												  FROM itc_question_answer_mapping 
												  WHERE fld_quesid='".$questionid."' AND fld_ans_type='".$answertypeid."' AND fld_flag='1'");
            ?>
            <div class='eight columns' style="height:90px; ">
                <div class='row'>
                    <span class="qq-upload-file" id="webfilename"><?php echo $filename; ?></span>
                    <input type="button" id="btnlibrary-questions-preview" value="Preview" class="mainBtn draganddrop" name="<?php echo $filename.",drag";?>" align="right" />
                </div>
            </div>
            <?php 
        } // Drag & Drop if ends
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
    </script>
    <input type="hidden" id="answertypeid" value="<?php echo $answertypeid; ?>" />
    <input type="hidden" id="boxcount" value="<?php echo $count; ?>" />
    <input type="hidden" name="answer" id="answer"/>
    <input type="hidden" name="orientationflag" id="orientationflag" value="<?php echo $ObjDB->SelectSingleValueInt("SELECT a.fld_lesson_type FROM itc_ipl_master AS a LEFT JOIN itc_question_details AS b ON a.fld_id=b.fld_lesson_id WHERE b.fld_id='".$questionid."' LIMIT 0,1"); ?>"/>
    </div>
</div>
</div>
<?php
	@include("footer.php");