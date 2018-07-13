<?php
if($_SERVER['SERVER_NAME'] == "localhost"){     include($_SERVER["DOCUMENT_ROOT"]."/live/sessioncheck.php"); }else{     @include("sessioncheck.php"); }

$date = date("Y-m-d H:i:s");
$date1 = date("Y-m-d 00:00:00");
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper=="showschedule" and $oper != " " )
{
    $clsid = isset($method['clsid']) ? $method['clsid'] : '';
    $type = isset($method['type']) ? $method['type'] : '';

    ?>
    Schedule
    <dl class='field row'>
        <div class="selectbox">
            <input type="hidden" name="schid" id="schid" value="" onchange="fn_showexp(this.value,<?php echo $type; ?>);" />
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Schedule</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Schedule">
                <ul role="options" style="width:100%">
                    <?php

                    $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                                    (SELECT a.fld_id AS schid, a.fld_schedule_name AS schedulename,15 AS exptype,a.fld_id AS assingnmentid
                                                    FROM itc_class_indasexpedition_master AS a
                                                LEFT JOIN itc_class_student_mapping AS b ON b.fld_class_id=a.fld_class_id
                                                WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_student_id='".$uid."') 
                                                UNION ALL
                                                    (SELECT a.fld_id AS sch, a.fld_schedule_name AS schedulename,17 AS exptype,a.fld_id AS assingnmentid 
                                                    FROM itc_class_rotation_expschedule_mastertemp AS a
                                                LEFT JOIN itc_class_student_mapping AS b ON b.fld_class_id=a.fld_class_id
                                                WHERE a.fld_class_id='".$clsid."' AND  a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_student_id='".$uid."')
                                                UNION ALL
                                                    (SELECT a.fld_id AS sch,a.fld_schedule_name AS schedulename, 20 AS exptype,a.fld_id AS assingnmentid
                                                    FROM itc_class_rotation_modexpschedule_mastertemp as a
                                                    WHERE a.fld_class_id='".$clsid."' AND a.fld_delstatus='0' AND a.fld_flag='1')
                                                    )AS w group by w.assingnmentid ORDER BY w.schedulename
                                                ");
                    if($qry->num_rows>0)
                    {
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $schid."~".$exptype;?>"><?php echo $schedulename; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </dl>


    <?php
}else

if($oper=="showexpedition" and $oper != " " )
{
    $type = isset($method['type']) ? $method['type'] : '';
    if($type==1 || $type==2)
    {
        $schid = isset($method['schid']) ? $method['schid'] : '';
        $classid = isset($method['clsid']) ? $method['clsid'] : '';

        $schid =explode("~",$schid );
        $scheduleid=$schid[0];
        $scheduletype=$schid[1];
    }
    else
    {
        $schid = isset($method['schid']) ? $method['schid'] : '';
        $classid = isset($method['clsid']) ? $method['clsid'] : '';

        $schid =explode("~",$schid );
        $classid=$schid[0];
        $scheduleid=$schid[1];
        $scheduletype=$schid[2];
    }



    ?>
    Expedition
    <dl class='field row'>
        <div class="selectbox">
            <input type="hidden" name="expid" id="expid" value="" onchange="fn_showrubric(this.value,<?php echo $type; ?>);" />
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Expedition</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Expedition">
                <ul role="options" style="width:100%">
                    <?php

                    if($scheduletype=='15')
                    {
                        $qry = $ObjDB->QueryObject("SELECT a.fld_exp_id AS expid,b.fld_exp_name AS expname FROM itc_class_indasexpedition_master AS a
                                                    LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_exp_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_id='".$scheduleid."' AND a.fld_delstatus='0' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                                    AND b.fld_flag='1' group by expid ORDER BY expname");
                    }
                    else if($scheduletype=='20')
                    {
                        $qry = $ObjDB->QueryObject("SELECT a.fld_module_id AS expid,b.fld_exp_name AS expname FROM itc_class_rotation_modexpschedulegriddet AS a
                                            LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_module_id
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_type='2' AND a.fld_flag='1' AND b.fld_delstatus='0' 
                                            AND b.fld_flag='1' group by expid ORDER BY expname");

                    }
                    else
                    {
                        $qry = $ObjDB->QueryObject("SELECT a.fld_expedition_id AS expid, b.fld_exp_name AS expname FROM itc_class_rotation_expschedulegriddet AS a
                                                    LEFT JOIN itc_exp_master AS b ON b.fld_id=a.fld_expedition_id
                                                    LEFT JOIN itc_class_rotation_expschedule_mastertemp AS c ON a.fld_schedule_id=c.fld_id 
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' 
                                                    AND b.fld_flag='1'  group by expid ORDER BY expname");
                    }

                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $expid;?>"><?php echo $expname; ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </dl>
    <?php
}else
if($oper=="showrubric" and $oper != " " )
{

    $type = isset($method['type']) ? $method['type'] : '';

    if($type==1 || $type==2)
    {
        $expid = isset($method['expid']) ? $method['expid'] : '';
        $schid = isset($method['schid']) ? $method['schid'] : '';
        $classid = isset($method['clsid']) ? $method['clsid'] : '';

        $schid =explode("~",$schid );
        $scheduleid=$schid[0];
        $scheduletype=$schid[1];
    }
    else
    {
        $clasid = isset($method['clsid']) ? $method['clsid'] : '';
        $expid = isset($method['expid']) ? $method['expid'] : '';
        $schid =explode("~",$clasid );
        $classid=$schid[0];
        $scheduleid=$schid[1];
        $scheduletype=$schid[2];
    }


    ?>
    Rubric
    <dl class='field row'>
        <div class="selectbox">
            <input type="hidden" name="rubid" id="rubid" value="" onchange="fn_viewrubric(this.value,<?php echo $type; ?>);" />
            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Rubric</span>
                <b class="caret1"></b>
            </a>
            <div class="selectbox-options">
                <input type="text" class="selectbox-filter" placeholder="Search Rubric">
                <ul role="options" style="width:100%">
                    <?php

                    if($scheduletype=='15')
                    {
                        $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");
                    }
                    else if($scheduletype=='20')
                    {
                        $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                            FROM itc_class_expmis_rubricmaster AS a
                                            WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."'
                                            AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                            GROUP BY rubricid");
                    }
                    else
                    {
                        $qry = $ObjDB->QueryObject("SELECT a.fld_rubric_id AS rubricid, a.fld_rubric_name AS rubname 
                                                FROM itc_class_expmis_rubricmaster AS a
                                                         LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_expmisid='".$expid."' AND b.fld_delstatus='0'
                                                                AND a.fld_schedule_type='".$scheduletype."' AND a.fld_delstatus='0' 
                                                                GROUP BY rubricid");
                    }


                    if($qry->num_rows>0){
                        while($row = $qry->fetch_assoc())
                        {
                            extract($row);
                            ?>
                            <li><a tabindex="-1" href="#" data-option="<?php echo $rubricid;?>"><?php echo $rubname; ?></a></li>
                            <?php
                        }
                    }
                    else
                    { ?>
                        <li><?php echo "No Rubric"; ?></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </dl>


    <?php
}
?>