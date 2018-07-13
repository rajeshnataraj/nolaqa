<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(',',$id);
$sid = $id[0];
$stype = $id[1];

$classid = $id[2];
$sname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_indassesment_master WHERE fld_id='".$sid."'");
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

<section data-type='#class-newclass' id='class-newclass-viewindprogress'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle"><?php echo $sname;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>	
        <div class='row'>
        	<div style="width:100%; overflow-x:scroll">        	
            <script>
				setTimeout('$("#example-basic").treetable({ expandable: true, clickableNodeNames:true })',2000);
			</script>
            <table id="example-basic" class="table">
            	<thead>
                    <tr>
                        <th>&nbsp;</th>
                        <?php 
                        $qrystudents = $ObjDB->QueryObject("SELECT a.fld_student_id, CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_indassesment_student_mapping AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id WHERE a.fld_schedule_id='".$sid."' AND a.fld_flag='1' ORDER BY b.fld_lname");						
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
                $qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS moduleid, a.fld_moduletype as mtype, (CASE WHEN a.fld_moduletype=1 
												THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=a.fld_module_id) 
												WHEN a.fld_moduletype=2 
												THEN (SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id) 
												WHEN a.fld_moduletype=7 
												THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=a.fld_module_id) 
                                                                                                WHEN a.fld_moduletype=17 
												THEN (SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id=a.fld_module_id) 
												END) AS modulename 
											FROM itc_class_indassesment_master AS a 
											WHERE a.fld_id='".$sid."' AND a.fld_delstatus='0'");
                if($qry->num_rows>0)
                {
					$row =$qry->fetch_assoc();	
					extract($row);
					if($mtype==7)
					{
						$sessname = "Chapter ";
						$modtypename = "Quests";
						$schtype='7';
						$tempmoduleid = $moduleid;
					}
					else if($mtype==2)
					{
						$sessname = "Session ";
						$modtypename = "Math Module";
						$schtype='6';
						$tempmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id 
													FROM itc_mathmodule_master 
													WHERE fld_id='".$moduleid."'");
					}
                                        else if($mtype==17)
					{
						$sessname = "Content ";
						$modtypename = "Custom Content";
						$schtype='17';
						$custommoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_id 
													FROM itc_customcontent_master 
													WHERE fld_id='".$moduleid."'");
					}
					else
					{
						$sessname = "Session ";
						$modtypename = "Module";
						$schtype='5';
						$tempmoduleid = $moduleid;
					}
					$totalsesscount = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_session_id)+1 
												FROM itc_module_performance_master 
												WHERE fld_module_id='".$tempmoduleid."'");
					?>
					<tr data-tt-id="1">
						<td class="progressUnitTitle progressHeaderFill"><div style="width: 200px; margin-top: -20px;"><?php echo $modulename.' / '; echo $modtypename;?></div></td>
                        <?php
                        
                        if($mtype==17){
                            ?>
                             
                           <?php for($h=0;$h<$qrystudents->num_rows;$h++)
                            { 
                            $pointsstored=$ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                            FROM itc_module_points_master 
                                                                            WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$moduleid."' AND fld_student_id='".$studentid[$h]."' 
                                                                                  AND fld_schedule_type='".$schtype."' AND fld_type='0' AND fld_delstatus='0'");
                            ?>
                        <td>
                                <div class="progressMeterBase" title="<?php if($pointsstored!=0) { echo "Completed"; } else { echo "Not Started"; }?>">
                                    <div class="progressMeter" style="width:<?php echo $percentage.'%';?>">
                                    </div>
                                </div>
                            </td>
                                <?php
                            }
                            ?>
                            <tr data-tt-parent-id="1" data-tt-id="1.<?php echo $sessname;?>">
                        <td class="progressIplTitle"><?php  echo $sessname;?></td>
                        <?php for($cus=0;$cus<$qrystudents->num_rows;$cus++)
                        { 
                            $pointssto=$ObjDB->SelectSingleValueInt("SELECT fld_teacher_points_earned 
                                                                            FROM itc_module_points_master 
                                                                            WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$moduleid."' AND fld_student_id='".$studentid[$cus]."' 
                                                                                  AND fld_schedule_type='".$schtype."' AND fld_type='0' AND fld_delstatus='0'");
                        
                          ?>   <td style="text-align:center"><?php if($pointssto!=0) { echo "Completed"; } else { echo "Not Started"; }?></td>
                       <?php  }
                       
                       
                       
                        }
                        else{
                        
                        for($i=0;$i<$qrystudents->num_rows;$i++)
                        { 
                            $modcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id) FROM (SELECT fld_id 
																	 FROM itc_module_points_master 
																	 WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$moduleid."' AND fld_student_id='".$studentid[$i]."' 
																	 AND fld_schedule_type='".$schtype."' AND fld_type='0' AND fld_delstatus='0' 
																	 GROUP BY fld_session_id) AS a");
                            $iplcount1 = 0;
							$iplcount2 = 0;
							if($mtype==2)
							{
								$qrydays = $ObjDB->QueryObject("SELECT fld_ipl_day1, fld_session_day1, fld_session_day2, fld_ipl_day2 
																FROM itc_mathmodule_master 
																WHERE fld_id='".$moduleid."' AND fld_delstatus='0'"); 
								$rowqrydays =$qrydays->fetch_assoc();	
								extract($rowqrydays);
								
								$session1 = $fld_session_day1;
								$session2 = $fld_session_day2;
								
								$ipl1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																	 FROM itc_assignment_sigmath_master 
																	 WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_student_id='".$studentid[$i]."' 
																	 AND fld_status<>'0' AND fld_test_type='5' AND fld_delstatus='0' AND fld_lesson_id IN (".$fld_ipl_day1.")"); 
								$ipl2 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																	 FROM itc_assignment_sigmath_master 
																	 WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_student_id='".$studentid[$i]."' 
																	 AND fld_status<>'0' AND fld_test_type='5' AND fld_delstatus='0' AND fld_lesson_id IN (".$fld_ipl_day2.")"); 
								
								if($ipl1==4)
									$iplcount1 = 1;
								if($ipl2==4)
									$iplcount2 = 1;
								
								$progcount = $modcount+$iplcount1+$iplcount2;
								$percentage = round(($progcount/9)* 100,2);
								$totcount = 9;
							}
							else
							{
								$percentage = round(($modcount/$totalsesscount)* 100,2);
								$totcount = $totalsesscount;
							}
                            ?>
                            <td>
                                <div class="progressMeterBase" title="<?php echo $modcount." out of ".$totcount." ".$sessname."Completed";?>">
                                    <div class="progressMeter" style="width:<?php echo $percentage.'%';?>">
                                    </div>
                                </div>
                            </td><?php
                        }
                } // else 
                        ?>
					</tr>
					<?php
                }
			if($mtype!=17){	
				for($i=0;$i<$totalsesscount;$i++)
				{
					$sess = $i;
					$sess++;
					?>
                    <tr data-tt-parent-id="1" data-tt-id="1.<?php echo $sess;?>">
                        <td class="progressIplTitle"><?php  echo $sessname.$sess;?></td>
						<?php
                        for($j=0;$j<$qrystudents->num_rows;$j++)
                        { 
                           
                            if($sess!=6 or $schtype==7){
								$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																	  FROM itc_module_points_master 
																	  WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$moduleid."' AND fld_student_id='".$studentid[$j]."' 
																	  	AND fld_schedule_type='".$schtype."' AND fld_session_id='".$i."' AND fld_type='0' AND fld_delstatus='0'");
                            }
                           
                            else{
								$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
																	  FROM itc_module_play_track 
																	  WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$moduleid."' AND fld_tester_id='".$studentid[$j]."' 
																	  	AND fld_schedule_type='".$schtype."' AND fld_section_id='".$i."' AND fld_delstatus='0'");
                            }
                            ?>
							<td style="text-align:center"><?php if($count!=0) { echo "Completed"; } else { echo "Not Started"; }?></td><?php
                        }
                        ?>
                    </tr>
                    <tr data-tt-parent-id="1" data-tt-id="1.<?php echo $sess;?>"><?php
					if($mtype==2)
					{
						if($sess == $session1)
						{ ?>
							<td class="progressIplTitle">Diagnostic Day1 </td>
						   <?php
						}
						else if($sess == $session2)
						{
							?>
							<td class="progressIplTitle">Diagnostic Day2</td>
							<?php
						}
					}
					for($j=0;$j<$qrystudents->num_rows;$j++)
					{ 
						if($mtype==2)
						{
							if($sess == $session1)
							{
								?>
								<td style="text-align:center"><?php if($iplcount1==1) { echo "Completed"; } else if($ipl1==0) { echo "Not Started"; } else { echo "In Progress"; }?></td>
								<?php
							}
							else if($sess == $session2)
							{
								?>
								<td style="text-align:center"><?php if($iplcount2==1) { echo "Completed"; } else if($ipl2==0) { echo "Not Started"; } else { echo "In Progress"; }?></td>
								<?php
							}
						}
					}
					?>
                    </tbody>
                    <?php
				}
                                } //if 
                ?>  
                </tbody> 
            </table>
            </div>
        </div>  
 	</div>   
</section>
<?php
@include("footer.php");