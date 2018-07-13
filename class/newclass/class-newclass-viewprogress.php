<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(',',$id);
$sid = $id[0];
$classid = $id[1];
$scount=array();
$sname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_sigmath_master WHERE fld_id='".$sid."'");
$stucnt = $ObjDB->SelectSingleValue("SELECT count(a.fld_student_id) 
										FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id 
										WHERE a.fld_sigmath_id='".$sid."' AND a.fld_flag='1' 
										ORDER BY b.fld_lname");		
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

<section data-type='#class-newclass' id='class-newclass-viewprogress'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle"><?php echo $sname; ?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>	
        <div class='row'>
        <?php	if($stucnt==1)
{ 	?>
	<div class="gridtableouter" style="width:931px;height: 459px;">
	<?php
}
else
{ 	?>
        	<div class="gridtableouter" style="width:950px;height: 459px;">
	<?php
}	?>
        	
            <table id="myTable05" class="table" cellpadding="0" cellspacing="0">
            	<thead>
                    <tr>
                        <th>&nbsp;</th>
                        <?php 
                        $qrystudents = $ObjDB->QueryObject("SELECT a.fld_student_id, CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname 
															FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id 
															WHERE a.fld_sigmath_id='".$sid."' AND a.fld_flag='1' 
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
								$scount[]=$fld_student_id;
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                $qryunits = $ObjDB->QueryObject("SELECT a.fld_unit_id, b.fld_unit_name AS unitname,fn_shortname(b.fld_unit_name,1) AS unitshortname 
												FROM itc_class_sigmath_unit_mapping AS a LEFT JOIN itc_unit_master AS b ON b.fld_id=a.fld_unit_id 
												WHERE a.fld_sigmath_id='".$sid."' AND a.fld_flag='1' 
												ORDER BY a.fld_order");
                if($qryunits->num_rows>0)
                {
					$m=0;
                    while($rowunits=$qryunits->fetch_assoc())
                    {
						$m++;
                        extract($rowunits);?>
                        <tr data-tt-id="<?php echo $m;?>">
                            <td class="tooltip progressUnitTitle progressHeaderFill" title="<?php echo $unitname; ?>"><div style="width: 200px;">
<?php echo $unitshortname.' / Units';?></div></td>
                            <?php
                            for($i=0;$i<$qrystudents->num_rows;$i++)
                            { 
                                $iplcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
																		 FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
																		 WHERE a.fld_sigmath_id='".$sid."' AND a.fld_flag='1' AND b.fld_unit_id='".$fld_unit_id."' AND b.fld_delstatus='0'");
                                $progcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) 
																		  FROM itc_assignment_sigmath_master AS a LEFT JOIN itc_class_sigmath_lesson_mapping AS b ON 
																		  	a.fld_lesson_id=b.fld_lesson_id AND a.fld_schedule_id=b.fld_sigmath_id 
																		  WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$sid."' AND a.fld_student_id='".$studentid[$i]."' 
																		  AND a.fld_status<>'0' AND a.fld_test_type='1' AND a.fld_delstatus='0' AND a.fld_unit_id='".$fld_unit_id."'"); 
								$percentage=0;
								if($iplcount!=0)
                                $percentage = round(($progcount/$iplcount)* 100,2);
                                ?>
                                <td>
                                    <div class="progressMeterBase" title="<?php echo $progcount." out of ".$iplcount." IPLs Completed";?>">
                                        <div class="progressMeter" style="margin-left:20px;width:<?php echo $percentage.'%';?>">
                                        </div>
                                    </div>
                                </td><?php
                            }
                            ?>
                        </tr>
                        <?php
                        $qryipls = $ObjDB->QueryObject("SELECT a.fld_lesson_id, b.fld_ipl_name AS iplname,fn_shortname(b.fld_ipl_name,1) AS iplshortname
													   FROM itc_class_sigmath_lesson_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id 
													   WHERE a.fld_sigmath_id='".$sid."' AND a.fld_flag='1' AND b.fld_unit_id='".$fld_unit_id."' AND b.fld_delstatus='0'  
													   ORDER BY a.fld_order");
                        if($qryipls->num_rows>0)
                        {
							$n=0;
                            while($rowipls=$qryipls->fetch_assoc())
                            {
								$n++;
                                extract($rowipls);?>
                                <tr data-tt-parent-id="<?php echo $m; ?>" data-tt-id="<?php echo $m.".".$n;?>" >
                                   <?php if(sizeof($scount)==1)
						{ 	?>
						 	<td class="tooltip progressIplTitle" title="<?php echo $iplname; ?>" style="background-color:lightgrey; height: 41px; width: 719px;"><?php echo $iplname.' / IPLs';?></td>
							<?php
						}
						else
						{ 	?>
                                    <td class="tooltip progressIplTitle" title="<?php echo $iplname; ?>" style="background-color:lightgrey;"><?php echo $iplshortname.' / IPLs';?></td>
                                <?php
						}
						?>
                                <?php
                                for($i=0;$i<$qrystudents->num_rows;$i++)
                                { 
									$counts = '';
									$count = '';
									$qrycount = $ObjDB->QueryObject("SELECT fld_status AS count 
																	FROM itc_assignment_sigmath_master WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' 
																	AND fld_lesson_id='".$fld_lesson_id."' AND fld_student_id='".$studentid[$i]."' AND fld_test_type='1' 
																	AND fld_delstatus='0'");
									if($qrycount->num_rows>0)
									{
										$row=$qrycount->fetch_assoc();
										extract($row);
										$counts = $count;
									}
									else
									{
										$counts = '';
									}?>
                                    <td style="text-align:center"><?php if($counts==2 || $counts==1) { echo "Completed"; } else if($counts=='0') { echo "In progress"; } else if($counts==''){ echo "Not Started"; }?></td><?php
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
            <script language="javascript" type="text/javascript">
        $('#myTable05').fixedHeaderTable({fixedColumn: true });
        $('div.fht-fixed-column').children().last().css('margin-top','-29px');
        
                            </script>
        </div>  
 	</div>   
</section>
<?php
	@include("footer.php");