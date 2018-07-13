<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
$classid = $id[2];
$studentid = $id[1];
?>
<style>
	.title
	{
		font-size: 50px; color:#808080; font-family:Arial;
	}
	.trgray
	{
		font-size:30px; background-color:#CCCCCC; font-weight:normal; 
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
</style>
<?php 
$qryassement = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_test_name, a.fld_score AS pointspossible, a.fld_total_question, a.fld_question_type AS testtype 
									FROM itc_test_master AS a 
									LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
									WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_student_id='".$studentid."' 
										AND b.fld_class_id='".$classid."' AND b.fld_flag='1' AND a.fld_ass_type='0'"); 

$roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
										FROM itc_class_grading_scale_mapping 
										WHERE fld_class_id = '".$classid."' AND fld_flag = '1' 
										GROUP BY fld_roundflag");
$studentname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) AS studentname
											FROM itc_user_master 
											WHERE fld_id='".$studentid."'");

$qry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_score AS score, a.fld_total_question, a.fld_question_type 
									FROM itc_test_master AS a 
									LEFT JOIN itc_test_student_mapping AS b ON a.fld_id=b.fld_test_id 
									WHERE a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_student_id='".$studentid."' 
										AND b.fld_class_id='".$classid."' AND b.fld_flag='1'  AND a.fld_ass_type='0'");
$totalpoints = '';
$totalearned = '';
while($rowqry = $qry->fetch_object())
{
        $pointsearned = '';
	$pointspossible = $rowqry->score;
	$totalques = $rowqry->fld_total_question;
        $testtype = $rowqry->fld_question_type;
        
        $qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                    FROM itc_test_student_answer_track 
                                                    WHERE fld_student_id='".$studentid."' 
                                                            AND fld_test_id='".$rowqry->fld_id."' 
                                                            AND fld_delstatus='0'");
        
	$teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                        FROM itc_test_student_mapping 
                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$rowqry->fld_id."' AND fld_flag='1' AND fld_class_id='".$classid."'");
        
	if($teacherpoint==='')
	{	
            if($testtype == '1')
            {
		$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                FROM itc_test_student_answer_track 
                                                                WHERE fld_student_id='".$studentid."' AND fld_test_id='".$rowqry->fld_id."' 
                                                                AND fld_correct_answer='1' AND fld_delstatus='0'");
                
		$pointsearned = round(($correctcount/$totalques)*$pointspossible,2);
            }
            else if($testtype == '2')
            {
                $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                        FROM itc_test_random_questionassign
                                                        WHERE fld_rtest_id='".$rowqry->fld_id."' AND fld_delstatus='0' 
                                                        ORDER BY fld_order_by");
                if($qryrandomtest->num_rows>0)
                {
                    while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                    {
                        extract($rowqryrandomtest);

                        $perscore = ($percent / 100)*$pointspossible;

                        $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                        FROM itc_test_student_answer_track 
                                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$rowqry->fld_id."' AND fld_tag_id='".$testtagid."'
                                                                                AND fld_correct_answer='1' AND fld_delstatus='0'");

                        $pointsearned = $pointsearned + round($correctcount*($perscore/$totques));
                    }
                }
            }
	}
	else
	{
		$pointsearned = $teacherpoint;
	}
	
        if($pointsearned!='')
		$totalearned = $totalearned+$pointsearned;
        else
		$totalearned = $totalearned;
        
        if($qcount!=0)
	{
		$totalpoints = $totalpoints+$pointspossible;
		if($roundflag==0)
			$percentage = round(($totalearned/$totalpoints)*100,2);
		else
			$percentage = round(($totalearned/$totalpoints)*100);
		
		$perarray = explode('.',$percentage);
		
		$grade = $ObjDB->SelectSingleValue("SELECT fld_grade FROM itc_class_grading_scale_mapping WHERE fld_class_id = '".$classid."' AND fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' AND fld_flag = '1'");
		
		$earnedtotal = round($totalearned,2);
		$pointstotal = $totalpoints;
	}
	else
	{
		if($totalearned=='')
		{
			$pointstotal = " - ";
			$earnedtotal = " - ";
			$percentage = " - ";
			$grade = " N/A ";
		}
	}
}	
?>
<table cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:70%;">Student : <?php echo $studentname; ?></td>
        <td style="width:30%;" align="center">
            <table>
                <tr style="font-size:35px; font-weight:bold">
                    <td><?php echo $grade;?></td>
                </tr>
                <tr>
                    <td><?php echo $percentage." % (".$earnedtotal." / ".$pointstotal.")";?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0" >
	<tr style="font-size:35px; font-weight:bold">
		<th style="width:25%;">Assessment Name</th>
		<th style="width:24%;">Points Earned</th>
		<th style="width:24%;">Points Possible</th>
		<th style="width:18%;">Percentage</th>
		<th style="width:10%;">Grade</th>
	</tr>

	<tbody>
	<?php
	if($qryassement->num_rows > 0)
	{ 	 
		$cnt=0;
                $pointsearned = '';
		while($row=$qryassement->fetch_assoc())
		{
			$grade='';
			extract($row);
			$qcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                    FROM itc_test_student_answer_track 
                                                                    WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' AND fld_delstatus='0'");
														
			$teacherpoint = $ObjDB->SelectSingleValue("SELECT fld_teacher_points_earned 
                                                                        FROM itc_test_student_mapping 
                                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' AND fld_flag='1' AND fld_class_id='".$classid."'");
			if($teacherpoint=='')
			{
                            if($testtype == '1')
                            {
				$correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
															FROM itc_test_student_answer_track 
															WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' AND fld_correct_answer='1' AND fld_delstatus='0'");
				$pointsearned = round(($correctcount/$fld_total_question)*$pointspossible,2);				
                            }
                            else if($testtype == '2')
                            {
                                $qryrandomtest = $ObjDB->QueryObject("SELECT fld_id AS testtagid, fld_pct_section AS percent, fld_qn_assign AS totques
                                                                        FROM itc_test_random_questionassign
                                                                        WHERE fld_rtest_id='".$fld_id."' AND fld_delstatus='0' 
                                                                        ORDER BY fld_order_by");
                                if($qryrandomtest->num_rows>0)
                                {
                                    while($rowqryrandomtest = $qryrandomtest->fetch_assoc())
                                    {
                                        extract($rowqryrandomtest);

                                        $perscore = ($percent / 100)*$pointspossible;

                                        $correctcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_correct_answer) 
                                                                                        FROM itc_test_student_answer_track 
                                                                                        WHERE fld_student_id='".$studentid."' AND fld_test_id='".$fld_id."' AND fld_tag_id='".$testtagid."'
                                                                                                AND fld_correct_answer='1' AND fld_delstatus='0'");

                                        $pointsearned = $pointsearned + round($correctcount*($perscore/$totques));
                                    }
                                }
                            }
                            $showcount = $qcount;
			}
			else
			{
				$pointsearned = $teacherpoint;
				$showcount = 1;
			}
			
			if($showcount==0)
			{
				$pointsearned = "-";
				$pointspossible = "-";
				$percentage = "-";
				$grade = "NA";
			}
			else
			{
				if($roundflag==0)
					$percentage = round(($pointsearned/$pointspossible)*100,2);
				else
					$percentage = round(($pointsearned/$pointspossible)*100);

				$perarray = explode('.',$percentage);
				$grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
													FROM itc_test_grading_scale_mapping 
													WHERE fld_test_id='".$fld_id."' AND fld_flag='1' 
														AND fld_lower_bound<='".$perarray[0]."' AND fld_upper_bound>='".$perarray[0]."'");				
			}
			
			?>
			<tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
				<td class="tdleft"><?php echo $fld_test_name; ?></td>
				<td class="tdmiddle"><?php echo $pointsearned; ?></td>
				<td class="tdmiddle"><?php echo $pointspossible; ?></td>
				<td class="tdmiddle"><?php echo $percentage." %"; ?></td>
				<td class="tdright"><?php echo $grade; ?></td>
			</tr>
			<?php
			if($cnt==0)
				$cnt=1;
			else if($cnt==1)
				$cnt=0;
		}
	}
	else
	{ ?>
		<tr class="trgray">
			<td style="border-top:1px solid #b4b4b4; border:1px solid #b4b4b4;" colspan="5">No records</td>
		</tr>
	<?php 
	} ?>
	</tbody>
</table>