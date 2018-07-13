<?php
//@include("../../../sessioncheck.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include("../includes/table.class.php");	
include("../includes/comm_func.php");
//session_start();	
$date = date("Y-m-d H:i:s");
$oper = isset($_REQUEST['oper']) ? $_REQUEST['oper'] : '';
if($oper=="showassignments" and $oper != " " )
{
    $taskid = (isset($_REQUEST['taskid'])) ? $_REQUEST['taskid'] : '';
    $test= explode(".",$taskid);
   //print_r($test);
    //$test[1]=390;
    //$test[0]=1;
   $qryresourcedetails=$ObjDB->QueryObject("SELECT a.fld_id,a.fld_res_name AS resname,b.fld_read_status AS ressts FROM itc_exp_resource_master AS a 
                                            LEFT JOIN itc_exp_res_play_track AS b ON b.fld_res_id=a.fld_id 
                                            LEFT JOIN itc_exp_res_status AS c ON c.fld_res_id=a.fld_id 
                                            WHERE b.fld_student_id='".$test[1]."' AND b.fld_schedule_id='".$test[0]."' AND c.fld_status='1' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
   
 
   
   ?>
<style>
table,th,td
{
border:1px solid black;
border-collapse:collapse;
}
th,td
{
padding:5px;
}
</style>
<?php
  
    if($qryresourcedetails->num_rows>0)
    {
         ?>
    <table style="width:100%; padding-top:25px; ">
    <tr>
      <th>Resource</th>
      <th>Status</th>		
      <th>Duration</th>
      </tr>
<?php
        while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
        {
            extract($rowresourcedetails);
            if($ressts==1)
            {
                $ressts='Completed';
            }
            else
            {
                $ressts='Inprogress';
            }
            ?>

            <tr>
              <td><?php echo $resname;?></td>
              <td><?php echo $ressts;?></td>		
              <td>50</td>
              </tr>
            <?php
        }
    }
    else
    {
         ?>
            <tr>
                <td>No records</td>                  
            </tr>
            <?php
    }
    ?>
      </table>
    <?php
}