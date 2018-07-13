<?php 
@include("../../includes/table.class.php");
@include("../../includes/comm_func.php");
?>
<style >
 .title
 {
	font-weight: bold; font-size: 58px;color:#606060;
 }
 .trgray
 {
	font-size:30px;background-color:#D1D1D1;font-weight:normal; 
 }
 .trclass
 {
	font-size:30px;background-color:#FFFFFF;font-weight:normal;
 }
 .tdleft{
	 border-top:1px solid #606060;border-left:1px solid #606060;border-bottom:1px solid #606060;
	 }
	 
.tdmiddle{
	border-top:1px solid #606060;border-bottom:1px solid #606060;
	 }
 .tdright{
	 border-top:1px solid #606060;border-right:1px solid #606060;border-bottom:1px solid #606060;
	 }
 	 
 </style>
        <?php 
		$qryschedules = Table::QueryObject("(SELECT a.fld_schedule_name AS sigmathschdule,a.fld_id,1 AS typename  FROM `itc_class_sigmath_master` AS a LEFT JOIN `itc_class_sigmath_student_mapping` AS b ON a.fld_id=b.fld_sigmath_id WHERE a.fld_class_id='5' AND b.fld_student_id='11' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_flag='1' ) UNION (SELECT a.fld_schedule_name AS sigmathschdule,a.fld_id,2 AS typename FROM `itc_class_rotation_schedule_mastertemp` AS a LEFT JOIN `itc_class_rotation_schedule_student_mappingtemp` AS b ON a.fld_id=b.fld_schedule_id WHERE a.fld_class_id='5' AND b.fld_student_id='11' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1') UNION (SELECT a.fld_schedule_name AS sigmathschdule,a.fld_id,3 AS typename FROM `itc_class_dyad_schedulemaster` AS a LEFT JOIN `itc_class_dyad_schedule_studentmapping` AS b ON a.fld_id=b.fld_schedule_id WHERE a.fld_class_id='5' AND b.fld_student_id='11' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1') UNION (SELECT a.fld_schedule_name AS sigmathschdule,a.fld_id,4 AS typename FROM `itc_class_triad_schedulemaster` AS a LEFT JOIN `itc_class_triad_schedule_studentmapping` AS b ON a.fld_id=b.fld_schedule_id WHERE a.fld_class_id='5' AND b.fld_student_id='11' AND a.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_flag='1')"); 
		
		if($qryschedules->num_rows > 0)
		{ 	 
			while($rowschedules=$qryschedules->fetch_assoc())
			{
				extract($rowschedules);
				?>
                <span class="title" ><?php echo $sigmathschdule;?><br /></span>
                <table cellpadding="0" cellspacing="0" >
                   
                   
                    <tr style="font-size:40px; font-weight:bold;" >
                        <th class='centerText'>Assignment Name</th>
                        <th class='centerText'>Start Date</th>
                        <th class='centerText'>End Date</th>
                    </tr>
                
                    <tbody>
						<?php 
                        if($typename==1)
                        {
                            $qry = Table::QueryObject("SELECT a.fld_unit_name AS assigmentname,c.fld_start_date AS startdate,c.fld_end_date AS enddate FROM itc_unit_master AS a LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id=b.fld_unit_id LEFT JOIN itc_class_sigmath_master AS c ON c.fld_id=b.fld_sigmath_id WHERE b.fld_sigmath_id='".$fld_id."' AND a.fld_activestatus='0' AND a.fld_delstatus='0' AND b.fld_flag='1' AND c.fld_flag='1' AND c.fld_delstatus='0'");
                        }
                        else if($typename==2)
                        {
                            $qry = Table::QueryObject("SELECT a.fld_module_name AS assigmentname,b.fld_startdate AS startdate,b.fld_enddate AS enddate, b.fld_row_id FROM itc_class_rotation_moduledet AS a LEFT JOIN itc_class_rotation_schedulegriddet AS b ON (b.fld_row_id=a.fld_row_id AND b.fld_schedule_id=a.fld_schedule_id) WHERE b.fld_schedule_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_student_id='11' AND a.fld_flag='1' AND b.fld_flag='1' ");
                        } 
                        else if($typename==3)
                        {
                            $qry = Table::QueryObject("SELECT a.fld_module_name AS assigmentname,b.fld_startdate AS startdate,b.fld_enddate AS enddate, b.fld_row_id FROM itc_class_dyad_moduledet AS a LEFT JOIN itc_class_dyad_schedulegriddet AS b ON(b.fld_row_id=a.fld_row_id AND b.fld_schedule_id=a.fld_schedule_id) WHERE b.fld_schedule_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_student_id='11' AND a.fld_flag='1' AND b.fld_flag='1' ");
                        } 
                        else if($typename==4)
                        {
                            $qry = Table::QueryObject("SELECT a.fld_module_name AS assigmentname,b.fld_startdate AS startdate,b.fld_enddate AS enddate, b.fld_row_id FROM itc_class_triad_moduledet AS a LEFT JOIN itc_class_triad_schedulegriddet AS b ON (b.fld_row_id=a.fld_row_id AND b.fld_schedule_id=a.fld_schedule_id) WHERE b.fld_schedule_id='".$fld_id."' AND a.fld_flag=1 AND b.fld_student_id='11' AND a.fld_flag='1' AND b.fld_flag='1' ");
                        } 
                    
						if($qry->num_rows > 0){
							while($rowqry=$qry->fetch_assoc())
							{
								extract($rowqry);  ?>
								<tr  class="trgray" >
									<td class="tdleft"><?php echo $assigmentname; ?></td>
									<td class="tdmiddle"><?php echo date("F d, Y",strtotime($startdate)); ?></td>
									<td class="tdright"><?php echo date("F d, Y",strtotime($enddate)); ?></td>
								</tr>
								<?php 	
							}
						}
						else
						{ ?>
                            <tr>
                            	<td class='centerText' colspan="3">no records</td>
                            </tr>
						<?php }
						?>
					</tbody>
				</table>
                <br />
                <br />
                <br />
				<?php
            } 
        }
	