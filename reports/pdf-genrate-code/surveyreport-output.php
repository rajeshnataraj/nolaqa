<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
?>
<style>
	.title
	{
		font-size: 50px; color:#808080; font-family:Arial;
	}
	.trgray
	{
		font-size:40px; background-color:#CCCCCC; font-weight:bold; border-top:1px solid #000; border-bottom:1px solid #000; 
	}
	.trclass
	{
		font-size:30px; background-color:#FFFFFF; font-weight:normal;
	}
	.tdleft{
		border-top:1px solid #b4b4b4; border-left:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdmiddle{
		border-top:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdright{
		border-top:1px solid #b4b4b4; border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
	
	.tdrighthead{
		border-right:1px solid #b4b4b4; border-bottom:1px solid #b4b4b4;
	}
</style>
<?php 
$sessids[] ='';
if($id[4]==4 || $id[4]==6)
	$newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM itc_mathmodule_master WHERE fld_id='".$id[3]."'");
else
	$newmodid = $id[3];
	
$moduleversion = $ObjDB->SelectSingleValue("SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id='".$newmodid."' AND fld_delstatus='0'");


$qryschedules = $ObjDB->QueryObject("SELECT fld_session_id, fld_question_text, fld_question_id 
									FROM itc_module_answer_track 
									WHERE fld_tester_id='".$id[1]."' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[2]."' 
										AND fld_schedule_type='".$id[4]."' AND fld_page_id=0 AND fld_delstatus='0' ORDER BY fld_session_id");

$count=0;

if($qryschedules->num_rows > 0)
{ 
	while($rowschedules=$qryschedules->fetch_assoc()){
		extract($rowschedules);
		
		$sessid[$count] = $fld_session_id;
		$qtext[$count] = $fld_question_text;
		$qid[$count] = $fld_question_id;
		$count++;
	}
	$sessids = array_unique($sessid);
}
else
{ 
	?>
    <table>
        <tr>
            <td style="color:#F00">The student has no Knowledge Survey scores associated with this assignment.</td>
        </tr>
	</table><?php
} 
if($qryschedules->num_rows > 0)
{
	
	$sesscount = 0;
	for($a=0;$a<sizeof($sessid);$a++)
	{
		if(isset($sessids[$a]) !='')
		{
			$sessionid[$sesscount]=$sessid[$a];
			$sesscount++;
		}
	}
	
	for($b=0;$b<sizeof($sessionid);$b++)
	{
		$sessions = $sessionid[$b];
		$sessions++;
		?>
        <?php if($b!=0) { ?>
        <div style="page-break-before: always;">
        <?php }?>
		<table cellpadding="4" cellspacing="0">
            <thead>
                <tr class="trgray"> 
                    <td><?php if($sessions == 1){ echo "Module Guide"; } else if($sessions == 7) { echo "Post Test"; } else { echo "RCA ".$sessions; } ?></td> 
                </tr>
            </thead> 
		
			<?php
            $i=1;
            for($c=0;$c<sizeof($qid);$c++)
            {
                if($sessid[$c] == $sessionid[$b])
                {
                    $ansqry = $ObjDB->QueryObject("SELECT fld_answer_text, fld_correct, fld_answer_id 
                                                    FROM itc_module_quesanswer 
                                                    WHERE fld_module_id='".$newmodid."' AND fld_question_id='".$qid[$c]."' AND fld_module_version='".$moduleversion."' AND fld_delstatus='0' 
                                                    GROUP BY fld_answer_id"); 
                    
                    $qryanswerstu = $ObjDB->QueryObject("SELECT fld_answer_option, fld_answer_option1, fld_correct, fld_attempts 
                                                        FROM itc_module_answer_track 
                                                        WHERE fld_tester_id='".$id[1]."' AND fld_module_id='".$id[3]."' AND fld_schedule_id='".$id[2]."' AND fld_schedule_type='".$id[4]."' 
                                                            AND fld_page_id=0 AND fld_delstatus='0' AND fld_question_id='".$qid[$c]."'");
                    
                    if($ansqry->num_rows > 0)
                    {
                        $cnt=0;
                        while($rowansqry=$ansqry->fetch_assoc()){
                            extract($rowansqry);
                            $correctanswer[$cnt] = $fld_answer_text;
                            $correct[$cnt] = $fld_correct;
                            $answerids[$cnt] = $fld_answer_id;
                            $cnt++;
                        }
                    }
                    
                    if($qryanswerstu->num_rows > 0)
                    { 
                        while($rowanswerstu=$qryanswerstu->fetch_assoc()){
                            extract($rowanswerstu);
                            
                            $answerstudent1 = $fld_answer_option;
                            $answerstudent2 = $fld_answer_option1;
                            $studentcorrect = $fld_correct;
                            $studentattempts = $fld_attempts;
                        }
                    }
                    if($answerstudent1=='A')
                        $anstextcount = '0';
                    else if($answerstudent1=='B')
                        $anstextcount = '1';
                    else if($answerstudent1=='C')
                        $anstextcount = '2';
                    else if($answerstudent1=='D')
                        $anstextcount = '3';
                    
                    if($answerstudent2=='A')
                        $anstextcount1 = '0';
                    else if($answerstudent2=='B')
                        $anstextcount1 = '1';
                    else if($answerstudent2=='C')
                        $anstextcount1 = '2';
                    else if($answerstudent2=='D')
                        $anstextcount1 = '3';
                    ?>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"><?php echo $i."."; ?></td>
                        <td style="font-weight:bold; width:94%"><?php echo $qtext[$c]; ?></td>
                    </tr>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"></td>
                        <td style="width:94%; <?php if($correct[0]==1) { ?> color:#090; font-weight:bold;<?php }?>"><b>&nbsp;&nbsp;&nbsp;&nbsp;A: </b><?php echo $correctanswer[0]; ?></td>
                    </tr>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"></td>
                        <td style="width:94%; <?php if($correct[1]==1) { ?> color:#090; font-weight:bold;<?php }?>"><b>&nbsp;&nbsp;&nbsp;&nbsp;B: </b><?php echo $correctanswer[1]; ?></td>
                    </tr>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"></td>
                        <td style="width:94%; <?php if($correct[2]==1) { ?> color:#090; font-weight:bold;<?php }?>"><b>&nbsp;&nbsp;&nbsp;&nbsp;C: </b><?php echo $correctanswer[2]; ?></td>
                    </tr>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"></td>
                        <td style="width:94%; <?php if($correct[3]==1) { ?> color:#090; font-weight:bold;<?php }?>"><b>&nbsp;&nbsp;&nbsp;&nbsp;D: </b><?php echo $correctanswer[3]; ?></td>
                    </tr>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"></td>
                        <td style="width:94%; color:<?php if($studentcorrect==1 && $studentattempts==1) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answer : </b><?php echo $correctanswer[$anstextcount]; ?></td>
                    </tr>
                    <?php if($fld_answer_option1!=''){ ?>
                    <tr nobr="true">
                        <td style="font-weight:bold; width:6%"></td>
                        <td style="width:94%; color:<?php if($studentcorrect==1 && $studentattempts==2) { ?>#090<?php } else {?>#F00<?php }?>"><b>Student Answer 2 : </b><?php echo $correctanswer[$anstextcount1]; ?></td>
                    </tr>
                    <?php }
                    $i++;			
                }
            }
            ?>
		</table>  
		<?php if($b < sizeof($sessionid)-1) {?>			
		<?php }
	}
}