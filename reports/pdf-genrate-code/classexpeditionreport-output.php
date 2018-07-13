<?php 
@include("table.class.php");
@include("comm_func.php");
$metdod = $_REQUEST;

$id = isset($metdod['id']) ? $metdod['id'] : '0';
$id = explode(",",$id);
?>
<style>
	.title
	{
		font-size: 50px; font-weight:bold; font-family:Arial;
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
$startdate = date('Y-m-d',strtotime($id[3]));
$enddate = date('Y-m-d',strtotime($id[4]));
$sqry = "AND (fld_created_date BETWEEN '".$startdate."' AND '".$enddate."' OR fld_updated_date BETWEEN '".$startdate."' AND '".$enddate."')";
$sqry1 = "AND (a.fld_created_date BETWEEN '".$startdate."' AND '".$enddate."' OR a.fld_updated_date BETWEEN '".$startdate."' AND '".$enddate."')";

$qryexp = $ObjDB->QueryObject("SELECT a.fld_exp_name AS expname, a.fld_id AS expid FROM itc_exp_master AS a LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_exp_id WHERE b.fld_id='".$id[2]."'");
$rowqryexp=$qryexp->fetch_assoc();
extract($rowqryexp);

$qrystudents = $ObjDB->QueryObject("SELECT CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname, a.fld_id AS studentid 
                                            FROM itc_user_master AS a 
                                            LEFT JOIN itc_class_exp_student_mapping AS b ON a.fld_id=b.fld_student_id 
                                            WHERE a.fld_activestatus='1' AND a.fld_delstatus='0' 
                                            AND b.fld_schedule_id='".$id[2]."' AND b.fld_flag='1' 
                                            ORDER BY a.fld_lname");

if($qrystudents->num_rows > 0)
{ 
    ?>
    <table cellpadding="2" cellspacing="0">
        <tr style="font-size:35px; font-weight: bold">
            <td style="width:16%;">
                <table>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Student</td>
                    </tr>
                </table>
            </td>
            <td style="width:22%;" align="center">
                <table>
                    <tr>
                        <td colspan="2">Performance Assessment</td>
                    </tr>
                    <tr>
                        <td>Points Earned</td>
                        <td>Points Possible</td>
                    </tr>
                </table>
            </td>
            <td style="width:20%;" align="center">
                <table>
                    <tr>
                        <td colspan="2">Post Test</td>
                    </tr>
                    <tr>
                        <td>Points Earned</td>
                        <td>Points Possible</td>
                    </tr>
                </table>
            </td>
            <td style="width:20%;" align="center">
                <table>
                    <tr>
                        <td colspan="2">Total Points</td>
                    </tr>
                    <tr>
                        <td>Points Earned</td>
                        <td>Points Possible</td>
                    </tr>
                </table>
            </td>
            <td style="width:12%;">
                <table>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Percentage</td>
                    </tr>
                </table>
            </td>
            <td style="width:10%;">
                <table>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2">Grade</td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php
        $cnt=0;
        while($rowqrystudents=$qrystudents->fetch_assoc())
        {
                extract($rowqrystudents);	

                for($i=2;$i<4;$i++) {
                        $qrypoints = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_exptype AS exptype, a.fld_pointspossible AS possiblepoint, 
                                                            (SELECT fld_teacher_points_earned 
                                                            FROM itc_exp_points_master 
                                                            WHERE fld_schedule_type='15' AND fld_student_id='".$studentid."' 
                                                                    AND fld_exp_id='".$expid."' AND fld_schedule_id='".$id[2]."' AND fld_exptype='".$i."' ".$sqry.") AS pointsearned 
                                                            FROM itc_class_exp_grade AS a
                                                            WHERE a.fld_exp_id='".$expid."'
                                                                AND a.fld_class_id='".$id[1]."' AND a.fld_schedule_id='".$id[2]."' 
                                                                AND a.fld_flag='1' AND a.fld_exptype='".$i."'
                                                            ORDER BY a.fld_exptype");

                        if($qrypoints->num_rows>0)
                        {
                                while($rowqry = $qrypoints->fetch_assoc())
                                {
                                        extract($rowqry);

                                        if($i==3 and $pointsearned=='')
                                        {
                                                $qryques = $ObjDB->QueryObject("SELECT IFNULL(b.fld_total_question,'-') AS quescount, COUNT(a.fld_id) AS correctcount FROM itc_test_student_answer_track AS a LEFT JOIN itc_test_master AS b ON a.fld_test_id=b.fld_id WHERE b.fld_expt='".$expid."' AND a.fld_student_id='".$studentid."' AND b.fld_delstatus='0' AND a.fld_show='1' AND a.fld_delstatus='0' ".$sqry1."");

                                                if($qryques->num_rows>0)
                                                {
                                                        $rowqryques = $qryques->fetch_assoc();
                                                        extract($rowqryques);

                                                        if($quescount==='-')
                                                                $pointsearned = '';
                                                        else
                                                                $pointsearned = round($correctcount*($possiblepoint/$quescount),2);
                                                }
                                        }
                                        $earned[$i] = $pointsearned;
                                        $possible[$i] = $possiblepoint;
                                }
                        }
                }
                $totalpossible = '';
                if($earned[2]!='' or $earned[3]!='')
                {
                    $totalearned = $earned[2]+$earned[3];
                    if($earned[2]!='')
                        $totalpossible = $totalpossible+$possible[2];
                    
                    if($earned[3]!='')
                        $totalpossible = $totalpossible+$possible[3];

                    $percentage = round(($totalearned/$totalpossible)*100,2);
                    $perarray = explode('.',$percentage);
                    $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
                                                        FROM itc_class_grading_scale_mapping 
                                                        WHERE fld_lower_bound <= '".$perarray[0]."' AND fld_upper_bound >= '".$perarray[0]."' 
                                                                AND fld_class_id='".$id[1]."' AND fld_flag='1'");
                }
                else
                {
                    $totalearned = "-";
                    $totalpossible = "-";
                    $percentage = "-";
                    $grade = "N/A";
                }
                if($earned[2]=='')
                {
                    $earned[2] = "-";
                    $possible[2] = "-";
                }
                if($earned[3]=='')
                {
                    $earned[3] = "-";
                    $possible[3] = "-";
                }
                ?>
                <tbody>			
                    <tr class="<?php if($cnt==0) { ?>trgray<?php } else if($cnt==1) { ?>trclass<?php }?>">
                        <td class="tdleft"><?php echo $studentname; ?></td>
                        <td class="tdmiddle" align="center">
                            <table>
                                <tr>
                                    <td><?php echo $earned[2]; ?></td>
                                    <td><?php echo $possible[2]; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td class="tdmiddle" align="center">
                            <table>
                                <tr>
                                    <td><?php echo $earned[3]; ?></td>
                                    <td><?php echo $possible[3]; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td class="tdmiddle" align="center">
                            <table>
                                <tr>
                                    <td><?php echo $totalearned; ?></td>
                                    <td><?php echo $totalpossible; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td class="tdmiddle" align="center"><?php echo $percentage." %"; ?></td>
                        <td class="tdright" align="center"><?php echo $grade; ?></td>
                    </tr>
                    <?php
                    if($cnt==0)
                            $cnt=1;
                    else if($cnt==1)
                            $cnt=0;
        }
        ?>
        </table>
        <?php
}
else
{ ?>
        <tr class="trgray">
                <td style="border:1px solid #b4b4b4;" colspan="5">No Records</td>
        </tr>
<?php 
} ?>    