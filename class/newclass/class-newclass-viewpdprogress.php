<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(',',$id);
$sid = $id[0];
$classid = $id[1];
$sname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_pdschedule_master WHERE fld_id='".$sid."'");
?>
<style>
	.progressMeterBase {
		position: relative;
		background: #f0f4f6;
	}
	.progressMeter {
		position: relative;
		background: #d0e0eb;
		height: 20px;
	}
	
</style>

<section data-type='#class-newclass' id='class-newclass-viewpdprogress'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle"><?php echo $sname; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>	
        <div class='row'>
        	<div style="width:100%; overflow-x:scroll">
        	<script>
				setTimeout('$("#example-basic").treetable({ expandable: true, clickableNodeNames:true })',3000);
			</script>
            <table id="example-basic" class="table">
            	<thead>
                    <tr>
                        <th>&nbsp;</th>
                        <?php 
                        $qrystudents = $ObjDB->QueryObject("SELECT a.fld_student_id, CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_pdschedule_student_mapping AS a 
                                                                LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id WHERE a.fld_pdschedule_id='".$sid."' AND a.fld_flag='1' 
                                                                ORDER BY b.fld_lname");						
                        if($qrystudents->num_rows>0)
                        {
                            $cnt=0;
                            while($rowstudents=$qrystudents->fetch_assoc())
                            {
                                extract($rowstudents);
                                $studentid[$cnt]=$fld_student_id;?>
                                <th style="text-align:center;font-weight:bold;" width="150"><?php echo $studentname;?></th>
                                <?php
                                $cnt++;
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                $qryunits = $ObjDB->QueryObject("SELECT a.fld_course_id, b.fld_course_name AS coursename FROM itc_class_pdschedule_course_mapping AS a 
                                                        LEFT JOIN itc_course_master AS b ON b.fld_id=a.fld_course_id WHERE a.fld_pdschedule_id='".$sid."' AND a.fld_flag='1' 
                                                        ORDER BY a.fld_order");
                if($qryunits->num_rows>0)
                {
					$m=0;
                    while($rowunits=$qryunits->fetch_assoc())
                    {
						$m++;
                        extract($rowunits);?>
                        <tr data-tt-id="<?php echo $m;?>">
                            <td class="progressUnitTitle progressHeaderFill"><div style="width: 200px; margin-top: -20px;">
<?php echo $coursename.' / Courses';?></div></td>
                            <?php
                            for($i=0;$i<$qrystudents->num_rows;$i++)
                            { 
                                $iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_class_pdschedule_lesson_mapping AS a 
                                                                                LEFT JOIN itc_pd_master AS b ON a.fld_lesson_id=b.fld_id WHERE a.fld_pdschedule_id='".$sid."'
                                                                                 AND a.fld_flag='1' AND b.fld_course_id='".$fld_course_id."' AND b.fld_delstatus='0'");
                                $progcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM itc_assignment_pd_master AS a 
                                                                                LEFT JOIN itc_class_pdschedule_lesson_mapping AS b ON 
                                                                                a.fld_lesson_id=b.fld_lesson_id AND a.fld_pdschedule_id=b.fld_pdschedule_id 
                                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_pdschedule_id='".$sid."' AND a.fld_student_id='".$studentid[$i]."' 
                                                                                AND a.fld_delstatus='0' AND a.fld_course_id='".$fld_course_id."'"); 
                                $percentage=0;
                                if($iplcount!=0)
                                $percentage = round(($progcount/$iplcount)* 100,2);
                                ?>
                                <td>
                                    <div class="progressMeterBase" title="<?php echo $progcount." out of ".$iplcount." IPLs Completed";?>">
                                        <div class="progressMeter" style="width:<?php echo $percentage.'%';?>">
                                        </div>
                                    </div>
                                </td><?php
                            }
                            ?>
                        </tr>
                        <?php
                        $qryipls = $ObjDB->QueryObject("SELECT a.fld_lesson_id, b.fld_pd_name AS iplname FROM itc_class_pdschedule_lesson_mapping AS a 
                                                            LEFT JOIN itc_pd_master AS b ON a.fld_lesson_id=b.fld_id WHERE a.fld_pdschedule_id='".$sid."'
                                                            AND a.fld_flag='1' AND b.fld_course_id='".$fld_course_id."' AND b.fld_delstatus='0'  
                                                            ORDER BY a.fld_order");
                        if($qryipls->num_rows>0)
                        {
							$n=0;
                            while($rowipls=$qryipls->fetch_assoc())
                            {
								$n++;
                                extract($rowipls);?>
                                <tr data-tt-parent-id="<?php echo $m; ?>" data-tt-id="<?php echo $m.".".$n;?>" >
                                    <td class="progressIplTitle"><?php echo $iplname.' / PDLessons';?></td>
                                <?php
                                for($i=0;$i<$qrystudents->num_rows;$i++)
                                { 
									$counts = '';
									$count = '';
                                                                        
									$qrycount = $ObjDB->QueryObject("SELECT count(fld_id) as cou FROM itc_assignment_pd_master WHERE fld_class_id='".$classid."' 
                                                                                                                AND fld_pdschedule_id='".$sid."' AND fld_lesson_id='".$fld_lesson_id."' AND fld_student_id='".$studentid[$i]."' 
                                                                                                                AND fld_delstatus='0'");
									if($qrycount->num_rows>0)
									{
										$row=$qrycount->fetch_assoc();
										extract($row);
										$counts = $cou;
									}
									else
									{
										$counts = '';
									}?>
                                    <td style="text-align:center"><?php if($counts==1) { echo "In progress"; } else { echo "Not Started"; }?></td><?php
                                }
                                ?>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
                ?>  
                </tbody> 
            </table>
            </div>
        </div>  
 	</div>   
</section>
<?php
	@include("footer.php");