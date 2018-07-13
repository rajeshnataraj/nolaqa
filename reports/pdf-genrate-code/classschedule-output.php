<?php 
@include("table.class.php");
@include("comm_func.php");
$method = $_REQUEST;

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);

function imagetext($text,$imnum,$size)
{
	// Set the content-type
	header('Content-Type: image/png');
	
	// Create the image
	$im = imagecreatetruecolor(30, $size);
	
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 30, 300, $white);
	
	// The text to draw
	$text = $text;
	// Replace path by your own font path
	$font = 'fonts/02520_Arial2.ttf';
	
	// Add some shadow to the text	
	
	// Add the text
	imagettftext($im, 9, 90, 20,$size, $black, $font, $text);
	
	// Using imagepng() results in clearer text compared with imagejpeg()	
	
	$directory = '../../img/image-classschedulereport'.$imnum.".png";
	// this will save your image 
	imagepng($im, $directory, 0, NULL);
	
	imagedestroy($im);
}

if($id[3]==0)
{
	$imagename = "Orientation";
}
else 
{
	$imagename = "Rotation ".$id[3];
}

imagetext("Grade",20,160); 
imagetext("Percentage",21,160); 
imagetext("Attendance",0,160); 
imagetext("Participation",1,160); 
imagetext("Module Guide",23,160);
imagetext("RCA 2",2,160);
imagetext("RCA 3",3,160);
imagetext("RCA 4",4,160);
imagetext("RCA 5",5,160);
imagetext("Post Test",6,160);
imagetext("Performance assessment1",7,160);
imagetext("Performance assessment2",8,160);
imagetext("Performance assessment3",9,160);
imagetext("Diagnostic Day1",10,160);
imagetext("Diagnostic Day2",11,160);
imagetext("Total",22,160);
imagetext($imagename,24,150);
?>
<style >
	.title
	{
		font-size: 50px; font-weight:bold; font-family:Arial;
	}
	.clsGrade
	{
		table-layout: fixed;
	}
	
	.clsGrade th, .clsGrade td
	{
		border: 1px solid black;
		text-align:center;
		vertical-align:middle;
	}
	
	.rotated_cell
	{
		height:150px;
		vertical-align:bottom
	}
	
	.rotate_text
	{
		-moz-transform:rotate(-90deg);
		-moz-transform-origin: top left;
		-webkit-transform: rotate(-90deg);
		-webkit-transform-origin: top left;
		-o-transform: rotate(-90deg);
		-o-transform-origin: top left;
		font-size:20px;
	}
</style>
<?php
	if($id[4]==1 or $id[4]==4)
	{
		$newrot = $id[3];
		$newrot++;
	}
	else
		$newrot = $id[3];	
	?>
    <span class="title"><?php if($newrot==0) echo "Orientation"; else echo "Rotation ".$id[3];?></span>
    <br />
	<table bordercolor="#000" cellpadding="2.5" cellspacing="0" border="1"   >
	    <tr>
	        <td style="border-top:none; border-left:none; width:20%" colspan="2">&nbsp;</td>
	        <td style="width:5%"><img src="../img/image-classschedulereport20.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport21.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport0.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport1.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport23.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport2.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport3.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport4.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport5.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport6.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport7.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport8.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport9.png" /></td>
	        <?php if($id[4]==4) {?>
	        <td style="width:5%"><img src="../img/image-classschedulereport10.png" /></td>
	        <td style="width:5%"><img src="../img/image-classschedulereport11.png" /></td>
	        <?php }?>
	        <td style="width:5%"><img src="../img/image-classschedulereport22.png" /></td>
	    </tr>

	    <tbody>
			<?php
	        $roundflag = $ObjDB->SelectSingleValue("SELECT fld_roundflag 
													FROM itc_class_grading_scale_mapping 
													WHERE fld_class_id = '".$id[2]."' AND fld_flag = '1' 
													GROUP BY fld_roundflag");
	        
			$qrysub = '';
			
	        if($id[4]==1)
	            $qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
							FROM itc_class_rotation_schedulegriddet AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id 
							WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
								AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
							GROUP BY a.fld_row_id ORDER BY modulename";
	        
	        else if($id[4]==2)
	            $qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
							FROM itc_class_dyad_schedulegriddet AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id
							WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
								AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
							GROUP BY a.fld_row_id ORDER BY modulename";
	        
	        else if($id[4]==3)
	            $qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
							FROM itc_class_triad_schedulegriddet AS a 
							LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON a.fld_module_id=c.fld_mod_id
							WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
								AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
							GROUP BY a.fld_row_id ORDER BY modulename";
	        
	        else if($id[4]==4)
	            $qrysub = "SELECT a.fld_row_id, a.fld_module_id, CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS modulename 
							FROM itc_class_rotation_schedulegriddet AS a 
							LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
							LEFT JOIN itc_module_version_track AS c ON b.fld_module_id=c.fld_mod_id
							WHERE a.fld_schedule_id ='".$id[1]."' AND a.fld_class_id ='".$id[2]."' AND a.fld_flag='1' 
								AND a.fld_rotation ='".$newrot."' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
							GROUP BY a.fld_row_id ORDER BY modulename";
	        
			$qrymodule = $ObjDB->QueryObject($qrysub);
			
	        if($qrymodule->num_rows>0)
	        {
	            while($rowmodule=$qrymodule->fetch_assoc())
	            {
	                extract($rowmodule);
	                if($id[4]==1 or $id[4]==4)
						$tablename = "itc_class_rotation_schedulegriddet";
	                
	                else if($id[4]==2)
					{
						$tablename = "itc_class_dyad_schedulegriddet";
						$stutablename = "itc_class_dyad_schedule_studentmapping";
					}
	                
	                else if($id[4]==3)
					{	
						$tablename = "itc_class_triad_schedulegriddet";
						$stutablename = "itc_class_triad_schedule_studentmapping";
					}
	                
					if($newrot==0)
						$qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
															FROM itc_user_master AS a 
															LEFT JOIN ".$tablename." AS b ON a.fld_id=b.fld_student_id 
															WHERE b.fld_schedule_id = '".$id[1]."' AND b.fld_flag='1' AND a.fld_delstatus='0' AND a.fld_activestatus='1'
															GROUP BY a.fld_id");
					
					else
						$qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS studentid, CONCAT(a.fld_fname,' ',a.fld_lname) AS studentname
															FROM itc_user_master AS a 
															LEFT JOIN ".$tablename." AS b ON a.fld_id=b.fld_student_id 
															WHERE b.fld_schedule_id = '".$id[1]."' AND b.fld_class_id = '".$id[2]."' 
																AND b.fld_rotation = '".$newrot."' AND b.fld_module_id = '".$fld_module_id."'  
																AND b.fld_row_id='".$fld_row_id."' AND b.fld_flag='1' AND a.fld_delstatus='0' 
																AND a.fld_activestatus='1'");
					
	                $count=$qrystudent->num_rows;
	                ?>
	                <tr>
	                <td rowspan="<?php echo $count;?>" style="width:10%"><?php echo $modulename;?></td>
	                <?php
	                $i=1;
	                if($qrystudent->num_rows>0)
	                {
	                    while($rowstudent=$qrystudent->fetch_assoc())
	                    {
	                        extract($rowstudent);
	                        
	                        if($id[4]==4)
	                        {
	                            $qryipl1ids = $ObjDB->QueryObject("SELECT fld_ipl_day1, fld_ipl_day2 FROM itc_mathmodule_master WHERE fld_id='".$fld_module_id."' AND fld_delstatus='0'");
								
								if($qryipl1ids->num_rows>0)
								{
									while($rowqryipl1ids=$qryipl1ids->fetch_assoc())
									{
										extract($rowqryipl1ids);
										$ipl1ids = $fld_ipl_day1;
										$ipl2ids = $fld_ipl_day2;
										$mathipls = $ipl1ids.",".$ipl2ids;
									}
								}
	                            
								$qrypoints = $ObjDB->QueryObject("SELECT SUM(w.earnedpoints) AS earnedpoints, SUM(w.pointspossible) AS pointspossible FROM (
																	(SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END) 
																		AS earnedpoints, SUM(fld_points_possible) AS pointspossible 
																	FROM itc_module_points_master 
																	WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' 
																		AND fld_schedule_type='4' AND fld_delstatus='0' AND fld_grade<>'0') 		
																			UNION ALL		
																	(SELECT ROUND(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END)/4) 
																		AS earnedpoints, ROUND(SUM(fld_points_possible)/4) AS pointspossible 
																	FROM itc_assignment_sigmath_master 
																	WHERE fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_module_id='".$fld_module_id."' AND fld_test_type='2' AND fld_class_id='".$id[2]."' AND fld_delstatus='0' 
                                                                                                                                            AND (fld_status='1' OR fld_status='2' OR fld_lock='1') AND fld_lesson_id IN (".$mathipls.")) 		
																) AS w");
	                            
								$qryiplvalues = $ObjDB->QueryObject("SELECT ROUND(SUM(CASE WHEN fld_lock='0' AND fld_lesson_id IN (".$ipl1ids.") THEN fld_points_earned WHEN fld_lock='1' 
																		AND fld_lesson_id IN (".$ipl1ids.") THEN fld_teacher_points_earned END)/4) AS pointsipl1, ROUND(SUM(CASE WHEN 
																		fld_lock='0' AND fld_lesson_id IN (".$ipl2ids.") THEN fld_points_earned WHEN fld_lock='1' 
																		AND fld_lesson_id IN (".$ipl2ids.") THEN fld_teacher_points_earned END)/4) AS pointsipl2  
																	FROM itc_assignment_sigmath_master 
																	WHERE fld_schedule_id='".$id[1]."'  AND fld_module_id='".$fld_module_id."' AND fld_student_id='".$studentid."' AND fld_test_type='2' AND fld_delstatus='0' ");
								
								if($qryiplvalues->num_rows>0)
								{
									while($rowqryiplvalues=$qryiplvalues->fetch_assoc())
									{
										extract($rowqryiplvalues);
										$day1 = $pointsipl1;
										$day2 = $pointsipl2;
									}
								}									
	                        }
	                        else
	                        {
	                            $qrypoints = $ObjDB->QueryObject("SELECT SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' 
																	THEN fld_teacher_points_earned END) AS earnedpoints, 
																	SUM(fld_points_possible) AS pointspossible, fld_grade AS grade 
																	FROM itc_module_points_master 
																	WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' 
																		AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' 
																		AND fld_delstatus='0' AND fld_grade<>'0'");
	                        }
	                        
	                        if($qrypoints->num_rows>0)
	                        {
	                            $rowpoints=$qrypoints->fetch_assoc();
	                            extract($rowpoints);
	                            
	                            if($earnedpoints!='')
	                            {
	                                if($roundflag==0)
	                                    $percentage = round(($earnedpoints/$pointspossible)*100,2);
	                                else
	                                    $percentage = round(($earnedpoints/$pointspossible)*100);	                                
	                                $perarray = explode('.',$percentage);
	                                $grade = $ObjDB->SelectSingleValue("SELECT fld_grade 
																		FROM itc_class_grading_scale_mapping 
																		WHERE fld_class_id='".$id[2]."' AND fld_lower_bound<='".$perarray[0]."' 
																			AND fld_flag='1' AND fld_upper_bound>='".$perarray[0]."'"); 
	                            }
	                            else
	                            {
	                                $earnedpoints = " - ";
	                                $percentage = " - ";
	                                $grade = " - "; 
	                            }
	                        }
	                        ?>
	                        <td style="width:10%" align="center"><?php echo $studentname;?></td>
	                        
	                        <td style="width:5%" align="center"><?php echo $grade;?></td>
	                        
	                        <td style="width:5%" align="center"><?php echo $percentage."%";?></td>
	                        
	                        <?php
	                        for($j=1;$j<3;$j++) {?>
	                            <td style="width:5%" align="center"><?php echo $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' AND fld_type='".$j."' AND fld_delstatus='0'");?></td>
	                        <?php }
	                        
	                        for($k=0;$k<7;$k++) {
	                            if($k!=5) {?>
	                                <td style="width:5%" align="center"><?php echo $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' AND fld_session_id='".$k."' AND fld_delstatus='0' AND fld_type='0' AND fld_preassment_id='0'");?></td>
	                            <?php } 
	                        }
							
							if($id[4]==4)
	                        	$newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id FROM `itc_mathmodule_master` WHERE fld_id='".$fld_module_id."' AND fld_delstatus='0'");
							else
								$newmodid = $fld_module_id;
								
							for($l=0;$l<3;$l++) {
								?>
	                            <td style="width:5%" align="center"><?php echo $ObjDB->SelectSingleValue("SELECT IFNULL(SUM(CASE WHEN fld_lock='0' THEN fld_points_earned WHEN fld_lock='1' THEN fld_teacher_points_earned END),' - ') FROM `itc_module_points_master` WHERE fld_module_id='".$fld_module_id."' AND fld_schedule_id='".$id[1]."' AND fld_student_id='".$studentid."' AND fld_schedule_type='".$id[4]."' AND fld_delstatus='0' AND fld_type='3' AND fld_preassment_id = (SELECT fld_id FROM `itc_module_performance_master` WHERE fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_performance_name<>'Participation' AND fld_performance_name<>'Attendance' AND fld_performance_name<>'Total Pages' LIMIT ".$l.",1 )");?></td>
	                        <?php 
	                        } if($id[4]==4) {?>
	                        <td style="width:5%" align="center"><?php if($day1=='') echo " - "; else echo $day1;?></td>
	                        
	                        <td style="width:5%" align="center"><?php if($day2=='') echo " - "; else echo $day2;?></td>
	                        <?php }?>
	                        <td style="width:5%" align="center"><?php echo $earnedpoints;?></td>
	                        
	                        <?php 
	                        if($i<$count)
	                        {
	                            ?>
	                            </tr>
	                            <tr>
	                            <?php
	                        }
	                        $i++;
	                    }
	                }
	                ?>
	                </tr>
	                <?php
	            }
	        }
	        ?>
	    </tbody>
	</table>