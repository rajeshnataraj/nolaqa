<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");

$id = isset($method['id']) ? $method['id'] : '';	
$id = explode("~",$id);

$scheduleid=$id[0];
$moduleid=$id[1];
$schtype=$id[2];
$sessionid=$id[3];
$type=$id[4];

if($schtype==4) $ipltype=2; if($schtype==6) $ipltype=5;
$qry = '';
if($schtype==1)
{
	$qry = "SELECT a.fld_schedule_name AS schedulename, b.fld_module_id AS moduleid, d.fld_file_name AS filename, 
					CONCAT(c.fld_module_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype 
			FROM itc_class_rotation_schedule_mastertemp AS a 
			LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
			LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND a.fld_delstatus='0' AND c.fld_delstatus='0' 
					AND b.fld_module_id='".$moduleid."' AND a.fld_moduletype='1' 
			GROUP BY c.fld_id";
	$typename = "Module";
}
else if($schtype==2)
{
	$qry = "SELECT a.fld_schedule_name AS schedulename, b.fld_module_id AS moduleid, d.fld_file_name AS filename, 
					CONCAT(c.fld_module_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype 
			FROM itc_class_dyad_schedulemaster AS a 
			LEFT JOIN itc_class_dyad_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
			LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND a.fld_delstatus='0' 
					AND b.fld_module_id='".$moduleid."' 
			GROUP BY c.fld_id";
	$typename = "Dyad";
}
else if($schtype==3)
{
	$qry = "SELECT a.fld_schedule_name AS schedulename, b.fld_module_id AS moduleid, d.fld_file_name AS filename, 
				CONCAT(c.fld_module_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype 
			FROM itc_class_triad_schedulemaster AS a 
			LEFT JOIN itc_class_triad_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
			LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND a.fld_delstatus='0' 
					AND b.fld_module_id='".$moduleid."' 
			GROUP BY c.fld_id";
	$typename = "Triad";
}
else if($schtype==4)
{
	$qry = "SELECT a.fld_schedule_name AS schedulename, b.fld_module_id AS moduleid, d.fld_file_name AS filename, 
					CONCAT(c.fld_mathmodule_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype, 
					c.fld_session_day1 AS sessid1, c.fld_session_day2 AS sessid2, c.fld_ipl_day1 AS iplid1, c.fld_ipl_day2 AS iplid2 
			FROM itc_class_rotation_schedule_mastertemp AS a 
			LEFT JOIN itc_class_rotation_schedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
			LEFT JOIN itc_mathmodule_master AS c ON b.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_module_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND a.fld_delstatus='0' 
					AND b.fld_module_id='".$moduleid."' AND a.fld_moduletype='2' 
			GROUP BY c.fld_id";
	$typename = "Math Module";
}
else if($schtype==5 || $schtype==7)
{
	if($schtype==5)
	{
		$modtype = 1;
		$typename = "Individual Module";
	}
	else if($schtype==7)
	{
		$modtype = 7;
		$typename = "Individual Quest";
	}
	$qry = "SELECT a.fld_schedule_name AS schedulename, a.fld_module_id AS moduleid, d.fld_file_name AS filename, 
					CONCAT(c.fld_module_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype 
			FROM itc_class_indassesment_master AS a 
			LEFT JOIN itc_module_master AS c ON a.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND a.fld_module_id='".$moduleid."' 
					AND a.fld_delstatus='0' AND a.fld_moduletype='".$modtype."' 
			GROUP BY c.fld_id";
	$typename = "Individual Module";
}
else if($schtype==6)
{
	$qry = "SELECT a.fld_schedule_name AS schedulename, a.fld_module_id AS moduleid, d.fld_file_name AS filename, 
					CONCAT(c.fld_mathmodule_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype, 
					c.fld_session_day1 AS sessid1, c.fld_session_day2 AS sessid2, c.fld_ipl_day1 AS iplid1, c.fld_ipl_day2 AS iplid2 
			FROM itc_class_indassesment_master AS a 
			LEFT JOIN itc_mathmodule_master AS c ON a.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_module_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND c.fld_delstatus='0' AND a.fld_module_id='".$moduleid."' 
					AND a.fld_delstatus='0' AND a.fld_moduletype='2' 
			GROUP BY c.fld_id";
	$typename = "Individual Math Module";
}
else if($schtype==8 OR $schtype==22)
{
	$qry = "SELECT fld_contentname AS modulename
			FROM itc_customcontent_master 
			WHERE fld_id='".$moduleid."' AND fld_delstatus='0'";
	$typename = "Custom Module";
}
else if($schtype==17)
{
    $qry = "SELECT fld_contentname AS modulename
			FROM itc_customcontent_master 
			WHERE fld_id='".$moduleid."' AND fld_delstatus='0'";

	$typename = "Individual Custom";
}
else if($schtype==21)
{
	$qry = "SELECT a.fld_schedule_name AS schedulename, b.fld_module_id AS moduleid, d.fld_file_name AS filename, 
					CONCAT(c.fld_module_name,' ',d.fld_version) AS modulename, d.fld_file_type AS filetype 
			FROM itc_class_rotation_modexpschedule_mastertemp AS a 
			LEFT JOIN itc_class_rotation_modexpschedulegriddet AS b ON a.fld_id=b.fld_schedule_id 
			LEFT JOIN itc_module_master AS c ON b.fld_module_id=c.fld_id 
			LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=c.fld_id 
			WHERE a.fld_id='".$scheduleid."' AND d.fld_delstatus='0' AND a.fld_delstatus='0' AND c.fld_delstatus='0' 
					AND b.fld_module_id='".$moduleid."' AND b.fld_type='1'
			GROUP BY c.fld_id";
	$typename = "Module";
}

$qrydetails = $ObjDB->QueryObject($qry);

if($qrydetails->num_rows>0){
	$rowqrydetails = $qrydetails->fetch_assoc();
	extract($rowqrydetails);
}
?>
<section data-type='2home' id='assignment-science-sessions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle"><?php echo $modulename." / ".$typename; ?></p>
            </div>
        </div>
        
        <?php if($schtype==8 || $schtype==17) { ?>
        <div class='row formBase rowspacer'>
            <div class="eleven columns centered insideForm">
                <div class="darkTitle" align="center"><?php echo $modulename; ?></div>
            </div>
        </div>
        
        <?php }else{ ?>
            <div class='row formBase rowspacer'>
            <div class="eleven columns centered insideForm">
                <?php
				if($schtype==4 or $schtype==6)
				{
					$tempmoduleid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id 
																FROM itc_mathmodule_master 
																WHERE fld_id='".$moduleid."'");
				}
				else{
					$tempmoduleid = $moduleid;
				}
				$totalchapters = $ObjDB->SelectSingleValueInt("SELECT MAX(fld_session_id)+1 
																FROM itc_module_performance_master 
																WHERE fld_module_id='".$tempmoduleid."'");
				$k=1;
				$j=1;

				if($uid1=='')
					$secuser = 0;
				else
					$secuser = $uid1;

				for($i=0;$i<=$totalchapters;$i++)
				{ 
					if($i==$totalchapters)
						$k = e;
					?>
					<a class='skip btn sm<?php echo $k;?> main' href="javascript:void(0);" id='btnassignment-science-player' onClick="showfullscreenmodule('<?php echo $i.",".$moduleid.",".$scheduleid.",".$schtype.",".$uid.",".$secuser;?>',1);">
						<div class="onBtn"><?php if($i==$totalchapters) {                                                    
                                                } 
                                                else {                                                    
                                                }?></div>
					</a>
					
					<?php if(($schtype==4 || $schtype==6) && ($j==$sessid1 || $j==$sessid2)) { 
					if($j==$sessid1) { $iplids=$iplid1; $diag = "D1"; } else if($j==$sessid2) { $iplids=$iplid2; $diag = "D2";}?>
					<a class='skip btn sm<?php echo $k."".$diag;?> mainBtn <?php if($uid1!='') {?>dim<?php }?>' href="javascript:void(0);" id='btnassignment-sigmath-test' name='<?php echo $scheduleid."~".$iplids."~".$ipltype."~".$moduleid;?>'>
					</a>
					<?php
					}
					$j++;
					$k++;
				}
                ?>
            </div>
        </div>
        <?php }?>
    </div>
    <script>
        <?php 
                if($sessionid!='')
                {
                ?>
                    setTimeout('showscreen();',2000);
                    function showscreen()
                    {
                        showfullscreenmodule('<?php echo $sessionid.",".$moduleid.",".$scheduleid.",".$schtype.",".$uid.",0";?>',<?php echo $type;?>);
                    }
                <?php
                }
                ?>
    </script>
</section>
<?php
	@include("footer.php");