<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(',',$id);
$sid = $id[0];
$stype = $id[1];

$classid = $id[2];
$sname = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_indasmission_master WHERE fld_id='".$sid."'");

if($sid!='0')
{
    $qryschmission=$ObjDB->QueryObject("SELECT a.fld_mis_name AS missionname FROM itc_mission_master AS a
                                        LEFT JOIN itc_class_indasmission_master AS b ON a.fld_id = b.fld_mis_id
                                        WHERE b.fld_id ='".$sid."' AND b.fld_delstatus = '0' AND a.fld_flag = '1' AND a.fld_delstatus = '0'");
    if($qryschmission->num_rows>0)
    {
            $rowsch=$qryschmission->fetch_assoc();
            extract($rowsch);
    }
}
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

<section data-type='#class-newclass' id='class-newclass-viewmission'>
	<div class='container'>
        <div class='row'>
            <div class="span10">
                <p class="darkTitle"><?php echo $sname;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>	
        <div class='row'>
        	<div style="width:100%; overflow-x:scroll">        	
            <table id="example-basic" class="table">
            	<thead>
                    <tr>
                        <th>&nbsp;</th>
                        <?php 
                        $qrystudents = $ObjDB->QueryObject("SELECT a.fld_student_id,CONCAT(b.fld_fname, ' ', b.fld_lname) AS studentname FROM itc_class_mission_student_mapping AS a LEFT JOIN itc_user_master AS b ON b.fld_id = a.fld_student_id
                                                                WHERE a.fld_schedule_id ='".$sid."' AND a.fld_flag = '1' ORDER BY studentname");						
                        if($qrystudents->num_rows>0)
                        {
                            $cnt=0;
                            while($rowstudents=$qrystudents->fetch_assoc())
                            {
                                extract($rowstudents);
                                $studentid[$cnt]=$fld_student_id;?>
                                <th style="text-align:center;font-weight:bold;"><?php echo $studentname;?></th>
                                <?php
                                $cnt++;
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qry = $ObjDB->QueryObject("SELECT a.fld_mis_id AS missionid, (SELECT fld_mis_name FROM itc_mission_master WHERE fld_id = a.fld_mis_id) AS missionname 
                                                        FROM itc_class_indasmission_master AS a 
                                                        WHERE a.fld_id = '".$sid."' AND a.fld_delstatus = '0' AND a.fld_flag = '1'");

                    if($qry->num_rows>0)
                    {
                        $row =$qry->fetch_assoc();	
                        extract($row);
                        
                        $totaldestcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(a.fld_id)
                                                                    FROM itc_mis_destination_master AS a
                                                                    LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id = b.fld_dest_id
                                                                    LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                                                    LEFT JOIN itc_class_indasmission_master as d ON b.fld_license_id = d.fld_license_id
                                                                    WHERE a.fld_mis_id = '".$missionid."' AND d.fld_id = '".$sid."' AND b.fld_mis_id = '".$missionid."'
                                                                            AND b.fld_flag = '1' AND a.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' 
                                                                            AND c.fld_school_id = '".$schoolid."' AND b.fld_delstatus = '0' AND c.fld_delstatus = '0'");

                        ?>
                	<tr>
                            <td class="progressUnitTitle progressHeaderFill"><div style="width: 100px;"><?php echo $missionname;?></div></td>
                            <?php
                            for($i=0;$i<$qrystudents->num_rows;$i++)
                            {
                                    $readcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track
                                                                                        WHERE fld_mis_id = '".$missionid."' AND fld_delstatus = '0' AND fld_schedule_id = '".$sid."' 
                                                                                        AND fld_read_status = '1' AND fld_student_id = '".$studentid[$i]."'");

                                    $progresscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_dest_play_track 
                                                                                    WHERE fld_mis_id='".$missionid."' AND fld_delstatus='0'
                                                                                            AND fld_student_id='".$studentid[$i]."' AND fld_schedule_id='".$sid."'");

                                    if($readcount == $totaldestcount)
                                            $status = "Completed";
                                    else if($progresscount > 0)
                                            $status = "Inprogress"; 
                                    else
                                            $status = "Not Started";													
                                    ?>
                                    <td style="text-align:center;"><?php echo $status;?></td>
                                    <?php
                            }
                            ?>
                    </tr>
                    <?php 
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