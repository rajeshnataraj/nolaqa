<?php 
@include("sessioncheck.php");

/*
	Created By - Mohan. M
	Page - reports-gradereports-classgradeajax.php
*/

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- District---*/
if($oper=="showteachers1" and $oper != " " )
{
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '';
	$individualid = isset($method['individualid']) ? $method['individualid'] : '';
	$val = isset($method['val']) ? $method['val'] : '';
	?>
	Teachers
	<div class="selectbox">
		<input type="hidden" name="teacherid" id="teacherid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Teacher</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Search Teacher">
			<ul role="options" style="width:100%">
				<?php 
				$qry = $ObjDB->QueryObject("SELECT CONCAT(fld_fname,' ',fld_lname) AS teachername, fld_id AS teacherid 
											FROM itc_user_master 
											WHERE fld_activestatus='1' AND fld_delstatus='0' AND fld_profile_id IN (7,8,9) 
											AND fld_school_id='".$schoolid."' AND fld_user_id='".$individualid."' 
											ORDER BY fld_lname");
				if($qry->num_rows>0){
					while($row = $qry->fetch_assoc())
					{
						extract($row);
						?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $teacherid;?>" onclick="fn_showclass1(<?php echo $teacherid;?>,<?php echo $val;?>);"><?php echo $teachername; ?></a></li>
						<?php
						
					}
				}?>      
			</ul>
		</div>
	</div>
	<?php
}

/*--- District---*/
if($oper=="showclass1" and $oper != " " )
{
	$teacherid = isset($method['teacherid']) ? $method['teacherid'] : '';
	$schoolid = isset($method['schoolid']) ? $method['schoolid'] : '0';
	$indid = isset($method['indid']) ? $method['indid'] : '0';
	$val = isset($method['val']) ? $method['val'] : '0';
	?>
	Class 
	<dl class='field row'>
		<div class="selectbox">
			<input type="hidden" name="classid" id="classid" value="">
			<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
				<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
				<b class="caret1"></b>
			</a>
			<div class="selectbox-options">
				<input type="text" class="selectbox-filter" placeholder="Search Class">
				<ul role="options" style="width:100%">
					<?php 
					$qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
												FROM itc_class_master 
												WHERE fld_delstatus='0' AND (fld_created_by='".$teacherid."' 
												OR fld_id IN (SELECT fld_class_id 
                                                                                                FROM itc_class_teacher_mapping 
                                                                                                WHERE fld_teacher_id='".$teacherid."' AND fld_flag='1')) 
												ORDER BY fld_class_name");
					if($qry->num_rows>0){
						while($row = $qry->fetch_assoc())
						{
							extract($row);
							
							?>
							<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_load_schedule1(8,<?php echo $classid;?>);"><?php echo $classname;?></a></li>
							<?php
						}
					}?>      
				</ul>
			</div>
		</div> 
	</dl>
	<?php
}

/*--- District---*/
if($oper=="showschedule1" and $oper != " " )
{
	$classid = isset($method['classid']) ? $method['classid'] : '';
	$type = isset($method['type']) ? $method['type'] : '';
        $preposttype = isset($method['preposttype']) ? $method['preposttype'] : '0'; /*****updated by mohan m******/
	?>
    Schedule
	<div class="selectbox">
		<input type="hidden" name="scheduleid" id="scheduleid" value="">
		<a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
			<span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
			<b class="caret1"></b>
		</a>
		<div class="selectbox-options">
			<input type="text" class="selectbox-filter" placeholder="Select Schedule">
			<ul role="options" style="width:100%">
				<?php 
                              
                                $qry = $ObjDB->QueryObject("SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-18') AS scheduleid, NULL AS expschid, 18 AS typename 
                                                                FROM itc_class_indasmission_master
                                                                WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'
                                                            UNION ALL	
                                                                SELECT fld_schedule_name AS schedulename, CONCAT(fld_id,'-21') AS scheduleid, fld_id AS expschid,  21 AS typename 
                                                                    FROM itc_class_rotation_mission_mastertemp 
                                                                    WHERE fld_class_id='".$classid."' AND fld_delstatus='0' AND fld_flag='1'");
                               
				if($qry->num_rows>0)
                                {
                                    while($row = $qry->fetch_assoc())
                                    {
                                        extract($row);
                                        if($typename==18)
                                        {
                                            $function = " $('#rotationdiv').hide(); $('#viewreportdiv').show();  $('#typeids').val(".$typename.");";
                                        }
                                        else if($typename==21)
                                        {
                                            $function = "fn_expschload_rotation1(".$expschid.",".$typename."); $('#typeids').val(".$typename.");";
                                        }
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $scheduleid;?>" onclick="<?php echo $function; ?>" ><?php echo $schedulename; ?></a></li>
                                        <?php

                                    }
                                }   ?>      
			</ul>
		</div>
        
	</div>
	<?php
}

/*--- Load Exp Schedule Rotation Dropdown ---*/
if($oper=="showexpschrotation" and $oper!= " " )
{
    $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '';
    $type = isset($method['type']) ? $method['type'] : '';
    if($type==21)
    {
            $query = "SELECT fld_rotation AS rotation, fld_rotation-1 AS realrotation 
                            FROM itc_class_rotation_mission_schedulegriddet 
                            WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1' 
                                GROUP BY fld_rotation  ORDER BY fld_rotation";	
    }
	
    ?>
    <input type="hidden" name="typeids" id="typeids" value="<?php echo $type;?>" />
    Rotations
    <div>
    	<table style="width:100%">
        	<tr>
                <?php 
                $qry = $ObjDB->QueryObject($query);
                if($qry->num_rows>0)
                {
                    $i = 1;
                    $j=1;
                    while($row = $qry->fetch_assoc())
                    {
                        extract($row);
                        $count = ($i % 4);
                        ?>
                        <td style="width:25%">
                        	<form id="frmrep" name="frmrep">
                            	<div class="field">
                                    <label class="checkbox" for="expcheck_<?php echo $realrotation;?>" onclick="fn_checkexpschrotation1(this.id,<?php echo $realrotation;?>)" id="0" style="width:110px">
                                        <input name="<?php echo $realrotation;?>" id="expcheck_<?php echo $realrotation;?>" value="1" type="checkbox" style="display:none;"/>
                                        <span></span><?php if($realrotation==0) { echo "	Orientation"; } else { echo "	Rotation ".$realrotation; }?>
                                    </label>
                                </div>
                            </form>
                        </td>
                        <?php
                        if($count == 0)
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
        </table>
    </div>
    <?php
}
/*--- Load Exp Schedule Rotation Dropdown ---*/


@include("footer.php");