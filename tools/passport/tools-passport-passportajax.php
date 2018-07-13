<?php 
@include("sessioncheck.php");

/*
	Created By - Vijayalakshmi PHP Programmer
	Page - reports-classroom-classroomajax.php
	Descriptiion : qry fetches from corressponding expedtion tabes
		
*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load Expedition Dropdown ---*/
if($oper=="showexpedition" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';

	?>
	Expedition
	<div class="selectbox">
		<input type="hidden" name="expeditionid" id="expeditionid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Expedition</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Expedition">
			<ul role="options" style="width:100%">
				<?php 
					$qry = $ObjDB->QueryObject("SELECT c.fld_exp_name as expname,c.fld_id as expid
									FROM itc_class_master AS a
									LEFT JOIN itc_class_indasexpedition_master AS b on a.fld_id=b.fld_class_id
									LEFT JOIN itc_exp_master AS c on b.fld_exp_id=c.fld_id
									WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' AND b.fld_class_id='".$classid."'
									AND (a.fld_created_by = '".$uid."' OR a.fld_id IN (SELECT fld_class_id
									FROM itc_class_teacher_mapping WHERE fld_teacher_id = '".$uid."' AND fld_flag = '1'))
									group by expname");
				if($qry->num_rows>0){
				
				  while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>" onclick="fn_showschedules(<?php echo $classid;?>,<?php echo $expid;?>);"><?php echo $expname; ?></a></li>
						<?php
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

if($oper=="showschedule" and $oper != " " )
{

$classid = isset($method['classid']) ? $method['classid'] : '';
$expid = isset($method['expid']) ? $method['expid'] : '';

?>
Schedule
	<div class="selectbox">
        <input type="hidden" name="hidscheduleid" id="hidscheduleid" value=""/>
        <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
            <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
            <b class="caret1"></b>
        </a>
        <div class="selectbox-options">
            <input type="text" class="selectbox-filter" placeholder="Search Schedule">
            <ul role="options" style="width:100%">
            <?php

			$schqry = $ObjDB->QueryObject("SELECT b.fld_id as scheduleid,b.fld_schedule_name as schedulename
							FROM itc_class_master AS a
							LEFT JOIN itc_class_indasexpedition_master AS b on a.fld_id=b.fld_class_id
							LEFT JOIN itc_exp_master AS c on b.fld_exp_id=c.fld_id
							WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0' AND b.fld_class_id='".$classid."'
							AND b.fld_exp_id='".$expid."' AND (a.fld_created_by = '".$uid."' OR a.fld_id IN (SELECT fld_class_id
							FROM itc_class_teacher_mapping WHERE fld_teacher_id = '".$uid."' AND fld_flag = '1'))
							group by schedulename");
			if($schqry->num_rows>0){
				
				  while($schrow = $schqry->fetch_assoc())
					{
						extract($schrow);
?>
             <li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid;?>" onclick="$('#viewexpeditiondiv').show();"><?php echo $schedulename; ?></a></li>
  			<?php 	}	}?>                   
            </ul>
        </div>
	</div>
<?php
}

if($oper=="savereslocked" and $oper != " " )
{

	$classid = isset($method['classid']) ? $method['classid'] : '';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$expid = isset($method['expid']) ? $method['expid'] : '';
	$grpresid = isset($method['selectedres']) ? $method['selectedres'] : '';

	$grpresid=explode("@",$grpresid);
	$sepresid = array_filter($grpresid);
	$resource_val=array();
	for($j=0;$j<sizeof($sepresid);$j++) {
	    $res_group = explode(",",$sepresid[$j]);
	    array_push($resource_val,$res_group[2]);
	}
	$comma_resval = implode(",", $resource_val);
	
	    $res_group = explode(",",$sepresid[$j]);

	    $unlock_resid = $ObjDB->QueryObject("SELECT x.fld_id as explockid FROM itc_exp_lockpassport AS x
						WHERE  x.fld_classid = '".$classid."' AND x.fld_scheduleid = '".$scheduleid."' AND x.fld_expid = '".$expid."'
	 					AND x.fld_resid NOT IN ($comma_resval)");	
	if($unlock_resid->num_rows>0){

		while($row = $unlock_resid->fetch_assoc())
		{
			extract($row);
			$ObjDB->NonQuery("UPDATE itc_exp_lockpassport SET fld_lock_flag = '0',fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id = '".$explockid."'");

		}
	 
	}
	

	for($i=0;$i<sizeof($sepresid);$i++) {

	    $res_group = explode(",",$sepresid[$i]);
 							
	    $findresid= $ObjDB->SelectSingleValue("SELECT fld_id FROM itc_exp_lockpassport WHERE fld_classid = '".$classid."' AND fld_scheduleid = '".$scheduleid."' AND fld_expid = '".$expid."' AND fld_destid = '".$res_group[0]."' AND fld_taskid = '".$res_group[1]."' AND fld_resid='".$res_group[2]."'");

		if($findresid == '')
		{
  			$ObjDB->NonQuery("INSERT INTO itc_exp_lockpassport(fld_classid, fld_scheduleid, fld_expid, fld_destid, fld_taskid, fld_resid, fld_lock_flag, fld_created_by, fld_created_date)
                                    VALUES ('".$classid."', '".$scheduleid."', '".$expid."', '".$res_group[0]."', '".$res_group[1]."', '".$res_group[2]."', '1','".$uid."','".date("Y-m-d H:i:s")."')");

		}
		else {
			$ObjDB->NonQuery("UPDATE itc_exp_lockpassport SET fld_lock_flag = '1',fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id = '".$findresid."'");
		}

	}
	echo "success"."~".$classid."~".$expid."~".$scheduleid;
	
}

if($oper=="saveresunlocked" and $oper != " " )
{

	$classid = isset($method['classid']) ? $method['classid'] : '';
	$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
	$expid = isset($method['expid']) ? $method['expid'] : '';

	$unlock_resid = $ObjDB->QueryObject("SELECT x.fld_id as explockid FROM itc_exp_lockpassport AS x
						WHERE  x.fld_classid = '".$classid."' AND x.fld_scheduleid = '".$scheduleid."' AND x.fld_expid = '".$expid."'
	 					");
	if($unlock_resid->num_rows>0){

		while($row = $unlock_resid->fetch_assoc())
		{
			extract($row);
			$ObjDB->NonQuery("UPDATE itc_exp_lockpassport SET fld_lock_flag = '0',fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id = '".$explockid."'");

		}
	 
	}
	echo "success"."~".$classid."~".$expid."~".$scheduleid;

}


@include("footer.php");
